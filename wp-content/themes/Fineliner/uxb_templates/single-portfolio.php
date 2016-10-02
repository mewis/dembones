<?php get_header(); ?>

<?php if ( is_plugin_active( 'option-tree/ot-loader.php' ) ) : ?>
	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<?php 
			
			$plugin_options = get_option( 'uxb_port_plugin_options' );
			
			
			
			$display_post_format_content = true; 
			
			$post_format = get_post_meta( get_the_ID(), 'uxbarn_portfolio_item_format', true );
			$images_format_layout = get_post_meta( get_the_ID(), 'uxbarn_portfolio_image_slideshow_layout', true );
			
			$show_meta = get_post_meta( get_the_ID(), 'uxbarn_portfolio_meta_info_display', true );
			
			// These meta info are used to display in the meta box, and for querying related items
			$meta_date = uxb_port_get_portfolio_meta_text( get_post_meta( get_the_ID(), 'uxbarn_portfolio_meta_info_date', true ) );
			$meta_client = uxb_port_get_portfolio_meta_text( get_post_meta( get_the_ID(), 'uxbarn_portfolio_meta_info_client', true ) );
			$meta_website_link = uxb_port_get_portfolio_meta_text( get_post_meta( get_the_ID(), 'uxbarn_portfolio_meta_info_website', true ) );
			
			// Prepare the values for layout mode (default values are for landscape view)
			$layout_images_column = ' large-12 ';
			$layout_info_column = ' large-12 ';
			$images_slider_class = '';
			$image_size = 'uxb-port-single-landscape';
			$meta_class = '';
			
			if ( $images_format_layout == 'portrait' ) {
				
				$layout_images_column = ' large-7 ';
				$layout_info_column = 'large-5 ';
				$images_slider_class = ' portrait-view ';
				$image_size = 'uxb-port-single-portrait';
				$meta_class = ' portrait-view ';
				
			}
			
		?>
		
		<div id="uxb-port-inner-content-container">
			
		<?php if ( $display_post_format_content ) : ?>
			
			<?php 
			
				$title = get_post_meta( $post->ID, 'uxbarn_portfolio_single_title', true );
				$title = trim( $title ) == '' ? get_the_title() : $title;
				//echo apply_filters( 'uxb_port_single_title', $title ); 
					
			?>
		
			<?php if ( $post_format == 'image-slideshow' ) : ?>
				
				<?php
					
					$images = get_post_meta( get_the_ID(), 'uxbarn_portfolio_image_slideshow', true );
					
					$hide_images_div_class = '';
					if ( empty( $images ) ) {
						$hide_images_div_class = ' no-margin-bottom ';
					}
					
				?>
		
				<!-- Portfolio Images/Video -->
				<div id="uxb-port-images-type" class="row <?php echo esc_attr( $hide_images_div_class ); ?>">
					<div class="port-slider uxb-col <?php echo esc_attr( $layout_images_column ); ?> columns">
						
						<?php if ( ! empty( $images ) ) : ?>
						
							<div id="uxb-port-single-images-container" class="slider-set <?php echo esc_attr( $images_slider_class ); ?>">
								<ul class="uxb-port-single-slides">
								
								<?php foreach ( $images as $image ) : ?>
									
									<?php
									
										$image_full_size_url = $image['uxbarn_portfolio_image_slideshow_upload'];
										$attachment_id = uxb_port_get_attachment_id_from_src( $image_full_size_url );
										$caption = '';
										$alt = '';
										$title = '';
										$image_width = '1020';
										$image_height = '676';
										
										$img_srcset_attr = '';
										$img_sizes_attr = '';
										
										// Whether the entered URL is from external site or not
										if ( isset( $attachment_id ) ) { // From its own attachement archive
										
											$attachment = uxb_port_get_attachment( $attachment_id );
											$caption = $attachment['caption'];
											$alt = $attachment['alt'];
											$title = $attachment['title'];
										
											// Got an array [0] => url, [1] => width, [2] => height
											$image_array = wp_get_attachment_image_src( $attachment_id, $image_size );
											$image_display_size_url = $image_array[0];
											$image_width = $image_array[1];
											$image_height = $image_array[2];
										
											// srcset and sizes attributes
											$img_srcset_attr = wp_get_attachment_image_srcset( $attachment_id, $image_size );
											$img_sizes_attr = wp_get_attachment_image_sizes( $attachment_id, $image_size );
											
										} else { // From external site
											$image_display_size_url = $image_full_size_url;
										}
										
									?>
									
									<?php if ( $image_full_size_url ) : ?>
										
										<li class="uxb-port-single-image">
											<a href="<?php echo esc_url( $image_full_size_url ); ?>" class="uxb-port-image-box" title="<?php echo esc_attr( $title ); ?>" data-fancybox-group="portfolio-image-group">
												
												<img 
													src="<?php echo esc_url( $image_display_size_url ); ?>" 
													alt="<?php echo esc_attr( $alt ); ?>" 
													width="<?php echo intval( $image_width ); ?>" 
													height="<?php echo intval( $image_height ); ?>" 
													class="border" 
													srcset="<?php echo esc_attr( $img_srcset_attr ); ?>" 
													sizes="<?php echo esc_attr( $img_sizes_attr ); ?>" 
												/>
												
											</a>
											
											<?php if ( $caption != '' ) : ?>
												<div class="uxb-port-image-caption-wrapper">
													<div class="uxb-port-image-caption">
														<?php echo esc_html( $caption ); ?>
													</div>
												</div>
											<?php endif; ?>
										</li>
										
									<?php endif; ?>
									
								<?php endforeach; ?>
								
								</ul>
							
								<a href="#" class="uxb-port-slider-controller uxb-port-slider-prev"><i class="fa fa-angle-left"></i></a>
								<a href="#" class="uxb-port-slider-controller uxb-port-slider-next"><i class="fa fa-angle-right"></i></a>
							
							</div>
							
						<?php endif; // if(!empty($images)) ?>
							
					</div>
					
				<?php if ( $images_format_layout == 'landscape' ) : // if it is "landscape" view, close the row div ?>
					</div>
				<?php endif; ?>
				
			<?php elseif ( $post_format == 'video' ) : ?>
				
				<?php
				
					$video_url = get_post_meta( get_the_ID(), 'uxbarn_portfolio_video_url', true );
				
				?>
				
				<div id="uxb-port-video-type" class="row">
					<div class="port-video uxb-col large-12 columns">
						
						<?php 
						
							if ( ! empty( $video_url ) ) {
								
								global $wp_embed;
								echo '<div class="uxb-port-embed">' . $wp_embed->run_shortcode( '[embed]' . esc_url( $video_url ) . '[/embed]' ) . '</div>';
								
							}
							
						?>
					
					</div>
				</div>
					
			<?php endif; // END: if($post_format == 'image-slideshow') ?>
			
		<?php endif; // END: if($display_post_format_content) ?>
		
		<?php if ( $post_format == 'image-slideshow' ) : ?>
			
			<?php if ( $images_format_layout == 'landscape' ) : // if it is "landscape" view, open the row div for info ?>
				<div class="row">
			<?php endif; ?>
			
		<?php else : // if it is video format, open the row div for info ?>
			<div class="row">
		<?php endif; ?>
		
			<div class="port-content uxb-col <?php echo esc_attr( $layout_info_column . $images_format_layout ); ?> columns">
				
				<div class="row <?php if ( uxb_port_is_using_vc() ) echo ' no-margin-bottom '; ?>">
				
					<?php 
						
						// Default for "landscape" mode
						$layout_content_columns = ' large-8 ';
						$layout_meta_columns = ' large-4 ';
						
						if ( $images_format_layout == 'portrait' ) {
							
							$layout_content_columns = ' large-12 ';
							$layout_meta_columns = ' large-12 ';
							
							// Functions from "custom-uxbarn-portfolio.php"
							uxbarn_display_port_meta_block( $show_meta, $meta_date, $meta_client, $meta_website_link, $meta_class, $layout_content_columns, $layout_meta_columns, $plugin_options );
							uxbarn_display_port_text_block( $layout_content_columns );
							
						} else {
							
							// Functions from "custom-uxbarn-portfolio.php"
							uxbarn_display_port_text_block( $layout_content_columns );
							uxbarn_display_port_meta_block( $show_meta, $meta_date, $meta_client, $meta_website_link, $meta_class, $layout_content_columns, $layout_meta_columns, $plugin_options );
							
						}
					
					?>
						
				</div>
				
			</div>
		</div>
		
		<?php
		
			$display_related_items = $plugin_options['uxb_port_po_single_page_display_related_works'];
			if ( ! isset( $display_related_items ) ) { // means first time
				$display_related_items = 'true';
			}
			
		?>
		
		<?php if ( $display_related_items == 'true' ) : ?>
			
			<?php
				
				$scope = isset( $plugin_options['uxb_port_po_single_page_related_works_scopes'] ) ? $plugin_options['uxb_port_po_single_page_related_works_scopes'] : '';
			   // echo var_dump($scope);
				if ( isset( $scope ) && ! empty( $scope ) ) {
					$scope = array_values( $scope );
				} else {
					$scope = array();
				}
				
				
				// Default category filter
				$category_id_list = array( -1 );
				$terms = get_the_terms( get_the_ID(), 'uxbarn_portfolio_tax' );
				//echo var_dump($terms);
				if ( $terms ) {
					foreach ( $terms as $term ) {
						$category_id_list[] = $term->term_id;
					}
				}
				
				$category_array = array(
					'tax_query' => array(
						array(
							'taxonomy' 	=> 'uxbarn_portfolio_tax',
							'field' 	=> 'id',
							'terms' 	=> $category_id_list,
						),
					),
				);
				
				
				// Meta info filter
				$raw_meta_info_array = array();
				
				// Custom meta info
				$use_custom_meta = isset( $plugin_options['uxb_port_po_use_custom_meta_info'] ) ? $plugin_options['uxb_port_po_use_custom_meta_info'] : 'off';
								
				if ( $use_custom_meta == 'on' ) {
				
					$raw_meta_info_array = array(
						'relation' => 'OR',
					);
					
					// When using custom meta info, if there are any saved values from default meta info, ignore them and assume that the scopes are empty
					if ( in_array( 'client', $scope ) || in_array( 'website', $scope ) || in_array( 'date', $scope ) ) {
						$scope = array();
					}
					
					if ( ! empty( $scope ) ) {
						//echo var_dump($scope);
						
						for ( $i=0; $i<count($scope); $i++ ) {
							//echo $scope[$i];
							$raw_meta_info_array[] = array(
								'key'		=> $scope[$i], // actual ID (initial + user-created ID) 
								'value'		=> uxb_port_get_portfolio_meta_text( get_post_meta( get_the_ID(), $scope[$i], true ) ),
								'compare'	=> '=',
							);
							
						}
						
					}
						//echo var_dump($raw_meta_info_array);
					
				} else { // Default ones
					
					$raw_client_field = array();
					if ( in_array( 'client', $scope ) ) {
						
						$raw_client_field = array(
							'key' 		=> 'uxbarn_portfolio_meta_info_client',
							'value' 	=> $meta_client,
							'compare' 	=> '=',
						);
						
					}
					
					$raw_website_field = array();
					if ( in_array( 'website', $scope ) ) {
						
						$raw_website_field = array(
							'key' 		=> 'uxbarn_portfolio_meta_info_website',
							'value' 	=> $meta_website_link,
							'compare' 	=> '=',
						);
						
					}
					
					$raw_date_field = array();
					if ( in_array( 'date', $scope ) ) {
						
						$raw_date_field = array(
							'key' 		=> 'uxbarn_portfolio_meta_info_date',
							'value' 	=> $meta_date,
							'compare' 	=> '=',
						);
						
					}
					
					$raw_meta_info_array = array(
						'relation' => 'OR',
						$raw_client_field,
						$raw_website_field,
						$raw_date_field,
					);
					
				}
				
				
				$meta_info_array = array(
					'relation' 		=> 'OR',
					'meta_query' 	=> $raw_meta_info_array,
				);
				
				// Final result for all filters
				$result_filtering_array = array_merge( $category_array, $meta_info_array );
				//echo var_dump($result_filtering_array);
				$args = array(
					'post_type' 	=> 'uxbarn_portfolio',
					'nopaging' 		=> true,
					'post__not_in' 	=> array( get_the_ID() ), // Not retrieve itself
				);
				
				$related_items = new WP_Query( array_merge( $args, $result_filtering_array ) );
				
				if ( $related_items->have_posts() ) {
					
					echo 
					'   
					<!-- Divider set -->
					<div class="divider-set1">
						<hr class="short" />
						<hr class="middle" />
					</div>
					
					<!-- Related Items -->
					<div class="row">
						<div class="uxb-col large-12 columns">
							<h3>' . __( 'Related Works', 'uxbarn' ) . '</h3>
							
							<div class="uxb-port-root-element-wrapper related-items col4">
								<span class="uxb-port-loading-text"><span>' . __( 'Loading', 'uxbarn' ) . '</span></span>
								<div class="uxb-port-element-wrapper">';
					
					while ( $related_items->have_posts() ) {
						
						$related_items->the_post();
						
						$thumbnail = '';
						if ( has_post_thumbnail( get_the_ID() ) ) {
							$thumbnail = get_the_post_thumbnail( get_the_ID(), 'uxb-port-related-items', array( 'alt'=>get_the_title(), 'class'=>'border' ) );
						} else {
							$thumbnail = '<img src="' . UXB_PORT_URL . 'images/placeholders/port-related-item.gif" alt="' . __( 'No Thumbnail', 'uxbarn' ) . '" />';
						}
						
						$show_title_code = '<hr/><h4>' . get_the_title() . '</h4><hr/>';
						
						echo 
						'<div class="uxb-port-element-item">
							<div class="uxb-port-element-item-hover-wrapper">
								<div class="uxb-port-element-item-hover">
									<a href="' . get_permalink() . '"></a>
									<div class="uxb-port-element-item-hover-info">' . $show_title_code . '</div>
								</div>
							</div>
							' . $thumbnail . '
						</div>';
						
					}
	
					echo '</div></div>'; // close "portfolio-wrapper", "portfolio-root-wrapper"
					echo '</div></div>'; // close "columns", "row"
	
				}
				
				wp_reset_postdata();
			?>
			
		<?php endif; // if($display_related_items) ?>
		
		<?php
			
			$is_comment_enabled = isset( $plugin_options['uxb_port_po_single_page_enable_comment'] ) ? $plugin_options['uxb_port_po_single_page_enable_comment'] : 'false';
			if ( ! isset( $is_comment_enabled ) ) {
				$is_comment_enabled = 'false';
			}
			
		?>
		
		<?php if ( $is_comment_enabled == 'true' ) : ?>
			
			<?php if ( comments_open() ) : ?>
				
				<!-- Comment Section -->
				<div class="row">
					<div class="uxb-col large-12 columns">
						
						<?php comments_template(); ?>
						
					</div>
				</div>
				
			<?php endif; ?>
			
		<?php endif; ?>
	
	<?php endwhile; endif; ?>
	
		</div>
		<!-- END: id="inner-content-container" -->
		
<?php else : // if ( is_plugin_active( 'option-tree/ot-loader.php' ) ) :?>
	
	<?php _e( 'OptionTree plugin must be installed and activated first.', 'uxbarn' ); ?>
	
<?php endif; ?>

<?php get_footer(); ?>
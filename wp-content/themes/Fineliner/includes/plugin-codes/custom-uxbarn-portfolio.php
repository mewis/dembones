<?php

/***** UXbarn Portfolio *****/
if ( ! function_exists( 'uxbarn_portfolio_custom' ) ) {
	
	function uxbarn_portfolio_custom() {
		
		// Remove default plugin's image sizes and register them again with some adjustment
		remove_image_size( 'uxb-port-element-thumbnails' );
		remove_image_size( 'uxb-port-related-items' );
		remove_image_size( 'uxb-port-single-landscape' );
		remove_image_size( 'uxb-port-single-portrait' );
		remove_image_size( 'uxb-port-large-square' );
	
		add_image_size( 'uxb-port-element-thumbnails', 305, 9999 );
		//add_image_size( 'uxb-port-element-thumbnails-col4', 304, 9999 );
		add_image_size( 'uxb-port-related-items', 230, 230, true );
		add_image_size( 'uxb-port-single-landscape', 1008, 9999 );
		add_image_size( 'uxb-port-single-portrait', 570, 9999 );
		
		// Will be re-enabled below with the custom function in the theme
		remove_action( 'admin_init', 'uxb_port_create_image_slideshow_format_content' );
		
		if ( class_exists( 'OT_Loader' ) ) {
			
			// From custom function in this theme
			add_action( 'admin_init', 'uxbarn_custom_create_image_slideshow_format_content' );
		
		}
		
		// Filter to override the plugin's shortcode output
		add_filter( 'uxb_port_load_portfolio_shortcode_filter', 'uxbarn_custom_port_load_portfolio_shortcode', 10, 2 );
		
		// Filter to override the plugin's CPT argument
		add_filter( 'uxb_port_register_cpt_args_filter', 'uxbarn_custom_port_cpt_args' );
		
		// Filter to override the plugin's taxonomy argument
		add_filter( 'uxb_port_register_tax_args_filter', 'uxbarn_custom_port_tax_args' );
		
	}
	
}



if ( ! function_exists( 'uxbarn_custom_create_image_slideshow_format_content' ) ) {

	function uxbarn_custom_create_image_slideshow_format_content() {
		
		$args = array(
            'id'          => 'uxbarn_portfolio_image_slideshow_format_meta_box',
            'title'       => __( 'Meta Box for Image/Slideshow Format', 'uxbarn' ),
            'desc'        => '',
            'pages'       => array( 'uxbarn_portfolio' ),
            'context'     => 'normal',
            'priority'    => 'high',
            'fields'      => array(
                array(
                'id'          => 'uxbarn_portfolio_image_slideshow',
                'label'       => __( 'Images', 'uxbarn' ),
                'desc'        => __( 'Use this setting to add images and rearrange the order by drag and drop. You can upload the image at any size. The full size of the image will be displayed on lightbox (when it is clicked). What is shown on the front end is a scaled version.<br/><br/>Note that the "Title" field will only be used here. On the front end, the theme will use the "alt", "title" and "caption" values from the image itself.', 'uxbarn' ),
                'std'         => '',
                'type'        => 'list-item',
                'section'     => 'uxbarn_portfolio_slideshow_format_sec',
                'rows'        => '',
                'post_type'   => '',
                'taxonomy'    => '',
                'class'       => '',
                'settings'    => array( 
                    array(
                        'id'          => 'uxbarn_portfolio_image_slideshow_upload',
                        'label'       => __( 'Image', 'uxbarn' ),
                        'desc'        => '',
                        'std'         => '',
                        'type'        => 'upload',
                        'rows'        => '',
                        'post_type'   => '',
                        'taxonomy'    => '',
                        'class'       => ''
                      ),
                ),
              ),
          
              array(
                    'id'          => 'uxbarn_portfolio_image_slideshow_layout',
                    'label'       => __( 'Layout Mode', 'uxbarn' ),
                    'desc'        => __( 'Select which layout to use for the single page.', 'uxbarn' ),
                    'std'         => 'landscape',
                    'type'        => 'select',
                    'rows'        => '',
                    'post_type'   => '',
                    'taxonomy'    => '',
                    'class'       => '',
                    'choices'     => array( 
                      array(
                        'value'       => 'landscape',
                        'label'       => __( 'Landscape', 'uxbarn' ),
                        'src'         => ''
                      ),
                      array(
                        'value'       => 'portrait',
                        'label'       => __( 'Portrait', 'uxbarn' ),
                        'src'         => ''
                      ),
                    ),
                  ),
                  
				  
            )
        );
        
        ot_register_meta_box( $args );
		
	}

}
	



if ( ! function_exists( 'uxbarn_custom_port_cpt_args' ) ) {

	function uxbarn_custom_port_cpt_args( $args ) {
	
		$args = array(
					'label' 			=> __( 'Portfolio', 'uxbarn' ),
					'labels' 			=> array(
												'singular_name'		=> __( 'Portfolio', 'uxbarn' ),
												'add_new' 			=> __( 'Add New Portfolio Item', 'uxbarn' ),
												'add_new_item' 		=> __( 'Add New Portfolio Item', 'uxbarn' ),
												'new_item' 			=> __( 'New Portfolio Item', 'uxbarn' ),
												'edit_item' 		=> __( 'Edit Portfolio Item', 'uxbarn' ),
												'all_items' 		=> __( 'All Portfolio Items', 'uxbarn' ),
												'view_item' 		=> __( 'View Portfolio', 'uxbarn' ),
												'search_items' 		=> __( 'Search Portfolio', 'uxbarn' ),
												'not_found' 		=> __( 'Nothing found', 'uxbarn' ),
												'not_found_in_trash' => __( 'Nothing found in Trash', 'uxbarn' ),
											),
					'menu_icon' 		=> UXB_THEME_ROOT_IMAGE_URL . 'admin/uxbarn-admin-s.jpg',
					'description' 		=> __( 'Portfolio of your business', 'uxbarn' ),
					'public' 			=> true,
					'show_ui' 			=> true,
					'capability_type' 	=> 'post',
					'hierarchical' 		=> false,
					'has_archive' 		=> false,
					'supports' 			=> array( 'title', 'editor', 'thumbnail', 'revisions', 'comments' ),
					'rewrite' 			=> array( 'slug' => __( 'portfolio', 'uxbarn' ), 'with_front' => false )
				);
		
		return $args;
		
	}

}



if ( ! function_exists( 'uxbarn_custom_port_tax_args' ) ) {

	function uxbarn_custom_port_tax_args( $args ) {
	
		$args = array(
					'label' 			=> __( 'Portfolio Categories', 'uxbarn' ), 
					'labels' 			=> array(
											'singular_name' => __( 'Portfolio Category', 'uxbarn' ),
											'search_items' 	=> __( 'Search Categories', 'uxbarn' ),
											'all_items' 	=> __( 'All Categories', 'uxbarn' ),
											'edit_item' 	=> __( 'Edit Category', 'uxbarn' ), 
											'update_item' 	=> __( 'Update Category', 'uxbarn' ),
											'add_new_item' 	=> __( 'Add New Category', 'uxbarn' ),
										),
					'singular_label' 	=> __( 'Portfolio Category', 'uxbarn' ),
					'hierarchical' 		=> true, 
					'rewrite' 			=> array( 'slug' => __( 'portfolio-category', 'uxbarn' ) ),
				);
				
		return $args;
	
	}
	
}



if ( ! function_exists( 'uxbarn_custom_port_load_portfolio_shortcode' ) ) {
	
	function uxbarn_custom_port_load_portfolio_shortcode( $val, $atts ) {
		
		if ( function_exists( 'vc_map_get_attributes' ) ) {
				
			extract( vc_map_get_attributes( 'uxb_portfolio', $atts ) );
			$show_title = 'true';
				
		} else {
			
			$default_atts = array(
								'categories' 	=> '',
								'max_item' 		=> '',
								'type' 			=> 'col4', // col3, col4, flexslider_fade, flexslider_slide
								'show_filter' 	=> 'true', // true, false
								'show_title' 	=> 'true', // true, false
								'img_size' 		=> '',
								'interval' 		=> '0', // 0, 5, ..
								'show_bullets' 	=> 'true', // true, false
								'orderby' 		=> 'ID',
								'order' 		=> 'ASC',
								'el_class' 	=> '',
							);              
							
			extract( shortcode_atts( $default_atts, $atts ) );
			
		}
		
		
		if ( trim( $categories ) == '' ) {
			return '<div class="error box">' . __( 'Cannot generate Portfolio element. Categories must be defined.', 'uxbarn' ) . '</div>';
		}

		$category_id_list = explode( ',', $categories );
		
		// If WPML is active, get translated category's ID
		if ( function_exists( 'icl_object_id' ) ) {
			
			$wpml_cat_list = array();
			
			foreach ( $category_id_list as $cat_id ) {
				$wpml_cat_list[] = icl_object_id( $cat_id, 'uxbarn_portfolio_tax', false, ICL_LANGUAGE_CODE );
			}
			
			$category_id_list = $wpml_cat_list;
			
		}
		
		
		if ( ! is_numeric( $max_item ) ) {
			$max_item = '';
		}
		
		// Prepare WP_Query args
		if ( $max_item == '' ) {
			
			$args = array(
				'post_type' 	=> 'uxbarn_portfolio',
				'nopaging' 		=> true,
				'tax_query' 	=> array(
										array(
											'taxonomy'  => 'uxbarn_portfolio_tax',
											'field' 	=> 'id',
											'terms' 	=> $category_id_list,
										),
									),
				'orderby' 		=> $orderby,
				'order' 		=> $order,
			);
			
		} else {
			
			$args = array(
				'post_type' 		=> 'uxbarn_portfolio',
				'posts_per_page' 	=> $max_item,
				'tax_query' 		=> array(
											array(
												'taxonomy'  => 'uxbarn_portfolio_tax',
												'field' 	=> 'id',
												'terms' 	=> $category_id_list,
											),
										),
				'orderby' 			=> $orderby,
				'order' 			=> $order,
			);
			
		}
		
		$portfolio = new WP_Query( $args );
		
		if ( ! $portfolio->have_posts() ) {
			return '<div class="error box">' . __( 'There are no portfolio items available in the selected categories.', 'uxbarn' ) . '</div>';
		}
		
		if ( $type == 'col3' || $type == 'col4' ) {
			
			$output = 
				'<div class="uxb-port-root-element-wrapper ' . $type . ' ' . $el_class . '">
					<span class="uxb-port-loading-text"><span>' . __( 'Loading', 'uxbarn' ) . '</span></span>
					
					<div class="uxb-port-loaded-element-wrapper">';
					
			if ( $show_filter == 'true' ) {
						
				$filter_string = 
						'<ul class="uxb-port-element-filters">
							<li><a href="#" class="active" data-filter="*">' . __( 'All', 'uxbarn' ) . '</a></li>';
				
				// Generate filter items
				$terms_args = array(
					'include' => $category_id_list,
					'orderby' => 'menu_order',
				);
				
				$terms = get_terms( 'uxbarn_portfolio_tax', $terms_args );
				
				if ( $terms && ! is_wp_error( $terms ) )  {
					
					foreach ( $terms as $term ) {
						$filter_string .= '<li><a href="#" data-filter=".term_' . $term->term_id . '">' . $term->name . '</a></li>';
					}
					
				}
				
				$filter_string .= '</ul>'; // close filter list
				$output .= $filter_string;
				
			}
			
			$output .= '<div class="uxb-port-element-wrapper">';
			
			// Generate grid columns
			if ( $portfolio->have_posts() ) {
				
				while ( $portfolio->have_posts() ) {
					
					$portfolio->the_post();
					
					// Prepare category string for each item's class
					$term_list = '';
					$terms = get_the_terms( get_the_ID(), 'uxbarn_portfolio_tax' );
					
					if ( $terms && ! is_wp_error( $terms ) )  {
						
						foreach ( $terms as $term ) {
							$term_list .= 'term_' . $term->term_id . ' ';
						}
						
					}
					
					$thumbnail = '';
					if ( has_post_thumbnail( get_the_ID() ) ) {
						$thumbnail = get_the_post_thumbnail( get_the_ID(), 'uxb-port-element-thumbnails', array( 'class'=>'border' ) );
					} else {
						$thumbnail = '<img src="' . UXB_PORT_URL . 'images/placeholders/port-grid.gif" alt="' . __( 'No Thumbnail', 'uxbarn' ) . '" />';
					}
					
					$show_title_code = '<hr/><h3>' . get_the_title() . '</h3><hr/>';
					if ( $show_title == 'false' ) {
						$show_title_code = '';
					}
					
					$output .= 
						'<div class="uxb-port-element-item ' . $term_list . ' portfolioid-' . get_the_ID() . '">
							<div class="uxb-port-element-item-hover-wrapper">
								<div class="uxb-port-element-item-hover">
									<a href="' . get_permalink() . '"></a>
									<div class="uxb-port-element-item-hover-info">' . $show_title_code . '</div>
								</div>
							</div>
							' . $thumbnail . '
						</div>';
					
				}

			} else {
				
			}
			
			$output .= '</div>'; // close class="portfolio-wrapper"
			$output .= '</div>'; // close class="portfolio-loaded-wrapper"
			$output .= '</div>'; // close class="portfolio-root-wrapper
			
		} else { // if($type == 'col3' ... ) and this is for "flexslider" type
			
			wp_enqueue_style( 'uxbarn-font-awesome' );
			
			$transition_effect = 'fade';
			
			if ( $type == 'flexslider_slide' ) {
				$transition_effect = 'slide';
			}
			
			if ( $show_bullets == 'false' ) {
				$show_bullets = ' hide-bullets ';
			}
			/*

			$border_class_array = array( 'class' => 'border' );
			
			if ( $border == 'no' ) {
				$border_class_array = array();
			}
			*/

			$output = '<div class="uxb-port-image-slider-root-container ' . $el_class . '">';
			$output .= '<div class="uxb-port-image-slider-wrapper uxb-port-slider-set ' . $show_bullets . '" data-auto-rotation="' . $interval . '" data-effect="' . $transition_effect . '">';
			$output .= '<ul class="uxb-port-image-slider">';
			
			if ( $portfolio->have_posts() ) {
				
				while ( $portfolio->have_posts() ) {
					
					$portfolio->the_post();
					
					// Default case if there is no thumbnail assigned
					$img_tag = '<img class="border" src="' . UXB_PORT_URL . 'images/placeholders/port-slider.gif" alt="' . __( 'No Thumbnail', 'uxbarn' ) . '" />';
					
					if ( has_post_thumbnail( get_the_ID() ) ) {
							
						$attachment_id = get_post_thumbnail_id( get_the_ID() );
						
						// If there is an alternate thumbnail specified, use it instead
						$alternate_thumbnail_url = get_post_meta( get_the_ID(), 'uxbarn_portfolio_alternate_thumbnail', true );
						
						if( $alternate_thumbnail_url != '' ) {
							$attachment_id = uxb_port_get_attachment_id_from_src( $alternate_thumbnail_url );
						}
						
						$attachment = uxb_port_get_attachment( $attachment_id );
						$img_srcset_attr = wp_get_attachment_image_srcset( $attachment_id, 'large' );
						$img_sizes_attr = wp_get_attachment_image_sizes( $attachment_id, 'large' );
		   
						$img_fullsize = $attachment['src']; 
						
						// Get an array: [0] => url, [1] => width, [2] => height
						$img_thumbnail = wp_get_attachment_image_src( $attachment_id, $img_size );
						
						$title = $attachment['title']; //trim(esc_attr(strip_tags( get_post_meta($attachment_id, '_wp_attachment_image_alt', true) )));
						
						$anchor_title = '';
						
						if ( $title != '' ) {
							$anchor_title = ' title="' . $title . '" ';
						}
						
						$img_tag = '<img src="' . esc_url( $img_thumbnail[0] ) . '" class="border" alt="' . esc_attr( $attachment['alt'] ) . '" width="' . intval( $img_thumbnail[1] ) . '" height="' . intval( $img_thumbnail[2] ) . '" srcset="' . esc_attr( $img_srcset_attr ) . '" sizes="' . esc_attr( $img_sizes_attr ) . '" />';
						
					}
					
					// Don't need to apply "width" or "height" here, it's aleady done in css for 100% width.
					$output .= '<li class="uxb-port-image-slider-item">';
					
					$output .= '<a href="' . get_permalink() . '"' . $anchor_title . ' class="image-link">' . $img_tag . '</a>';
					
					if ( trim( $attachment['caption'] ) != '' ) {
						$output .= '<div class="uxb-port-image-caption-wrapper"><div class="uxb-port-image-caption">' . $attachment['caption'] . '</div></div>';
					}
					
					$output .= '</li>'; // close "image-slider-item"
					
				}
			}
			
			$output .= '</ul>'; // close "image-slider"
			$output .= '</div>'; // close "image-slider-wrapper slider-set"
			$output .= 
						'<a href="#" class="uxb-port-slider-controller uxb-port-slider-prev"><i class="fa fa-angle-left"></i></a>
						<a href="#" class="uxb-port-slider-controller uxb-port-slider-next"><i class="fa fa-angle-right"></i></a>';
			$output .= '</div>'; // close "image-slider-root-container"
			
		}
		
		wp_reset_postdata();
		
		return $output;
		
	}

}



// To be used in "single-portfolio.php"
if ( ! function_exists( 'uxbarn_display_port_text_block' ) ) {

    function uxbarn_display_port_text_block( $layout_content_columns ) {
		?>

		<div class="port-text uxb-col <?php echo esc_attr( $layout_content_columns ); ?> columns">
					
			<?php the_content(); ?>
			
		</div>
		
		<?php
	}

}

// To be used in "single-portfolio.php"
if ( ! function_exists( 'uxbarn_display_port_meta_block' ) ) {

    function uxbarn_display_port_meta_block( $show_meta, $meta_date, $meta_client, $meta_website_link, $meta_class, $layout_content_columns, $layout_meta_columns, $plugin_options ) {
		?>

		<?php if ( $show_meta == 'true' ) : ?>
						
			<div class="port-meta uxb-col <?php echo esc_attr( $layout_meta_columns ); ?> columns">
				
				<?php
					
					$use_custom_meta = isset( $plugin_options['uxb_port_po_use_custom_meta_info'] ) ? $plugin_options['uxb_port_po_use_custom_meta_info'] : 'off';
					
					if ( $use_custom_meta == 'on' ) : 
				
				?>
					
					<?php
					
						$custom_set = isset( $plugin_options['uxb_port_po_custom_meta_info'] ) ? $plugin_options['uxb_port_po_custom_meta_info'] : array();
						$display_meta_cat = isset( $plugin_options['uxb_port_po_custom_meta_info_display_categories'] ) ? $plugin_options['uxb_port_po_custom_meta_info_display_categories'] : 'on';
						
						// Custom meta info
						if ( ! empty( $custom_set ) ) :
					?>
					
					<ul id="uxb-port-item-meta" class="<?php echo esc_attr( $meta_class ); ?>">
						
						<?php
							
							foreach ( $custom_set as $item ) {
								
								$meta_unique_id = UXB_PORT_CUSTOM_META_ID_INITIAL . uxb_port_clean_string_for_id( $item['uxb_port_po_custom_meta_info_id'] );
								$custom_meta_title = $item['title'];
								?>
								<li><strong class="title"><?php echo esc_html( $custom_meta_title ); ?></strong><?php echo uxb_port_find_url_create_link( uxb_port_get_portfolio_meta_text( get_post_meta( get_the_ID(), $meta_unique_id, true ) ) ); ?></li>
								<?php
								
							}
							
							if ( $display_meta_cat == 'on' ) {
								uxb_port_print_meta_portfolio_categories();
							}	
						
						?>
						
					</ul>
					
					<?php endif; // if ( ! empty( $custom_set ) ) :?>
					
				<?php else : // Default meta info ?>
					
					<ul id="uxb-port-item-meta" class="<?php echo esc_attr( $meta_class ); ?>">
						<li>
							<strong class="title"><?php _e( 'Date', 'uxbarn' ); ?></strong><?php echo esc_html( $meta_date ); ?>
						</li>
						<li>
							<strong class="title"><?php _e( 'Client', 'uxbarn' ); ?></strong><?php echo esc_html( $meta_client ); ?>
						</li>
						<?php uxb_port_print_meta_portfolio_categories(); ?>
						<li>
							<strong class="title"><?php _e( 'Website', 'uxbarn' ); ?></strong>
							<?php if ( $meta_website_link != '-' ) : ?>
								
								<a href="<?php echo esc_url( $meta_website_link ); ?>" target="_blank">
									<?php
										$to_be_replaced = array( 'http://', 'https://' ); 
										echo str_replace( $to_be_replaced, '', $meta_website_link ); 
									?>
								</a>
								
							<?php else : ?>
								<?php echo esc_html( $meta_website_link ); ?>
							<?php endif; ?>
						</li>
					</ul>
					
				<?php endif; // if ( $use_custom_meta == 'on' ) : ?>
			
			</div>
			
		<?php else : // if($show_meta) ?>
			
			<?php 
				
				$layout_content_columns = ' large-12 ';
				
			?>
			
		<?php endif; ?>
		
		<?php
	}

}
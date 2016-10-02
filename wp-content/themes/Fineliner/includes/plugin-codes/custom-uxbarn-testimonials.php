<?php

/***** UXbarn Testimonials *****/
if ( ! function_exists( 'uxbarn_testimonials_custom' ) ) {
	
	function uxbarn_testimonials_custom() {
		
		// Remove default plugin's image sizes and register them again with some adjustment
		remove_image_size( 'uxb-tmnl-testimonial-thumbnail' );
		add_image_size( 'uxb-tmnl-testimonial-thumbnail', 230, 230, true );
		
		// Filter to override the plugin's shortcode output
		add_filter( 'uxb_tmnl_load_testimonials_shortcode_filter', 'uxbarn_custom_load_testimonials_shortcode', 10, 2 );

		// Filter to override the plugin's CPT argument
		add_filter( 'uxb_tmnl_register_cpt_args_filter', 'uxbarn_custom_tmnl_cpt_args' );
		
	}
	
}



if ( ! function_exists( 'uxbarn_custom_tmnl_cpt_args' ) ) {
		
	function uxbarn_custom_tmnl_cpt_args( $args ) {
		
		$args = array(
				'label' 			=> esc_html__( 'Testimonials', 'uxbarn' ),
				'labels' 			=> array(
											'singular_name'		 => esc_html__( 'Testimonial', 'uxbarn' ),
											'add_new' 			 => esc_html__( 'Add New Testimonial', 'uxbarn' ),
											'add_new_item' 		 => esc_html__( 'Add New Testimonial', 'uxbarn' ),
											'new_item' 			 => esc_html__( 'New Testimonial', 'uxbarn' ),
											'edit_item' 		 => esc_html__( 'Edit Testimonial', 'uxbarn' ),
											'all_items' 		 => esc_html__( 'All Testimonials', 'uxbarn' ),
											'view_item' 		 => esc_html__( 'View Testimonials', 'uxbarn' ),
											'search_items' 		 => esc_html__( 'Search Testimonials', 'uxbarn' ),
											'not_found' 		 => esc_html__( 'Nothing found', 'uxbarn' ),
											'not_found_in_trash' => esc_html__( 'Nothing found in Trash', 'uxbarn' ),
										),
				'menu_icon' 		=> UXB_THEME_ROOT_IMAGE_URL . 'admin/uxbarn-admin-s.jpg',
				'description' 		=> esc_html__( 'Testimonials of your business', 'uxbarn' ),
				'public' 			=> false,
				'show_ui' 			=> true,
				'capability_type' 	=> 'post',
				'hierarchical' 		=> false,
				'has_archive' 		=> false,
				'supports' 			=> array( 'title', 'thumbnail' ),
				'rewrite' 			=> false
				);
				
		return $args;
				
	}

}



if ( ! function_exists( 'uxbarn_custom_load_testimonials_shortcode' ) ) {

    function uxbarn_custom_load_testimonials_shortcode( $val, $atts ) {
		
		if ( function_exists( 'vc_map_get_attributes' ) ) {
			
			extract( vc_map_get_attributes( 'uxb_testimonials', $atts ) );
				
		} else {
			
			$default_atts = array(
								'id_list' 		=> '', 
								'type' 			=> 'full-width', // full-width, left, right
								'width' 		=> '', // % or px 
								'interval' 		=> '0',
								'orderby' 		=> 'ID',
								'order' 		=> 'ASC',
								'extra_class' 	=> '',
							);
							
			extract( shortcode_atts( $default_atts, $atts ) );
			
		}
		
		if ( trim( $id_list ) == '' ) {
            return '<div class="error box">' . __( 'Cannot create the Testimonials element. You need to select any items first.', 'uxb_tmnl' ) . '</div>';
        }
        
        $id_list = explode( ',', $id_list );
        
        $args = array(
                    'post_type' => 'uxbarn_testimonials',
                    'nopaging' 	=> true,
                    'post__in' 	=> $id_list,
                    'orderby' 	=> $orderby,
                    'order' 	=> $order,
                );
                
        $testimonials = new WP_Query( $args );
        //echo var_dump($testimonials);
        
        $style_class = '';
		$width_style_attr = '';
		$type_class = $type;
		
		// left, right styles
		if ( $type != 'full-width' ) { 
			
			$style_class = ' style2 ';
			
        	$unit = '';
			if ( trim( $width ) != '' ) {
	            // Set default prefix to pixel unit if it is not specified
	            if ( strpos( $width, 'px' ) === false && strpos( $width, '%' ) === false ) {
	                $unit = 'px';
	            }
	            
	        } else {
	            // Default width if it is left blank
	            $width = '100';
	            $unit = '%';
	        }
	        
	        $width_style_attr = 'style="width: ' . $width . $unit . '"';
			
		} else { // if it is "full-width" set the class to empty because it doesn't require
			$type_class = '';
		}
        
		$output = '<div class="uxb-tmnl-testimonial-wrapper ' . $style_class . $type_class . '" ' . $width_style_attr . '>';
		$output .= '<div class="uxb-tmnl-testimonial-list" data-auto-rotation="' . $interval . '">';
		
		if ( $testimonials->have_posts() ) {
            while ( $testimonials->have_posts() ) {
            	
                $testimonials->the_post();
				
				$thumbnail = '';
				if ( $type == 'full-width' ) {
					
					if ( has_post_thumbnail( get_the_ID() ) ) {
						$thumbnail = get_the_post_thumbnail( get_the_ID(), 'uxb-tmnl-testimonial-thumbnail' );
					} else {
						$thumbnail = '<img src="' . UXB_TMNL_URL . 'images/placeholders/no-thumbnail.gif" alt="' . __( 'No Thumbnail', 'uxb_tmnl' ) . '" />';
					}
		
					$thumbnail = '<div class="uxb-tmnl-testimonial-thumbnail">' . $thumbnail . '</div>';
		
				}
				
				$cite = get_the_title();
                if ( trim( $cite ) != '' ) {
                    $cite = '<p class="uxb-tmnl-cite">' . $cite . '</p>';
                }
				
				$output .= '<div class="uxb-tmnl-testimonial-item tmnlid-' . get_the_ID() . '">
								<div class="uxb-tmnl-blockquote-wrapper">
									' . $thumbnail . '
									<blockquote>
										<p>' . get_post_meta( get_the_ID(), 'uxbarn_testimonial_text', true ) . '</p>
									</blockquote>
									' . $cite . '
								</div>
							</div>';
							
			}
		}
		
		$output .= '</div>'; // close class="testimonial-list"
		$output .= '<div class="uxb-tmnl-testimonial-bullets"></div>';
		$output .= '</div>'; // close class="testimonial-wrapper"
		

		wp_reset_postdata();
		
		return $output;
		
	}
	
}
	

/*

if ( ! function_exists( 'uxbarn_custom_tmnl_register_widgets' ) ) {
		
	function uxbarn_custom_tmnl_register_widgets() {
		
		unregister_widget( 'UXTestimonialWidget' );
		
		// This custom widget removes some "wp_enqueue_style" and "wp_enqueue_script" since they are already in the theme
		register_widget( 'UXbarnTestimonialWidget_Custom' );
		
	}
	
}*/
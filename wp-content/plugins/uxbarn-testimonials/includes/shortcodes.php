<?php

if ( ! function_exists( 'uxb_tmnl_load_testimonials_shortcode' ) ) {
    
    function uxb_tmnl_load_testimonials_shortcode( $atts ) {
    	
		// Making this shortcode output "override-able" by the filter with custom callback in theme
		$output = apply_filters( 'uxb_tmnl_load_testimonials_shortcode_filter', '', $atts );
		if ( $output != '' ) {
			return $output;
		}
		
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
        
		$output .= '<div class="uxb-tmnl-testimonial-wrapper ' . $style_class . $type_class . '" ' . $width_style_attr . '>';
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
				
				$output .= '<div class="uxb-tmnl-testimonial-item">
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
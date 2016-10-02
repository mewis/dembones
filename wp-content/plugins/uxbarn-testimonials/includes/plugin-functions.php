<?php


if ( ! function_exists( 'uxb_tmnl_register_plugin_image_sizes' ) ) {
	
	function uxb_tmnl_register_plugin_image_sizes() {
		add_image_size( 'uxb-tmnl-testimonial-thumbnail', 230, 230, true );
	}
	
}



// *** Deprecated since v1.0.4 ***
// For getting array value when using with OptionTree meta box
if ( ! function_exists( 'uxb_tmnl_get_array_value' ) ) {
	
	function uxb_tmnl_get_array_value( $array, $index ) {
	    return isset( $array[ $index ] ) ? $array[ $index ] : '';
	}
	
}



// For checking whether there is specified shortcode incluced in the current post
if ( ! function_exists( 'uxb_tmnl_has_shortcode' ) ) {
		
	function uxb_tmnl_has_shortcode( $shortcode = '', $content ) {
	    
	    // false because we have to search through the post content first
	    $found = false;
	    
	    // if no short code was provided, return false
	    if ( ! $shortcode ) {
	        return $found;
	    }
	    // check the post content for the short code
	    if ( stripos( $content, '[' . $shortcode) !== false ) {
	        // we have found the short code
	        $found = true;
	    }
	    
	    // return our final results
	    return $found;
	}

}



if ( ! function_exists( 'uxb_tmnl_update_element_params' ) ) {
		
	function uxb_tmnl_update_element_params() {
		
		if ( class_exists( 'WPBMap' ) && function_exists( 'vc_update_shortcode_param' ) ) {
			
			$vc_element = 'uxb_testimonials';
			
			if ( WPBMap::exists( $vc_element ) ) {
				
				$param = WPBMap::getParam( $vc_element, 'id_list' );
				if ( $param ) {
					$param['value'] = uxb_tmnl_get_testimonial_posts();
					vc_update_shortcode_param( $vc_element, $param );
				}
				
			}
			
		}
		
	}
	
}



if ( ! function_exists( 'uxb_tmnl_get_testimonial_posts' ) ) {
		
	function uxb_tmnl_get_testimonial_posts() {
		
		// Prepare ID list array for selection
		$id_array = array();
		
		$args = array(
		            'post_type' => 'uxbarn_testimonials',
		            'nopaging' 	=> true,
		            'orderby' 	=> 'title',
		            'order' 	=> 'ASC',
		        );
				
		$testimonials = get_posts( $args );
		
		if ( ! empty( $testimonials ) ) {
			
			foreach ( $testimonials as $post ) : setup_postdata( $post );
			
				// If WPML is active
				if ( function_exists( 'icl_object_id' ) && is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
					
					$original_id = $post->ID;
					
					global $sitepress;
					
					if ( isset( $sitepress ) ) {
						
						$default_lang = $sitepress->get_default_language();
						
						// WPML's function
						$post_lang_info = array();
						
						if ( version_compare( ICL_SITEPRESS_VERSION, '3.2', '>=' ) ) {
							$post_lang_info = apply_filters( 'wpml_post_language_details', NULL, $original_id );
							//Code for the new version greater than or equal to 3.2
						} else {
							$post_lang_info = wpml_get_language_information( $original_id );
							//support for older versions
						}
						
						// If the post is the translated one (not default lang)
						if ( ! empty( $post_lang_info ) && strpos( $post_lang_info['locale'], $default_lang ) !== false ) {
							
							// If the post is translated, display it or else, display the original title
							$title = get_the_title( icl_object_id( $original_id, 'uxbarn_testimonials', true ) );
							$id_array[ $title ] = $original_id;
							
						}
						
					} else {
						$id_array[ $post->post_title ] = $post->ID;
					}
					
				} else { // If there is no WPML
					$id_array[ $post->post_title ] = $post->ID;
				}
				
			endforeach;
			
		} else {
			$id_array = array( 'No items' => -1 );
		}

		wp_reset_postdata();
		
		return $id_array;
	}
	
}



if ( ! function_exists( 'uxb_tmnl_load_testimonials_element' ) ) {
	
	function uxb_tmnl_load_testimonials_element() {
		
		$list_heading = __( 'Available items', 'uxb_tmnl' );
		
		if ( function_exists( 'vc_map' ) ) {
			
			vc_map( array(
			   'name' 		=> __( 'Testimonials', 'uxb_tmnl' ),
			   'base' 		=> 'uxb_testimonials',
			   'icon' 		=> 'icon-wpb-uxb_testimonials',
			   'class' 		=> '',
			   'category' 	=> __( 'Content', 'uxb_tmnl' ),
			   'params' 	=> array(
			      array(
			         'type' 		=> 'checkbox',
			         'holder' 		=> 'div',
			         'class' 		=> '',
			         'heading' 		=> $list_heading,
			         'param_name' 	=> 'id_list',
			         'value' 		=> '', // will be updated later in uxb_tmnl_update_element_params()
			         'description' 	=> __( 'Select the items from the list.', 'uxb_tmnl' ),
			         'admin_label' 	=> false,
			      ),
			      array(
			         'type' 		=> 'dropdown',
			         'holder' 		=> 'div',
			         'class' 		=> '',
			         'heading' 		=> __( 'Style', 'uxb_tmnl' ),
			         'param_name' 	=> 'type',
			         'value' 		=> array(
					                        __( 'Full-width + thumbnail (work best on 1/1 column)', 'uxb_tmnl' ) => 'full-width', 
					                        __( 'Text only + float left', 'uxb_tmnl' ) 	=> 'left',
					                        __( 'Text only + float right', 'uxb_tmnl' ) => 'right',
					                    ),
			         'description' 	=> __( 'Choose the testimonial style.', 'uxb_tmnl' ),
			         'admin_label' 	=> true,
	                 'std'			=> 'full-width',
			      ),
			      array(
			         'type' 		=> 'textfield',
			         'holder' 		=> 'div',
			         'class' 		=> '',
			         'heading' 		=> __( 'Width', 'uxb_tmnl' ),
			         'param_name' 	=> 'width',
			         'value' 		=> '',
			         'description' 	=> __( 'Specify the width in % or px unit. Example: <em>400px</em> OR <em>50%</em>. Leave it blank to use 100% width as default.', 'uxb_tmnl' ),
			         'dependency' 	=> array(
			                            'element' 	=> 'type',
			                            'value' 	=> array( 'left', 'right' ),
			                        ),
			         'admin_label' 	=> false,
			      ),
			      array(
	                 'type' 		=> 'dropdown',
	                 'holder' 		=> 'div',
	                 'class' 		=> '',
	                 'heading' 		=> __( 'Auto rotation duration', 'uxb_tmnl' ),
	                 'param_name' 	=> 'interval',
	                 'value' 		=> array(
			                                __( 'Disable auto rotation', 'uxb_tmnl' ) => '0',
			                                '5' => '5',
			                                '6' => '6',
			                                '7' => '7',
			                                '8' => '8',
			                                '9' => '9',
			                                '10' => '10',
			                                '12' => '12',
			                                '14' => '14',
			                                '16' => '16',
			                                '18' => '18',
			                                '20' => '20',
			                                '25' => '25',
			                                '30' => '30',
			                                '40' => '40',
			                                '60' => '60',
			                                '80' => '80',
			                                '100' => '100',
			                            ),
	                 'description' 	=> __( 'Select how many seconds to stay on current slide before rotating to the next one.', 'uxb_tmnl' ),
	                 'admin_label' 	=> false,
	                 'std'			=> '0',
	              ),
	              array(
		             'type' 		=> 'dropdown',
		             'holder' 		=> 'div',
		             'class' 		=> '',
		             'heading' 		=> __( 'Order by', 'uxb_tmnl' ),
		             'param_name' 	=> 'orderby',
		             'value' 		=> array(
				                            __( 'ID', 'uxb_tmnl' ) 			 	=> 'ID', 
				                            __( 'Title', 'uxb_tmnl' ) 		 	=> 'title',
				                            __( 'Slug', 'uxb_tmnl' ) 			=> 'name',
				                            __( 'Published Date', 'uxb_tmnl' ) 	=> 'date',
				                            __( 'Modified Date', 'uxb_tmnl' )  	=> 'modified',
				                            __( 'Random', 'uxb_tmnl' ) 		 	=> 'rand',
				                        ),
		             'description' 	=> __( 'Select the which parameter to be used for ordering. <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">See more info here</a>', 'uxb_tmnl' ),
		             'admin_label' 	=> false,
	                 'std'			=> 'ID',
		          ),
		          array(
		             'type' 		=> 'dropdown',
		             'holder' 		=> 'div',
		             'class' 		=> '',
		             'heading' 		=> __( 'Order', 'uxb_tmnl' ),
		             'param_name' 	=> 'order',
		             'value' 		=> array(
				                            __( 'Ascending', 'uxb_tmnl' )  => 'ASC', 
				                            __( 'Descending', 'uxb_tmnl' ) => 'DESC',
				                        ),
		             'description' 	=> __( '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">See more info here</a>', 'uxb_tmnl' ),
		             'admin_label' 	=> false,
	                 'std'			=> 'ASC',
		          ),
			   )
			) );
			
		}
		
	}
	
}



if ( ! function_exists( 'uxb_tmnl_load_shortcodes' ) ) {
		
	function uxb_tmnl_load_shortcodes() {
		
		add_shortcode( 'uxb_testimonials', 'uxb_tmnl_load_testimonials_shortcode' );
		
	}
	
}



if ( ! function_exists( 'uxb_tmnl_register_widgets' ) ) {
		
	function uxb_tmnl_register_widgets() {
		
    	register_widget( 'UXTestimonialWidget' );
		
	}
	
}



if ( ! function_exists( 'uxb_tmnl_get_allowed_tags_for_widgets' ) ) {
		
	function uxb_tmnl_get_allowed_tags_for_widgets() {
		
		return array(
					'div' => array( 'id' => array(), 'class' => array() ),
					'h5' => array( 'class' => array() ),
					'h4' => array( 'class' => array() ),
					'h3' => array( 'class' => array() ),
				);
				
	}

}
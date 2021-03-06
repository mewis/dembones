<?php


if ( ! function_exists( 'uxb_team_register_plugin_image_sizes' ) ) {
	
	function uxb_team_register_plugin_image_sizes() {
		add_image_size( 'uxb-team-single-page', 400, 9999, true );
	}
	
}



// *** Deprecated since v1.1.3 ***
// For getting array value when using with OptionTree meta box
if ( ! function_exists( 'uxb_team_get_array_value' ) ) {
	
	function uxb_team_get_array_value( $array, $index ) {
	    return isset( $array[ $index ] ) ? $array[ $index ] : '';
	}
	
}



// For checking whether there is specified shortcode incluced in the current post
if ( ! function_exists( 'uxb_team_has_shortcode' ) ) {
		
	function uxb_team_has_shortcode( $shortcode = '', $content ) {
	    
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



if ( ! function_exists( 'uxb_team_update_element_params' ) ) {
		
	function uxb_team_update_element_params() {
		
		if ( class_exists( 'WPBMap' ) && function_exists( 'vc_update_shortcode_param' ) ) {
			
			$vc_element = 'uxb_team';
			
			if ( WPBMap::exists( $vc_element ) ) {
				
				$param = WPBMap::getParam( $vc_element, 'member_id' );
				if ( $param ) {
					$param['value'] = uxb_team_get_team_members();
					vc_update_shortcode_param( $vc_element, $param );
				}
				
			}
			
		}
		
	}
	
}



if ( ! function_exists( 'uxb_team_get_team_members' ) ) {
		
	function uxb_team_get_team_members() {
		// Prepare ID list array for selection
		global $post; // required 
		
		$id_array = array();
		
		$args = array(
		            'post_type' => 'uxbarn_team',
		            'nopaging' 	=> true,
		            'orderby' 	=> 'title',
		            'order' 	=> 'ASC',
		        );
				
		$items = get_posts( $args );
		
		if ( ! empty( $items ) ) {
			
			foreach ( $items as $post ) : setup_postdata( $post );
				
				$post_id_text =  ' (ID: ' . $post->ID . ')';
				
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
							$title = get_the_title( icl_object_id( $original_id, 'uxbarn_team', true ) );
							$id_array[ $title . $post_id_text ] = get_the_title( $original_id ) . '|' . $original_id;
							
						}
						
					} else {
						$id_array[ $post->post_title . $post_id_text ] = get_the_title() . '|' . get_the_ID();
					}
					
				} else { // If there is no WPML
					$id_array[ $post->post_title . $post_id_text ] = get_the_title() . '|' . get_the_ID();
				}
				
			endforeach;
			
		}
		
		array_unshift( $id_array, __( '--- Select Member ---', 'uxbarn' ) );
		
		wp_reset_postdata();
		
		return $id_array;
		
	}
	
}



if ( ! function_exists( 'uxb_team_load_team_member_element' ) ) {
	
	function uxb_team_load_team_member_element() {
		
		if ( function_exists( 'vc_map' ) ) {
		
			vc_map( array(
			   'name' 		=> __( 'Team Member', 'uxb_team' ),
			   'base' 		=> 'uxb_team',
			   'icon' 		=> 'icon-wpb-uxb_team',
			   'class' 		=> '',
			   'category' 	=> __( 'Content', 'uxb_team' ),
			   'params' 	=> array(
			      array(
			         'type' 		=> 'dropdown',
			         'holder' 		=> 'div',
			         'class' 		=> '',
			         'heading' 		=> __( 'Team member', 'uxb_team' ),
			         'param_name' 	=> 'member_id',
			         'value' 		=> '', // will be updated later in uxb_team_update_element_params()
			         'description' 	=> __( 'Select a member to be added into the column.', 'uxb_team' ),
			         'admin_label' 	=> true,
			      ),
			      array(
			         'type' 		=> 'dropdown',
			         'holder' 		=> 'div',
			         'class' 		=> '',
			         'heading' 		=> __( 'Thumbnail size', 'uxb_team' ),
			         'param_name' 	=> 'image_size',
			         'value' 		=> uxb_team_get_image_size_array(),
			         'description' 	=> __( 'Select which size to be used for the member thumbnail. Anyway, the image will be scaled depending on its original size and containing column. If you are not sure which one to use, try <em>Original size</em>.', 'uxb_team' ),
			         'admin_label' 	=> false,
			      ),
			      array(
			         'type' 		=> 'dropdown',
			         'holder' 		=> 'div',
			         'class' 		=> '',
			         'heading' 		=> __( 'Link?', 'uxb_team' ),
			         'param_name' 	=> 'link',
			         'value' 		=> array(
					                        __( 'Yes, enable link on thumbnail and member name to the single page', 'uxb_team' ) => 'true',
					                        __( 'No link', 'uxb_team' ) => 'false',
					                    ),
			         'description' 	=> __( "Whether to have a link to member's single page.", 'uxb_team' ),
			         'admin_label' 	=> false,
			      ),
			      array(
			         'type' 		=> 'dropdown',
			         'holder' 		=> 'div',
			         'class' 		=> '',
			         'heading' 		=> __( 'Heading size', 'uxb_team' ),
			         'param_name' 	=> 'heading_size',
			         'value' 		=> array(
					                        __( 'Large', 'uxb_team' ) 	=> 'large',
					                        __( 'Smaller', 'uxb_team' ) => 'small',
					                    ),
			         'description' 	=> __( 'Select the size for heading which is used to display name and position.', 'uxb_team' ),
			         'admin_label' 	=> false,
			      ),
			      array(
			         'type' 		=> 'dropdown',
			         'holder' 		=> 'div',
			         'class' 		=> '',
			         'heading' 		=> __( 'Display social icons?', 'uxb_team' ),
			         'param_name' 	=> 'display_social',
			         'value' 		=> array(
					                        __( 'Yes', 'uxb_team' ) => 'true',
					                        __( 'No', 'uxb_team' ) 	=> 'false',
					                    ),
			         'description' 	=> __( 'Whether to display the social network icons.', 'uxb_team' ),
			         'admin_label' 	=> false,
			      ),
			      array(
			          'type' 		=> 'dropdown',
			          'heading' 	=> __( 'CSS Animation', 'uxb_team' ),
			          'param_name' 	=> 'css_animation',
			          'admin_label' => false,
			          'value' 		=> array( 
				          					__( 'No', 'uxb_team' ) 				 => '', 
				          					__( 'Top to bottom', 'uxb_team' ) 	 => 'top-to-bottom', 
				          					__( 'Bottom to top', 'uxb_team' ) 	 => 'bottom-to-top', 
				          					__( 'Left to right', 'uxb_team' ) 	 => 'left-to-right', 
				          					__( 'Right to left', 'uxb_team' ) 	 => 'right-to-left', 
				          					__( 'Appear from center', 'uxb_team' ) => 'appear',
										),
			          'description' => __( 'Select animation type if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'uxb_team' )
		        	),
			   )
			) );
		
		}
		
	}
	
}



if ( ! function_exists( 'uxb_team_load_shortcodes' ) ) {
		
	function uxb_team_load_shortcodes() {
		
		add_shortcode( 'uxb_team', 'uxb_team_load_team_shortcode' );
		
	}
	
}



if ( ! function_exists( 'uxb_team_get_css_animation_complete_class' ) ) {
    
    function uxb_team_get_css_animation_complete_class( $css_animation ) {
    	
        // Code copied from "/lib/shortcodes.php" of VC plugin v3.6.5. Function: getCSSAnimation()
        if ( $css_animation != '' ) {
        	
            wp_enqueue_script( 'waypoints' );
            return ' wpb_animate_when_almost_visible wpb_' . $css_animation;
			
        } else {
            return '';
        }
        
    }
	
}



if ( ! function_exists( 'uxb_team_get_member_social_list_string' ) ) {
	
	function uxb_team_get_member_social_list_string( $member_id ) {
		
		$plugin_options = get_option( 'uxb_team_plugin_options', '' );
		$social_set = isset( $plugin_options['uxb_team_po_social_set'] ) ? $plugin_options['uxb_team_po_social_set'] : 'default';
		
		// Default set
		if ( $social_set == '' || $social_set == 'default' ) {
			
		    $social_name_array = array(
		        __('Twitter', 'uxb_team') 	=> 'uxbarn_team_social_twitter', 
		        __('Facebook', 'uxb_team') 	=> 'uxbarn_team_social_facebook', 
		        __('Google+', 'uxb_team') 	=> 'uxbarn_team_social_googleplus',  
		        __('LinkedIn', 'uxb_team') 	=> 'uxbarn_team_social_linkedin', 
		        __('Dribbble', 'uxb_team') 	=> 'uxbarn_team_social_dribbble', 
		        __('Forrst', 'uxb_team') 	=> 'uxbarn_team_social_forrst', 
		        __('Flickr', 'uxb_team') 	=> 'uxbarn_team_social_flickr',
		    );
		    
		    foreach ( $social_name_array as $key => $value ) {
		        uxb_team_get_member_social_list_item( $member_id, $value, $key );
		    }
			
		} else { // Custom set
		
			$custom_set = ( isset( $plugin_options['uxb_team_po_social_custom_set'] ) ? $plugin_options['uxb_team_po_social_custom_set'] : array() );
			
			if ( ! empty( $custom_set ) ) {
				
				foreach ( $custom_set as $icon ) {
					
					$social_unique_id = 'uxb_team_po_social_custom_set_' . uxb_team_clean_string_for_id ( $icon['uxb_team_po_social_custom_set_id'] );
					
					$title = $icon['title'];
					$url = trim( get_post_meta( $member_id, $social_unique_id, true ) ); // custom generated ID of meta field
					$image_url = $icon['uxb_team_po_social_custom_set_icon'];
					
					$icon_width = 22;
					$icon_height = 22;
					$icon_attachment = wp_get_attachment_image_src( uxb_team_get_attachment_id_from_src( $image_url ) );
					
					if ( $icon_attachment ) {
						
						$icon_width = $icon_attachment[1];
						$icon_height = $icon_attachment[2];
						
					}
					
					if ( $url ) {
						?>
						<li><a href="<?php echo esc_url( $url ); ?>" target="_blank"><img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" title="<?php echo esc_attr( $title ); ?>" width="<?php echo intval( $icon_width ); ?>" height="<?php echo intval( $icon_height ); ?>" /></a></li>
						<?php
					}
					
				}
				
			} else {
				echo '';
			}
		
		}
		
	}
	
}

if ( ! function_exists( 'uxb_team_get_member_social_list_item' ) ) {
	
	function uxb_team_get_member_social_list_item( $member_id, $custom_field_id, $name ) {
	    
	    $link = trim( get_post_meta( $member_id, $custom_field_id, true ) );
		$filename = strtolower( $name );
	    
		if ( $filename == 'google+' ) {
			$filename = 'google_plus';
		}

		// Default plugin icon, width and height
		$icon_image_src = UXB_TEAM_URL . 'images/social/' . $filename . '.png';
		$icon_width = 22;
		$icon_height = 22;

		// If there is custom icon specified, use it instead
		$plugin_options = get_option( 'uxb_team_plugin_options' );
		$option_icon = $plugin_options[ 'uxb_team_po_social_icon_' . $filename ];
		
		if ( trim( $option_icon ) != '' ) {
			
			$icon_image_src = $option_icon;
			$icon_attachment = wp_get_attachment_image_src( uxb_team_get_attachment_id_from_src( $option_icon ) );
			
			if ( $icon_attachment ) {
				
				$icon_width = $icon_attachment[1];
				$icon_height = $icon_attachment[2];
				
			}
			
		}

	    if ( $link ) {
	    	?>
	        <li><a href="<?php echo esc_url( $link ); ?>" target="_blank"><img src="<?php echo esc_url( $icon_image_src ); ?>" alt="<?php echo esc_attr( $name ); ?>" title="<?php echo esc_attr( $name ); ?>" width="<?php echo intval( $icon_width ); ?>" height="<?php echo intval( $icon_height ); ?>" /></a></li>
	        <?php
	    } else {
	        echo '';
	    }
	}
	
}



if ( ! function_exists( 'uxb_team_get_attachment_id_from_src' ) ) {
	
	function uxb_team_get_attachment_id_from_src( $image_src ) {
		
	    global $wpdb;
	    $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
	    $id = $wpdb->get_var( $query );
	    return $id;
		
	}

}



if ( ! function_exists( 'uxb_team_clean_string_for_id' ) ) {
	
	function uxb_team_clean_string_for_id( $string ) {
		
		$string = str_replace( ' ', '_', strtolower( $string ) ); // Replaces all spaces with underscores.
		return preg_replace( '/[^A-Za-z0-9\-]/', '', $string ); // Removes special chars.
	   
	}

}
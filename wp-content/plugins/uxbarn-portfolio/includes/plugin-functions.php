<?php


if ( ! function_exists( 'uxb_port_register_plugin_image_sizes' ) ) {
	
	function uxb_port_register_plugin_image_sizes() {
		
	    add_image_size( 'uxb-port-element-thumbnails', 320, 9999 );
		add_image_size( 'uxb-port-related-items', 232, 232, true );
		add_image_size( 'uxb-port-single-landscape', 1100, 676, true );
		add_image_size( 'uxb-port-single-portrait', 600, 816, true );
		add_image_size( 'uxb-port-large-square', 400, 400, true );
		
	}
	
}



// Deprecated since v1.1.4
// For getting array value when using with OptionTree meta box
if ( ! function_exists( 'uxb_port_get_array_value' ) ) {
	
	function uxb_port_get_array_value( $array, $index ) {
	    return isset( $array[ $index ] ) ? $array[ $index ] : '';
	}
	
}



if ( ! function_exists( 'uxb_port_get_portfolio_meta_text' ) ) {
	
	function uxb_port_get_portfolio_meta_text( $string ) {
	    if ( trim( $string ) == '' || trim( $string ) == 'http://' ) {
	        return '-';
	    } else {
	        return $string;
	    }
	}
	
}



if ( ! function_exists( 'uxb_port_get_attachment' ) ) {
	
	function uxb_port_get_attachment( $attachment_id ) {
	    
	    $attachment = get_post( $attachment_id );
	    
	    // Need to check it first
	    if( isset( $attachment ) ) {
	            
	       	return array(
	            'alt' 			=> get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
	            'caption' 		=> $attachment->post_excerpt,
	            'description' 	=> $attachment->post_content,
	            'href' 			=> get_permalink($attachment->ID),
	            'src' 			=> $attachment->guid,
	            'title' 		=> $attachment->post_title,
	        );
	    
	    } else {
	        return array(
	            'alt' 			=> 'N/A',
	            'caption' 		=> 'N/A',
	            'description' 	=> 'N/A',
	            'href' 			=> 'N/A',
	            'src' 			=> 'N/A',
	            'title' 		=> 'N/A',
	        );
	    }
	}
	
}




if ( ! function_exists( 'uxb_port_get_attachment_id_from_src' ) ) {
	
	function uxb_port_get_attachment_id_from_src( $image_src ) {
		
	    global $wpdb;
	    $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
	    $id = $wpdb->get_var( $query );
	    return $id;
		
	}

}



if ( ! function_exists( 'uxb_port_alter_query_object' ) ) {
	
	function uxb_port_alter_query_object( $query ) {
		
		if ( ! is_admin() && $query->is_main_query() ) {
			
			if ( is_tax( 'uxbarn_portfolio_tax' ) ) {
				$query->set( 'posts_per_page', -1 ); // Reset posts-per-page for taxonomy-portfolio.php
			}
			
		}
		
	}
	
}



// For checking whether there is specified shortcode incluced in the current post
if ( ! function_exists( 'uxb_port_has_shortcode' ) ) {
		
	function uxb_port_has_shortcode( $shortcode = '', $content ) {
	    
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



if ( ! function_exists( 'uxb_port_update_element_params' ) ) {
		
	function uxb_port_update_element_params() {
		
		if ( class_exists( 'WPBMap' ) && function_exists( 'vc_update_shortcode_param' ) ) {
			
			$vc_element = 'uxb_portfolio';
			
			if ( WPBMap::exists( $vc_element ) ) {
				
				$param = WPBMap::getParam( $vc_element, 'categories' );
				if ( $param ) {
					$param['value'] = uxb_port_get_portfolio_terms();
					vc_update_shortcode_param( $vc_element, $param );
				}
				
			}
			
		}
		
	}
	
}



if ( ! function_exists( 'uxb_port_get_portfolio_terms' ) ) {
		
	function uxb_port_get_portfolio_terms() {
		
		$id_array = array();
		//$id_array[''] = ''; // Set first dummy item (not used)
		$args = array(
					'hide_empty' 	=> 0,
					'orderby' 		=> 'title',
					'order' 		=> 'ASC',
					'parent'		=> 0,
				);
		
		$taxonomy_name = 'uxbarn_portfolio_tax';
		$terms = get_terms( $taxonomy_name, $args );
		//echo var_dump($terms);
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
			
			foreach ( $terms as $term ) {
				
				$term_id_text =  ' <span style="display: none">(ID: ' . $term->term_id . ' )</span>';
				
				// If WPML is active (function is available)
				if ( function_exists( 'icl_object_id' ) && is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
					
					global $sitepress;
					
					if ( isset( $sitepress ) ) {
						
						$default_lang = $sitepress->get_default_language();
						
						// Text will be changed depending on current active lang, but the IDs are still original ones from default lang
						$id_array[ $term->name . $term_id_text ] = icl_object_id( $term->term_id, $taxonomy_name, true, $default_lang );
						$id_array = uxb_port_generate_subcategories( $id_array, $term, $taxonomy_name, true, $default_lang );
						
					} else {
						$id_array[ $term->name . $term_id_text ] = $term->term_id;
						$id_array = uxb_port_generate_subcategories( $id_array, $term, $taxonomy_name );
					}
						
				} else { // If there is no WPML
				
					$id_array[ $term->name . $term_id_text ] = $term->term_id;
					$id_array = uxb_port_generate_subcategories( $id_array, $term, $taxonomy_name );
					
				}
				
			}
			
		}
		
		return $id_array;
		
	}
	
}



if ( ! function_exists( 'uxb_port_generate_subcategories' ) ) {
		
	function uxb_port_generate_subcategories( $id_array, $term, $taxonomy_name, $wpml = false, $default_lang = '' ) {
		
		$child_terms_array = get_term_children( $term->term_id, $taxonomy_name );
		//echo var_dump($child_terms_array);
		
		if ( ! empty( $child_terms_array ) ) {
			foreach ( $child_terms_array as $child_term_id ) {
				
				$child_term = get_term( $child_term_id, $taxonomy_name );
				
				if ( $child_term && ! is_wp_error( $child_term ) )  {
						
					$child_term_id_value = $child_term->term_id;
					
					if ( $wpml ) {
						$child_term_id_value = icl_object_id( $child_term->term_id, $taxonomy_name, true, $default_lang );
					}
					
					$id_array[ '--- ' . $child_term->name . ' <span style="display: none">(ID: ' . $child_term->term_id . ' )</span>' ] = $child_term_id_value;
					
				}
				
			}
		}
		
		return $id_array;
		
	}

}



if ( ! function_exists( 'uxb_port_load_portfolio_element' ) ) {
	
	function uxb_port_load_portfolio_element() {
		
		if ( function_exists( 'vc_map' ) ) {
			
			vc_map( array(
			   'name' 		=> __( 'Portfolio', 'uxb_port' ),
			   'base' 		=> 'uxb_portfolio',
			   'icon' 		=> 'icon-wpb-uxb_portfolio',
			   'class' 		=> '',
			   'category' 	=> __( 'Content', 'uxb_port' ),
			   'params' 	=> array(
			      array(
			         'type' 		=> 'checkbox',
			         'holder' 		=> 'div',
			         'class' 		=> '',
			         'heading' 		=> __( 'Portfolio categories', 'uxb_port' ),
			         'param_name' 	=> 'categories',
			         'value' 		=> '', // will be updated later in uxb_port_update_element_params()
			         'description' 	=> __( 'Select the categories from the list.', 'uxb_port' ),
			         'admin_label' 	=> true,
			      ),
			      array(
			         'type' 		=> 'textfield',
			         'holder' 		=> 'div',
			         'class' 		=> '',
			         'heading' 		=> __( 'Maximum number of items to be displayed', 'uxb_port' ),
			         'param_name' 	=> 'max_item',
			         'value' 		=> '',
			         'description' 	=> __( 'Enter a number to limit the max number of items to be listed. Leave it blank to show all items from the selected categories above. Only number is allowed.', 'uxb_port' ),
			         'admin_label' 	=> true,
			      ),
			      array(
			         'type' 		=> 'dropdown',
			         'holder' 		=> 'div',
			         'class' 		=> '',
			         'heading' 		=> __( 'Type', 'uxb_port'),
			         'param_name' 	=> 'type',
			         'value' 		=> array(
					                        __( 'Grid 3 Columns', 'uxb_port' ) 		=> 'col3',
					                        __( 'Grid 4 Columns', 'uxb_port' ) 		=> 'col4',
					                        __( 'Slider (fade transition)', 'uxb_port' )  => 'flexslider_fade',
					                        __( 'Slider (slide transition)', 'uxb_port' ) => 'flexslider_slide',
					                    ),
			         'description' => __('Select the display type for this element.', 'uxb_port'),
			         'admin_label' => true,
			         'std'			=> 'col4',			
			      ),
			      array(
			         'type' 		=> 'dropdown',
			         'holder' 		=> 'div',
			         'class' 		=> '',
			         'heading' 		=> __( 'Show category filter', 'uxb_port' ),
			         'param_name' 	=> 'show_filter',
			         'value' 		=> array(
					                        __( 'Yes', 'uxb_port' ) => 'true',
					                        __( 'No', 'uxb_port' ) 	=> 'false',
					                    ),
					'std'	=> 'true',
			         'description' 	=> __( 'Whether to display the category filter at the top of the element.', 'uxb_port' ),
			         'dependency' 	=> array(
				                            'element' => 'type',
				                            'value' => array( 'col3', 'col4' ),
				                        ),
			         'admin_label' 	=> false,
			      ),
			      /*
				  array(
								   'type' 		=> 'dropdown',
								   'holder' 		=> 'div',
								   'class' 		=> '',
								   'heading' 		=> __( 'Show title on hover', 'uxb_port' ),
								   'param_name' 	=> 'show_title',
								   'value' 		=> array(
														  __( 'Yes', 'uxb_port' ) => 'true',
														  __( 'No', 'uxb_port' ) 	=> 'false',
													  ),
								   'description' 	=> __( 'Whether to display the item title on mouse hover.', 'uxb_port' ),
								   'dependency' 	=> array(
														  'element' => 'type',
														  'value' => array( 'col3', 'col4' ),
													  ),
								   'admin_label' 	=> false,
								),*/
				  
				  array(
			         'type'			=> 'dropdown',
			         'holder' 		=> 'div',
			         'class' 		=> '',
			         'heading' 		=> __( 'Thumbnail size', 'uxb_port' ),
			         'param_name' 	=> 'img_size',
			         'value' 		=> uxb_port_get_image_size_array(),
			         'description' 	=> __( 'Select which size to be used for the thumbnails. Anyway, the image will be scaled depending on its original size and containing column. If you are not sure which one to use, try <em>Large Square</em> or <em>Original size</em>.', 'uxb_port' ),
			         'admin_label' 	=> false,
			         'dependency' 	=> array(
				                            'element' 	=> 'type',
				                            'value' 	=> array( 'flexslider_fade', 'flexslider_slide' ),
				                        ),
			      ),
			      uxb_port_get_auto_rotation( 'type', array( 'flexslider_fade', 'flexslider_slide' ) ),
			      uxb_port_get_show_bullets( 'type', array( 'flexslider_fade', 'flexslider_slide' ) ),
			      uxb_port_get_orderby(),
			      uxb_port_get_order(),
			      uxb_port_get_extra_class_name(),
			   )
			   
			) );
			
		}
		
	}

}



if ( ! function_exists( 'uxb_port_load_shortcodes' ) ) {
		
	function uxb_port_load_shortcodes() {
		
		add_shortcode( 'uxb_portfolio', 'uxb_port_load_portfolio_shortcode' );
		
	}
	
}



if ( ! function_exists( 'uxb_port_sanitize_numeric_input' ) ) {
		
	function uxb_port_sanitize_numeric_input( $input, $default ) {
	    
	    if ( trim( $input ) != '' ) {
	        
	        if ( is_numeric( $input ) ) {
	            return $input;
	        } else {
	            return $default;
	        }
	        
	    } else {
	        return $default;
	    }
	    
	}
	
}




if ( ! function_exists( 'uxb_port_is_using_vc' ) ) {
	
	function uxb_port_is_using_vc() {
	    
	    // If user is using VC for the content
	    if ( uxb_port_has_shortcode( 'vc_row', get_the_content() ) ) {
	    	return true;
	    } else { // In case the user is using normal post editor (no "vc_row" shortcode found)
	    	return false;
	    }
	}

}




if ( ! function_exists( 'uxb_port_clean_string_for_id' ) ) {
	
	function uxb_port_clean_string_for_id( $string ) {
		
		$string = str_replace( ' ', '_', strtolower( $string ) ); // Replaces all spaces with underscores.
		return preg_replace( '/[^A-Za-z0-9\-]/', '', $string ); // Removes special chars.
	   
	}

}



if ( ! function_exists( 'uxb_port_print_meta_portfolio_categories' ) ) {

	function uxb_port_print_meta_portfolio_categories() {
		?>
		
			<li>
	            <strong class="title"><?php _e( 'Categories', 'uxb_port' ); ?></strong>
	        <?php
	        
				$terms = get_the_terms( get_the_ID(), 'uxbarn_portfolio_tax' );
	            if ( $terms && ! is_wp_error( $terms ) )  {
	            	?>
	                <ul id="uxb-port-item-categories">
	                	<?php
	                        foreach ( $terms as $term ) { ?>
	                            <li><a href="<?php echo get_term_link( intval( $term->term_id ), $term->taxonomy ); ?>"><?php echo esc_html( $term->name ); ?></a></li>
	                        <?php }
						?>
	                </ul>
					<?php
	            } else {
	            	echo '-';
	            }
				?>
				</li>
				
		<?php
	}	
		
}



if ( ! function_exists( 'uxb_port_get_optional_scopes_array' ) ) {
	
	function uxb_port_get_optional_scopes_array() {
		
		$plugin_options = get_option( 'uxb_port_plugin_options' );
		$use_custom_meta = isset( $plugin_options['uxb_port_po_use_custom_meta_info'] ) ? $plugin_options['uxb_port_po_use_custom_meta_info'] : 'off';
		
		if ( $use_custom_meta == 'on' ) {
			
			$custom_set = isset( $plugin_options['uxb_port_po_custom_meta_info'] ) ? $plugin_options['uxb_port_po_custom_meta_info'] : array();
			if ( ! empty( $custom_set ) ) {
				
				$final_array = array();
				foreach( $custom_set as $item ) {
					$final_array[] = array(
						'value'       => UXB_PORT_CUSTOM_META_ID_INITIAL . uxb_port_clean_string_for_id( $item['uxb_port_po_custom_meta_info_id'] ), // actual ID to be used on frontend (initial + user-created ID)
	                    'label'       => $item['title'],
	                    'src'         => ''
					);
				}
				
				return $final_array;
				
			} else {
				return array();
			}
			
		} else {
				
			return array( 
	                  array(
	                    'value'       => 'client',
	                    'label'       => __( 'Client', 'uxb_port' ),
	                    'src'         => ''
	                  ),
	                  array(
	                    'value'       => 'website',
	                    'label'       => __( 'Website', 'uxb_port' ),
	                    'src'         => ''
	                  ),
	                  array(
	                    'value'       => 'date',
	                    'label'       => __( 'Date', 'uxb_port' ),
	                    'src'         => ''
	                  )
	                );
					
		}		
	}
		
}



if ( ! function_exists( 'uxb_port_find_url_create_link' ) ) {
		
	function uxb_port_find_url_create_link( $text ) {
		
		// The Regular Expression filter
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

		// Check if there is a url in the text
		if ( preg_match( $reg_exUrl, $text, $url ) ) {
			
			$to_be_replaced = array( 'http://', 'https://' ); 
			// make the urls hyper links
			return preg_replace($reg_exUrl, '<a href="' . $url[0] . '" rel="nofollow" target="_blank">' . str_replace( $to_be_replaced, '', $url[0] ) . '</a>', $text);

		} else {

			// if no urls in the text just return the text
			return $text;

		}
		
	}
	
}
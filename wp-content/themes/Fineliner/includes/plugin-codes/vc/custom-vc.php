<?php

/***** Visual Composer *****/
define( 'UXB_VC_THEME_DEFAULT_STYLE_VALUE', 'theme-default' );



if ( ! function_exists( 'uxbarn_customize_vc_elements' ) ) {

	function uxbarn_customize_vc_elements() {

		if ( class_exists( 'WPBMap' ) && function_exists( 'vc_update_shortcode_param' ) ) {

			// Heading
			uxbarn_customize_vc_element_vc_custom_heading();
			// Button
			uxbarn_customize_vc_element_vc_btn();
			// Image
			uxbarn_customize_vc_element_vc_single_image();
			// Gallery
			uxbarn_customize_vc_element_vc_gallery();
			// Tabs
			uxbarn_customize_vc_element_vc_tta_tabs();
			// Tour
			uxbarn_customize_vc_element_vc_tta_tour();
			// Accordion
			uxbarn_customize_vc_element_vc_tta_accordion();
			// CTA
			uxbarn_customize_vc_element_vc_cta();
			// Google Maps
			uxbarn_customize_vc_element_vc_gmaps();
			// Posts Slider
			uxbarn_customize_vc_element_vc_posts_slider();
			// Message Box
			uxbarn_customize_vc_element_vc_message();
			
		}

	}

}



if ( ! function_exists( 'uxbarn_customize_vc_element_vc_message' ) ) {

	function uxbarn_customize_vc_element_vc_message() {
		
		$vc_element = 'vc_message';
		
		if ( WPBMap::exists( $vc_element ) ) {
			
			/*** "style" ***/
			/* Purpose: to set the default value as square shape */
			$param = WPBMap::getParam( $vc_element, 'style' );
			if ( $param ) {
				$param['std'] = 'square';
				vc_update_shortcode_param( $vc_element, $param );
			}
			
		}
		
	}
	
}

		
		
if ( ! function_exists( 'uxbarn_customize_vc_element_vc_cta' ) ) {

	function uxbarn_customize_vc_element_vc_cta() {
		
		$vc_element = 'vc_cta';
		
		if ( WPBMap::exists( $vc_element ) ) {
				
			$theme_default_style_array = uxbarn_get_theme_default_style_array();

			/*** "style" param ***/
			/* Purpose: to add theme's style as a default option for the element */
			$param = WPBMap::getParam( $vc_element, 'style' );
			if ( $param ) {
				$param['value'] = $theme_default_style_array + $param['value'];
				$param['std'] = UXB_VC_THEME_DEFAULT_STYLE_VALUE;
				vc_update_shortcode_param( $vc_element, $param );
			}



			/*** "shape" ***/
			/* Purpose: to change the default value */
			$param = WPBMap::getParam( $vc_element, 'shape' );
			if ( $param ) {
				$param['std'] = 'square';
				vc_update_shortcode_param( $vc_element, $param );
			}


			/*** "color" ***/
			/* Purpose: to edit the dependency of this param so if the theme's style is used, the param will not display */
			$param = WPBMap::getParam( $vc_element, 'color' );
			if ( $param ) {
				vc_update_shortcode_param( $vc_element, uxbarn_add_dependency_not_equal_to_for_param( $param, array( UXB_VC_THEME_DEFAULT_STYLE_VALUE ) ) );
			}
			

			/*** "btn_style" param ***/
			/* Purpose: to add theme's style as a default option for the element */
			$param = WPBMap::getParam( $vc_element, 'btn_style' );
			if ( $param ) {
				$param['value'] = $theme_default_style_array + $param['value'];
				$param['std'] = UXB_VC_THEME_DEFAULT_STYLE_VALUE;
				vc_update_shortcode_param( $vc_element, $param );
			}


			/*** "btn_color" ***/
			/* Purpose: to edit the dependency of this param so if the theme's style is used, the param will not display */
			$param = WPBMap::getParam( $vc_element, 'btn_color' );
			if ( $param ) {
				vc_update_shortcode_param( $vc_element, uxbarn_add_dependency_not_equal_to_for_param( $param, array( UXB_VC_THEME_DEFAULT_STYLE_VALUE ) ) );
			}


			/*** "btn_shape" ***/
			/* Purpose: to change the default value */
			$param = WPBMap::getParam( $vc_element, 'btn_shape' );
			if ( $param ) {
				$param['std'] = 'square';
				vc_update_shortcode_param( $vc_element, $param );
			}
			
		}
		
	}

}




if ( ! function_exists( 'uxbarn_customize_vc_element_vc_custom_heading' ) ) {

	function uxbarn_customize_vc_element_vc_custom_heading() {
		
		$vc_element = 'vc_custom_heading';
		
		if ( WPBMap::exists( $vc_element ) ) {
				
			/*** "use_theme_fonts" ***/
			/* Purpose: to set the default value to use theme's font */
			$param = WPBMap::getParam( $vc_element, 'use_theme_fonts' );
			if ( $param ) {
				$param['std'] = 'yes';
				vc_update_shortcode_param( $vc_element, $param );
			}
			
		}
		
	}

}



if ( ! function_exists( 'uxbarn_customize_vc_element_vc_single_image' ) ) {

	function uxbarn_customize_vc_element_vc_single_image() {
		
		$vc_element = 'vc_single_image';
		
		if ( WPBMap::exists( $vc_element ) ) {

			/*** "img_size" ***/
			/* Purpose: to set the default value to use full-size image */
			$param = WPBMap::getParam( $vc_element, 'img_size' );
			if ( $param ) {
				$param['value'] = 'full';
				vc_update_shortcode_param( $vc_element, $param );
			}
			
		}

	}

}



if ( ! function_exists( 'uxbarn_customize_vc_element_vc_btn' ) ) {

	function uxbarn_customize_vc_element_vc_btn() {

		$vc_element = 'vc_btn';
		
		if ( WPBMap::exists( $vc_element ) ) {
				
			$theme_default_style_array = uxbarn_get_theme_default_style_array();

			/*** "style" param ***/
			/* Purpose: to add theme's style as a default option for the element */

			// Get current values stored in the "style" param in "Button" element
			$param = WPBMap::getParam( $vc_element, 'style' );
			if ( $param ) {
				// Append new value to the 'value' array
				$param['value'] = $theme_default_style_array + $param['value'];

				// Edit the value of param
				$param['std'] = UXB_VC_THEME_DEFAULT_STYLE_VALUE;

				// Finally "mutate" param with new values
				vc_update_shortcode_param( $vc_element, $param );
			}


			/*** "color" ***/
			/* Purpose: to edit the dependency of this param so if the theme's style is used, the param will not display */
			$param = WPBMap::getParam( $vc_element, 'color' );
			if ( $param ) {
				vc_update_shortcode_param( $vc_element, uxbarn_add_dependency_not_equal_to_for_param( $param, array( UXB_VC_THEME_DEFAULT_STYLE_VALUE ) ) );
			}



			/*** "shape" ***/
			/* Purpose: to change the default value */
			$param = WPBMap::getParam( $vc_element, 'shape' );
			if ( $param ) {
				$param['std'] = 'square';
				vc_update_shortcode_param( $vc_element, $param );
			}
			
		}

	}

}



if ( ! function_exists( 'uxbarn_customize_vc_element_vc_tta_tabs' ) ) {

	function uxbarn_customize_vc_element_vc_tta_tabs() {

		$vc_element = 'vc_tta_tabs';
		
		if ( WPBMap::exists( $vc_element ) ) {
				
			$theme_default_style_array = uxbarn_get_theme_default_style_array();

			/*** "style" param ***/
			/* Purpose: to add theme's style as a default option for the element */
			$param = WPBMap::getParam( $vc_element, 'style' );
			if ( $param ) {
				$param['value'] = $theme_default_style_array + $param['value'];
				$param['std'] = UXB_VC_THEME_DEFAULT_STYLE_VALUE;
				vc_update_shortcode_param( $vc_element, $param );
			}



			/*** "shape", "color", "no_fill_content_area", "spacing", "gap" ***/
			/* Purpose: to edit the dependency of this param so if the theme's style is used, the param will not display */
			$param = WPBMap::getParam( $vc_element, 'shape' );
			if ( $param ) { vc_update_shortcode_param( $vc_element, uxbarn_add_dependency_not_equal_to_for_param( $param, array( UXB_VC_THEME_DEFAULT_STYLE_VALUE ) ) ); }
			$param = WPBMap::getParam( $vc_element, 'color' );
			if ( $param ) { vc_update_shortcode_param( $vc_element, uxbarn_add_dependency_not_equal_to_for_param( $param, array( UXB_VC_THEME_DEFAULT_STYLE_VALUE ) ) ); }
			$param = WPBMap::getParam( $vc_element, 'no_fill_content_area' );
			if ( $param ) { vc_update_shortcode_param( $vc_element, uxbarn_add_dependency_not_equal_to_for_param( $param, array( UXB_VC_THEME_DEFAULT_STYLE_VALUE ) ) ); }
			$param = WPBMap::getParam( $vc_element, 'spacing' );
			if ( $param ) { vc_update_shortcode_param( $vc_element, uxbarn_add_dependency_not_equal_to_for_param( $param, array( UXB_VC_THEME_DEFAULT_STYLE_VALUE ) ) ); }
			$param = WPBMap::getParam( $vc_element, 'gap' );
			if ( $param ) { vc_update_shortcode_param( $vc_element, uxbarn_add_dependency_not_equal_to_for_param( $param, array( UXB_VC_THEME_DEFAULT_STYLE_VALUE ) ) ); }
			
		}
		
	}

}



if ( ! function_exists( 'uxbarn_customize_vc_element_vc_tta_tour' ) ) {

	function uxbarn_customize_vc_element_vc_tta_tour() {

		$vc_element = 'vc_tta_tour';
		
		if ( WPBMap::exists( $vc_element ) ) {
				
			$theme_default_style_array = uxbarn_get_theme_default_style_array();

			/*** "style" param ***/
			/* Purpose: to add theme's style as a default option for the element */
			$param = WPBMap::getParam( $vc_element, 'style' );
			if ( $param ) {
				$param['value'] = $theme_default_style_array + $param['value'];
				$param['std'] = UXB_VC_THEME_DEFAULT_STYLE_VALUE;
				vc_update_shortcode_param( $vc_element, $param );
			}



			/*** "shape", "color", "no_fill_content_area", "spacing", "gap" ***/
			/* Purpose: to edit the dependency of this param so if the theme's style is used, the param will not display */
			$param = WPBMap::getParam( $vc_element, 'shape' );
			if ( $param ) {vc_update_shortcode_param( $vc_element, uxbarn_add_dependency_not_equal_to_for_param( $param, array( UXB_VC_THEME_DEFAULT_STYLE_VALUE ) ) ); }
			$param = WPBMap::getParam( $vc_element, 'color' );
			if ( $param ) { vc_update_shortcode_param( $vc_element, uxbarn_add_dependency_not_equal_to_for_param( $param, array( UXB_VC_THEME_DEFAULT_STYLE_VALUE ) ) ); }
			$param = WPBMap::getParam( $vc_element, 'no_fill_content_area' );
			if ( $param ) { vc_update_shortcode_param( $vc_element, uxbarn_add_dependency_not_equal_to_for_param( $param, array( UXB_VC_THEME_DEFAULT_STYLE_VALUE ) ) ); }
			$param = WPBMap::getParam( $vc_element, 'spacing' );
			if ( $param ) { vc_update_shortcode_param( $vc_element, uxbarn_add_dependency_not_equal_to_for_param( $param, array( UXB_VC_THEME_DEFAULT_STYLE_VALUE ) ) ); }
			$param = WPBMap::getParam( $vc_element, 'gap' );
			if ( $param ) { vc_update_shortcode_param( $vc_element, uxbarn_add_dependency_not_equal_to_for_param( $param, array( UXB_VC_THEME_DEFAULT_STYLE_VALUE ) ) ); }
			
		}
		
	}

}



if ( ! function_exists( 'uxbarn_customize_vc_element_vc_tta_accordion' ) ) {

	function uxbarn_customize_vc_element_vc_tta_accordion() {

		$vc_element = 'vc_tta_accordion';
		
		if ( WPBMap::exists( $vc_element ) ) {
				
			$theme_default_style_array = uxbarn_get_theme_default_style_array();

			/*** "style" param ***/
			/* Purpose: to add theme's style as a default option for the element */
			$param = WPBMap::getParam( $vc_element, 'style' );
			if ( $param ) {
				$param['value'] = $theme_default_style_array + $param['value'];
				$param['std'] = UXB_VC_THEME_DEFAULT_STYLE_VALUE;
				vc_update_shortcode_param( $vc_element, $param );
			}



			/*** "shape", "color", "no_fill_content_area", "spacing", "gap" ***/
			/* Purpose: to edit the dependency of this param so if the theme's style is used, the param will not display */
			$param = WPBMap::getParam( $vc_element, 'shape' );
			if ( $param ) { vc_update_shortcode_param( $vc_element, uxbarn_add_dependency_not_equal_to_for_param( $param, array( UXB_VC_THEME_DEFAULT_STYLE_VALUE ) ) ); }
			$param = WPBMap::getParam( $vc_element, 'color' );
			if ( $param ) { vc_update_shortcode_param( $vc_element, uxbarn_add_dependency_not_equal_to_for_param( $param, array( UXB_VC_THEME_DEFAULT_STYLE_VALUE ) ) ); }
			$param = WPBMap::getParam( $vc_element, 'no_fill' );
			if ( $param ) { vc_update_shortcode_param( $vc_element, uxbarn_add_dependency_not_equal_to_for_param( $param, array( UXB_VC_THEME_DEFAULT_STYLE_VALUE ) ) ); }
			$param = WPBMap::getParam( $vc_element, 'spacing' );
			if ( $param ) { vc_update_shortcode_param( $vc_element, uxbarn_add_dependency_not_equal_to_for_param( $param, array( UXB_VC_THEME_DEFAULT_STYLE_VALUE ) ) ); }
			$param = WPBMap::getParam( $vc_element, 'gap' );
			if ( $param ) { vc_update_shortcode_param( $vc_element, uxbarn_add_dependency_not_equal_to_for_param( $param, array( UXB_VC_THEME_DEFAULT_STYLE_VALUE ) ) ); }
			
		}
		
	}

}



if ( ! function_exists( 'uxbarn_customize_vc_element_vc_gallery' ) ) {

	function uxbarn_customize_vc_element_vc_gallery() {

		$vc_element = 'vc_gallery';
		
		if ( WPBMap::exists( $vc_element ) ) {
				
			/*** "type" ***/
			/* Purpose: to remove "Nivo Slider" type out of the list */
			$param = WPBMap::getParam( $vc_element, 'type' );
			if ( $param ) {
				
				if ( ( $key = array_search( 'nivo', $param['value'] ) ) !== false ) {
					unset( $param['value'][$key] );
				}

				vc_update_shortcode_param( $vc_element, $param );
				
			}
			
		}
		
	}

}



if ( ! function_exists( 'uxbarn_customize_vc_element_vc_posts_slider' ) ) {

	function uxbarn_customize_vc_element_vc_posts_slider() {

		$vc_element = 'vc_posts_slider';
		
		if ( WPBMap::exists( $vc_element ) ) {
				
			/*** "type" ***/
			/* Purpose: to remove "Nivo Slider" type out of the list */
			$param = WPBMap::getParam( $vc_element, 'type' );
			if ( $param ) {
					
				if ( ( $key = array_search( 'nivo', $param['value'] ) ) !== false ) {
					unset( $param['value'][$key] );
				}

				vc_update_shortcode_param( $vc_element, $param );
				
			}
			
		}
		
	}

}



if ( ! function_exists( 'uxbarn_customize_vc_element_vc_gmaps' ) ) {

	function uxbarn_customize_vc_element_vc_gmaps() {

		$vc_element = 'vc_gmaps';
		
		if ( WPBMap::exists( $vc_element ) ) {
				
			// Firstly remove unwanted params from the default VC
			if ( function_exists( 'vc_remove_param' ) ) {
				
				vc_remove_param( $vc_element, 'link' );
				vc_remove_param( $vc_element, 'size' );
				vc_remove_param( $vc_element, 'el_class' );

			}

			// Then add custom params for the element
			if ( function_exists( 'vc_add_params' ) ) {
				
				$params = array(
						array(
							'type' => 'textarea',
							'class' => '',
							'heading' => __('Address', 'uxbarn'),
							'param_name' => 'address',
							'value' => '',
							'description' => __('By default, the theme will use this address field as a primary value for generating the map. For example: <em>Tillary St., New York, US</em>. <br/><strong>NOTE:</strong> In case you want to use latitude and logitude values below, just leave this field blank.', 'uxbarn'),
						),
						array(
							'type' => 'textfield',
							'class' => '',
							'heading' => __('Latitude', 'uxbarn'),
							'param_name' => 'latitude',
							'value' => '',
							'description' => __('Enter the latitude value. <a href="http://itouchmap.com/latlong.html" target="_blank">Click here to find yours</a>', 'uxbarn'),
						),
						array(
							'type' => 'textfield',
							'class' => '',
							'heading' => __('Logitude', 'uxbarn'),
							'param_name' => 'longitude',
							'value' => '',
							'description' => __('Enter the longitude value. <a href="http://itouchmap.com/latlong.html" target="_blank">Click here to find yours</a>', 'uxbarn'),
						),
						array(
							'type' => 'dropdown',
							'class' => '',
							'heading' => __('Zoom level', 'uxbarn'),
							'param_name' => 'zoom',
							'value' => array(
								'7' => '7', 
								'8' => '8',
								'9' => '9',
								'10' => '10',
								'11' => '11', 
								'12' => '12',
								'13' => '13',
								'14' => '14',
								'15' => '15',
								'16' => '16',
								'17' => '17',
								'18' => '18',
								'19' => '19',
								'20' => '20',
								),
							'description' => __('Select the zoom level.', 'uxbarn'),
							'std' => '17',
						),
						array(
							'type' => 'dropdown',
							'class' => '',
							'heading' => __('Display type', 'uxbarn'),
							'param_name' => 'type',
							'value' => array(
								__('Roadmap', 'uxbarn') => 'm', 
								__('Satellite', 'uxbarn') => 'k',
								__('Hybrid', 'uxbarn') => 'p',
								__('Terrain', 'uxbarn') => 'TERRAIN',
								),
							'description' => __('Choose the display type.', 'uxbarn'),
						),
						array(
							'type' => 'textfield',
							'class' => '',
							'heading' => __('Height', 'uxbarn'),
							'param_name' => 'size',
							'value' => '250',
							'description' => __('Enter the height in pixel unit. Enter only a number.', 'uxbarn'),
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Extra class name', 'uxbarn' ),
							'param_name' => 'el_class',
							'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'uxbarn' )
						),
					);

				vc_add_params( $vc_element, $params );

			}
			
		}

	}

}



if ( ! function_exists( 'uxbarn_get_theme_default_style_array' ) ) {

	function uxbarn_get_theme_default_style_array( $additional_entry_array = array() ) {
		
		return array( 
					__( "Theme's Default Style", 'uxbarn' ) => UXB_VC_THEME_DEFAULT_STYLE_VALUE,
				) + $additional_entry_array;

	}

}
	


// For adding "value_not_equal_to" dependency for element's param
if ( ! function_exists( 'uxbarn_add_dependency_not_equal_to_for_param' ) ) {

	function uxbarn_add_dependency_not_equal_to_for_param( $param, $not_equal_to_string_array ) {

		if ( ! empty( $param['dependency'] ) && ! empty( $param['dependency']['value_not_equal_to'] ) ) {

			foreach( $not_equal_to_string_array as $value ) {
				array_push( $param['dependency']['value_not_equal_to'], $value );
			}
			
		} else {

			$param['dependency'] = array(
				'element' => 'style',
				'value_not_equal_to' => $not_equal_to_string_array,
			);

		}

		return $param;

	}

}



if ( ! function_exists( 'uxbarn_remove_vc_prettyphoto' ) ) {

	function uxbarn_remove_vc_prettyphoto() {
	
		wp_deregister_script( 'prettyphoto' );
		wp_deregister_style( 'prettyphoto' );

	}

}



// UXbarn VC Extension uses this
if ( ! function_exists( 'uxbarn_get_css_animation_param' ) ) {
	
    function uxbarn_get_css_animation_param() {
    	
        // CSS animation param code copied from "config/map.php" of VC plugin v3.6.5
        $add_css_animation = array(
          'type' 		=> 'dropdown',
          'heading' 	=> __( 'CSS Animation', 'uxbarn' ),
          'param_name' 	=> 'css_animation',
          'admin_label' => false,
          'value' 		=> array( 
	          					__( 'No', 'uxbarn' ) 				 => '', 
	          					__( 'Top to bottom', 'uxbarn' ) 	 => 'top-to-bottom', 
	          					__( 'Bottom to top', 'uxbarn' ) 	 => 'bottom-to-top', 
	          					__( 'Left to right', 'uxbarn' ) 	 => 'left-to-right', 
	          					__( 'Right to left', 'uxbarn' ) 	 => 'right-to-left', 
	          					__( 'Appear from center', 'uxbarn' ) => 'appear',
							),
          'description' => __( 'Select animation type if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'uxbarn' )
        );
        
        return $add_css_animation;
		
    }
	
}



// UXbarn VC Extension uses this
if ( ! function_exists( 'uxbarn_get_extra_class_name' ) ) {
	
    function uxbarn_get_extra_class_name() {
        
        $param = array(
             'type' 		=> 'textfield',
             'holder' 		=> 'div',
             'class' 		=> '',
             'heading' 		=> __( 'Extra class name', 'uxbarn' ),
             'param_name' 	=> 'el_class',
             'value' 		=> '',
             'description' 	=> __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'uxbarn' ),
             'admin_label' 	=> false,
          );
          
        return $param;
		
    }
	
}



// [DEPRECATED SINCE THEME V1.9.3]
// Customize the VC rows and columns to use theme's classes
if ( ! function_exists( 'uxbarn_customize_vc_rows_columns' ) ) {
	
	function uxbarn_customize_vc_rows_columns( $class_string, $tag ) {
			
		// vc_row 
		if ( $tag == 'vc_row' || $tag == 'vc_row_inner' ) {
			
			$replace = array(
				'vc_row-fluid' 	=> 'row',
				'wpb_row' 		=> '',
				'vc_row'		=> '', 
			);
			
			$class_string = uxbarn_replace_string_with_assoc_array( $replace, $class_string );
			
		}
		
		// vc_column
		if ( $tag == 'vc_column' || $tag == 'vc_column_inner' ) {
			
			$replace = array(
				'wpb_column' 		=> '',
				'vc_column_container' 	=> '',
			);
			
			// VC 4.3.x (it changed the tags)
			$class_string = uxbarn_replace_string_with_assoc_array(
								$replace, preg_replace('/vc_col-(xs|sm|md|lg)-(\d{1,2})/', 'uxb-col large-$2 columns', $class_string)
							);
							
		}
		
		return $class_string;
		
	}

}
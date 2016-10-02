<?php

if ( ! function_exists( 'uxb_port_create_plugin_options' ) ) {
		
	function uxb_port_create_plugin_options() {
	
	  // Only execute in admin & if OT is installed
	  if ( is_admin() && function_exists( 'ot_register_settings' ) ) {
	
	    // Register the pages
	    ot_register_settings( 
	      array(
	        array( 
	          'id'              => 'uxb_port_plugin_options',
	          'pages'           => array(
	            array(
	              'id'              => 'uxb_port_plugin_options_page',
	              'parent_slug'     => 'options-general.php',
	              'page_title'      => __( 'UXbarn Portfolio Options', 'uxb_port' ),
	              'menu_title'      => __( 'UXbarn Portfolio Options', 'uxb_port' ),
	              'capability'      => 'edit_theme_options',
	              'menu_slug'       => 'uxb_port_options',
	              'icon_url'        => null,
	              'position'        => null,
	              'updated_message' => __( 'Plugin options updated.', 'uxb_port' ),
	              'reset_message'   => __( 'Plugin options reset.', 'uxb_port' ),
	              'button_text'     => __( 'Save Changes', 'uxb_port' ),
	              'show_buttons'    => true,
	              'screen_icon'     => 'options-general',
	              'contextual_help' => null,
	              'sections'        => array(
	              		
		                array(
		                  'id'          => 'uxb_port_po_single_page_section',
		                  'title'       => __( 'Portfolio Single Page', 'uxb_port' )
		                ),
		                
						array(
		                  'id'          => 'uxb_port_po_custom_meta_info_section',
		                  'title'       => __( 'Custom Meta Info', 'uxb_port' )
		                ),
					
	              ),
	              'settings'        => array(
	              		
						
						
						// Portfolio Single Page Tab
		                array(
			                'id'          => 'uxb_port_po_single_page_slider_transition',
			                'label'       => __( "Slider's Transition Effect", 'uxb_port' ),
			                'desc'        => __( 'Select the transition for the image slider.', 'uxb_port' ),
			                'std'         => 'fade',
			                'type'        => 'select',
			                'section'     => 'uxb_port_po_single_page_section',
			                'rows'        => '',
			                'post_type'   => '',
			                'taxonomy'    => '',
			                'class'       => '',
			                'choices'     => array( 
			                  array(
			                    'value'       => 'fade',
			                    'label'       => __( 'Fade', 'uxb_port' ),
			                    'src'         => ''
			                  ),
			                  array(
			                    'value'       => 'slide',
			                    'label'       => __( 'Slide', 'uxb_port' ),
			                    'src'         => ''
			                  ),
			                ),
			              ),
			              
			              array(
			                'id'          => 'uxb_port_po_single_page_slider_transition_speed',
			                'label'       => __( "Slider's Transition Speed", 'uxb_port' ),
			                'desc'        => __( 'Enter a number of how fast you want the transition to animate, in milliseconds.', 'uxb_port' ),
			                'std'         => '600',
			                'type'        => 'text',
			                'section'     => 'uxb_port_po_single_page_section',
			                'rows'        => '',
			                'post_type'   => '',
			                'taxonomy'    => '',
			                'class'       => ''
			              ),
			              
			              array(
			                'id'          => 'uxb_port_po_single_page_slider_auto_rotation',
			                'label'       => __( "Enable Slider's Auto Rotation?", 'uxb_port'),
			                'desc'        => __( 'Whether to enable the auto rotation mode for the slider.', 'uxb_port' ),
			                'std'         => 'true',
			                'type'        => 'radio',
			                'section'     => 'uxb_port_po_single_page_section',
			                'rows'        => '',
			                'post_type'   => '',
			                'taxonomy'    => '',
			                'class'       => '',
			                'choices'     => array( 
			                  array(
			                    'value'       => 'true',
			                    'label'       => __( 'Yes', 'uxb_port' ),
			                    'src'         => ''
			                  ),
			                  array(
			                    'value'       => 'false',
			                    'label'       => __( 'No', 'uxb_port' ),
			                    'src'         => ''
			                  )
			                ),
			              ),
			              
			              array(
			                'id'          => 'uxb_port_po_single_page_slider_rotation_duration',
			                'label'       => __( "Slider's Rotation Delay", 'uxb_port' ),
			                'desc'        => __( 'Enter a number of how long to stay on the current slide before rotating to the next one, in milliseconds.', 'uxb_port' ),
			                'std'         => '5000',
			                'type'        => 'text',
			                'section'     => 'uxb_port_po_single_page_section',
			                'rows'        => '',
			                'post_type'   => '',
			                'taxonomy'    => '',
			                'class'       => ''
			              ),
			              
						  array(
			                'id'          => 'uxb_port_po_single_page_display_related_works',
			                'label'       => __( 'Display Related Works?', 'uxb_port'),
			                'desc'        => __( 'Whether to display the Related Works section.', 'uxb_port' ),
			                'std'         => 'true',
			                'type'        => 'radio',
			                'section'     => 'uxb_port_po_single_page_section',
			                'rows'        => '',
			                'post_type'   => '',
			                'taxonomy'    => '',
			                'class'       => '',
			                'choices'     => array( 
			                  array(
			                    'value'       => 'true',
			                    'label'       => __( 'Yes', 'uxb_port' ),
			                    'src'         => ''
			                  ),
			                  array(
			                    'value'       => 'false',
			                    'label'       => __( 'No', 'uxb_port' ),
			                    'src'         => ''
			                  )
			                ),
			              ),
			              
						  array(
			                'id'          => 'uxb_port_po_single_page_related_works_scopes',
			                'label'       => __( 'Related Works: Optional Scopes', 'uxb_port'),
			                'desc'        => __( 'By default, theme uses only portfolio category for displaying related works. This option lets you choose any optional scopes (from meta info) to be used with the portfolio category. The default meta info are Date, Client, and Website. The operators are applied as:<br/><br/><strong>category AND (client OR website OR date)</strong><br/><br/>Also note that all scopes here use the exact match (=) to compare the values.<br/><br/>If you are using custom meta info, your custom ones will list here instead.', 'uxb_port' ),
			                'std'         => '',
			                'type'        => 'checkbox',
			                'section'     => 'uxb_port_po_single_page_section',
			                'rows'        => '',
			                'post_type'   => '',
			                'taxonomy'    => '',
			                'class'       => '',
			                'choices'     => uxb_port_get_optional_scopes_array(),
			              ),
			              
						  array(
			                'id'          => 'uxb_port_po_single_page_enable_comment',
			                'label'       => __( 'Enable Portfolio Comment?', 'uxb_port' ),
			                'desc'        => __( 'Whether to enable the comment function for all portfolio items by default.<br/><br/>When you have enabled it, please make sure that each portfolio item is also marked as "Allow Comments". You can find that mark from the Quick Edit menu of each item on Portfolio menu.', 'uxb_port' ),
			                'std'         => 'false',
			                'type'        => 'radio',
			                'section'     => 'uxb_port_po_single_page_section',
			                'rows'        => '',
			                'post_type'   => '',
			                'taxonomy'    => '',
			                'class'       => '',
			                'choices'     => array( 
			                  array(
			                    'value'       => 'true',
			                    'label'       => __( 'Yes', 'uxb_port' ),
			                    'src'         => ''
			                  ),
			                  array(
			                    'value'       => 'false',
			                    'label'       => __( 'No', 'uxb_port' ),
			                    'src'         => ''
			                  )
			                ),
			              ),
			              
						  
						  
						  
						  
						  // Custom Meta Info Tab
					  	array(
				            'id'          => 'uxb_port_po_use_custom_meta_info',
				            'label'       => __( 'Use Custom Meta Info?', 'uxbarn' ),
				            'desc'        => __( 'Whether to create your own list of meta info for all portfolio items instead of the default ones. Note that the default ones are Date, Client, Categories, and Website.', 'uxb_port' ),
				            'std'         => 'off',
				            'type'        => 'on-off',
				            'section'     => 'uxb_port_po_custom_meta_info_section',
				            'rows'        => '',
				            'post_type'   => '',
				            'taxonomy'    => '',
				            'min_max_step'=> '',
				            'class'       => '',
				            'condition'   => '',
				            'operator'    => 'and'
						),
						
						array(
					        'id'          => 'uxb_port_po_custom_meta_info',
					        'label'       => __( 'Custom Meta Info', 'uxbarn' ),
					        'desc'        => __( "Use this option to create the list of custom meta info of portfolio item. After saving, you can then enter the data on portfolio item's edit screen and they will display on the portfolio single page.", 'uxb_port' ),
					        'std'         => '',
					        'type'        => 'list-item',
					        'section'     => 'uxb_port_po_custom_meta_info_section',
					        'rows'        => '',
					        'post_type'   => '',
					        'taxonomy'    => '',
					        'min_max_step'=> '',
					        'class'       => '',
							'condition'   => 'uxb_port_po_use_custom_meta_info:is(on)',
					        'operator'    => 'and',
					        'settings'    => array( 
					          	array(
						            'id'          => 'uxb_port_po_custom_meta_info_id',
						            'label'       => __( 'Unique ID', 'uxb_team' ),
						            'desc'        => __( '<p>Assign the ID for this meta field.</p><p><strong>Important: This ID is required and must be only in plain English without special characters (underscore is allowed). Each ID must also be unique.</strong></p><p>For examples, if this is for Client, you might enter the ID as "meta_client".</p>', 'uxb_port' ),
						            'std'         => '',
						            'type'        => 'text',
						            'rows'        => '',
						            'post_type'   => '',
						            'taxonomy'    => '',
						            'min_max_step'=> '',
						            'class'       => '',
						            'condition'   => '',
						            'operator'    => 'and'
					          	),
				        	),
					      ),
			        	
						array(
				            'id'          => 'uxb_port_po_custom_meta_info_display_categories',
				            'label'       => __( 'Display Portfolio Categories?', 'uxbarn' ),
				            'desc'        => __( 'Whether to display the portfolio categories on the frontend (portfolio single page) right below the created custom meta info.', 'uxb_port' ),
				            'std'         => 'on',
				            'type'        => 'on-off',
				            'section'     => 'uxb_port_po_custom_meta_info_section',
				            'rows'        => '',
				            'post_type'   => '',
				            'taxonomy'    => '',
				            'min_max_step'=> '',
				            'class'       => '',
				            'condition'   => 'uxb_port_po_use_custom_meta_info:is(on)',
				            'operator'    => 'and'
						),      
					
	              )
	            )
	          )
	        )
	      )
	    );
	
	  }
	
	}

}
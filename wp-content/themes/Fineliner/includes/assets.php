<?php

if ( ! function_exists( 'uxbarn_load_css' ) ) {
	
	function uxbarn_load_css() {
		
		// Prepare all styles
		wp_register_style( 'uxbarn-google-fonts', uxbarn_get_google_fonts_url(), array(), null );
		wp_register_style( 'uxbarn-reset', get_template_directory_uri() . '/css/reset.css', array(), null );
		wp_register_style( 'uxbarn-foundation', get_template_directory_uri() . '/css/foundation.css', array(), null );
		wp_register_style( 'uxbarn-font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), null );
		wp_register_style( 'uxbarn-flexslider', get_template_directory_uri() . '/css/flexslider.css', array(), null );
		wp_register_style( 'uxbarn-fancybox', get_template_directory_uri() . '/css/jquery.fancybox.css', array(), null );
		wp_register_style( 'uxbarn-fancybox-helpers-thumbs', get_template_directory_uri() . '/css/fancybox/helpers/jquery.fancybox-thumbs.css', array(), null );
		wp_register_style( 'uxbarn-theme', get_template_directory_uri() . '/style.css', array( 'uxbarn-reset', 'uxbarn-foundation', 'uxbarn-flexslider' ), null );
		wp_register_style( 'uxbarn-theme-responsive', get_template_directory_uri() . '/css/fineliner-responsive.css', array( 'uxbarn-theme' ), null );
		
		// Prepare css for the selected accent color in Style Customizer
		$option_set = get_option( 'uxbarn_sc_global_color_scheme' );
		
		if ( $option_set ) {
			
			if( $option_set != 'custom' ) {
				wp_register_style( 'uxbarn-color-scheme', get_template_directory_uri() . '/css/colors/' . $option_set . '.css', array( 'uxbarn-theme' ), null );
			}
			
		} else { // default
			wp_register_style( 'uxbarn-color-scheme', get_template_directory_uri() . '/css/colors/red.css', array( 'uxbarn-theme' ), null );
		}
		
		
		
		// Initially load the prepared styles
		wp_enqueue_style( 'uxbarn-google-fonts' );
		wp_enqueue_style( 'uxbarn-reset' );
		wp_enqueue_style( 'uxbarn-foundation' );
		wp_dequeue_style( 'uxbarn-font-awesome' );
		wp_enqueue_style( 'uxbarn-font-awesome' );
		wp_enqueue_style( 'uxbarn-color-scheme' );
		
		// For conditional comment for IE8
		global $wp_styles;
		wp_enqueue_style( 'uxbarn-foundation-ie8', get_template_directory_uri() . '/css/foundation-ie8.css', array( 'uxbarn-theme' ), null );
		$wp_styles->add_data( 'uxbarn-foundation-ie8', 'conditional', 'IE 8' );
		wp_enqueue_style( 'uxbarn-theme-ie8', get_template_directory_uri() . '/css/fineliner-ie8.css', array( 'uxbarn-theme' ), null );
		$wp_styles->add_data( 'uxbarn-theme-ie8', 'conditional', 'IE 8' );
		
	}

}



if ( ! function_exists( 'uxbarn_load_js' ) ) {
	
	function uxbarn_load_js() {
		
		// Get a Google API Key
		$api_key = '';
		if ( function_exists( 'ot_get_option' ) ) {
			$api_key = ot_get_option( 'uxbarn_to_setting_google_maps_api_key', '' );
		}
		
		// Prepare all scripts
		wp_register_script( 'uxbarn-modernizr', get_template_directory_uri() . '/js/custom.modernizr.js', array(), null );
		wp_register_script( 'uxbarn-google-map', esc_url( add_query_arg( 'key', $api_key, '//maps.google.com/maps/api/js?sensor=false&v=3.5' ) ), array(), null, true);
		wp_register_script( 'uxbarn-foundation', get_template_directory_uri() . '/js/foundation.min.js', array( 'jquery' ), null, true );
		wp_register_script( 'uxbarn-hoverintent', get_template_directory_uri() . '/js/jquery.hoverIntent.js', array( 'jquery' ), null, true );
		wp_register_script( 'uxbarn-superfish', get_template_directory_uri() . '/js/superfish.js', array( 'jquery' ), null, true );
		wp_register_script( 'uxbarn-flexslider', get_template_directory_uri() . '/js/jquery.flexslider.js', array( 'jquery' ), null, true );
		wp_register_script( 'uxbarn-mousewheel', get_template_directory_uri() . '/js/jquery.mousewheel-3.0.6.pack.js', array( 'jquery' ), null, true );
		wp_register_script( 'uxbarn-fancybox', get_template_directory_uri() . '/js/jquery.fancybox.pack.js', array( 'jquery' ), null, true );
		wp_register_script( 'uxbarn-fancybox-helpers-thumbs', get_template_directory_uri() . '/js/fancybox-helpers/jquery.fancybox-thumbs.js', array( 'jquery' ), null, true );
		wp_register_script( 'uxb-tmnl-easing', get_template_directory_uri() . '/js/jquery.easing.1.3.js', array( 'jquery' ), null, true ); // named it same as Testimonial's
		wp_register_script( 'uxbarn-scrollup', get_template_directory_uri() . '/js/jquery.scrollUp.min.js', array( 'jquery', 'uxb-tmnl-easing' ), null, true );
		wp_register_script( 'uxbarn-imagesloaded', get_template_directory_uri() . '/js/imagesloaded.pkgd.min.js', array( 'jquery' ), null, true );
		wp_register_script( 'uxbarn-theme', get_template_directory_uri() . '/js/fineliner.js', array( 'jquery', 'jquery-ui-core', 'uxbarn-imagesloaded' ), null, true );
		
		// Initially load the prepared scripts
		wp_enqueue_script( 'uxbarn-modernizr' );
		wp_enqueue_script( 'uxbarn-foundation' );
		wp_enqueue_script( 'uxbarn-hoverintent' );
		wp_enqueue_script( 'uxbarn-superfish' );
		
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		
		// Prepare any values from Theme Options to be used in the front-end JS
		if ( function_exists( 'ot_get_option' ) ) {
				
			$default_slider_transition 			= ot_get_option( 'uxbarn_to_setting_default_slider_transition', 'fade' );
			$default_slider_transition_speed 	= uxbarn_sanitize_numeric_input(
													ot_get_option( 'uxbarn_to_setting_default_slider_transition_speed' ), 
													700);
			
			$default_slider_auto_rotation 		= ot_get_option( 'uxbarn_to_setting_default_slider_auto_rotation' ) == 'false' ? false : true;
			$default_slider_rotation_duration 	= uxbarn_sanitize_numeric_input(
													ot_get_option( 'uxbarn_to_setting_default_slider_rotation_duration' ), 
													8000);
			$default_slider_caption_animation 	= ot_get_option( 'uxbarn_to_setting_default_slider_caption_animation' ) == 'false' ? false : true;
			
			$enable_lightbox_wp_gallery = ot_get_option( 'uxbarn_to_setting_enable_lightbox_wp_gallery' ) == 'false' ? false : true;
			
		} else {
			
			$default_slider_transition = 'fade';
			$default_slider_transition_speed = 700;
			$default_slider_auto_rotation = true;
			$default_slider_rotation_duration = 8000;
			$default_slider_caption_animation = true;
			$enable_lightbox_wp_gallery = true;
			
		}
		
		$params = array(
					'default_slider_transition' 		=> $default_slider_transition,
					'default_slider_transition_speed' 	=> $default_slider_transition_speed,
					'default_slider_auto_rotation' 		=> $default_slider_auto_rotation,
					'default_slider_rotation_duration' 	=> $default_slider_rotation_duration,
					'default_slider_caption_animation' 	=> $default_slider_caption_animation,
					
					'enable_lightbox_wp_gallery' => $enable_lightbox_wp_gallery,
				);
			
		wp_localize_script( 'uxbarn-theme', 'ThemeOptions', $params );
	
		// ScrollUp JS
		if ( function_exists( 'ot_get_option' ) ) {
			
			if ( ot_get_option( 'uxbarn_to_setting_display_scrollup_button', 'false' ) == 'true' ) {
				wp_enqueue_script( 'uxbarn-scrollup' );
			}
			
		}
		
		
		
		$foundation_params = array(
								'back_text' => __( 'Back', 'uxbarn' ),
							);
		wp_localize_script( 'uxbarn-foundation', 'FoundationParams', $foundation_params );
		
	}

}



if ( ! function_exists( 'uxbarn_load_on_demand_assets' ) ) {
	
	function uxbarn_load_on_demand_assets() {
		
		// For home slider
		if ( is_front_page() || uxbarn_is_frontpage_child() ) {
			
			wp_enqueue_style( 'uxbarn-flexslider' );
			wp_enqueue_script( 'uxbarn-flexslider' );
			
		}
		
		
		// For content section
		if ( is_page() || is_single() ) {
			
			global $post;
			
			if ( uxbarn_has_shortcode( 'vc_single_image', $post->post_content ) ||
				 uxbarn_has_shortcode( 'vc_gallery', $post->post_content ) ||
				 uxbarn_has_shortcode( 'vc_images_carousel', $post->post_content ) ||
				 uxbarn_has_shortcode( 'vc_media_grid ', $post->post_content ) ||
				 uxbarn_has_shortcode( 'vc_masonry_media_grid ', $post->post_content ) ) {
				
				wp_enqueue_script( 'uxbarn-mousewheel' );
				wp_enqueue_style( 'uxbarn-fancybox' );
				wp_enqueue_script( 'uxbarn-fancybox' );
				wp_enqueue_style( 'uxbarn-fancybox-helpers-thumbs' );
				wp_enqueue_script( 'uxbarn-fancybox-helpers-thumbs' );
				
			}
			
			if ( uxbarn_has_shortcode( 'gallery', $post->post_content ) ) { // WP gallery
			
				wp_enqueue_script( 'uxbarn-mousewheel' );
				wp_enqueue_style( 'uxbarn-fancybox' );
				wp_enqueue_script( 'uxbarn-fancybox' );
				wp_enqueue_style( 'uxbarn-fancybox-helpers-thumbs' );
				wp_enqueue_script( 'uxbarn-fancybox-helpers-thumbs' );
				
			}
			
			if ( uxbarn_has_shortcode( 'vc_gmaps', $post->post_content ) ) {
				wp_enqueue_script('uxbarn-google-map');
			}
			
			if ( uxbarn_has_shortcode( 'vc_accordion', $post->post_content ) ) {
				wp_enqueue_script( 'jquery-ui-accordion' );
			}
			
		}

			
		// Disable plugin's to avoid conflict
		if ( is_singular( 'uxbarn_portfolio' ) || is_tax( 'uxbarn_portfolio_tax' ) ) {
			
			wp_dequeue_style( 'uxb-port-foundation' );
			wp_dequeue_script( 'uxb-port-foundation' );
			
		}
		
		if ( is_singular( 'uxbarn_team' ) ) {
			
			wp_dequeue_style( 'uxb-team-foundation' );
			wp_dequeue_script( 'uxb-team-foundation' );
			
		}
		
		// Finally load the theme JS & CSS
		wp_enqueue_script( 'uxbarn-theme' );
		
		wp_enqueue_style( 'uxbarn-theme' );
		wp_enqueue_style( 'uxbarn-theme-responsive' );

	}

}



if ( ! function_exists( 'uxbarn_load_admin_assets' ) ) {
	
	function uxbarn_load_admin_assets( $page ) {
		
		global $post;
		
		 // Edit screen
		if ( $page == 'post.php' || 
			 $page == 'post-new.php' || 
			 $page == 'wpml-translation-management/menu/translations-queue.php' ) {
				
			wp_enqueue_style( 'uxbarn-admin', get_template_directory_uri() . '/css/admin.css', false );
			
			if ( ( $page == 'post.php' || $page == 'post-new.php' ) && ( isset( $post ) && ( $post->post_type == 'page' || $post->post_type == 'uxbarn_homeslider' ) ) ) {
				
				wp_enqueue_script( 'uxbarn-admin', get_template_directory_uri() . '/js/theme-admin.js', false, false, true );
				
				$params = array(
					'sidebar_text' 			  => __( '"Default Sidebar" is the sidebar that you have set in "Settings > Reading" on your admin panel.', 'uxbarn' ),
					'sidebar_appearance_text' => __( 'This is for page sidebar only. For blog sidebar, you can see the option in "Theme Options > Blog".', 'uxbarn' ),
				);
				
				wp_localize_script( 'uxbarn-admin', 'AdminSettings', $params );
				
			}
			
		}
		
		
		// Load custom code for OptionTree Settings page
		if ( $page == 'toplevel_page_ot-settings' ) {

			wp_enqueue_script( 'uxbarn-custom-ot', get_template_directory_uri() . '/js/custom-ot.js', array( 'jquery' ) );
			wp_enqueue_style( 'uxbarn-custom-ot', get_template_directory_uri() . '/css/custom-ot.css', false );

			$params = array(
				'layouts_desc' => uxbarn_wp_kses_escape( __( '<p>Layout is just like "Profile" of Theme Options. You can use this page to manage the layouts then they will display for selection on Theme Options page.</p><p>For example, if you have created 2 layouts which are "Layout A" and "Layout B". It means that you now have 2 profiles appeared on Theme Options page. You can then set different options for each layout such as, for "Layout A" you want to display site tagline while "Layout B" you want to hide it.</p>', 'uxbarn' ) ),
			);

			wp_localize_script( 'uxbarn-custom-ot', 'CustomOT', $params );

		}
		
		
		// Load some custom code in Theme Options
		if ( $page == 'toplevel_page_ot-theme-options' ) {
			
			wp_enqueue_style( 'uxbarn-tipr', get_template_directory_uri() . '/css/tipr.css', false );
			wp_enqueue_script( 'uxbarn-tipr', get_template_directory_uri() . '/js/tipr.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'uxbarn-theme-options', get_template_directory_uri() . '/js/theme-options.js', array( 'jquery', 'uxbarn-tipr' ) );
			wp_enqueue_style( 'uxbarn-theme-options', get_template_directory_uri() . '/css/theme-options.css', false );
			
			$theme = wp_get_theme();
			
			$params = array(
				'layout_hint'	=> esc_html__( 'What is Layout?', 'uxbarn' ),
				'layout_hint_desc' 	=> esc_attr__( 'Layout is just like "Profile" of Theme Options so you can create many variations of Theme Options with one active layout at a time. For example, if you have created 2 layouts which are "Layout A" and "Layout B". It means that you now have 2 profiles appeared on Theme Options page. You can then set different options for each layout such as, for "Layout A" you want to display site tagline while "Layout B" you want to hide it. *You can manage the created layout list by going to the "OptionTree > Layouts".', 'uxbarn' ),
			);
			
			wp_localize_script( 'uxbarn-theme-options', 'ThemeOptions', $params );
		
		}
		
		
		// Item list page in backend
		if ( $page == 'edit.php' && ( isset( $post ) && $post->post_type == 'uxbarn_homeslider' ) ) { 
			wp_enqueue_style( 'admin-edit-css', get_template_directory_uri() . '/css/admin-edit.css', false );
		}
		
		
		// UXbarn plugins settings 
		if ( $page == 'settings_page_uxb_port_options' || $page == 'settings_page_uxb_team_options' ) {
			wp_enqueue_style( 'uxbarn-theme-options', get_template_directory_uri() . '/css/theme-options.css', false );
		}
		

		// For VC plugin settings
		if ( $page == 'settings_page_wpb_vc_settings' || $page == 'settings_page_vc_settings' || $page == 'toplevel_page_vc-general' ) { // toplevel_page_vc-general since VC 4.5
			wp_enqueue_script( 'admin-vc-settings', get_template_directory_uri() . '/js/admin-vc-settings.js', array( 'jquery' ) );
		}
		
		
		// For TGMPA class
		wp_enqueue_style( 'uxbarn-tgmpa', get_template_directory_uri() . '/css/tgmpa.css', false );
		
	}

}

<?php

// ---------------------------------------------- //
// Set up constants
// ---------------------------------------------- //
define( 'UXB_THEME_ROOT_IMAGE_URL', get_template_directory_uri() . '/images/' );
define( 'UXB_DEFAULT_GOOGLE_FONTS', 'Josefin+Slab:400,700|Open+Sans:300italic,400italic,400,600,700' );



// ---------------------------------------------- //
// Included all required PHP assets
// ---------------------------------------------- //
require_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // for supporting "is_plugin_active()" usage
require_once( get_template_directory() . '/includes/assets.php' );
require_once( get_template_directory() . '/includes/theme-functions.php' );
require_once( get_template_directory() . '/includes/custom-menu.php' );
require_once( get_template_directory() . '/includes/custom-widgets.php' );
require_once( get_template_directory() . '/includes/comment-item.php' );
require_once( get_template_directory() . '/includes/post-types.php' );
require_once( get_template_directory() . '/includes/meta-boxes/meta-post.php' );
require_once( get_template_directory() . '/includes/meta-boxes/meta-page.php' );
require_once( get_template_directory() . '/includes/meta-boxes/meta-homeslider.php' );
require_once( get_template_directory() . '/includes/theme-options.php' );
require_once( get_template_directory() . '/includes/style-customizer/loader.php' );
require_once( get_template_directory() . '/includes/plugin-codes/plugin-custom-functions.php' );
require_once( get_template_directory() . '/includes/plugin-codes/envato-market-github.php' );
require_once( get_template_directory() . '/includes/plugin-codes/class-tgm-plugin-activation.php' );



// ---------------------------------------------- //
// Initialize the theme
// ---------------------------------------------- //
add_action( 'after_setup_theme', 'uxbarn_init_theme' );



// Exceptionally here for LayerSlider plugin that needed to setup outside the "after_setup_theme" hook.
if ( is_plugin_active( 'LayerSlider/layerslider.php' ) ) {
	add_action( 'layerslider_ready', 'uxbarn_layerslider_overrides' );
}



if ( ! function_exists( 'uxbarn_init_theme' ) ) {
	
	function uxbarn_init_theme() {
		
		/***** Register WP features *****/
		if ( ! isset( $content_width ) ) {
			$content_width = 1020;
		}
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		
		
		
		/***** Register theme scripts and styles *****/
		// [assets.php]
		add_action( 'wp_enqueue_scripts', 'uxbarn_load_css' );
		add_action( 'wp_enqueue_scripts', 'uxbarn_load_js' );
		add_action( 'wp_enqueue_scripts', 'uxbarn_load_on_demand_assets' );
		add_action( 'admin_enqueue_scripts', 'uxbarn_load_admin_assets' );
		
		
		
		/***** Register main WP modules *****/
		// [theme-functions.php]
		add_action( 'init', 'uxbarn_register_menus' );
		add_action( 'widgets_init', 'uxbarn_register_sidebars' );
		add_filter( 'user_contactmethods', 'uxbarn_update_user_profile_fields' );
		add_filter( 'wp_title', 'uxbarn_rewrite_site_title', 10, 3 ); // Handle the site title rewrite
		add_filter( 'widget_text', 'do_shortcode' ); // Enable shortcode usage in widget area
		
		
		
		/***** Register meta boxes and Theme Options (OptionTree plugin is required) *****/
		// [theme-functions.php, theme-options.php]
		if ( is_plugin_active( 'option-tree/ot-loader.php' ) ) {
			
			add_action( 'admin_init', 'uxbarn_create_meta_boxes' );
			add_action( 'admin_init', 'uxbarn_custom_theme_options', 1 );
			
		}
		
		
		
		/***** Register theme's post types *****/
		// [post-types.php]
		add_action( 'init', 'uxbarn_register_cpt_homeslider' );
		
		
		
		/***** Register Style Customizer *****/
		// [loader.php, customizer-functions.php]
		add_action( 'customize_register', 'uxbarn_ctmzr_register_customizer_sections' );
		add_action( 'customize_controls_enqueue_scripts', 'uxbarn_ctmzr_load_customizer_assets' );
		add_action( 'admin_menu', 'uxbarn_ctmzr_register_customizer_menu' );
		
		
		
		/***** Others *****/
		// [theme-functions.php]
		
		// Register theme's custom widgets
		add_action( 'widgets_init', 'uxbarn_register_widgets' );
		
		// Register theme's image sizes
		add_action( 'init', 'uxbarn_register_theme_image_sizes' );
		
		// Add all image sizes into the list of media editor
		add_filter( 'image_size_names_choose', 'uxbarn_merge_image_sizes' );  
		
		// Make the empty search value navigating to the correct page
		add_filter( 'request', 'uxbarn_request_filter' );
		
		// Change the WP excerpt length (in words count)
		add_filter( 'excerpt_length', 'uxbarn_custom_excerpt_length', 999 );
		
		// Change trimmed excerpt from "[...]" to just "..."
		add_filter( 'excerpt_more', 'uxbarn_new_excerpt_more' );
		
		// Add "wmode" to the YouTube embed of WordPress
		add_filter( 'oembed_result', 'uxbarn_add_oembed_wmode', 10, 2 );
		
		// Load available text domains for localization of the theme
		load_theme_textdomain( 'uxbarn', get_template_directory() . '/languages' );
		
		// Filter images in the srcset attribute
		add_filter( 'wp_calculate_image_srcset', 'uxbarn_filter_image_srcset' );
		
		
		
		/***** Plugin-related *****/
		// [plugin-custom-functions.php]
		add_action( 'tgmpa_register', 'uxbarn_register_additional_plugins' );
		
		// Visual Composer plugin
		if ( is_plugin_active( 'js_composer/js_composer.php' ) ) {
			
			require_once( get_template_directory() . '/includes/plugin-codes/vc/custom-vc.php' );
			require_once( get_template_directory() . '/includes/plugin-codes/vc/custom-mappings.php' );
			
			add_action( 'init', 'uxbarn_customize_vc_elements' );
			add_action( 'admin_init', 'uxbarn_create_theme_custom_elements' );
			add_action( 'wp_enqueue_scripts', 'uxbarn_remove_vc_prettyphoto', 99 );

			// This will hide certain tabs under the Settings->Visual Composer page + disable auto update checker by passing "true"
			if ( function_exists( 'vc_set_as_theme' ) ) vc_set_as_theme( true );
			
			// Changed VC template's folder name
			if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
				$dir = get_stylesheet_directory() . '/updated_vc_templates';
				vc_set_shortcodes_templates_dir( $dir );
			}
			
		}
		
		// WooCommerce plugin
		if ( class_exists( 'Woocommerce' ) ) {
			
			add_theme_support( 'woocommerce' );
			add_action( 'wp_enqueue_scripts', 'uxbarn_load_wooc_assets', 20 ); // using higher priority than the plugin's to make it working
			add_action( 'admin_enqueue_scripts', 'uxbarn_load_wooc_admin_assets' );
			add_action( 'init', 'uxbarn_init_woocommerce' );
			add_action( 'widgets_init', 'uxbarn_wooc_register_sidebars' );
			
		}
		
		
		
		// UXbarn Portfolio plugin
		if ( is_plugin_active( 'uxbarn-portfolio/uxbarn-portfolio.php' ) ) {
			
			require_once( get_template_directory() . '/includes/plugin-codes/custom-uxbarn-portfolio.php' );
			
			// Run theme's custom code to override functions of the plugin
			add_action( 'init', 'uxbarn_portfolio_custom' );
			
		}
		
		// UXbarn Team plugin
		if ( is_plugin_active( 'uxbarn-team/uxbarn-team.php' ) ) {
			
			require_once( get_template_directory() . '/includes/plugin-codes/custom-uxbarn-team.php' );
			
			// Run theme's custom code to override functions of the plugin
			add_action( 'init', 'uxbarn_team_custom' );
			
		}
		
		// UXbarn Testimonials plugin
		if ( is_plugin_active( 'uxbarn-team/uxbarn-team.php' ) ) {
			
			require_once( get_template_directory() . '/includes/plugin-codes/custom-uxbarn-testimonials.php' );
			
			// Run theme's custom code to override functions of the plugin
			add_action( 'init', 'uxbarn_testimonials_custom' );
			
		}
		
	}
	
}



/***** OptionTree Filters *****/

// Hide Theme Options UI builder
add_filter( 'ot_show_options_ui', '__return_false' );
// Hide Documentation menu
add_filter( 'ot_show_docs', '__return_false' );
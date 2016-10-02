<?php

// ---------------------------------------------- //
// Plugins-related functions & codes
// ---------------------------------------------- //

/***** WooCommerce *****/
if ( ! function_exists( 'uxbarn_init_woocommerce' ) ) {
	
	function uxbarn_init_woocommerce() {
		
		// Change main wrapper
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
		add_action( 'woocommerce_before_main_content', 'uxbarn_wooc_theme_wrapper_start', 10 );
		add_action( 'woocommerce_after_main_content', 'uxbarn_wooc_theme_wrapper_end', 10 );
		
		// Rearrange the position of "cart total" and "related items" on the cart template
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
		add_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals' ); // comes first
		add_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
		
		// Remove breadcrumbs
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
		
		// Remove WooCommerce default sidebar (use our own predefined)
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
		
		// Remove default shop page title
		add_filter( 'woocommerce_show_page_title', 'uxbarn_wooc_remove_shop_page_title' );
		
		// Remove default product category description
		remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
		remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );
		
		
		// Set a number of columns for the shop page and archive page
		if ( is_shop() || is_product_category() ) {
			
			$columns_option = '3';
			if ( function_exists( 'ot_get_option' ) ) {
				$columns_option = ot_get_option( 'uxbarn_to_setting_wooc_number_of_columns', '3' ); // get from Theme Options
			}
			add_filter( 'loop_shop_columns', create_function( '', 'return ' . $columns_option . ';' ) );
			
		}
		
		
		// Set a number of products per page
		$products_number_option = '15';
		if ( function_exists( 'ot_get_option' ) ) {
			$products_number_option = ot_get_option( 'uxbarn_to_setting_wooc_number_of_products', '15' ); // get from Theme Options
		}
		
		if ( ! is_numeric( $products_number_option ) ) {
			$products_number_option = '15';
		}
		add_filter( 'loop_shop_per_page', create_function( '$cols', 'return ' . $products_number_option . ';' ), 20 );
		
		
		// Change up-sell and related-product sections to use our own action and function
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		add_action( 'uxbarn_woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		
		$display_related_product = 'true';
		if ( function_exists( 'ot_get_option' ) ) {
			$display_related_product = ot_get_option( 'uxbarn_to_setting_wooc_display_related_product', 'true' );
		}
		if ( $display_related_product == 'true' ) {
			add_action( 'uxbarn_woocommerce_after_single_product_summary', 'uxbarn_woocommerce_output_related_products', 20 );
		}
		
		// Create theme filters for updating image sizes specifically for the theme (only if they have not been updated in the first time yet)
		if ( ! get_option( 'uxbarn_are_wooc_image_sizes_already_updated' ) ) {
			
			add_filter( 'uxbarn_filter_update_wooc_image_sizes', 'uxbarn_update_wooc_image_sizes' );
			apply_filters( 'uxbarn_filter_update_wooc_image_sizes', array( 'shop_thumbnail', '125', '125', 1 ) );
			apply_filters( 'uxbarn_filter_update_wooc_image_sizes', array( 'shop_catalog', '340', '340', 1 ) );
			apply_filters( 'uxbarn_filter_update_wooc_image_sizes', array( 'shop_single', '406', '406', 1 ) );
			
			add_option( 'uxbarn_are_wooc_image_sizes_already_updated', true );
			
		} 
		
		// Disable WooCommerce default styles 
		if ( version_compare( WOOCOMMERCE_VERSION, '2.1' ) >= 0 ) {
			add_filter( 'woocommerce_enqueue_styles', '__return_false' );
		} else {
			define( 'WOOCOMMERCE_USE_CSS', false );
		}
		
		
		
		// Edit the HTML output of the main product image and thumbnails
		add_filter( 'woocommerce_single_product_image_html', 'uxbarn_wooc_custom_product_image_html', 10, 2 );
		add_filter( 'woocommerce_single_product_image_thumbnail_html', 'uxbarn_wooc_custom_product_image_thumbnail_html', 10, 4 );
		
	}
	
}



if ( ! function_exists( 'uxbarn_wooc_custom_product_image_html' ) ) {

    function uxbarn_wooc_custom_product_image_html( $html, $post_id ) {
		
		// Add the "image-box" class and edit the "data-rel" so the theme's Fancybox can be working
		$html = str_replace( 'class="woocommerce-main-image', 'class="image-box woocommerce-main-image', $html );
		$html = str_replace( 'data-rel="prettyPhoto', 'data-fancybox-group="prod_' . $post_id . '_prettyPhoto', $html );
		
		return $html;
		
	}

}



if ( ! function_exists( 'uxbarn_wooc_custom_product_image_thumbnail_html' ) ) {

	function uxbarn_wooc_custom_product_image_thumbnail_html( $html, $attachment_id, $post_id, $image_class ) {
		
		// Add the "image-box" class and edit the "data-rel" so the theme's Fancybox can be working
		$html = str_replace( 'class="zoom', 'class="image-box zoom', $html );
		$html = str_replace( 'data-rel="prettyPhoto', 'data-fancybox-group="prod_' . $post_id . '_prettyPhoto', $html );
		
		return $html;
		
	}

}
	



if ( ! function_exists( 'uxbarn_woocommerce_output_related_products' ) ) {
		
	function uxbarn_woocommerce_output_related_products() {
		
		$max_number = 4;
		if ( function_exists( 'ot_get_option' ) ) {
			$max_number = (int)ot_get_option( 'uxbarn_to_setting_wooc_max_related_products', 4 ); // get from Theme Options
		}
		
		// Set max number of Related Products section
		if ( version_compare( WOOCOMMERCE_VERSION, '2.1' ) >= 0 ) { // For later than v2.1
			
			$args = array(
				'posts_per_page' => $max_number,
				'columns' => 4,
				'orderby' => 'rand'
			);
	
			woocommerce_related_products( apply_filters( 'woocommerce_output_related_products_args', $args ) );
			
		} else {
			
			woocommerce_related_products( $max_number, 4 ); // 4 products max, 4 columns (not used because the theme is using 4-columns layout by default)
			
		}
		
	}

}



if ( ! function_exists( 'uxbarn_wooc_register_sidebars' ) ) {
		
	function uxbarn_wooc_register_sidebars() {
		
		// Shop page sidebar
		register_sidebar( array (
				'name' 			=> __( 'Shop Page Sidebar', 'uxbarn' ),
				'id' 			=> 'uxbarn-wooc-shop-page-sidebar',
				'description' 	=> __( 'Sidebar for WooCommerce shop and archive page.', 'uxbarn' ),
				'before_widget' => '<div id="%1$s" class="%2$s widget-item row"><div class="uxb-col large-12 columns"><div class="inner-widget-item">',
				'after_widget' 	=> '</div></div></div>',
				'before_title' 	=> '<h4>',
				'after_title' 	=> '</h4>',
			)
		);
			
	}

}



if ( ! function_exists( 'uxbarn_wooc_remove_shop_page_title' ) ) {
		
	function uxbarn_wooc_remove_shop_page_title() {
		return '';
	}

}



if ( ! function_exists( 'uxbarn_update_wooc_image_sizes' ) ) {
	
	function uxbarn_update_wooc_image_sizes( $image_size_array ) {
		
		$size = array(
					'width' 	=> $image_size_array[1],
					'height' 	=> $image_size_array[2],
					'crop' 		=> $image_size_array[3],
				);
				
		update_option( $image_size_array[0] . '_image_size', $size );
		
	}
	
}
	
			

if ( ! function_exists( 'uxbarn_wooc_theme_wrapper_start' ) ) {
	
	function uxbarn_wooc_theme_wrapper_start() {
		
		$class = '';
		$shop_sidebar_wrapper_start = '';
		
		if ( is_shop() ) {
			
			$class = ' shop-page ';
			
			$sidebar_appearance = ot_get_option( 'uxbarn_to_setting_wooc_shop_page_sidebar', 'none' ); // get from Theme Options
			$content_class = '';
			$sidebar_class = '';
			$content_column_class = ' large-9 ';
			
			if ( $sidebar_appearance != 'none' ) {
				
				if ( $sidebar_appearance == 'left' ) {
					
					$content_class = ' push-3 ';
					$sidebar_class = ' pull-9 ';
					
				}
				
				$content_class .= ' with-sidebar ';
				$shop_sidebar_wrapper_start = '<div class="row"><div class="uxb-col ' . $content_column_class . ' columns ' . $content_class . '">';
				
			}
			
		}
		
		if ( is_product_category() ) {
			$class = ' product-category-page ';
		}
		
		
		echo '<div id="wooc-content-container" class="columns-content-width ' . $class . '">' . $shop_sidebar_wrapper_start;
	}
	
}



if ( ! function_exists( 'uxbarn_wooc_theme_wrapper_end' ) ) {
	
	function uxbarn_wooc_theme_wrapper_end() {
		
		$shop_sidebar_wrapper_end = '';
		$display_shop_sidebar_content = false;
		
		if ( is_shop() ) {
			
			$sidebar_appearance = ot_get_option( 'uxbarn_to_setting_wooc_shop_page_sidebar', 'none' ); // get from Theme Options
			$sidebar_class = '';
			
			if ( $sidebar_appearance != 'none' ) {
				
				if ( $sidebar_appearance == 'left' ) {
					$sidebar_class = ' pull-9 ';
				}
				
				$shop_sidebar_wrapper_end = '</div></div>';
				$display_shop_sidebar_content = true;
				
			}

		}
		
		echo '</div>';
		
		if ( $display_shop_sidebar_content ) : 
		?>
			<div id="sidebar-wrapper" class="uxb-col large-3 columns <?php echo $sidebar_class; ?>"><?php dynamic_sidebar( 'uxbarn-wooc-shop-page-sidebar' ); ?></div>
		<?php
		endif;
		
		echo $shop_sidebar_wrapper_end;
	}
	
}



if ( ! function_exists( 'uxbarn_load_wooc_assets' ) ) {
	
	function uxbarn_load_wooc_assets() {
		
		wp_register_style( 'uxbarn-woocommerce', get_template_directory_uri() . '/css/woocommerce-custom.css', false );
		wp_register_style( 'uxbarn-woocommerce-newer', get_template_directory_uri() . '/css/woocommerce-custom-newer.css', false );
		wp_enqueue_style( 'uxbarn-woocommerce' );
		
		if ( version_compare( WOOCOMMERCE_VERSION, '2.1' ) >= 0 ) {
			wp_enqueue_style( 'uxbarn-woocommerce-newer' );
		}
		
		wp_register_script( 'uxbarn-scrollintoview', get_template_directory_uri() . '/js/jquery.scrollintoview.min.js', array( 'jquery' ), null );
		
		// Disable WooCommerce's lightbox to use our own on the product single page
		//if ( is_product() ) {
			
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
			wp_dequeue_script( 'prettyPhoto' );
			wp_dequeue_script( 'prettyPhoto-init' );
			
			wp_enqueue_script( 'uxbarn-mousewheel' );
			wp_enqueue_style( 'uxbarn-fancybox' );
			wp_enqueue_script( 'uxbarn-fancybox' );
			wp_enqueue_style( 'uxbarn-fancybox-helpers-thumbs' );
			wp_enqueue_script( 'uxbarn-fancybox-helpers-thumbs' );
			
			wp_enqueue_script( 'uxbarn-scrollintoview' );
			
		//}

	}
	
}



if ( ! function_exists( 'uxbarn_load_wooc_admin_assets' ) ) {
	
	function uxbarn_load_wooc_admin_assets( $page ) {
		
		if ( $page == 'woocommerce_page_woocommerce_settings' || 
			 $page == 'woocommerce_page_wc-settings' ) { // For WooC v2.1
			wp_enqueue_script( 'uxbarn-admin-wooc', get_template_directory_uri() . '/js/theme-wooc.js', false, false, true );
		}
		
	}
	
}



/***** LayerSlider *****/
if ( ! function_exists( 'uxbarn_layerslider_overrides' ) ) {
	
	function uxbarn_layerslider_overrides() {
		// Disable auto-update feature
		$GLOBALS['lsAutoUpdateBox'] = false;
	}
	
}



/***** OptionTree *****/
// Hide the "Settings" menu of OptionTree 
if ( ! function_exists( 'uxbarn_hide_ot_admin_menu' ) ) {
	
	function uxbarn_hide_ot_admin_menu() {
		echo '<style type="text/css">#toplevel_page_ot-settings{display:none;}</style>';
	}
	
}



// To register OptionTree's "Theme Options" menu at the root of admin panel.
function ot_register_theme_options_page() {
  
	/* get the settings array */
	$get_settings = get_option( 'option_tree_settings' );
	
	/* sections array */
	$sections = isset( $get_settings['sections'] ) ? $get_settings['sections'] : array();
	
	/* settings array */
	$settings = isset( $get_settings['settings'] ) ? $get_settings['settings'] : array();
	
	/* contexual_help array */
	$contextual_help = isset( $get_settings['contextual_help'] ) ? $get_settings['contextual_help'] : array();
	
	/* build the Theme Options */
	if ( function_exists( 'ot_register_settings' ) && OT_USE_THEME_OPTIONS ) {
	  
	  ot_register_settings( array(
		  array(
			'id'                  => 'option_tree',
			'pages'               => array( 
			  array(
				'id'              => 'ot_theme_options',
				'parent_slug'     => '',
				'page_title'      => apply_filters( 'ot_theme_options_page_title', __( 'Theme Options', 'uxbarn' ) ),
				'menu_title'      => apply_filters( 'ot_theme_options_menu_title', __( 'Theme Options', 'uxbarn' ) ),
				'capability'      => $caps = apply_filters( 'ot_theme_options_capability', 'edit_theme_options' ),
				'menu_slug'       => apply_filters( 'ot_theme_options_menu_slug', 'ot-theme-options' ),
				'icon_url'        => apply_filters( 'ot_theme_options_icon_url', UXB_THEME_ROOT_IMAGE_URL . 'admin/uxbarn-admin-s.jpg' ),
				'position'        => apply_filters( 'ot_theme_options_position', null ),
				'updated_message' => apply_filters( 'ot_theme_options_updated_message', __( 'Theme Options updated.', 'uxbarn' ) ),
				'reset_message'   => apply_filters( 'ot_theme_options_reset_message', __( 'Theme Options reset.', 'uxbarn' ) ),
				'button_text'     => apply_filters( 'ot_theme_options_button_text', __( 'Save Changes', 'uxbarn' ) ),
				'screen_icon'     => 'themes',
				'contextual_help' => $contextual_help,
				'sections'        => $sections,
				'settings'        => $settings
			  )
			)
		  )
		) 
	  );
	  
	  // Filters the options.php to add the minimum user capabilities.
	  add_filter( 'option_page_capability_option_tree', create_function( '$caps', "return '$caps';" ), 999 );
	
	}
  
}
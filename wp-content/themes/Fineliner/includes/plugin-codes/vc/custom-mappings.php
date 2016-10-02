<?php

if ( ! function_exists( 'uxbarn_create_theme_custom_elements' ) ) {
	
	function uxbarn_create_theme_custom_elements() {
		
		// Remap custom code for old post grids element
		if ( function_exists( 'vc_remove_element' ) ) {
			//vc_remove_element( 'vc_posts_grid' );
		}
		
		if ( function_exists( 'vc_map' ) ) {
				
			// Custom blog posts element
			$id_array = array();
			$args = array(
						'hide_empty' => 0,
						'orderby' => 'title',
						'order' => 'ASC',
					);
			$categories = get_categories($args);
			if(count($categories) > 0) {
				foreach($categories as $category) {
						
					// If WPML is active
					if( function_exists( 'icl_object_id' ) && is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' )) {
						
						global $sitepress;
						
						if ( isset( $sitepress ) ) {
								
							$default_lang = $sitepress->get_default_language();
							
							// Text will be changed depending on current active lang, but the IDs are still original ones from default lang
							$id_array[$category->name] = icl_object_id($category->term_id, 'category', true, $default_lang);
							
						} else {
							$id_array[$category->name] = $category->term_id;
						}
						
					} else { // If there is no WPML
						$id_array[$category->name] = $category->term_id;
					}
					
				}
			}

			vc_map( array(
			   'name' => __('Blog Posts (Theme Custom)', 'uxbarn' ),
			   'base' => 'vc_posts_grid',
			   'class' => '',
			   'category' => __('Theme Custom', 'uxbarn' ),
			   'params' => array(
				  array(
					 'type' => 'checkbox',
					 'holder' => 'div',
					 'class' => '',
					 'heading' => __('Blog categories', 'uxbarn' ),
					 'param_name' => 'grid_categories',
					 'value' => $id_array,
					 'description' => __('Select the categories from the list.', 'uxbarn' ),
					 'admin_label' => true,
				  ),
				  array(
					 'type' => 'textfield',
					 'holder' => 'div',
					 'class' => '',
					 'heading' => __('Maximum number of items to be displayed', 'uxbarn' ),
					 'param_name' => 'grid_teasers_count',
					 'value' => '',
					 'description' => __('Enter a number to limit the max number of items to be listed. Leave it blank to show all items from the selected categories above. Only number is allowed.', 'uxbarn' ),
					 'admin_label' => false,
				  ),
				  array(
					 'type' => 'dropdown',
					 'holder' => 'div',
					 'class' => '',
					 'heading' => __('Type', 'uxbarn' ),
					 'param_name' => 'type',
					 'value' => array(
									__('Columns Grid', 'uxbarn' ) => 'grid',
									__('List Items', 'uxbarn' ) => 'list',
								),
					'std'	=> 'grid',
					 'description' => __('Select the display type.', 'uxbarn' ),
					 'admin_label' => true,
				  ),
				  array(
					 'type' => 'dropdown',
					 'holder' => 'div',
					 'class' => '',
					 'heading' => __('Columns', 'uxbarn' ),
					 'param_name' => 'grid_columns_count',
					 'value' => array(
									__('3 Columns', 'uxbarn' ) => '3',
									__('4 Columns', 'uxbarn' ) => '4',
								),
					'std'	=> '3',
					 'description' => __('Select the number of columns.', 'uxbarn' ),
					 'admin_label' => true,
					 'dependency' => array(
										'element' => 'type',
										'value' => array('grid'),
									)
				  ),
				  array(
					 'type' => 'dropdown',
					 'holder' => 'div',
					 'class' => '',
					 'heading' => __('Grid item display', 'uxbarn' ),
					 'param_name' => 'grid_layout',
					 'value' => array(
									__('Thumbnail + Title + Excerpt', 'uxbarn' ) => 'thumbnail_title_text',
									__('Title + Thumbnail + Excerpt', 'uxbarn' ) => 'title_thumbnail_text',
									//__('Thumbnail + Excerpt', 'uxbarn' ) => 'thumbnail_text',
									__('Thumbnail + Title', 'uxbarn' ) => 'thumbnail_title',
								),
					'std'	=> 'thumbnail_title_text',
					 'description' => __('Select the display for each item.', 'uxbarn' ),
					 'admin_label' => false,
					 'dependency' => array(
										'element' => 'type',
										'value' => array('grid'),
									)
				  ),
				  
				  array(
					 'type' => 'dropdown',
					 'holder' => 'div',
					 'class' => '',
					 'heading' => __('Display thumbnail?', 'uxbarn' ),
					 'param_name' => 'display_thumbnail',
					 'value' => array(
									__('Yes', 'uxbarn' ) => 'true',
									__('No', 'uxbarn' ) => 'false',
								),
					'std'	=> 'true',
					 'description' => __('Whether to display post thumbnails.', 'uxbarn' ),
					 'admin_label' => false,
				  ),
				  array(
					 'type' => 'dropdown',
					 'holder' => 'div',
					 'class' => '',
					 'heading' => __('Display post meta info?', 'uxbarn' ),
					 'param_name' => 'display_meta',
					 'value' => array(
									__('Yes', 'uxbarn' ) => 'true',
									__('No', 'uxbarn' ) => 'false',
								),
					'std'	=> 'true',
					 'description' => __('Whether to display the meta info of each post (date and comment count)', 'uxbarn' ),
					 'admin_label' => false,
				  ),
				  array(
					 'type' 		=> 'dropdown',
					 'holder' 		=> 'div',
					 'class' 		=> '',
					 'heading' 		=> __( 'Order by', 'uxbarn' ),
					 'param_name' 	=> 'orderby',
					 'value' 		=> array(
											__( 'ID', 'uxbarn' ) 			 => 'ID', 
											__( 'Title', 'uxbarn' ) 		 => 'title',
											__( 'Slug', 'uxbarn' ) 			 => 'name',
											__( 'Published Date', 'uxbarn' ) => 'date',
											__( 'Modified Date', 'uxbarn' )  => 'modified',
											__( 'Random', 'uxbarn' ) 		 => 'rand',
										),
					'std'	=> 'ID',
					 'description' 	=> __( 'Select the which parameter to be used for ordering. <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">See more info here</a>', 'uxbarn' ),
					 'admin_label' 	=> false,
				  ),
				  array(
					 'type' 		=> 'dropdown',
					 'holder' 		=> 'div',
					 'class' 		=> '',
					 'heading' 		=> __( 'Order', 'uxbarn' ),
					 'param_name' 	=> 'order',
					 'value' 		=> array(
											__( 'Ascending', 'uxbarn' )  => 'ASC', 
											__( 'Descending', 'uxbarn' ) => 'DESC',
										),
					'std'	=> 'ASC',
					 'description' 	=> __( '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">See more info here</a>', 'uxbarn' ),
					 'admin_label' 	=> false,
				  ),
				  array(
					 'type' 		=> 'textfield',
					 'holder' 		=> 'div',
					 'class' 		=> '',
					 'heading' 		=> __( 'Extra class name', 'uxbarn' ),
					 'param_name' 	=> 'el_class',
					 'value' 		=> '',
					 'description' 	=> __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'uxbarn' ),
					 'admin_label' 	=> false,
				  ),
			   )
			) );

			}
		
	}
	
}
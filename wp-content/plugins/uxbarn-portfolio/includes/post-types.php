<?php

if ( ! function_exists( 'uxb_port_register_cpt' ) ) {

	function uxb_port_register_cpt() {
	
		$cpt_args = array(
		            'label' 			=> __( 'Portfolio', 'uxb_port' ),
		            'labels' 			=> array(
						                        'singular_name'		=> __( 'Portfolio', 'uxb_port' ),
						                     	'add_new' 			=> __( 'Add New Portfolio Item', 'uxb_port' ),
						                        'add_new_item' 		=> __( 'Add New Portfolio Item', 'uxb_port' ),
						                        'new_item' 			=> __( 'New Portfolio Item', 'uxb_port' ),
						                        'edit_item' 		=> __( 'Edit Portfolio Item', 'uxb_port' ),
						                        'all_items' 		=> __( 'All Portfolio Items', 'uxb_port' ),
						                        'view_item' 		=> __( 'View Portfolio', 'uxb_port' ),
						                        'search_items' 		=> __( 'Search Portfolio', 'uxb_port' ),
						                        'not_found' 		=> __( 'Nothing found', 'uxb_port' ),
						                        'not_found_in_trash' => __( 'Nothing found in Trash', 'uxb_port' ),
			                        		),
		            'menu_icon' 		=> UXB_PORT_URL . 'images/uxbarn_sm2.jpg',
		            'description' 		=> __( 'Portfolio of your business', 'uxb_port' ),
		            'public' 			=> true,
		            'show_ui' 			=> true,
		            'capability_type' 	=> 'post',
		            'hierarchical' 		=> false,
		            'has_archive' 		=> false,
		            'supports' 			=> array( 'title', 'editor', 'thumbnail', 'revisions', 'comments' ),
		            'rewrite' 			=> array( 'slug' => __( 'portfolio', 'uxb_port' ), 'with_front' => false )
	        	);
				
				
		// Making this argument "override-able" by the filter with custom callback in theme
		$cpt_args = apply_filters( 'uxb_port_register_cpt_args_filter', $cpt_args );
		
		// Register post type
		register_post_type( 'uxbarn_portfolio', $cpt_args );
		
		
		
		$tax_args = array(
			            'label' 			=> __( 'Portfolio Categories', 'uxb_port' ), 
			            'labels' 			=> array(
													'singular_name' => __( 'Portfolio Category', 'uxb_port' ),
										            'search_items' 	=> __( 'Search Categories', 'uxb_port' ),
										            'all_items' 	=> __( 'All Categories', 'uxb_port' ),
										            'edit_item' 	=> __( 'Edit Category', 'uxb_port' ), 
										            'update_item' 	=> __( 'Update Category', 'uxb_port' ),
										            'add_new_item' 	=> __( 'Add New Category', 'uxb_port' ),
										        ),
			            'singular_label' 	=> __( 'Portfolio Category', 'uxb_port' ),
			            'hierarchical' 		=> true, 
			            'rewrite' 			=> array( 'slug' => __( 'portfolio-category', 'uxb_port' ) ),
			        );
		
		// Making this argument "override-able" by the filter with custom callback in theme
		$tax_args = apply_filters( 'uxb_port_register_tax_args_filter', $tax_args );
		
		// Register taxonomy
		register_taxonomy( 'uxbarn_portfolio_tax', array( 'uxbarn_portfolio' ), $tax_args );
        
		
		
		// Custom columns
        add_filter( 'manage_uxbarn_portfolio_posts_columns', 'uxb_port_create_columns_header' );  
        add_action( 'manage_uxbarn_portfolio_posts_custom_column', 'uxb_port_create_columns_content' );  
		
	}

}



if ( ! function_exists( 'uxb_port_create_columns_header' ) ) {
	
    function uxb_port_create_columns_header( $defaults ) {
    	
        $custom_columns = array(
            'cb' 			=> '<input type=\"checkbox\" />',
            'title' 		=> __( 'Title', 'uxb_port' ),
            'cover_image' 	=> __( 'Thumbnail', 'uxb_port' ),
            'item_format' 	=> __( 'Item Format', 'uxb_port' ),
            'layout_mode' 	=> __( 'Layout Mode', 'uxb_port' ),
            'terms' 		=> __( 'Categories', 'uxb_port' )
        );

        $merged_columns = array_merge( $custom_columns, $defaults );
        
		$merged_columns = apply_filters( 'uxb_port_create_columns_header_filter', $merged_columns, $defaults, $custom_columns );
		
        return $merged_columns;
        
    }
    
}



if ( ! function_exists( 'uxb_port_create_columns_content') ) {
	
    function uxb_port_create_columns_content( $column ) {
    	
        global $post;
        switch ( $column )
        {
            case 'cover_image':
                the_post_thumbnail( 'thumbnail' );
                break;
            case 'item_format':
                echo ucwords( get_post_meta( $post->ID, 'uxbarn_portfolio_item_format', true ) );
                break;
            case 'layout_mode':
				
				if ( get_post_meta( $post->ID, 'uxbarn_portfolio_item_format', true ) == 'image-slideshow' ) {
                	echo ucwords( get_post_meta( $post->ID, 'uxbarn_portfolio_image_slideshow_layout', true ) );
                } else {
                	echo '-';
                }

                break;
            case 'terms':
                $terms = get_the_terms( $post->ID, 'uxbarn_portfolio_tax' );
				
                if ( ! empty( $terms ) ) {
                    $out = array();
                    foreach ( $terms as $term )
                        $out[] = '<a href="' . get_term_link( $term->slug, 'uxbarn_portfolio_tax' ) .'">' . $term->name . '</a>';
                        echo join( ', ', $out );
                
                } else {
                    echo ' ';
                }
                break;
        }
		
    }
	
}
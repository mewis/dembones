<?php

/***** UXbarn Team *****/
if ( ! function_exists( 'uxbarn_team_custom' ) ) {
	
	function uxbarn_team_custom() {
		
		// Remove default plugin's image sizes and register them again with some adjustment
		remove_image_size( 'uxb-team-single-page' );
		add_image_size( 'uxb-team-single-page', 395, 9999 );
		
		// Filter to override the plugin's shortcode output
		add_filter( 'uxb_team_load_team_shortcode_filter', 'uxbarn_custom_team_load_team_shortcode', 10, 2 );
		
		// Filter to override the plugin's CPT argument
		add_filter( 'uxb_team_register_cpt_args_filter', 'uxbarn_custom_team_cpt_args' );
		
	}
	
}



if ( ! function_exists( 'uxbarn_custom_team_cpt_args' ) ) {

	function uxbarn_custom_team_cpt_args( $args ) {
		
		$args = array(
			'label' 			=> __( 'Team', 'uxb_team' ),
			'labels' 			=> array(
										'singular_name'		=> __( 'Team Member', 'uxb_team' ),
										'add_new' 			=> __( 'Add New Member', 'uxb_team' ),
										'add_new_item' 		=> __( 'Add New Member', 'uxb_team' ),
										'new_item' 			=> __( 'New Member', 'uxb_team' ),
										'edit_item' 		=> __( 'Edit Member', 'uxb_team' ),
										'all_items' 		=> __( 'All Members', 'uxb_team' ),
										'view_item' 		=> __( 'View', 'uxb_team' ),
										'search_items' 		=> __( 'Search Member', 'uxb_team' ),
										'not_found' 		=> __( 'Nothing found', 'uxb_team' ),
										'not_found_in_trash' => __( 'Nothing found in Trash', 'uxb_team' ),
									),
			'menu_icon' 		=> UXB_THEME_ROOT_IMAGE_URL . 'admin/uxbarn-admin-s.jpg',
			'description' 		=> __( 'Team', 'uxb_team' ),
			'public' 			=> true,
			'show_ui' 			=> true,
			'capability_type' 	=> 'post',
			'hierarchical' 		=> false,
			'has_archive' 		=> false,
			'supports' 			=> array( 'title', 'editor', 'thumbnail', 'revisions' ),
			'rewrite' 			=> array( 'slug' => __( 'team', 'uxb_team' ), 'with_front' => false ) 
		);
		
		return $args;
		
	}
	
}



if ( ! function_exists( 'uxbarn_custom_team_load_team_shortcode' ) ) {
	
	function uxbarn_custom_team_load_team_shortcode( $val, $atts ) {
		
		if ( function_exists( 'vc_map_get_attributes' ) ) {
			
			extract( vc_map_get_attributes( 'uxb_team', $atts ) );
				
		} else {
				
			$default_atts = array(
								'member_id' 		=> '', // Name|ID
								'image_size' 		=> 'full', 
								'link' 				=> 'true', // true, false
								'heading_size' 		=> 'large',
								'display_social' 	=> 'true', // true, false
								'css_animation' 	=> '',
							);  
										
			extract( shortcode_atts( $default_atts, $atts ) );
			
		}
			
			
		if ( $member_id != '' ) {
			
			$member_id = explode( '|', $member_id );
			$member_id = $member_id[1];
				
			// If WPML is active, get translated ID
			if ( function_exists( 'icl_object_id' ) ) {
				$member_id = icl_object_id( $member_id, 'uxbarn_team', false, ICL_LANGUAGE_CODE );
				// If the returned ID is NULL (meaning no translated post), return empty string.
				if ( ! isset( $member_id ) ) {
					return '';
				}
			}
			
			
			$thumbnail ='';
			if ( has_post_thumbnail( $member_id ) ) {
				$thumbnail = get_the_post_thumbnail( $member_id, $image_size, array( 'class' => 'border' ) );
			}
			
			$name = get_the_title( $member_id );
			if ( $link == 'true' ) {
				
				$thumbnail = '<a href="' . get_permalink( $member_id ) . '" class="image-link">' . $thumbnail . '</a>';
				$name = '<a href="' . get_permalink( $member_id ) . '">' . $name . '</a>';
				
			}
			
			$position = get_post_meta( $member_id, 'uxbarn_team_meta_info_position', true );
			
			$excerpt = get_post_meta( $member_id, 'uxbarn_team_excerpt', true );
			
			$heading_name = 'h2';
			$heading_position = 'h3';
			$heading_class = '';
			
			if ( $heading_size == 'small' ) {
				
				$heading_name = 'h3';
				$heading_position = 'h4';
				$heading_class = ' smaller ';
				
			}
			
			$css_animation = uxb_team_get_css_animation_complete_class( $css_animation );
			
			$output = '<div class="uxb-team-wrapper ' . $css_animation . ' teamid-' . $member_id . '">';
			$output .= '
				<div class="uxb-team-thumbnail">
					' . $thumbnail . '
				</div>
				<' . $heading_name . ' class="uxb-team-name">' . $name . '</' . $heading_name . '>
				<' . $heading_position . ' class="uxb-team-position">' . $position . '</' . $heading_position . '>
				<p>
					' . $excerpt . '
				</p>';
			
			$includedHTML = '';
			if ( $display_social == 'true' ) {
				
				ob_start();
				?>
				
				<ul class="uxb-team-social">
					<?php uxb_team_get_member_social_list_string( $member_id ); ?>
					<li class="dummy-li">&nbsp;</li>
				</ul>
				
				<?php
				$includedHTML = ob_get_clean();
				
			}
			
			$output .= $includedHTML;
			$output .= '</div>'; // close "team-member-wrapper"
			
			return $output;
			
		} else { // If no member selected
			return '';
		}
		
	}
	
}
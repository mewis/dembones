<?php

if ( ! function_exists( 'uxb_team_load_team_shortcode' ) ) {
    
    function uxb_team_load_team_shortcode( $atts ) {
    	
		// Making this shortcode output "override-able" by the filter with custom callback in theme
		$output = apply_filters( 'uxb_team_load_team_shortcode_filter', '', $atts );
		if ( $output != '' ) {
			return $output;
		}
		
		
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
            
            $output = '<div class="uxb-team-wrapper ' . $css_animation . '">';
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
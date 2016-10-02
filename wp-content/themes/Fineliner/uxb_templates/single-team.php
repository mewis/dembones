<?php get_header(); ?>

<?php if ( have_posts() ) : while( have_posts() ) : the_post(); ?>
    
<?php

    $member_id = get_the_ID();
    
    $thumbnail ='';
    $member_info_col_class = ' large-12 ';
	
	$member_image_url = get_post_meta( $member_id, 'uxbarn_team_single_page_image_upload', true );
	
    if ( $member_image_url != '' ) {
    	
        $member_info_col_class = ' large-7 ';
		$member_image_url = esc_url( $member_image_url );
		
    }
    
    $position = get_post_meta( $member_id, 'uxbarn_team_meta_info_position', true );
    $email = get_post_meta( $member_id, 'uxbarn_team_meta_info_email', true );
    
    //$social_list_item_string = uxb_team_get_member_social_list_string( $member_id );

?>

<div id="uxb-team-single" class="row">
	
	<?php if ( $member_image_url != '' ) : ?>
		
		<div id="uxb-team-single-photo" class="uxb-col large-5 columns">
	    	<img src="<?php echo esc_url( $member_image_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="border" />
	    </div>
	    
    <?php endif; ?>
    
    <div class="uxb-col <?php echo esc_attr( $member_info_col_class ); ?> columns">
    
	    <div id="uxb-team-info">
	        <h1 class="uxb-team-name"><?php the_title(); ?></h1>
	        
	        <?php if ( trim( $position ) != '' ) : ?>
	            <h2 class="uxb-team-position light"><?php echo esc_html( $position ); ?></h2>
	        <?php endif; ?>
	        
	        <?php if ( trim( $email ) != '' ) : ?>
	        	
	            <p>
	                <a href="mailto:<?php echo sanitize_email( $email ); ?>"><?php echo esc_html( $email ); ?></a>
	            </p>
	            
	        <?php endif; ?>
	        
	        <?php //if ( $social_list_item_string != '' ) : ?>
	            
	            <ul class="uxb-team-social">
	                <?php uxb_team_get_member_social_list_string( $member_id ); ?>
	                <li class="dummy-li">&nbsp;</li>
	            </ul>
	            
	        <?php //endif; ?>
	    </div>

		<?php the_content(); ?>
	    
    </div>
    
</div>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
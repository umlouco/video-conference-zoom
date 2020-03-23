<?php
//var_dump($GLOBALS['zoom_shortcode_args']);
$zoom_meetings = ( isset( $GLOBALS['zoom_passed_template_args']['zoom_meetings'] ) && ! empty( $GLOBALS['zoom_passed_template_args']['zoom_meetings'] ) ) ? $GLOBALS['zoom_passed_template_args']['zoom_meetings'] : '';
if ( ! is_object( $zoom_meetings ) && ! ( $zoom_meetings instanceof \WP_Query ) ) {
	return;
}
?>
<div class="vczapi-list-zoom-meetings">
    <div class="vczapi-list-zoom-meetings--items">
		<?php
		while ( $zoom_meetings->have_posts() ):
			$zoom_meetings->the_post();
			$meeting_id      = get_the_ID();
			$meeting_details = get_post_meta( $meeting_id, '_meeting_fields', true );
			?>
            <div class="vczapi-list-zoom-meetings--item">
                <div class="vczapi-list-zoom-meetings--item__image">
					<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail();
					} else {
						echo '<img src="' . VZAPI_WOOCOMMERCE_ADDON_DIR_URI . '/assets/images/zoom-placeholder.png" alt="Placeholder Image">';
					}
					?>
                </div><!--vczapi-list-zoom-meetings--item__image-->
                <div class="vczapi-list-zoom-meetings--item__details">
                    <h2><?php the_title(); ?></h2>
                    <div class="vczapi-list-zoom-meetings--item__details__meta">
                        <div class="hosted-by meta">
                            <strong>Hosted By:</strong>
                            <span><?php echo get_the_author(); ?></span>
                        </div>
                        <div class="start-date meta">
                            <strong><?php _e( 'Start', 'video-conferencing-with-zoom-api' ); ?>:</strong>
                            <span><?php echo date( 'F j, Y @ g:i a', strtotime( $meeting_details['start_date'] ) ); ?>
                        </div>
                        <div class="timezone meta">
                            <strong><?php _e( 'Start', 'video-conferencing-with-zoom-api' ); ?>:</strong>
                            <span><?php echo $meeting_details['timezone']; ?>
                        </div>
                    </div>
                    <a href="<?php echo esc_url( get_the_permalink() ) ?>" class="btn">See More</a>
                </div><!--vczapi-list-zoom-meetings--item__details-->
            </div><!--vczapi-list-zoom-meetings--item-->


		<?php
		endwhile;
		?>
    </div><!--items-->
    <div class="vczapi-list-zoom-meetings--pagination">
		<?php Zoom_Video_Conferencing_Shorcodes::pagination( $zoom_meetings ); ?>
    </div>
	<?php
	wp_reset_postdata();
	?>
</div><!--vczapi-list-zoom-meetings-->
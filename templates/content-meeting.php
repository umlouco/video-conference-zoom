<?php
/**
 * The template for displaying content of archive page meetings
 *
 * This template can be overridden by copying it to yourtheme/video-conferencing-zoom/content-meeting.php.
 *
 * @author Deepen
 * @since 3.0.0
 */

defined( 'ABSPATH' ) || exit;

global $zoom;
?>
<div class="vczapi-list-zoom-meetings--item">
	<?php if ( has_post_thumbnail() ) { ?>
        <div class="vczapi-list-zoom-meetings--item__image">
			<?php the_post_thumbnail(); ?>
        </div><!--Image End-->
	<?php } ?>
    <div class="vczapi-list-zoom-meetings--item__details">
        <h3><?php the_title(); ?></h3>
        <div class="vczapi-list-zoom-meetings--item__details__meta">
            <div class="hosted-by meta">
                <strong><?php _e( 'Hosted By:', 'video-conferencing-with-zoom-api' ); ?></strong> <span><?php echo get_the_author(); ?></span>
            </div>
            <div class="start-date meta">
                <strong><?php _e( 'Start', 'video-conferencing-with-zoom-api' ); ?>:</strong>
                <span><?php echo vczapi_dateConverter( $zoom['api']->start_time, $zoom['api']->timezone, 'F j, Y @ g:i a' ); ?></span>
            </div>
            <div class="timezone meta">
                <strong><?php _e( 'Timezone', 'video-conferencing-with-zoom-api' ); ?>:</strong> <span><?php echo $zoom['api']->timezone; ?></span>
            </div>
        </div>
        <a href="<?php echo esc_url( get_the_permalink() ) ?>" class="btn"><?php _e( 'See More', 'video-conferencing-with-zoom-api' ); ?></a>
    </div><!--Details end-->
</div><!--List item end-->

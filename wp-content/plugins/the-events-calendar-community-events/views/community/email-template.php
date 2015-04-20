<?php
/**
 * Email Template
 * The template for the Event Submission Notification Email
 *
 * Override this template in your own theme by creating a file at
 * [your-theme]/tribe-events/community/email-template.php
 *
 * @package TribeCommunityEvents
 * @since  3.6
 * @author Modern Tribe Inc.
 *
 */

if ( !defined('ABSPATH') ) { die('-1'); }
?>
	<html>
		<body>
			<h2><?php echo $post->post_title; ?></h2>
			<h4><?php echo tribe_get_start_date( $tribe_event_id ); ?> - <?php echo tribe_get_end_date( $tribe_event_id ); ?></h4>
			
			<hr />

			<h3><?php _e( 'Event Organizer', 'tribe-events-community' ); ?></h3>
			<p><?php echo tribe_get_organizer( tribe_get_event_meta( $post->ID, '_EventOrganizerID', true ) ); ?></p>

			<h3><?php _e( 'Event Venue', 'tribe-events-community' ); ?></h3>
			<p><?php echo tribe_get_venue( tribe_get_event_meta( $post->ID, '_EventVenueID', true ) ); ?></p>

			<h3><?php _e( 'Description','tribe-events-community' ); ?></h3>
			<?php echo $post->post_content; ?>
			
			<hr />

			<h4><?php echo $this->getEditButton( $post, __( 'Review Event', 'tribe-events-community' ) ); if ( $post->post_status == 'publish' ) { ?> | <a href="<?php echo get_permalink( $tribe_event_id ); ?>">View Event</a><?php } ?></h4>
		</body>
	</html>
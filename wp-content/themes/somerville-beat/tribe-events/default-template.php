<?php
/**
 * Default Events Template
 * This file is the basic wrapper template for all the views if 'Default Events Template'
 * is selected in Events -> Settings -> Template -> Events Template.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/default-template.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

get_header(); ?>
	<div id="tribe-events-pg-template" <?php if ( false === (tribe_is_event_query() && tribe_is_month()) ) echo('class="ui-block-1"') ?>
>
		<?php tribe_events_before_html(); ?>
		<?php tribe_get_view(); ?>
		<?php tribe_events_after_html(); ?>
	</div> <!-- #tribe-events-pg-template -->

	<section id="sidebar" class="ui-block-2">
		<?php get_sidebar(); ?>
	</section>

</section> <!-- end #main -->

<?php get_footer(); ?>

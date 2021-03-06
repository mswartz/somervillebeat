<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */

get_header(); ?>

	<section class="ui-block-1 search-content">

	<?php if ( have_posts() ) : ?>
		<h1 class="h2"><?php printf( __( 'Search Results for: %s', 'boilerplate' ), '' . get_search_query() . '' ); ?></h1>
		<?php
		/* Run the loop for the search to output the results.
		 * If you want to overload this in a child theme then include a file
		 * called loop-search.php and that will be used instead.
		 */
		 get_template_part( 'loop', 'search' );
		?>
	<?php else : ?>
		<h2><?php _e( 'Nothing Found', 'boilerplate' ); ?></h2>
		<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'boilerplate' ); ?></p>
		<?php get_search_form(); ?>
	<?php endif; ?>
	</section>

	<section id="sidebar" class="ui-block-2">
	<?php get_sidebar(); ?>
	</section>
</section><!-- #main -->

<?php get_footer(); ?>

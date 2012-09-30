<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */

get_header(); ?>

<div class="mainbar ui-block-1">
	
<?php if ( have_posts() ) : ?>
			<section id="page-descrip">
				<div class="entry-content">
					<h1><?php printf( __( 'Search Results for: %s', 'boilerplate' ), '' . get_search_query() . '' ); ?></h1>
				</div>
			</section>
				<?php
				/* Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called loop-search.php and that will be used instead.
				 */
				 get_template_part( 'loop', 'search' );
				?>
<?php else : ?>
	
		<section id="page-descrip">
			<div class="entry-content">
					<h2><?php _e( 'Oops!', 'boilerplate' ); ?></h2>
					<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'boilerplate' ); ?></p>
			</div>
		</section>
		
		<div class="search-again">
					<?php get_search_form(); ?>
		</div>
		
<?php endif; ?>

</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

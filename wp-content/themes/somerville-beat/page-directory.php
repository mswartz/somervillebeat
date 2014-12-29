<?php
/*
Template Name: Directory
*/
?>

<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */

get_header(); ?>

	<section class="ui-block-1 page-content">

		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<?php if ( is_front_page() ) { ?>
							<h1><?php the_title(); ?></h1>
						<?php } else { ?>	
							<h1><?php the_title(); ?></h1>
						<?php } ?>
		
							<div class="page-directory">
								<?php the_content(); ?>
							</div><!-- .page-directory -->
						</article><!-- #post-## -->
		<?php endwhile; ?>
	</section>
	<section id="sidebar" class="ui-block-2">
	<?php get_sidebar(); ?>
	</section>
</section><!-- main -->
<?php get_footer(); ?>
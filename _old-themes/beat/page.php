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

<div class="mainbar ui-block-1">
	dfgdfgdfg
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	
	<section id="page-descrip">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php if ( is_front_page() ) { ?>
					<h2 class="entry-title about"><?php the_title(); ?></h2>
				<?php } else { ?>	
					<h1 class="entry-title about"><?php the_title(); ?></h1>
				<?php } ?>
	</section>
					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'boilerplate' ), 'after' => '' ) ); ?>
						<?php edit_post_link( __( 'Edit', 'boilerplate' ), '', '' ); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-## -->
				<?php comments_template( '', true ); ?>
<?php endwhile; ?>


</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
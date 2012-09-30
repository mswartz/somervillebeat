<?php
/*
Template Name: Grid page
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

	<section class="ui-block-1 page-content page-grid">

		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<?php if ( is_front_page() ) { ?>
							<h2 class="entry-title"><?php the_title(); ?></h2>
						<?php } else { ?>	
							<h1 class="entry-title"><?php the_title(); ?></h1>
						<?php } ?>

						<div class="dropdown">								 
							<div class="dropdown-trigger-hover">
								<a class="dropdown-trigger-link">Jump to a day <span></span></a>
								<ul class="dropdown-menu">
									<li><a href="#sunday">Sunday</a></li>
									<li><a href="#monday">Monday</a></li>
									<li><a href="#tuesday">Tuesday</a></li>
									<li><a href="#wednesday">Wednesday</a></li>
									<li><a href="#thursday">Thursday</a></li>
									<li><a href="#friday">Friday</a></li>
									<li><a href="#saturday">Saturday</a></li>
								</ul> <!-- /.dropdown-menu -->
							</div> <!-- /.dropdown-trigger-hover -->			
						</div> <!-- /.dropdown-container -->
		
							<div class="entry-content">
								<?php the_content(); ?>
								<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'boilerplate' ), 'after' => '' ) ); ?>
								<?php edit_post_link( __( 'Edit', 'boilerplate' ), '', '' ); ?>
							</div><!-- .entry-content -->
						</article><!-- #post-## -->
						<?php comments_template( '', true ); ?>
		<?php endwhile; ?>
	</section>
	<section class="ui-block-2">
	<?php get_sidebar(); ?>
	</section>
</section><!-- main -->
<?php get_footer(); ?>
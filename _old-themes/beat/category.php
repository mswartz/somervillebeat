<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */

get_header(); ?>

<div class="mainbar ui-block-1">
	
	<section id="page-descrip">
				<h2 class="entry-title"><?php
					printf( __( 'Category Archives: %s', 'boilerplate' ), '' . single_cat_title( '', false ) . '' );
				?></h2>
	</section>
				<?php
					$category_description = category_description();
					if ( ! empty( $category_description ) )
						echo '' . $category_description . '';

				/* Run the loop for the category page to output the posts.
				 * If you want to overload this in a child theme then include a file
				 * called loop-category.php and that will be used instead.
				 */
				get_template_part( 'loop', 'category' );
				?>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */

get_header(); ?>

	<section class="ui-block-1 category-content">

		<h1><?php
			printf( __( '%s', 'boilerplate' ), '' . single_cat_title( '', false ) . '' );
		?></h1>

		<?php // Get the category ID and store it
		$cat = get_query_var('cat'); ?>

		<ul class="child-cats"><?php wp_list_categories('title_li&hide_empty=1&child_of='.$cat); ?></ul>

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
	</section>

	<section id="sidebar" class="ui-block-2">
		<?php get_sidebar(); ?>
	</section>
</section><!-- #main -->

<?php get_footer(); ?>
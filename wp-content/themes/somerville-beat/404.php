<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */

get_header(); ?>

	<section class="ui-block-1">
			<article id="post-0" class="post error404 not-found" role="main">
				<h1 class="h1"><?php _e( 'Oops!', 'boilerplate' ); ?></h1>
				<p><?php _e( 'Page not found. Try searching for what you want, or check out some of our favorite posts', 'boilerplate' ); ?></p>
				<script>
					// focus on search field after it has loaded
					document.getElementById('s') && document.getElementById('s').focus();
				</script>
			</article>

			<section class="home-zones">
	<?php 
	$z1 = z_get_zones(); 
	foreach($z1 as $zone){ ?>
		<section class="home-zones-cat">
			<?php
				$f2 = z_get_posts_in_zone($zone->slug);
				echo '<h3 class="home-zones-hdr"><a href="'.get_category_link($zone->term_id).'">'.$zone->name.'</h3>';
				echo '<div class="home-zones-tiles">';
				foreach($f2 as $link){
					//first image in list has thumbnail
					echo '<div class="tile media-block"><div class="home-zones-img">';
					$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $link->ID ), "thumbnail" );
					echo '<a href="'.get_permalink($link->ID).'"><img src="'.$thumbnail_src[0].'"/></a></div>';
					echo '<h4 class="home-zones-title"><a href="'.get_permalink($link->ID).'">'.$link->post_title.'</a></h4></div>';
				}
				echo '</div>';

			?>
		</section>
	<?php }	?><!-- end foreach that looped thru $zones -->
</section><!-- home zones -->
</section><!-- ui block -->

	<section id="sidebar" class="ui-block-2">
	<?php get_sidebar(); ?>
	</section>
</section><!-- main -->


<?php get_footer(); ?>

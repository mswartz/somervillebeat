<?php
/**
* Template Name: Front-Page
*
*/

get_header(); ?>

	<section class="ui-block-1">
		<?php $post = $posts[0]; $c=0;?>
		<?php query_posts('showposts=5'); ?>
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<?php $c++; ?>

			<?php if($c == 1) :?>
				<!-- cover post -->
				<article id="home-cover" <?php post_class(); ?>>
					<div id="home-cover-img"><?php the_post_thumbnail('original');	?></div>
					<div id="home-cover-txt">
						<small><?php the_category(' &raquo; '); ?></small>
						<h1 class="cover-entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'boilerplate' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
						<?php the_excerpt(); ?>
					</div><!-- cover-text -->
				</article>
				<div class="home-tz">
			<?php else : ?>
					<article id="post-<?php the_ID(); ?>" class="home-tz-mod" ?>
						<div class="home-tz-img ui-block-1"><?php the_post_thumbnail('thumbnail');	?></div>
						<div class="ui-block-2"><small><?php the_category(' &raquo; '); ?></small>
						<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p><?php the_excerpt(); ?></p>
						</div>
					</article>
			<?php endif; ?>
		<?php endwhile; ?>
		</div><!-- home-tz -->
	</section>

	<section id="sidebar" class="ui-block-2">
		<?php get_sidebar(); ?>
	</section>
</section><!-- #main -->

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
					echo '<div class="media-block"><div class="home-zones-img">';
					$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $link->ID ), "thumbnail" );
					echo '<a href="'.get_permalink($link->ID).'"><img src="'.$thumbnail_src[0].'"/></a></div>';
					echo '<h4 class="home-zones-title"><a href="'.get_permalink($link->ID).'">'.$link->post_title.'</a></h4></div>';
				}
				echo '</div>';

			?>
		</section>
	<?php }	?><!-- end foreach that looped thru $zones -->
</section>

<?php get_footer(); ?>

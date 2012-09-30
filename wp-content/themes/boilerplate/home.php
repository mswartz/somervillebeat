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
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div id="home-cover-img"><?php	the_post_thumbnail('orginal');	?></div>
					<div id="home-cover-txt">
						<h1 class="cover-entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'boilerplate' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
						<?php the_excerpt(); ?>
					</div><!-- cover-text -->
				</article>
				<div class="home-tz">
			<?php else : ?>
					<article id="post-<?php the_ID(); ?>" class="home-tz-mod media-block" ?>
						<div class="home-tz-img"><?php the_post_thumbnail('thumbnail');	?></div>
						<h3 class="entry-title"><?php the_title(); ?></h3>
						<p><?php the_excerpt(); ?></p>
					</article>
			<?php endif; ?>
		<?php endwhile; ?>
		</div><!-- home-tz -->
	</section>

	<section class="ui-block-2">
		<?php get_sidebar(); ?>
	</section>
</section><!-- #main -->

<section class="home-dp">
	<?php 
	$z1 = z_get_zones(); 
	
	foreach($z1 as $zone){ ?>
		<section class="home-dp-cat media-block">
			<?php
				echo '<h3>'.$zone->name.'</h3>';
				echo '<p>'.$zone->description.'</p>';

				$f2 = z_get_posts_in_zone($zone->slug);
			
				echo '<ul>';
				foreach($f2 as $link){
					//first image in list has thumbnail
					if($f2[0]==$link){
						$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $link->ID ), "medium" );
						echo '<a href="'.get_permalink($link->ID).'"><img src="'.$thumbnail_src[0].'"/></a>';
					}

					echo '<li><a href="'.$link->path.'">'.$link->post_title.'</a>';
					echo '</li>';
				}
				echo '</ul> <!-- linklist -->';
			?>
		</section>
	<?php }	?><!-- end foreach that looped thru $zones -->
</section>

<?php get_footer(); ?>

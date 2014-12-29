<?php
/*
Template Name: Food
*/
?><?php


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
	
	<section id="page-descrip">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				
					<div class="entry-content">
						<?php if ( is_front_page() ) { ?>
							<h2 class="entry-title"><?php the_title(); ?></h2>
						<?php } else { ?>	
							<h1 class="entry-title"><?php the_title(); ?></h1>
						<?php } ?>
						
						
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'boilerplate' ), 'after' => '' ) ); ?>
						<?php edit_post_link( __( 'Edit', 'boilerplate' ), '', '' ); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-## -->
			
<?php endwhile; ?>
</section> <!-- page descrip -->


<?php query_posts('cat=3'); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	
	
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'boilerplate' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

		
		<div class="entry-meta">
			<!-- just in case -->
		</div><!-- .entry-meta -->
		
		<div class="entry-wrapper">
			
	
				<?php
				// check if the post has a Post Thumbnail assigned to it.
				if ( has_post_thumbnail() ) { ?>
						<div class="ui-block-1">
							<div class="post-thumb">
							
							<?php
							
							the_post_thumbnail('large');
							
							?>
					
							</div>
					</div>
				<?php } ?>
				
				
		
		
		<?php if ( has_post_thumbnail() ) { ?> <div class="ui-block-2"> <?php } ?>
			
<?php if ( is_archive() || is_search() ) : // Only display excerpts for archives and search. ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
<?php else : ?>
		<div class="entry-content">
			<div class="posted-on"><?php boilerplate_posted_on(); ?></div>
			
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'boilerplate' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'boilerplate' ), 'after' => '</div>' ) ); ?>
			
			
		</div><!-- .entry-content -->
<?php endif; ?>

		<?php if ( has_post_thumbnail() ) { ?> </div><!-- ui-block-1 --> <?php } ?> 
		<?php // if there was a thumbnail, end the ui-block-2 ?>
		
		</div>
		
		<footer class="entry-utility">
			<?php if ( count( get_the_category() ) ) : ?>
				<?php printf( __( '%2$s', 'boilerplate' ), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list( ' ' ) ); ?>
				
			<?php endif; ?>

			<?php comments_popup_link( __( 'Leave a comment', 'boilerplate' ), __( '1 Comment', 'boilerplate' ), __( '% Comments', 'boilerplate' ) ); ?>
			<?php edit_post_link( __( 'Edit', 'boilerplate' ), ' ', '' ); ?>
			
			<span class="share-tools">
					<a href="https://twitter.com/share" class="twitter-share-button" data-text="<?php the_title(); ?>" data-url="<?php the_permalink(); ?>" data-via="somervillebeat" data-lang="en">Tweet</a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</span>
			
		</footer><!-- .entry-utility -->
	</article><!-- #post-## -->





<?php endwhile; endif; ?>
	
	

</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package wprig
 */

?>


<div class="front-page">
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php wprig_post_thumbnail(); ?>
	<header class="entry-header">



		<div class="post-cats">
			<?php wprig_post_categories(); ?>
		</div>
		<?php

			the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );


		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
					wprig_posted_on();
					wprig_posted_by();
					wprig_comments_link();
				?>
			</div><!-- .entry-meta -->
			<?php
		endif;
		?>
	</header><!-- .entry-header -->

	<div class="contentbox-about">
            <div class="flexbox-3">
				<div class="featured-box-heading"><h2><?php if( get_theme_mod('aboutus_heading', '') != '' ) { echo esc_attr( get_theme_mod('aboutus_heading') ); } else { ?><?php _e('creative','wprig'); } ?></h2></div>
                <div class="singlecontent">

					<div class="featured-box-content"> <p><?php if( get_theme_mod('aboutus_content', '') != '' ){ echo wp_kses_post( get_theme_mod('aboutus_content') ); } else { _e('We have the most talented people in house that are excited to start with your new project. It is the drive that makes them creative.','wprig'); } ?></p></div>
            	</div><!-- .singlecontent -->
            </div><!-- .flexbox-3 -->
     </div><!-- .contentbox-about -->

	<div class="contentbox-category">

                <?php get_template_part('inc/front', 'category-box-section'); ?>

    </div><!-- .contentbox-category -->

	<div class="news-events-section">

		<?php
		// the query
		$the_query = new WP_Query( array(
			'category_name' => 'news-events',
			'posts_per_page' => 3,
		));
			?>
		<div class="news-events-category">

			<?php if ( $the_query->have_posts() ) : ?>
			<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

			<div class="news-events-content"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail(); ?><h4 class="caption-content"><?php the_title(); ?></h4>
			</a></div><!-- .news-event-content -->

			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>

			<?php else : ?>
			<p><?php __('No News'); ?></p>
			<?php endif; ?>

			</div><!--.news-event-category -->

	</div><!-- .news-events-section -->

	<div class="contentbox-program">

		<?php get_template_part('inc/front', 'featured-program-section'); ?>

	</div><!-- .contentbox-program -->

    <footer class="entry-footer">
		<?php
		wprig_post_tags();
		wprig_edit_post_link();
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
	</div><!-- .front-page -->


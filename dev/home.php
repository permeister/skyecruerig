<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package wprig
 */

get_header(); ?>

	<header class="entry-header">
	<h1 class="entry-title">News & Events</h1>
	</header><!-- .entry-header -->


	<main id="primary" class="site-main">
	<div class="news-event">
	<div class="news-event-multi">

	<?php

	if ( have_posts() ) :

		/**
		 * Include the component stylesheet for the content.
		 * This call runs only once on index and archive pages.
		 * At some point, override functionality should be built in similar to the template part below.
		 */
		wp_print_styles( array( 'wprig-content' ) ); // Note: If this was already done it will be skipped.

		/* Display the appropriate header when required. */


		/* Counter to keep track of what post we're on. */
		$post_count = 1;

		/* Start the Loop. */
		while ( have_posts() ) :
			the_post();

			/*
			 * Include the Post-Type-specific template for the content.
			 * If you want to override this in a child theme, then include a file
			 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
			 */
			get_template_part( 'template-parts/content', 'multi' );

			/* Increment counter. */
			$post_count++;

		endwhile;



	else :

		get_template_part( 'template-parts/content', 'multi' );

	endif;
	?>
	</div><!-- .news-event-multi -->



	</div><!-- .news-event -->

	<?php
	if ( ! is_singular() ) :
		the_posts_navigation();
	endif;
	?>

	</main><!-- #primary -->

<?php
get_sidebar();
get_footer();

<?php
/**
 * Template Name: Main Content Template
 *
 * When active, by adding the heading above and providing a custom name
 * this template becomes available in a drop-down panel in the editor.
 *
 * Filename can be anything.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-template-files/#creating-custom-page-templates-for-global-use
 *
 * @package wprig
 */

get_header(); ?>


	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' );	?>
	</header><!-- .entry-header -->

	<main id="primary" class="site-main content">

		<?php
		while ( have_posts() ) :
			the_post();

			/*
			 * Include the component stylesheet for the content.
			 * This call runs only once on index and archive pages.
			 * At some point, override functionality should be built in similar to the template part below.
			 */
			wp_print_styles( array( 'wprig-content' ) ); // Note: If this was already done it will be skipped.

			get_template_part( 'template-parts/content', 'content' );

		endwhile; // End of the loop.
		?>

	</main><!-- #primary -->



<?php
get_sidebar();
get_footer();

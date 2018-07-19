<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package wprig
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>



		<div class="news-event-content">


		<?php wprig_post_thumbnail(); ?>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>

			<?php
		endif;
		?>
	</header><!-- .entry-header -->





	<footer class="entry-footer">

	</footer><!-- .entry-footer -->


	</div><!-- .news-event-content -->


</article><!-- #post-<?php the_ID(); ?> -->


<?php


	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;


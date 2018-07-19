<?php
/**
 * Custom Header feature
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package wprig
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses wprig_header_style()
 */
function wprig_custom_header_setup() {
	add_theme_support(
		'custom-header', apply_filters(
			'wprig_custom_header_args', array(
				'default-image'          => '',
				'default-text-color'     => '000000',
				'width'                  => 1600,
				'height'                 => 250,
				'flex-height'            => true,
				'wp-head-callback'       => 'wprig_header_style',
			)
		)
	);
}
add_action( 'after_setup_theme', 'wprig_custom_header_setup' );


	

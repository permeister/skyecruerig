<?php
/**
 * WP Rig Theme Customizer
 *
 * @package wprig
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wprig_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname', array(
				'selector'        => '.site-title a',
				'render_callback' => 'wprig_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription', array(
				'selector'        => '.site-description',
				'render_callback' => 'wprig_customize_partial_blogdescription',
			)
		);
	}

	/**
	 * Theme options.
	 */
	$wp_customize->add_section(
		'theme_options', array(
			'title'    => __( 'Theme Options', 'wprig' ),
			'priority' => 130, // Before Additional CSS.
		)
	);

	if ( function_exists( 'wprig_lazyload_images' ) ) {
		$wp_customize->add_setting(
			'lazy_load_media', array(
				'default'           => 'lazyload',
				'sanitize_callback' => 'wprig_sanitize_lazy_load_media',
				'transport'         => 'postMessage',
			));

		$wp_customize->add_control(
			'lazy_load_media', array(
				'label'           => __( 'Lazy-load images', 'wprig' ),
				'section'         => 'theme_options',
				'type'            => 'radio',
				'description'     => __( 'Lazy-loading images means images are loaded only when they are in view. Improves performance, but can result in content jumping around on slower connections.', 'wprig' ),
				'choices'         => array(
					'lazyload'    => __( 'Lazy-load on (default)', 'wprig' ),
					'no-lazyload' => __( 'Lazy-load off', 'wprig' ),
				),
			)
		);
	}

	/**
	 * Custom Customizer Customizations
	 */

	// Setting for header and footer background color
	$wp_customize->add_setting( 'theme_bg_color', array(
		'default'			=> '#ffffff',
		'transport'			=> 'postMessage',
		'type'				=> 'theme_mod',
		'sanitize_callback' => 'sanitize_hex_color',
	));

	// Control for header and footer background color.
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'theme_bg_color',
			array(
				'label'		=> __( 'Header and footer background color', 'wprig'),
				'section'	=> 'colors',
				'settings'	=> 'theme_bg_color'
			)
		)
	);

	$wp_customize->add_setting( 'interactive_color' ,
		array(
			'default'			=> '#b51c35',
			'transport'			=> 'postMessage',
			'type'				=> 'theme_mod',
			'sanitize_callback'	=> 'sanitize_hex_color',
			'transport'			=> 'postMessage',
		)
	);

	// Add the controls
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'interactive_color', array(
				'label'		=> __( 'Interactive color (links etc)', 'wprig' ),
				'section'	=> 'colors',
				'settings'	=> 'interactive_color'
			)
		)
	);

	// Add option to select index content
	$wp_customize->add_section( 'theme_options',
		array(
			'title'			=> __( 'Theme Options', 'wprig' ),
			'priority'      => 95,
			'capability'	=> 'edit_theme_options',
			'description'	=> __( 'Change how much of a post is displayed on index and archive pages.', 'wprig' ),
                        
                        
                    
		)
	);
        
        // Create excerpt or full content settings
	$wp_customize->add_setting(	'length_setting',
		array(
			'default'			=> 'excerpt',
			'type'				=> 'theme_mod',
			'sanitize_callback' => 'wprig_sanitize_length', // Sanitization function appears further down
			'transport'			=> 'postMessage'
		)
	);

	// Add the controls
	$wp_customize->add_control(	'wprig_length_control',
		array(
			'type'		=> 'radio',
			'label'		=> __( 'Index/archive displays', 'wprig' ),
			'section'	=> 'theme_options',
			'choices'	=> array(
				'excerpt'		=> __( 'Excerpt (default)', 'wprig' ),
				'full-content'	=> __( 'Full content', 'wprig' )
			),
			'settings'	=> 'length_setting' // Matches setting ID from above
		)
	);

	// Add Home Page Options Panel
        
	$wp_customize->add_panel( 'home_page_panel', array(
		'title' 		=> __( 'Home Page Options', 'wprig' ),
		'description'	=> esc_html__('Add/Edit Content to Front Page'),
		'priority'		=> 95,
		'capability' => 'edit_theme_options',
		) 
	);

	// Add sections to Home Page Options Panel

	$wp_customize->add_section( 'about_us' , array(
		'title' => __('About Us','wprig'),
		'panel' => 'home_page_panel',
            ) );

	$wp_customize->add_section( 'home_featured_section', array(
		'title'		=> __('Featured Box', 'wprig'),
		'description'	=> esc_html__( 'Add icons for categories within site' ),
		'panel'		=> ('home_page_panel'),
		'capability'	=> 'edit_theme_options',
		)
	);

	$wp_customize->add_section( 'home_featured_program_section', array(
		'title'		=> __('Featured Program Box', 'wprig'),
		'description'	=> esc_html__( 'Featured Programs For Your Site' ),
		'panel'		=> ('home_page_panel'),
		'capability'	=> 'edit_theme_options',
		)
	);

	// Home Page Options Section Settings

	// About Us

	$wp_customize->add_setting( 'aboutus_heading', array(
		'default'           => 'Heading',
		'sanitize_callback' => 'sanitize_text_field',
            ) );
        
	$wp_customize->add_control( 'aboutus_heading', array(
		'label' => __( 'About Us Heading:', 'wprig' ),
		'description' 	=> __('Enter about us heading.', 'wprig' ),
		'type'			=> 'text',
		'section' => 'about_us',
            ) );

	$wp_customize->add_setting( 'aboutus_content', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
            ) );
        
	$wp_customize->add_control( 'aboutus_content', array(
		'label' => __( 'About us Content', 'wprig' ),
		'description' 	=> __('Enter your about us content.', 'wprig' ),
		'section' => 'about_us',
		'type' => 'textarea'
            ) );


	// Category Icons

	// First Featured Box

	$wp_customize->add_setting( 'category1_heading', array(
		'default'           => 'Heading',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'		=> 'postMessage',
		) 
	);

	$wp_customize->add_control( 'category1_heading', array(
		'label' => __( 'First Category Heading:', 'wprig' ),
		'description' => esc_html__( 'Enter Categories You Want To Display' ),
		'section' => 'home_featured_section',
		'priority' => 10, // Optional. Order priority to load the control. Default: 10
		'type' => 'text',
		'capability' => 'edit_theme_options', // Optional. Default: 'edit_theme_options'
		)
	);

	$wp_customize->add_setting( 'category1_icon', array(
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control(  new WP_Customize_Image_Control( $wp_customize, 'category1_icon', array(
		'section' 		=> 'home_featured_section',
		'label' 		=> __( 'First Featured Box Icon', 'wprig' ),
		'description' 	=> esc_html__('Choose image for first featured area.', 'wprig' ),
		'mime_type' => 'image',
		'button_labels' => array( // Optional
				'select' => __( 'Select File' ),
				'change' => __( 'Change File' ),
				'default' => __( 'Default' ),
				'remove' => __( 'Remove' ),
				'placeholder' => __( 'No file selected' ),
				'frame_title' => __( 'Select File' ),
				'frame_button' => __( 'Choose File' ),
			))) 
	);

	$wp_customize->add_setting( 'category1_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control( 'category1_link', array(
		'type'     		=> 'url',
		'section'  		=> 'home_featured_section',
		'label'    		=> __( 'First Featured Box Link:', 'wprig' ),
		'description' 	=> __('Enter first featured box link.', 'wprig' ),
			) 
	);

	// Second Featured Box

	$wp_customize->add_setting( 'category2_heading', array(
		'default'           => 'Heading',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'		=> 'postMessage',
		) 
	);

	$wp_customize->add_control( 'category2_heading', array(
		'label' => __( 'Second Category Heading:', 'wprig' ),
		'description' => esc_html__( 'Enter Categories You Want To Display' ),
		'section' => 'home_featured_section',
		'priority' => 10, // Optional. Order priority to load the control. Default: 10
		'type' => 'text',
		'capability' => 'edit_theme_options', // Optional. Default: 'edit_theme_options'
		)
	);

	$wp_customize->add_setting( 'category2_icon', array(
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control(  new WP_Customize_Image_Control( $wp_customize, 'category2_icon', array(
		'section' 		=> 'home_featured_section',
		'label' 		=> __( 'Second Featured Box Icon', 'wprig' ),
		'description' 	=> esc_html__('Choose image for second featured area.', 'wprig' ),
		'mime_type' => 'image',
		'button_labels' => array( // Optional
				'select' => __( 'Select File' ),
				'change' => __( 'Change File' ),
				'default' => __( 'Default' ),
				'remove' => __( 'Remove' ),
				'placeholder' => __( 'No file selected' ),
				'frame_title' => __( 'Select File' ),
				'frame_button' => __( 'Choose File' ),
			))) 
	);

	$wp_customize->add_setting( 'category2_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control( 'category2_link', array(
		'type'     		=> 'url',
		'section'  		=> 'home_featured_section',
		'label'    		=> __( 'Second Featured Box Link:', 'wprig' ),
		'description' 	=> __('Enter second featured box link.', 'wprig' ),
			) 
	);

	// Third Featured Box

	$wp_customize->add_setting( 'category3_heading', array(
		'default'           => 'Heading',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'		=> 'postMessage',
		) 
	);

	$wp_customize->add_control( 'category3_heading', array(
		'label' => __( 'Third Category Heading:', 'wprig' ),
		'description' => esc_html__( 'Enter Categories You Want To Display' ),
		'section' => 'home_featured_section',
		'priority' => 10, // Optional. Order priority to load the control. Default: 10
		'type' => 'text',
		'capability' => 'edit_theme_options', // Optional. Default: 'edit_theme_options'
		)
	);

	$wp_customize->add_setting( 'category3_icon', array(
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control(  new WP_Customize_Image_Control( $wp_customize, 'category3_icon', array(
		'section' 		=> 'home_featured_section',
		'label' 		=> __( 'Third Featured Box Icon', 'wprig' ),
		'description' 	=> esc_html__('Choose image for third featured area.', 'wprig' ),
		'mime_type' => 'image',
		'button_labels' => array( // Optional
				'select' => __( 'Select File' ),
				'change' => __( 'Change File' ),
				'default' => __( 'Default' ),
				'remove' => __( 'Remove' ),
				'placeholder' => __( 'No file selected' ),
				'frame_title' => __( 'Select File' ),
				'frame_button' => __( 'Choose File' ),
			))) 
	);

	$wp_customize->add_setting( 'category3_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control( 'category3_link', array(
		'type'     		=> 'url',
		'section'  		=> 'home_featured_section',
		'label'    		=> __( 'Third Featured Box Link:', 'wprig' ),
		'description' 	=> __('Enter third featured box link.', 'wprig' ),
			) 
	);

	// Fourth Featured Box

	$wp_customize->add_setting( 'category4_heading', array(
		'default'           => 'Heading',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'		=> 'postMessage',
		) 
	);

	$wp_customize->add_control( 'category4_heading', array(
		'label' => __( 'Fourth Category Heading:', 'wprig' ),
		'description' => esc_html__( 'Enter Categories You Want To Display' ),
		'section' => 'home_featured_section',
		'priority' => 10, // Optional. Order priority to load the control. Default: 10
		'type' => 'text',
		'capability' => 'edit_theme_options', // Optional. Default: 'edit_theme_options'
		)
	);

	$wp_customize->add_setting( 'category4_icon', array(
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control(  new WP_Customize_Image_Control( $wp_customize, 'category4_icon', array(
		'section' 		=> 'home_featured_section',
		'label' 		=> __( 'Fourth Featured Box Icon', 'wprig' ),
		'description' 	=> esc_html__('Choose image for fourth featured area.', 'wprig' ),
		'mime_type' => 'image',
		'button_labels' => array( // Optional
				'select' => __( 'Select File' ),
				'change' => __( 'Change File' ),
				'default' => __( 'Default' ),
				'remove' => __( 'Remove' ),
				'placeholder' => __( 'No file selected' ),
				'frame_title' => __( 'Select File' ),
				'frame_button' => __( 'Choose File' ),
			))) 
	);

	$wp_customize->add_setting( 'category4_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control( 'category4_link', array(
		'type'     		=> 'url',
		'section'  		=> 'home_featured_section',
		'label'    		=> __( 'Fourth Featured Box Link:', 'wprig' ),
		'description' 	=> __('Enter fourth featured box link.', 'wprig' ),
			) 
	);

	// Fifth Featured Box

	$wp_customize->add_setting( 'category5_heading', array(
		'default'           => 'Heading',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'		=> 'postMessage',
		) 
	);

	$wp_customize->add_control( 'category5_heading', array(
		'label' => __( 'Fifth Category Heading:', 'wprig' ),
		'description' => esc_html__( 'Enter Categories You Want To Display' ),
		'section' => 'home_featured_section',
		'priority' => 10, // Optional. Order priority to load the control. Default: 10
		'type' => 'text',
		'capability' => 'edit_theme_options', // Optional. Default: 'edit_theme_options'
		)
	);

	$wp_customize->add_setting( 'category5_icon', array(
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control(  new WP_Customize_Image_Control( $wp_customize, 'category5_icon', array(
		'section' 		=> 'home_featured_section',
		'label' 		=> __( 'Fifth Featured Box Icon', 'wprig' ),
		'description' 	=> esc_html__('Choose image for fifth featured area.', 'wprig' ),
		'mime_type' => 'image',
		'button_labels' => array( // Optional
				'select' => __( 'Select File' ),
				'change' => __( 'Change File' ),
				'default' => __( 'Default' ),
				'remove' => __( 'Remove' ),
				'placeholder' => __( 'No file selected' ),
				'frame_title' => __( 'Select File' ),
				'frame_button' => __( 'Choose File' ),
			))) 
	);

	$wp_customize->add_setting( 'category5_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control( 'category5_link', array(
		'type'     		=> 'url',
		'section'  		=> 'home_featured_section',
		'label'    		=> __( 'Fifth Featured Box Link:', 'wprig' ),
		'description' 	=> __('Enter fifth featured box link.', 'wprig' ),
			) 
	);

	// Featured Programs

	// First Featured Program Box

	$wp_customize->add_setting( 'program1_heading', array(
		'default'           => 'Heading',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'		=> 'postMessage',
		) 
	);

	$wp_customize->add_control( 'program1_heading', array(
		'label' => __( 'First Featured Program Heading:', 'wprig' ),
		'description' => esc_html__( 'Enter Featured Programs You Want To Display' ),
		'section' => 'home_featured_program_section',
		'priority' => 10, // Optional. Order priority to load the control. Default: 10
		'type' => 'text',
		'capability' => 'edit_theme_options', // Optional. Default: 'edit_theme_options'
		)
	);

	$wp_customize->add_setting( 'program1_icon', array(
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control(  new WP_Customize_Image_Control( $wp_customize, 'program1_icon', array(
		'section' 		=> 'home_featured_program_section',
		'label' 		=> __( 'First Featured Program Image', 'wprig' ),
		'description' 	=> esc_html__('Choose image for first featured program area.', 'wprig' ),
		'mime_type' => 'image',
		'button_labels' => array( // Optional
				'select' => __( 'Select File' ),
				'change' => __( 'Change File' ),
				'default' => __( 'Default' ),
				'remove' => __( 'Remove' ),
				'placeholder' => __( 'No file selected' ),
				'frame_title' => __( 'Select File' ),
				'frame_button' => __( 'Choose File' ),
			))) 
	);

	$wp_customize->add_setting( 'program1_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control( 'program1_link', array(
		'type'     		=> 'url',
		'section'  		=> 'home_featured_program_section',
		'label'    		=> __( 'First Featured Program Link:', 'wprig' ),
		'description' 	=> __('Enter first featured program link.', 'wprig' ),
			) 
	);

	// Second Program Featured Box

	$wp_customize->add_setting( 'program2_heading', array(
		'default'           => 'Heading',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'		=> 'postMessage',
		) 
	);

	$wp_customize->add_control( 'program2_heading', array(
		'label' => __( 'Second Program Heading:', 'wprig' ),
		'description' => esc_html__( 'Enter Programs You Want To Display' ),
		'section' => 'home_featured_program_section',
		'priority' => 10, // Optional. Order priority to load the control. Default: 10
		'type' => 'text',
		'capability' => 'edit_theme_options', // Optional. Default: 'edit_theme_options'
		)
	);

	$wp_customize->add_setting( 'program2_icon', array(
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control(  new WP_Customize_Image_Control( $wp_customize, 'program2_icon', array(
		'section' 		=> 'home_featured_program_section',
		'label' 		=> __( 'Second Featured Program Image', 'wprig' ),
		'description' 	=> esc_html__('Choose image for second featured program area.', 'wprig' ),
		'mime_type' => 'image',
		'button_labels' => array( // Optional
				'select' => __( 'Select File' ),
				'change' => __( 'Change File' ),
				'default' => __( 'Default' ),
				'remove' => __( 'Remove' ),
				'placeholder' => __( 'No file selected' ),
				'frame_title' => __( 'Select File' ),
				'frame_button' => __( 'Choose File' ),
			))) 
	);

	$wp_customize->add_setting( 'program2_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control( 'program2_link', array(
		'type'     		=> 'url',
		'section'  		=> 'home_featured_program_section',
		'label'    		=> __( 'Second Featured Program Link:', 'wprig' ),
		'description' 	=> __('Enter second featured program link.', 'wprig' ),
			) 
	);

	// Third Featured Program Box

	$wp_customize->add_setting( 'program3_heading', array(
		'default'           => 'Heading',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'		=> 'postMessage',
		) 
	);

	$wp_customize->add_control( 'program3_heading', array(
		'label' => __( 'Third Featured Program Heading:', 'wprig' ),
		'description' => esc_html__( 'Enter Programs You Want To Display' ),
		'section' => 'home_featured_program_section',
		'priority' => 10, // Optional. Order priority to load the control. Default: 10
		'type' => 'text',
		'capability' => 'edit_theme_options', // Optional. Default: 'edit_theme_options'
		)
	);

	$wp_customize->add_setting( 'program3_icon', array(
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control(  new WP_Customize_Image_Control( $wp_customize, 'program3_icon', array(
		'section' 		=> 'home_featured_program_section',
		'label' 		=> __( 'Third Featured Program Image', 'wprig' ),
		'description' 	=> esc_html__('Choose image for third featured program area.', 'wprig' ),
		'mime_type' => 'image',
		'button_labels' => array( // Optional
				'select' => __( 'Select File' ),
				'change' => __( 'Change File' ),
				'default' => __( 'Default' ),
				'remove' => __( 'Remove' ),
				'placeholder' => __( 'No file selected' ),
				'frame_title' => __( 'Select File' ),
				'frame_button' => __( 'Choose File' ),
			))) 
	);

	$wp_customize->add_setting( 'program3_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control( 'program3_link', array(
		'type'     		=> 'url',
		'section'  		=> 'home_featured_program_section',
		'label'    		=> __( 'Third Featured Program Link:', 'wprig' ),
		'description' 	=> __('Enter third featured program link.', 'wprig' ),
			) 
	);

	// Fourth Featured Program Box

	$wp_customize->add_setting( 'program4_heading', array(
		'default'           => 'Heading',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'		=> 'postMessage',
		) 
	);

	$wp_customize->add_control( 'program4_heading', array(
		'label' => __( 'Fourth Program Heading:', 'wprig' ),
		'description' => esc_html__( 'Enter Categories You Want To Display' ),
		'section' => 'home_featured_program_section',
		'priority' => 10, // Optional. Order priority to load the control. Default: 10
		'type' => 'text',
		'capability' => 'edit_theme_options', // Optional. Default: 'edit_theme_options'
		)
	);

	$wp_customize->add_setting( 'program4_icon', array(
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control(  new WP_Customize_Image_Control( $wp_customize, 'program4_icon', array(
		'section' 		=> 'home_featured_program_section',
		'label' 		=> __( 'Fourth Featured Program Image', 'wprig' ),
		'description' 	=> esc_html__('Choose image for fourth featured program area.', 'wprig' ),
		'mime_type' => 'image',
		'button_labels' => array( // Optional
				'select' => __( 'Select File' ),
				'change' => __( 'Change File' ),
				'default' => __( 'Default' ),
				'remove' => __( 'Remove' ),
				'placeholder' => __( 'No file selected' ),
				'frame_title' => __( 'Select File' ),
				'frame_button' => __( 'Choose File' ),
			))) 
	);

	$wp_customize->add_setting( 'program4_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control( 'category4_link', array(
		'type'     		=> 'url',
		'section'  		=> 'home_featured_program_section',
		'label'    		=> __( 'Fourth Featured Program Link:', 'wprig' ),
		'description' 	=> __('Enter fourth featured program link.', 'wprig' ),
			) 
	);

	// Fifth Featured Box

	$wp_customize->add_setting( 'program5_heading', array(
		'default'           => 'Heading',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'		=> 'postMessage',
		) 
	);

	$wp_customize->add_control( 'program5_heading', array(
		'label' => __( 'Fifth Program Heading:', 'wprig' ),
		'description' => esc_html__( 'Enter Programs You Want To Display' ),
		'section' => 'home_featured_program_section',
		'priority' => 10, // Optional. Order priority to load the control. Default: 10
		'type' => 'text',
		'capability' => 'edit_theme_options', // Optional. Default: 'edit_theme_options'
		)
	);

	$wp_customize->add_setting( 'program5_icon', array(
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control(  new WP_Customize_Image_Control( $wp_customize, 'program5_icon', array(
		'section' 		=> 'home_featured_program_section',
		'label' 		=> __( 'Fifth Featured Program Image', 'wprig' ),
		'description' 	=> esc_html__('Choose image for fifth featured program area.', 'wprig' ),
		'mime_type' => 'image',
		'button_labels' => array( // Optional
				'select' => __( 'Select File' ),
				'change' => __( 'Change File' ),
				'default' => __( 'Default' ),
				'remove' => __( 'Remove' ),
				'placeholder' => __( 'No file selected' ),
				'frame_title' => __( 'Select File' ),
				'frame_button' => __( 'Choose File' ),
			))) 
	);

	$wp_customize->add_setting( 'program5_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
            ) );
        
	$wp_customize->add_control( 'program5_link', array(
		'type'     		=> 'url',
		'section'  		=> 'home_featured_program_section',
		'label'    		=> __( 'Fifth Featured Program Link:', 'wprig' ),
		'description' 	=> __('Enter fifth featured program link.', 'wprig' ),
			) 
	);

	
}
add_action( 'customize_register', 'wprig_customize_register' );



/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */


/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function wprig_customize_partial_blogdescription() {
	bloginfo( 'description' );
}



/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function wprig_customize_preview_js() {
	wp_enqueue_script( 'wprig-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
};

add_action( 'customize_preview_init', 'wprig_customize_preview_js' );

/**
 * Sanitize length options:
 * If something goes wrong and one of the two specified options are not used, 
 * apply the default (excerpt).
 */

 function wprig_sanitize_length( $value) {
	 if ( ! in_array( $value, array( 'excerpt', 'full-content'))) {
		 $value = 'excerpt';
	 }
	 return $value;
 }

if ( ! function_exists( 'wprig_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see wprig_custom_header_setup().
	 */

function wprig_header_style() {
	$header_text_color = get_header_textcolor();
	$header_bg_color = get_theme_mod('theme_bg_color' );

	/*
	 * If no custom options for text are set, let's bail.
	 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
	 */
	if ( get_theme_support( 'custom-header', 'default-text-color' ) != $header_text_color ) {
		
	

		// If we get this far, we have custom styles. Let's do this.
		?>
		<style type="text/css">
		<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) :
			?>
			.site-title,
			.site-description {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px);
			}
			<?php
			// If the user has set a custom color for the text use that.
		else :
			?>
			.site-title a,
			.site-description {
				color: #<?php echo esc_attr( $header_text_color ); ?>;
			}
		<?php endif; ?>
		</style>
		<?php
	}

	if ( '#ffffff' != $header_bg_color) { ?>
		<style type="text/css">
			.site-header,
			.site-footer {
				background-color: <?php echo esc_attr( $header_bg_color); ?>;
				</style>
			<?php
			}
	
	}


endif;

/**
 * Sanitize the lazy-load media options.
 *
 * @param string $input Lazy-load setting.
 */
function wprig_sanitize_lazy_load_media( $input ) {
	$valid = array(
		'lazyload' => __( 'Lazy-load images', 'wprig' ),
		'no-lazyload' => __( 'Load images immediately', 'wprig' ),
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	}

	return '';
}


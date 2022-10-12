<?php

//$content_width is required even though this is a variable-width theme
if ( ! isset( $dw_cosmosv2_content_width ) ) {
	$dw_cosmosv2_content_width = 400;
}

function dw_cosmosv2_sanitize_html( $html ) {
	return wp_filter_post_kses( $html );
}

function dw_cosmosv2_sanitize_checkbox( $input ) {
	return ( ( isset( $input ) && true === $input ) ? true : false );
}

//makes replying look neat
if ( ! function_exists( 'dw_cosmosv2_threaded_comments' ) ) :
	function dw_cosmosv2_threaded_comments() {
		if ( get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
	add_action( 'wp_enqueue_scripts', 'dw_cosmosv2_threaded_comments' );
endif;

// displays "Comments are closed" instead of comment form when comments are closed.
if ( ! function_exists( 'dw_cosmosv2_closed_comments' ) ) :
	function dw_cosmosv2_closed_comments() {
		echo '<p>', esc_html__( 'Comments are closed.', 'dw-cosmosv2' ), '</p>';
	}
	add_action( 'comment_form_comments_closed', 'dw_cosmosv2_closed_comments' );
endif;

if ( ! function_exists( 'dw_cosmosv2_setup' ) ) :
	function dw_cosmosv2_setup() {
		load_theme_textdomain( 'dw-cosmosv2', get_template_directory() . '/languages' );
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 190,
				'width'       => 190,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
		add_theme_support( 'title-tag' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'customize-selective-recosmosv2-widgets' );
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 150, 150 );
		add_image_size( 'dw-cosmosv2-single-post-thumbnail', 9999, 9999 );
		add_image_size( 'dw-cosmosv2-homepage-featured', 350, 350 );
		add_theme_support(
			'starter-content', 
			array(
				'widgets' => array(
					'left-sidebar'  => array(
						array(
							'text',
							array(
								'title' => esc_html__( 'First Sidebar', 'dw-cosmosv2' ),
								'text'  => _x( 'This is some text in the first sidebar.', 'Theme Starter Content', 'dw-cosmosv2' ),
							),
						),
					),
					'right-sidebar' => array(
						array(
							'text',
							array(
								'title' => esc_html__( 'Second Sidebar', 'dw-cosmosv2' ),
								'text'  => _x( 'This is some text in the second sidebar.', 'Theme Starter Content', 'dw-cosmosv2' ),
							),
						),
					),
					'center-block'  => array(
						array(
							'text',
							array(
								'title' => esc_html__( 'Center Block', 'dw-cosmosv2' ),
								'text'  => _x( 'This is some text in the center block.', 'Theme Starter Content', 'dw-cosmosv2' ),
							),
						),
					),
				),
			)
		);
	}
	add_action( 'after_setup_theme', 'dw_cosmosv2_setup' );
endif;

if ( ! function_exists( 'dw_cosmosv2_register_menu' ) ) :
	function dw_cosmosv2_register_menu() {
		register_nav_menus(
			array(
				'top_header_menu' => esc_html__( 'Top Header Menu', 'dw-cosmosv2' ),
			)
		);
	}
	add_action( 'init', 'dw_cosmosv2_register_menu' );
endif;

if ( ! function_exists( 'dw_cosmosv2_stylesheet' ) ) :
	function dw_cosmosv2_stylesheet() {
		wp_enqueue_style( 'dw-cosmosv2-style', get_stylesheet_directory_uri() . '/style.css', array(), wp_get_theme()->version );
	}
	add_action( 'wp_enqueue_scripts', 'dw_cosmosv2_stylesheet' );
endif;

if ( ! function_exists( 'dw_cosmosv2_sidebars' ) ) :
	// register the sidebars
	function dw_cosmosv2_sidebars() {
		register_sidebar( 
			array(
				'name' => __( 'Left Sidebar 1', 'dw-cosmosv2' ),
				'id'   => 'left-sidebar',
			)
		);
		register_sidebar(
			array(
				'name' => __( 'Right Sidebar 1', 'dw-cosmosv2' ),
				'id'   => 'right-sidebar',
			)
		);
		register_sidebar(
			array(
				'name' => __( 'Center Block 1', 'dw-cosmosv2' ),
				'id'   => 'center-block',
			)
		);
	}
	add_action( 'widgets_init', 'dw_cosmosv2_sidebars' );
endif;

// display information about a post under the post title
if ( ! function_exists( 'dw_cosmosv2_post_meta' ) ) :
	function dw_cosmosv2_post_meta() {
		echo '<div class="meta">';
		if ( get_post_type() === 'page' ) :
			printf(
				/* translators: %s = author */
				esc_html__( 'Posted by %s', 'dw-cosmosv2' ),
				get_the_author()
			);
		elseif ( has_category() ) :
			printf(
				/* translators: %1$s = date, %2$s = categories, %3$s = author */
				esc_html__( 'Posted on %1$s in %2$s by %3$s', 'dw-cosmosv2' ),
				get_the_date(),
				wp_kses_post( get_the_category_list( ',' ) ),
				get_the_author()
			);
		else :
			printf(
				/* translators: %1$s = date, %2$s = author */
				esc_html__( 'Posted on %1$s by %2$s', 'dw-cosmosv2' ),
				get_the_date(),
				get_the_author()
			);
		endif;
		edit_post_link( _x( 'Edit', 'verb', 'dw-cosmosv2' ), ' | ' );
		if ( get_post_type() !== 'page' && has_tag() ) :
			echo '<br>';
			the_tags();
		endif;
		echo '</div>';
	}
endif;

// change the archive title to show the search query when showing the archive title on a search result page.
if ( ! function_exists( 'dw_cosmosv2_search_title' ) ) :
	function dw_cosmosv2_search_title( $title ) {
		if ( is_search() ) {
			/* translators: %s = search query */
			$title = sprintf( __( 'Search: %s', 'dw-cosmosv2' ), get_search_query() );
		}
		return $title;
	}
	add_filter( 'get_the_archive_title', 'dw_cosmosv2_search_title' );
endif;

function dw_cosmosv2_google_fonts() {
	wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Londrina+Solid:wght@100;300;400;900&display=swap', array(), wp_get_theme()->version );
}
add_action( 'wp_enqueue_scripts', 'dw_cosmosv2_google_fonts' );

/**
 * Enqueue scripts
 */
function dw_cosmosv2_enqueue_scripts() {
	wp_enqueue_script( 'dw-cosmosv2-header-clock', get_template_directory_uri() . '/js/dw-cosmosv2-header-clock.js', array(), wp_get_theme()->version, true );
	wp_enqueue_script( 'dw-cosmosv2-top-button', get_template_directory_uri() . '/js/dw-cosmosv2-top-button.js', array(), wp_get_theme()->version, true );
	wp_enqueue_script( 'dw-cosmosv2-navigation', get_template_directory_uri() . '/js/dw-cosmosv2-navigation.js', array( 'jquery' ), '20141205', true );
}
add_action( 'wp_enqueue_scripts', 'dw_cosmosv2_enqueue_scripts' );

// Add Footer Text Box section to customize
function dw_cosmosv2_fcontent( $wp_customize ) {
	$wp_customize->add_section(
		'dw-cosmosv2-fcontent-section', 
		array(
			'title' => __( 'Footer Text Box', 'dw-cosmosv2' ),
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'dw-cosmosv2-fcontent-text-control', 
			array(
				'type'        => 'button',
				'settings'    => array(),
				'label'       => __( 'Get this feature with premium!', 'dw-cosmosv2' ),
				'priority'    => 10,
				'section'     => 'dw-cosmosv2-fcontent-section',
				'input_attrs' => array(
					'value'   => __( 'Click Here!', 'dw-cosmosv2' ),
					'class'   => 'button-secondary',
					'onclick' => "window.open('https://www.designwicked.com/premium-cosmosv2', '_blank')",
				),
			)
		)
	);
}

add_action( 'customize_register', 'dw_cosmosv2_fcontent' );

// Premium Upgrade Section
function dw_cosmosv2_procontent( 
	$wp_customize ) {
	$wp_customize->add_section(
		'pro_sec', 
		array(
			'title'       => __( 'GET PREMIUM VERSION', 'dw-cosmosv2' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
		) 
	);
	
	$wp_customize->add_control(
		'button_id',
		array(
			'type'        => 'button',
			'settings'    => array(),
			'priority'    => 10,
			'section'     => 'pro_sec',
			'input_attrs' => array(
				'value'   => __( 'BUY PREMIUM VERSION', 'dw-cosmosv2' ),
				'class'   => 'button-primary',
				'onclick' => "window.open('https://www.designwicked.com/premium-cosmosv2', '_blank')",
			),
		)
	);

	$wp_customize->add_control(
		'label',
		array(
			'type'        => 'button',
			'settings'    => array(),
			'label'       => __( 'Premium Features:', 'dw-cosmosv2' ),
			'priority'    => 10,
			'section'     => 'pro_sec',
			'input_attrs' => array(
				'value'   => __( '12 More Widget Blocks', 'dw-cosmosv2' ),
				'class'   => 'button-secondary',
				'onclick' => "window.open('https://www.designwicked.com/wordpress-themes', '_blank')",
			),
		)
	);
	
	$wp_customize->add_control(
		'label1',
		array(
			'type'        => 'button',
			'settings'    => array(),
			'priority'    => 10,
			'section'     => 'pro_sec',
			'input_attrs' => array(
				'value'   => __( 'Footer HTML/Text Content Area', 'dw-cosmosv2' ),
				'class'   => 'button-secondary',
				'onclick' => "window.open('https://www.designwicked.com/wordpress-themes', '_blank')",
			),
		)
	);
	
	$wp_customize->add_control(
		'label2',
		array(
			'type'        => 'button',
			'settings'    => array(),
			'priority'    => 10,
			'section'     => 'pro_sec',
			'input_attrs' => array(
				'value'   => __( 'Top Header and Bottom Footer Length Widget Area', 'dw-cosmosv2' ),
				'class'   => 'button-secondary',
				'onclick' => "window.open('https://www.designwicked.com/wordpress-themes', '_blank')",
			),
		)
	);
	
	$wp_customize->add_control(
		'label3',
		array(
			'type'        => 'button',
			'settings'    => array(),
			'priority'    => 10,
			'section'     => 'pro_sec',
			'input_attrs' => array(
				'value'   => __( 'bbPress Matching Forum Style', 'dw-cosmosv2' ),
				'class'   => 'button-secondary',
				'onclick' => "window.open('https://www.designwicked.com/wordpress-themes', '_blank')",
			),
		)
	);
	
	$wp_customize->add_control(
		'label4',
		array(
			'type'        => 'button',
			'settings'    => array(),
			'priority'    => 10,
			'section'     => 'pro_sec',
			'input_attrs' => array(
				'value'   => __( 'And More!', 'dw-cosmosv2' ),
				'class'   => 'button-secondary',
				'onclick' => "window.open('https://www.designwicked.com/wordpress-themes', '_blank')",
			),
		)
	);
	
	$wp_customize->add_control(
		'label5',
		array(
			'type'        => 'button',
			'settings'    => array(),
			'label'       => __( 'FOR MORE DW THEMES:', 'dw-cosmosv2' ),
			'priority'    => 10,
			'section'     => 'pro_sec',
			'input_attrs' => array(
				'value'   => __( 'CLICK HERE!', 'dw-cosmosv2' ),
				'class'   => 'button-primary',
				'onclick' => "window.open('https://www.designwicked.com/wordpress-themes', '_blank')",
			),
		)
	);
	
}
	
	add_action( 'customize_register', 'dw_cosmosv2_procontent' );

// Header Nav
function dw_cosmosv2_hnav( $wp_customize ) {
	$wp_customize->add_section(
		'dw-cosmosv2-hnav-section',
		array(
			'title' => esc_html__( 'Header Navigation', 'dw-cosmosv2' ),
		)
	);

	$wp_customize->add_setting(
		'dw-cosmosv2-hnav-linkname1',
		array(
			'default'           => esc_html__( 'Link 1', 'dw-cosmosv2' ),
			'sanitize_callback' => 'dw_cosmosv2_sanitize_html',
		)
	);


	$wp_customize->add_control( 
		new WP_Customize_Control(
			$wp_customize,
			'dw-cosmosv2-hnav-control-linkname1',
			array(
				'label'       => esc_html__( 'Link 1 Name:', 'dw-cosmosv2' ),
				'section'     => 'dw-cosmosv2-hnav-section',
				'description' => esc_html__( 'The visible name of the link on the button.', 'dw-cosmosv2' ),
				'settings'    => 'dw-cosmosv2-hnav-linkname1',
				'type'        => 'text',
			)
		)
	);

	$wp_customize->add_setting(
		'dw-cosmosv2-hnav-link1',
		array(
			'default'           => esc_html__( 'http://', 'dw-cosmosv2' ),
			'sanitize_callback' => 'dw_cosmosv2_sanitize_html',
		)
	);


	$wp_customize->add_control( 
		new WP_Customize_Control(
			$wp_customize,
			'dw-cosmosv2-hnav-control-link1',
			array(
				'label'       => esc_html__( 'Link 1 Address:', 'dw-cosmosv2' ),
				'section'     => 'dw-cosmosv2-hnav-section',
				'description' => esc_html__( 'You can use /address to point to a directory, or just put a full link including http://', 'dw-cosmosv2' ),
				'settings'    => 'dw-cosmosv2-hnav-link1',
				'type'        => 'text',
			)
		)
	);

	$wp_customize->add_setting(
		'dw-cosmosv2-hnav-linkname2',
		array(
			'default'           => esc_html__( 'Link 2', 'dw-cosmosv2' ),
			'sanitize_callback' => 'dw_cosmosv2_sanitize_html',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'dw-cosmosv2-hnav-control-linkname2',
			array(
				'label'    => esc_html__( 'Link 2 Name:', 'dw-cosmosv2' ),
				'section'  => 'dw-cosmosv2-hnav-section',
				'settings' => 'dw-cosmosv2-hnav-linkname2',
				'type'     => 'text',
			)
		)
	);

	$wp_customize->add_setting(
		'dw-cosmosv2-hnav-link2',
		array(
			'default'           => esc_html__( 'http://', 'dw-cosmosv2' ),
			'sanitize_callback' => 'dw_cosmosv2_sanitize_html',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'dw-cosmosv2-hnav-control-link2',
			array(
				'label'    => esc_html__( 'Link 2 Address:', 'dw-cosmosv2' ),
				'section'  => 'dw-cosmosv2-hnav-section',
				'settings' => 'dw-cosmosv2-hnav-link2',
				'type'     => 'text',
			)
		)
	);

	$wp_customize->add_setting(
		'dw-cosmosv2-hnav-linkname3',
		array(
			'default'           => esc_html__( 'Link 3', 'dw-cosmosv2' ),
			'sanitize_callback' => 'dw_cosmosv2_sanitize_html',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'dw-cosmosv2-hnav-control-linkname3',
			array(
				'label'    => esc_html__( 'Link 3 Name:', 'dw-cosmosv2' ),
				'section'  => 'dw-cosmosv2-hnav-section',
				'settings' => 'dw-cosmosv2-hnav-linkname3',
				'type'     => 'text',
			)
		)
	);

	$wp_customize->add_setting(
		'dw-cosmosv2-hnav-link3',
		array(
			'default'           => esc_html__( 'http://', 'dw-cosmosv2' ),
			'sanitize_callback' => 'dw_cosmosv2_sanitize_html',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'dw-cosmosv2-hnav-control-link3',
			array(
				'label'    => esc_html__( 'Link 3 Address:', 'dw-cosmosv2' ),
				'section'  => 'dw-cosmosv2-hnav-section',
				'settings' => 'dw-cosmosv2-hnav-link3',
				'type'     => 'text',
			)
		)
	);

	$wp_customize->add_setting(
		'dw-cosmosv2-hnav-linkname4',
		array(
			'default'           => esc_html__( 'Link 4', 'dw-cosmosv2' ),
			'sanitize_callback' => 'dw_cosmosv2_sanitize_html',
		)
	);


	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'dw-cosmosv2-hnav-control-linkname4',
			array(
				'label'    => esc_html__( 'Link 4 Name:', 'dw-cosmosv2' ),
				'section'  => 'dw-cosmosv2-hnav-section',
				'settings' => 'dw-cosmosv2-hnav-linkname4',
				'type'     => 'text',
			)
		)
	);

	$wp_customize->add_setting(
		'dw-cosmosv2-hnav-link4',
		array(
			'default'           => esc_html__( 'http://', 'dw-cosmosv2' ),
			'sanitize_callback' => 'dw_cosmosv2_sanitize_html',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'dw-cosmosv2-hnav-control-link4',
			array(
				'label'    => esc_html__( 'Link 4 Address:', 'dw-cosmosv2' ),
				'section'  => 'dw-cosmosv2-hnav-section',
				'settings' => 'dw-cosmosv2-hnav-link4',
				'type'     => 'text',
			)
		)
	);

	$wp_customize->add_setting(
		'dw-cosmosv2-hnav-linkname5',
		array(
			'default'           => esc_html__( 'Link 5', 'dw-cosmosv2' ),
			'sanitize_callback' => 'dw_cosmosv2_sanitize_html',
		)
	);


	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'dw-cosmosv2-hnav-control-linkname5',
			array(
				'label'    => esc_html__( 'Link 5 Name:', 'dw-cosmosv2' ),
				'section'  => 'dw-cosmosv2-hnav-section',
				'settings' => 'dw-cosmosv2-hnav-linkname5',
				'type'     => 'text',
			)
		)
	);

	$wp_customize->add_setting(
		'dw-cosmosv2-hnav-link5',
		array(
			'default'           => esc_html__( 'http://', 'dw-cosmosv2' ),
			'sanitize_callback' => 'dw_cosmosv2_sanitize_html',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'dw-cosmosv2-hnav-control-link5',
			array(
				'label'    => esc_html__( 'Link 5 Address:', 'dw-cosmosv2' ),
				'section'  => 'dw-cosmosv2-hnav-section',
				'settings' => 'dw-cosmosv2-hnav-link5',
				'type'     => 'text',
			)
		)
	);
}
add_action( 'customize_register', 'dw_cosmosv2_hnav' );

//Social Bar
function dw_cosmosv2_socialbar( $wp_customize ) {
	$wp_customize->add_section(
		'dw_cosmosv2_socialbar_section',
		array(
			'title'       => esc_html__( 'Social Bar', 'dw-cosmosv2' ),
			'description' => esc_html__( 'Add Social Bar Content', 'dw-cosmosv2' ),
			'priority'    => null,
		)
	);
	
	//Social Icons
	$wp_customize->add_setting(
		'dw_cosmosv2_facebook_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'dw_cosmosv2_facebook_url',
		array(
			'label'       => esc_html__( 'Facebook Link:', 'dw-cosmosv2' ),
			'description' => esc_html__( '--Erase the field and make blank to remove icons.', 'dw-cosmosv2' ),
			'section'     => 'dw_cosmosv2_socialbar_section',
			'setting'     => 'dw_cosmosv2_facebook_url',
			'type'        => 'url',
		)
	);

	$wp_customize->add_setting(
		'dw_cosmosv2_gplus_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'dw_cosmosv2_gplus_url',
		array(
			'label'   => esc_html__( 'Google+ Link:', 'dw-cosmosv2' ),
			'section' => 'dw_cosmosv2_socialbar_section',
			'setting' => 'dw_cosmosv2_gplus_url',
			'type'    => 'url',
		)
	);
	
	$wp_customize->add_control(
		'labels',
		array(
			'type'        => 'button',
			'settings'    => array(),
			'label'       => __( 'Twitter Link:', 'dw-cosmosv2' ),
			'priority'    => 10,
			'section'     => 'dw_cosmosv2_socialbar_section',
			'input_attrs' => array(
				'value'   => __( 'Get pro for this feature!', 'dw-cosmosv2' ),
				'class'   => 'button-secondary',
				'onclick' => "window.open('https://www.designwicked.com/premium-cosmosv2', '_blank')",
			),
		)
	);
	
	$wp_customize->add_control(
		'labels1',
		array(
			'type'        => 'button',
			'settings'    => array(),
			'label'       => __( 'Linkedin Link:', 'dw-cosmosv2' ),
			'priority'    => 10,
			'section'     => 'dw_cosmosv2_socialbar_section',
			'input_attrs' => array(
				'value'   => __( 'Get pro for this feature!', 'dw-cosmosv2' ),
				'class'   => 'button-secondary',
				'onclick' => "window.open('https://www.designwicked.com/premium-cosmosv2', '_blank')",
			),
		)
	);
	
	$wp_customize->add_control(
		'labels2',
		array(
			'type'        => 'button',
			'settings'    => array(),
			'label'       => __( 'Instagram Link:', 'dw-cosmosv2' ),
			'priority'    => 10,
			'section'     => 'dw_cosmosv2_socialbar_section',
			'input_attrs' => array(
				'value'   => __( 'Get pro for this feature!', 'dw-cosmosv2' ),
				'class'   => 'button-secondary',
				'onclick' => "window.open('https://www.designwicked.com/premium-cosmosv2', '_blank')",
			),
		)
	);
	
	$wp_customize->add_control(
		'labels3',
		array(
			'type'        => 'button',
			'settings'    => array(),
			'label'       => __( 'Youtube Link:', 'dw-cosmosv2' ),
			'priority'    => 10,
			'section'     => 'dw_cosmosv2_socialbar_section',
			'input_attrs' => array(
				'value'   => __( 'Get pro for this feature!', 'dw-cosmosv2' ),
				'class'   => 'button-secondary',
				'onclick' => "window.open('https://www.designwicked.com/premium-cosmosv2', '_blank')",
			),
		)
	);
	
	$wp_customize->add_control(
		'labels4',
		array(
			'type'        => 'button',
			'settings'    => array(),
			'label'       => __( 'Discord Link:', 'dw-cosmosv2' ),
			'priority'    => 10,
			'section'     => 'dw_cosmosv2_socialbar_section',
			'input_attrs' => array(
				'value'   => __( 'Get pro for this feature!', 'dw-cosmosv2' ),
				'class'   => 'button-secondary',
				'onclick' => "window.open('https://www.designwicked.com/premium-cosmosv2', '_blank')",
			),
		)
	);
	
}
	add_action( 'customize_register', 'dw_cosmosv2_socialbar' );


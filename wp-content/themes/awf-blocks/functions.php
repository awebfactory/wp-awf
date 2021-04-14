<?php

if ( ! function_exists( 'awf_blocks_support' ) ) :
	function awf_blocks_support()  {

		// Adding support for featured images.
		add_theme_support( 'post-thumbnails' );

		// Adding support for alignwide and alignfull classes in the block editor.
		add_theme_support( 'align-wide' );

		// Adding support for core block visual styles.
		add_theme_support( 'wp-block-styles' );

		// Adding support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style.css' );

		// Add support for custom units.
		add_theme_support( 'custom-units' );
	}
	add_action( 'after_setup_theme', 'awf_blocks_support' );
endif;

/**
 * Enqueue scripts and styles.
 */
function awf_blocks_scripts() {

	// Enqueue theme stylesheet.
	wp_enqueue_style( 'awf-blocks-style', get_template_directory_uri() . '/style.css', array(), wp_get_theme()->get( 'Version' ) );

	// Enqueue alignments stylesheet.
	wp_enqueue_style( 'awf-blocks-alignments-style', get_template_directory_uri() . '/assets/css/alignments-front.css', array(), wp_get_theme()->get( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'awf_blocks_scripts' );

/**
* Enqueue theme fonts.
*/
function awf_blocks_theme_fonts() {
	wp_enqueue_style( 'awf_blocks-theme-fonts', get_template_directory_uri() . '/assets/css/theme-fonts.css', array(), wp_get_theme()->get( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'awf_blocks_theme_fonts', 1 );
add_action( 'enqueue_block_editor_assets', 'awf_blocks_theme_fonts', 1 );
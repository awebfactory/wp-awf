<?php
/**
 * AWF Block Ready theme functions.
 */

/**
 * Enqueue parent theme stylesheet
 *
 * This runs only if parent theme does not claim support for
 * `child-theme-stylesheet`, and so we need to enqueue this
 * child theme's `style.css` file ourselves.
 *
 * If parent theme supports `child-theme-stylesheet`, it enqueues
 * this child theme's `style.css` file automatically.
 *
 * @since    1.0.0
 * @version  1.0.0
 */
function awf_block_ready_parent_theme_style() {
	if ( ! current_theme_supports( 'child-theme-stylesheet' ) ) {
		wp_enqueue_style( 'awf_block_ready-parent-style', get_template_directory_uri() . '/style.css' );
		wp_enqueue_style( 'awf_block_ready-child-style', get_stylesheet_uri() );
	}
}
add_action( 'wp_enqueue_scripts', 'awf_block_ready_parent_theme_style', 1000 );

/**
 * Copy parent theme options (customizer settings)
 *
 * This runs only during child theme activation,
 * and only when there are no child theme options saved.
 *
 * @since    1.0.0
 * @version  1.0.0
 */
function awf_block_ready_parent_theme_options() {
	if ( false === get_theme_mods() ) {
		$parent_theme_options = get_option( 'theme_mods_' . get_template() );
		update_option( 'theme_mods_' . get_stylesheet(), $parent_theme_options );
	}
}
add_action( 'after_switch_theme', 'awf_block_ready_parent_theme_options' );

/**
 * Put your custom PHP code below...
 */
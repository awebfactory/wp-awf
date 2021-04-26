<?php
/**
 * Site info / footer credits area.
 *
 * SVG icon from Genericons Neue.
 * @link  https://github.com/Automattic/genericons-neue/blob/master/svg/collapse.svg
 *
 * @package    Michelle
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since  1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>

<div class="site-info-section site-footer-section">
    <div class="site-info-content site-footer-content">
        <?php

		/**
		 * Fires before actual site info container opening tag.
		 *
		 * @since  1.0.0
		 */
		do_action( 'michelle/site_info/before' );

		?>

        <div class="site-info">
            <span class="site-info-item">
                <a href="#top" class="back-to-top">
                    <svg class="svg-icon" width="3em" aria-hidden="true" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                        <polygon points="8,4.6 1.3,11.3 2.7,12.7 8,7.4 13.3,12.7 14.7,11.3 " />
                    </svg>
                    <span
                        class="screen-reader-text"><?php esc_html_e( 'Back to top of the page', 'michelle' ); ?></span>
                </a>
            </span>

            <span class="site-info-item">
                <!-- Remove previous info, redirect instead to Colophon -->
                <a href="/colophon"><span>Colophon</span></a>
            </span>
        </div>

        <?php

		/**
		 * Fires after actual site info container closing tag.
		 *
		 * @since  1.0.0
		 */
		do_action( 'michelle/site_info/after' );

		?>
    </div>
</div>
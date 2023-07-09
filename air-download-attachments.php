<?php
/**
 * Plugin Name: AIR Download Attachments
 * Plugin URI: https://wordpress.org/plugins/air-download-attachments/
 * Description: Adds a "Download All Attachments" button to posts, allowing users to download all attached images as a zip archive.
 * Version: 1.0.0
 * Author: Dan Zakirov
 * Author URI: https://profiles.wordpress.org/alexodiy/
 * Text Domain: air-download-attachments
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * WP tested up to: 6.2.2
 *
 *     Copyright Dan Zakirov
 *
 *     This file is part of AIR Download Attachments,
 *     a plugin for WordPress.
 *
 *     AIR Download Attachments is free software:
 *     You can redistribute it and/or modify it under the terms of the
 *     GNU General Public License as published by the Free Software
 *     Foundation, either version 3 of the License, or (at your option)
 *     any later version.
 *
 *     AIR Download Attachments is distributed in the hope that
 *     it will be useful, but WITHOUT ANY WARRANTY; without even the
 *     implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 *     PURPOSE. See the GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with WordPress. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Air_Download_Attachments
 */

if (!defined('ABSPATH')) {
    exit;
}


/**
 * Load plugin text domain for localization.
 */
function air_load_text_domain() {
    load_plugin_textdomain('air-download-attachments', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'air_load_text_domain');

/**
 * Enqueue the plugin stylesheet.
 */
function air_enqueue_styles() {
    if (is_single()) {
        $stylesheet_path = plugin_dir_url(__FILE__) . 'assets/css/air-download-attachments.css';
        $stylesheet_file = plugin_dir_path(__FILE__) . 'assets/css/air-download-attachments.css';
        $stylesheet_version = filemtime($stylesheet_file); // File version based on the last modification

        wp_enqueue_style('air-download-attachments', $stylesheet_path, array(), $stylesheet_version);
    }
}
add_action('wp_enqueue_scripts', 'air_enqueue_styles');

/**
 * Adds a "Download All Attachments" button to the post content.
 *
 * @param string $content The post content.
 * @return string The modified post content.
 */
function air_add_download_button($content) {
    if (is_single()) {
        $attachments = get_attached_media('image');
        if (!empty($attachments)) {
            $button_text = __('Download All Attachments', 'air-download-attachments');

            ob_start();
            ?>
            <br />
            <div class="air-download-attachments">
                <a href="<?php echo esc_url(add_query_arg('air_download_attachments', 'true')); ?>" class="air-download-attachments-button">
                    <?php echo esc_html($button_text); ?>
                </a>
            </div>
            <br />
            <?php
            $button_html = ob_get_clean();

            $content .= $button_html;
        }
    }
    return $content;
}
add_filter('the_content', 'air_add_download_button');

/**
 * Handles the dynamic generation and download of the attachments zip archive.
 */
function air_handle_attachments_download() {
    if (isset($_GET['air_download_attachments']) && $_GET['air_download_attachments'] === 'true') {
        $attachments = get_attached_media('image');
        if (empty($attachments)) {
            return;
        }

        // Create a new zip archive.
        $zip = new ZipArchive();
        $zip_name = 'attachments.zip';
        $temp_dir = wp_upload_dir()['basedir'] . '/temp'; // Get the path to the uploads/temp folder
        $temp_file = $temp_dir . '/' . $zip_name; // Form the path to the temporary file

        // Create the temporary folder if it doesn't exist
        if (!is_dir($temp_dir)) {
            if (!mkdir($temp_dir) && !is_dir($temp_dir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $temp_dir));
            }
        }

        // Set appropriate headers for zip download.
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zip_name . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Open the zip stream.
        $zip->open($temp_file, ZipArchive::CREATE);

        foreach ($attachments as $attachment) {
            $file_path = get_attached_file($attachment->ID);
            if (wp_attachment_is_image($attachment->ID)) {
                $zip->addFile($file_path, $attachment->post_title . '.' . pathinfo($file_path, PATHINFO_BASENAME));
            }
        }

        // Close the zip stream.
        $zip->close();

        // Read the zip file and output its contents.
        readfile($temp_file);
        unlink($temp_file); // Remove the temporary file

        exit;
    }
}
add_action('template_redirect', 'air_handle_attachments_download');
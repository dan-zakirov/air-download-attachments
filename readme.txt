=== AIR Download Attachments ===
Contributors: alexodiy
Tags: attachments, download, images, zip, media, files, archive, post, button
Requires at least: 4.8
Tested up to: 6.2
Stable tag: 1.0.0
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

The AIR Download Attachments plugin adds a "Download All Attachments" button to posts, allowing users to download all attached images as a zip archive.

== Features: ==

1. Adds a "Download All Attachments" button to the post content.
2. Creates a zip archive containing all attached images.
3. Automatically generates a temporary folder for storing the zip archive.
4. Provides localized translations for the plugin.

== Translations ==

If you wish to help translate this plugin, you are most welcome!
To contribute, please visit [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/air-download-attachments/)

== Installation ==

This section describes how to install the plugin and get it working.

Install From WordPress Admin Panel:

1. Login to your WordPress Admin Area
2. Go to Plugins -> Add New
3. Type "**Air Download Attachments**" into the Search and hit Enter.
4. Find this plugin Click "install now"
5. Activate The Plugin

Manual Installation:

1. Download the plugin from WordPress.org repository
2. On your WordPress admin dashboard, go to ‘Plugins -> Add New -> Upload Plugin’
3. Upload the downloaded plugin file and click ‘Install Now’
4. Activate ‘**Air Download Attachments**’ from your Plugins page.

== Frequently Asked Questions ==

= How to use this plugin, how does it work? =

Simply activate the plugin, and the button will appear in your posts. In the future, I plan to introduce additional settings that will interact with the button and custom post types.

= How can I customize the button text? =

The button text can be customized by modifying the $button_text variable in the air_add_download_button() function.

= Where can I find the downloaded zip archive? =

The zip archive is automatically downloaded when the "Download All Attachments" button is clicked. It is not saved on the server.

== Screenshots ==

1. Air Download Attachments
2. Download Attachments button

== Upgrade Notice ==

== Changelog ==

= 1.0.0 =
* Release
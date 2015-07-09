<?php
/**
* Plugin Name: Latest Posts With Thumbnails and Ads
* Plugin URI: http://www.shomtek.com/plugins/latest_posts_with_thumbnails_and_ads
* Description: Simple wordpress plugin that shows latest posts of your blog with pics, possibility to show your ads between posts. If the widget genereted by the plugin will be showed on a single post page the current post will be hidden from the list. <a href="http://www.shomtek.com" title="SHOMTek">www.shomtek.com</a>
* Version: 1.0
* Author: Eduart Milushi
* Author URI: http://shomtek.com
* License: GPL2
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

// Don't expose any info if file is called directly
if ( !function_exists( 'add_action' ) ) {
echo '<pre>Once upon a midnight dreary, while I pondered, weak and weary,
Over many a quaint and curious volume of forgotten lore,
While I nodded, nearly napping, suddenly there came a tapping,
As of some one gently rapping, rapping at my chamber door.
Tis some visitor," I muttered, "tapping at my chamber door —
Only this, and nothing more.</pre>';
	exit;
}

// Pluing version
define( 'SHOMTEK_VERSION', '1.0' );

// Define plugin directory
define( 'SHOMTEK__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SHOMTEK__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Include Recent Post With Pics Widget
require_once( SHOMTEK__PLUGIN_DIR . 'latest-posts.php' );

// Adding stylesheet
function shomtek_load_plugin_css() {

	wp_enqueue_style( 'plugin_style', SHOMTEK__PLUGIN_URL . 'assets/style.css', array(), SHOMTEK_VERSION );

}

add_action( 'wp_enqueue_scripts', 'shomtek_load_plugin_css' );

// Register Recent Post With Pics Widget
if( !function_exists( 'register_shomtek_widget' ) ) {

	function register_shomtek_widget() {

		register_widget( 'shomtek_latest_posts' );
	
	}

}

add_action( 'widgets_init', 'register_shomtek_widget' );
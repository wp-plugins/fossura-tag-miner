<?php
	defined( 'ABSPATH' ) or die( "Suka wena." );
	include ( 'fossura-keyword-getter.php' );
	include ( 'fossura-tag-miner-admin.php' );

/**
 * Plugin Name: Tag Miner
 * Plugin URI: http://www.textcavate.com
 * Description: Automatically add relevant tags to your blog posts..
 * Version: 1.1.1
 * Author: textCavate
 * Author URI: http://www.textcavate.com
 * Text Domain: fossura-tag-miner
 * Domain Path: /languages
 * License: GPL2
 */
 
 /*  Copyright 2014 FOSSURA_COMPUTATIONAL_LINGUISTICS  (email : info@fossura.co.za)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action( 'transition_post_status', 'fossura_set_tags', 10, 3 );

function fossura_set_tags( $new_status, $old_status, $post ) {
		
	$trigger = get_option('fossura_tags_trigger');

	if (NULL == $trigger) {
		update_option('fossura_tags_trigger', 'publish');
	}	
	
	if ($trigger == 'publish') {
		if ('publish' == $new_status && 'publish' != $old_status ) {
			  	$title = get_bloginfo('name') . '|' . get_bloginfo( 'description' );
				$content = $post->post_title . "\n" . $post->post_content;
			  	$tags = fossura_get_keywords( $title, $content );
			 	$id = $post->ID;
				wp_set_post_tags( $id, $tags, true );
		}
	}
	
	elseif($trigger =='draft') {
		if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
			return;
		}
		else {
			if ('draft' == $new_status && 'draft' == $old_status ) {
			  	$title = get_bloginfo('name') . '|' . get_bloginfo( 'description' );
				$content = $post->post_title . "\n" . $post->post_content;
			  	$tags = fossura_get_keywords( $title, $content );
				$id = $post->ID;
				wp_set_post_tags( $id, $tags, true );	
			}
		}
	}
}
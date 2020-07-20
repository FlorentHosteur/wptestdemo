<?php
/*
Plugin Name: nginx fastcgi Cache Purge
Plugin URI: http://mattgadient.com/flushing-the-nginx-fastcgi-cache-via-php-and-or-wordpress/
Description: WARNING: verify cache location in nginx-fastcgi-cache-purge.php BEFORE activating. Purges cache automatically after posting/editing, and manually via settings page. 
Version: 0.0.2
Author: Matt Gadient
Author URI: http://mattgadient.com/

Copyright (C)2013 Matt Gadient

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/ 

if (!defined('ABSPATH')) {
    die();
}


//
// You MUST verify that the cache location below is correct before
// enabling the plugin, and change it if not.
//
// Also a good idea to ensure that permissions are correct.
//
// YOU HAVE BEEN WARNED!
//

$nginx_cache_location = "/var/www/webroot/.cache/";




//
// The actual filters that will be checked against to auto-purge the entire cache.
// If you do not want the plugin to auto-purge, comment them out.
//

add_filter ('switch_theme',  'deleteCache', 100);
add_filter ('publish_post',  'deleteCache', 100);
add_filter ('edit_post',  'deleteCache', 100);
add_filter ('delete_post',  'deleteCache', 100);
add_filter ('save_post',  'deleteCache', 100);
add_filter ('add_category',  'deleteCache', 100);
add_filter ('delete_category',  'deleteCache', 100);


//
// The function that deletes the entire cache when called.
//

function deleteCache() {

	echo '';
	global $nginx_cache_location;
	if ((is_dir($nginx_cache_location)) && (is_writable($nginx_cache_location))) {
		array_map('unlink', glob($nginx_cache_location . "*/*/*"));
		array_map('rmdir', glob($nginx_cache_location . "*/*"));
		array_map('rmdir', glob($nginx_cache_location . "*"));
	} else {
		echo ('<p style="color:red;font-size:14px;">Warning! ' . $nginx_cache_location 
			. ' was not found, or not accessible! Doing nothing.</p>');
	}
	
}


//
// Creates the menu and adds the option page to it.
//
			
add_action('admin_menu', 'add_plugin_nginx_cache_menu');
function add_plugin_nginx_cache_menu() {
    add_options_page('nginx cache settings', 'nginx Cache Purge', 'manage_options', 'nginx_cache_purge', 'nginx_cache_page');
}

//
// The actual option page. Contains most of the logic (a bit messy).
//

function nginx_cache_page() {
	global $wpdb;
	global $nginx_cache_location;

	// If manually purging all is selected, this happens.
	if (isset($_POST['purge'])) {
    	echo ('<p style="color:green;font-size:14px;">Attempted to purge all cached files from ' . $nginx_cache_location 
    		. '...<br />check file counts below to see if it was successful (should show 0).</p>');
		deleteCache();
	}
	
	// If manually purging a single post/page, this happens.
	if (isset($_POST['purge_single']))
	{
		$url = parse_url($_POST['purge_single']);
			if (!$url) {
				echo '<p style="color:red;font-size:14px;">There was a problem parsing the URL, so we are 
						<b>not</b> attempting to clear it. Possible misconfiguration.</p>';
			} else {
				$scheme = $url['scheme'];
				$host = $url['host'];
				$requesturi = $url['path'];
				$hash = md5($scheme.'GET'.$host.$requesturi);
				$file_success = unlink($nginx_cache_location . substr($hash, -1) . '/' . substr($hash,-3,2) . '/' . $hash);
				if ($file_success) {
					echo ('<p style="color:green;font-size:14px;">Successfully removed ' 
					. $_POST['purge_single']
					. ' from the cache!</p>');
				} else {
					echo ('<p style="color:orange;font-size:14px;">Unable to remove cached file!<br />
					It is possible that nginx cleared it just before you did, although if you always&nbsp;
					get this message, chances are that something is not configured correctly.</p>');
				}
			}
	}


	//
	// This collects a list of page/post url's from the wordpress database, and
	// checks for the existance of each in the cache. We end up with:
	// 	-a total number of pages/posts that are cached. 
	// 	-a string we can print later listing them (with a purge button on each)
	//	
	
	$url_list = "";
	$counted_urls = 0;
	$postID = $wpdb->get_col("
	SELECT ID FROM $wpdb->posts
	WHERE ((post_type = 'post') OR (post_type = 'page'))
	AND (post_status = 'publish')
	");

	echo '<ul id="post_links">';
	foreach($postID as $post_link) {
		$url = parse_url(get_permalink($post_link));
		if(!$url) {
			echo '<p style="color:red;font-size:14px;"><b>WARNING: Unable to determine the URLs from 
				the wordpress database. Recommend you do NOT continue.</b></p>';
		} else {
			$scheme = $url['scheme'];
			$host = $url['host'];
			$requesturi = $url['path'];
			$hash = md5($scheme.'GET'.$host.$requesturi);

			if (file_exists($nginx_cache_location . substr($hash, -1) . '/' . substr($hash,-3,2) . '/' . $hash)) {
				$counted_urls++;
				$url_list .= ('<li><button type="submit" name="purge_single" value="' 
					. get_permalink($post_link) 
					. '" ><i>(purge from cache)</i></button> <b>' 
					. get_the_title($post_link) . '</b> ' . get_permalink($post_link) 
					. '</li>');
			}
		}
	}	



//
// Finally, the main option page output.
//

?>

<p>The path currently hardwired into the plugin is <b><?php echo $nginx_cache_location ?></b>
  and it also assumes you have used <b>levels=1:2</b> <i>(suggest you verify both before using this plugin).</i></p>

<p>A quick check was done to see if <b><?php echo $nginx_cache_location; ?></b> existed and was writable, and...&nbsp;

<?php if ((is_dir($nginx_cache_location)) && (is_writable($nginx_cache_location))) {
	echo '<span style="color:green;">It appears to be writable!</span>';
	} else {
	echo '<span style="color:red;">It does not appear to be writable!!! You should deactivate the plugin immediately'
		. ' and verify that the value hardcoded in the plugin matches the setting in your nginx config!</span>';
	} 
?>
</p>

<p> </p>
<p>------------------------------</p>
<p> </p>

<p>Saving/editing a post, adding/deleting a category, approving comments, and changing the theme
should all automatically clear the entire cache unless the check above failed.</p>

<p> </p>
<p>------------------------------</p>
<p> </p>

<p style="font-size:14px;">Currently there appear to be <b><?php echo sizeof(glob($nginx_cache_location . "*/*/*")); ?></b> items cached by nginx in total.<br />
<b><?php echo $counted_urls; ?></b> were matched to your posts/pages, and are listed below.</p>

<form method="post" action="">
<?php wp_nonce_field(); ?>	
	<p class="submit">
    	<input class="button" type="submit" name="purge" value="Purge all items from cache" />
	</p>
<p>Clicking the button above will attempt to purge the cache of all files. You can selectively
target files below if you would prefer.</p>

<?php

echo $url_list;

} 
 
 ?>

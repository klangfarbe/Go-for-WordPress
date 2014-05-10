<?php
/* Plugin Name: Go for Wordpress
Plugin URI: http://guzumi.de/
Description: A plugin for displaying SGF files using the <a href=http://wgo.waltheri.net>wgo.js library</a> and getting player data from the European Go Database. It adds a new shortcode <strong>[sgf]</strong> to the editor which can be used to embed the given SGF.
Version: 0.1
Author: Christian Mocek
Author URI: http://github.com/klangfarbe
License: MIT
*/

require_once(dirname(__FILE__) . '/egd.php');
require_once(dirname(__FILE__) . '/sgf.php');
require_once(dirname(__FILE__) . '/tablesorter.php');

// ---------------------------------------------------------------------------------------------------------------------
// Activation and deactivation hooks
// ---------------------------------------------------------------------------------------------------------------------

register_activation_hook(__FILE__, 'igo_activation');
function igo_activation() {
	add_option('igo_settings_background', 1);
	add_option('igo_settings_line_width', 1);
	add_option('igo_settings_default_width', '90%');
	add_option('igo_settings_max_width', '900px');
	add_option('igo_settings_stone_handler', 'NORMAL');
}

// ---------------------------------------------------------------------------------------------------------------------

register_deactivation_hook(__FILE__, 'igo_deactivation');
function igo_deactivation() {
	delete_option('igo_settings_background');
	delete_option('igo_settings_line_width');
	delete_option('igo_settings_default_width');
	delete_option('igo_settings_max_width');
	delete_option('igo_settings_stone_handler');
}

// ---------------------------------------------------------------------------------------------------------------------

add_action('wp_enqueue_scripts', 'igo_egd_scripts');
function igo_egd_scripts() {
	global $post;
	if (has_shortcode($post->post_content, 'egd') || has_shortcode($post->post_content, 'wgo_sgf')) {
		wp_register_style('go-css', plugins_url('go.css', __FILE__));
		wp_enqueue_style('go-css');
	}
}

// ---------------------------------------------------------------------------------------------------------------------
// Admin page
// ---------------------------------------------------------------------------------------------------------------------

add_action('admin_menu', 'igo_plugin_settings');
function igo_plugin_settings() {
	add_submenu_page('themes.php', 'Wordpress Go', 'Wordpress Go', 'administrator', 'igo_settings', 'igo_display_settings');
}

// ---------------------------------------------------------------------------------------------------------------------

function igo_display_settings() {
		wp_register_style('go-css', plugins_url('go.css', __FILE__));
		wp_enqueue_style('go-css');
	$images = "";
	for($i = 1; $i < 7; $i++) {
		$f = "wood" . $i . ".jpg";
		if($f == get_option('igo_settings_background')) {
 			$images .= '<div class="wgo-bg-img"><input type="radio" name="igo_settings_background" value="' . $f . '" checked><img src="' . plugins_url('/img/' . $f, __FILE__) . '"/></input></div>';
		} else {
			$images .= '<div class="wgo-bg-img"><input type="radio" name="igo_settings_background" value="' . $f . '"><img src="' . plugins_url('/img/' .  $f, __FILE__) . '"/></input></div>'; 
		}
	}
	echo get_option('igo_settings_background');
	$html = '<div class="wrap">
		<form action="options.php" method="post" name="options">
		<h2>Kifu Layout</h2>' . wp_nonce_field('update-options') .
		'<table class="form-table" >
			<tbody>
				<tr>
					<td scope="row" align="left">
 						<label>Stone design</label>
 					</td>
 					<td scope="row" align="left">
						<select name="igo_settings_stone_handler">
							<option value="NORMAL"' . (get_option('igo_settings_stone_handler') == 'NORMAL' ? 'selected' : '') . '>Normal</option>
							<option value="GLOW"' . (get_option('igo_settings_stone_handler') == 'GLOW' ? 'selected' : '') . '>Glow</option>
							<option value="MONO"' . (get_option('igo_settings_stone_handler') == 'MONO' ? 'selected' : '') . '>Monochrome</option>
						</select>
					</td>
				</tr>
				<tr>
					<td scope="row" align="left">
 						<label>Background image</label>
 					</td>
 					<td scope="row" align="left">' . $images .  '</td>
				</tr>
				<tr>
					<td scope="row" align="left">
						<label>Line width</label>
					</td>
 					<td scope="row" align="left">
						<input type="text" name="igo_settings_line_width" value="' . get_option('igo_settings_line_width') . '"/>
					</td>
				</tr>
				<tr>
					<td scope="row" align="left">
						<label>Default Kifu width</label>
					</td>
 					<td scope="row" align="left">
						<input type="text" name="igo_settings_default_width" value="' . get_option('igo_settings_default_width') . '"/>
					</td>
				</tr>
				<tr>
					<td scope="row" align="left">
						<label>Maximum Kifu width</label>
					</td>
 					<td scope="row" align="left">
						<input type="text" name="igo_settings_max_width" value="' . get_option('igo_settings_max_width') . '"/>
					</td>
				</tr>
			</tbody>
		</table>
 		<input type="hidden" name="action" value="update" />
 		<input type="hidden" name="page_options" value="igo_settings_background,igo_settings_line_width,igo_settings_max_width,igo_settings_default_width,igo_settings_stone_handler" />
 		<input type="submit" name="Submit" value="Update" />
 		</form>
 	</div>';
    echo $html;
}
?>

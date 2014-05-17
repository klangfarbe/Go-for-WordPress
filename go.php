<?php
/**
 * Plugin Name: Go, Baduk, Weiqi
 * Plugin URI: http://guzumi.de/wgo-plugin
 * Description: A plugin for displaying SGF files using the <a href=http://wgo.waltheri.net>wgo.js library</a> and getting player data from the European Go Database. It adds a new shortcode <strong>[wgo]</strong> to the editor which can be used to embed the given SGF.
 * Version: 0.4
 * Author: Christian Mocek
 * Author URI: http://github.com/klangfarbe
 * License: MIT
 * Text Domain: igo-lang
 * Domain Path: /languages
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
	add_option('igo_settings_i18n', 'en');
	add_option('igo_settings_min_width_for_float', '-1');
}

// ---------------------------------------------------------------------------------------------------------------------

register_deactivation_hook(__FILE__, 'igo_deactivation');
function igo_deactivation() {
	delete_option('igo_settings_background');
	delete_option('igo_settings_line_width');
	delete_option('igo_settings_default_width');
	delete_option('igo_settings_max_width');
	delete_option('igo_settings_stone_handler');
	delete_option('igo_settings_i18n');
	delete_option('igo_settings_min_width_for_float');
}

// ---------------------------------------------------------------------------------------------------------------------

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'igo_plugin_settings_link');
function igo_plugin_settings_link($links) {
	$url = get_admin_url() . '/themes.php?page=igo_settings';
	$settings_link = '<a href="' . $url . '">' . __('Settings', 'igo-lang') . '</a>';
	array_unshift($links, $settings_link);
	return $links;
}

// ---------------------------------------------------------------------------------------------------------------------

add_action('wp_enqueue_scripts', 'igo_egd_scripts');
function igo_egd_scripts() {
	global $post;
	if (have_posts()) {
		while(have_posts()) {
			the_post();
			if (has_shortcode($post->post_content, 'egd') || has_shortcode($post->post_content, 'wgo')) {
				wp_register_style('go-css', plugins_url('go.css', __FILE__));
				wp_enqueue_style('go-css');
			}
		}
	}
}

// ---------------------------------------------------------------------------------------------------------------------
// Admin page
// ---------------------------------------------------------------------------------------------------------------------

add_action('admin_menu', 'igo_plugin_settings');
function igo_plugin_settings() {
	add_submenu_page('themes.php', 'Go, Baduk, Weiqi', 'Go, Baduk, Weiqi', 'administrator', 'igo_settings', 'igo_display_settings');
}

/**
 * Load plugin textdomain.
 */
function igo_load_textdomain() {
  load_plugin_textdomain( 'igo-lang', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

// ---------------------------------------------------------------------------------------------------------------------

add_action( 'plugins_loaded', 'igo_load_textdomain' );
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

	$html = '<div class="wrap">
		<form action="options.php" method="post" name="options">
		<h2>' .__('Kifu Layout', 'igo-lang'). '</h2>' . wp_nonce_field('update-options') .
		'<table class="form-table" >
			<tbody>
				<tr>
					<td scope="row" align="left">
 						<label>' .__('Stone design', 'igo-lang') . '</label>
 					</td>
 					<td scope="row" align="left">
						<select name="igo_settings_stone_handler">
							<option value="NORMAL"' . (get_option('igo_settings_stone_handler') == 'NORMAL' ? 'selected' : '') . '>' .__('Normal', 'igo-lang'). '</option>
							<option value="GLOW"' . (get_option('igo_settings_stone_handler') == 'GLOW' ? 'selected' : '') . '>' .__('Glow', 'igo-lang'). '</option>
							<option value="MONO"' . (get_option('igo_settings_stone_handler') == 'MONO' ? 'selected' : '') . '>' .__('Monochrome', 'igo-lang'). '</option>
						</select>
					</td>
				</tr>
				<tr>
					<td scope="row" align="left">
 						<label>' . __('Background image', 'igo-lang') . '</label>
 					</td>
 					<td scope="row" align="left">' . $images .  '</td>
				</tr>
				<tr>
					<td scope="row" align="left">
						<label>' . __('Line width', 'igo-lang') .'</label>
					</td>
 					<td scope="row" align="left">
						<input type="text" name="igo_settings_line_width" value="' . get_option('igo_settings_line_width') . '"/>
					</td>
				</tr>
				<tr>
					<td scope="row" align="left">
						<label>' . __('Default Kifu width', 'igo-lang') .'</label>
					</td>
 					<td scope="row" align="left">
						<input type="text" name="igo_settings_default_width" value="' . get_option('igo_settings_default_width') . '"/>
					</td>
				</tr>
				<tr>
					<td scope="row" align="left">
						<label>' .__('Maximum Kifu width', 'igo-lang') .'</label>
					</td>
 					<td scope="row" align="left">
						<input type="text" name="igo_settings_max_width" value="' . get_option('igo_settings_max_width') . '"/>
					</td>
				</tr>
				<tr>
					<td scope="row" align="left">
						<label>' . __('Disable Kifu floating if screen width is smaller than (px)', 'igo-lang') .'</label>
					</td>
 					<td scope="row" align="left">
						<input type="text" name="igo_settings_min_width_for_float" value="' . get_option('igo_settings_min_width_for_float') . '"/>
					</td>
				</tr>
				<tr>
					<td scope="row" align="left">
						<label>' .__('Language', 'igo-lang') .'</label>
					</td>
					<td scope="row" align="left">
						<select name="igo_settings_i18n">
							<option value="en"' . (get_option('igo_settings_i18n') == 'en' ? 'selected' : '') . '>' . __('English (Default)', 'igo-lang') . '</option>
							<option value="de"' . (get_option('igo_settings_i18n') == 'de' ? 'selected' : '') . '>' . __('German', 'igo-lang') . '</option>
							<option value="fr"' . (get_option('igo_settings_i18n') == 'fr' ? 'selected' : '') . '>' . __('French', 'igo-lang') . '</option>
							<option value="it"' . (get_option('igo_settings_i18n') == 'it' ? 'selected' : '') . '>' . __('Italien', 'igo-lang') . '</option>
							<option value="cs"' . (get_option('igo_settings_i18n') == 'cs' ? 'selected' : '') . '>' . __('Czech', 'igo-lang') . '</option>
							<option value="zh"' . (get_option('igo_settings_i18n') == 'zh' ? 'selected' : '') . '>' . __('Chinese (Simplified)', 'igo-lang') . '</option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
 		<input type="hidden" name="action" value="update" />
 		<input type="hidden" name="page_options" value="igo_settings_background,igo_settings_line_width,igo_settings_max_width,igo_settings_default_width,igo_settings_stone_handler,igo_settings_i18n,igo_settings_min_width_for_float" />
 		<input type="submit" name="Submit" value="Update" />
 		</form>
 	</div>';
    echo $html;
}
?>

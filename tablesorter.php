<?php
add_action('wp_enqueue_scripts', 'igo_tablesorter_scripts');
function igo_tablesorter_scripts() {
	global $post;
	if (has_shortcode($post->post_content, 'table')) {
		wp_register_script('tablesorter_js', plugins_url('tablesorter.js', __FILE__), array('jquery'));
		wp_enqueue_script('tablesorter_js');
	}
}
?>

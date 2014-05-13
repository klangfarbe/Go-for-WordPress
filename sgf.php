<?php
// ---------------------------------------------------------------------------------------------------------------------

add_filter('upload_mimes', 'igo_mimetypes');
function igo_mimetypes($mime_types) {
	$mime_types['sgf'] = 'text/sgf';
	return $mime_types;
}

// ---------------------------------------------------------------------------------------------------------------------

add_filter('post_mime_types', 'igo_post_mime_types');
function igo_post_mime_types($post_mime_types) {
	$post_mime_types['text/sgf'] = array(
		__('Kifus', 'igo-lang'),
		__('Manage Kifus', 'igo-lang'),
		_n_noop('Kifu <span class="count">(%s)</span>', 'Kifus <span class="count">(%s)</span>')
	);
	return $post_mime_types;
}

// ---------------------------------------------------------------------------------------------------------------------

add_action('wp_enqueue_scripts', 'igo_sgf_scripts');
function igo_sgf_scripts() {
	global $post;
	if (has_shortcode($post->post_content, 'wgo_sgf')) {
		wp_register_script('sgf_js', plugins_url('sgf.js', __FILE__), array('jquery'));
		wp_register_script('wgo_js', plugins_url('wgo/wgo.min.js', __FILE__));
		wp_register_script('wgo_js_player', plugins_url('wgo/wgo.player.min.js', __FILE__));
		wp_register_script('wgo_js_player_i18n', plugins_url('wgo/i18n/i18n.'.get_option('igo_settings_i18n').'.js', __FILE__));
		wp_enqueue_script('sgf_js');
		wp_enqueue_script('wgo_js');
		wp_enqueue_script('wgo_js_player');
		wp_enqueue_script('wgo_js_player_i18n');

		wp_register_style('wgo_player', plugins_url('wgo/wgo.player.css', __FILE__));
		wp_enqueue_style('wgo_player');
	}
}

// ---------------------------------------------------------------------------------------------------------------------
// Render the shortcode
//
// Available parameters:
//   width
//   maxwidth
//   stones
//   background
//   limit="top,right,bottom,left"
//   static
//   float
// ---------------------------------------------------------------------------------------------------------------------

add_shortcode('wgo_sgf', 'igo_shortcode_sgf');
function igo_shortcode_sgf($atts, $content=null) {
	extract(
		shortcode_atts(
			array(
				'width' => get_option('igo_settings_default_width'),
				'maxwidth' => get_option('igo_settings_max_width'),
				'stones' => get_option('igo_settings_stone_handler'),
				'background' => get_option('igo_settings_background'),
				'move' => null,
				'static' => null,
				'limit' => null,
				'float' => null,
			),
			$atts
		)
	);

	if (!(strpos($background, '#') === 0)) {
		$background = plugins_url('img/' . $background, __FILE__);
	}

	$out = "<div data-wgo-board='stoneHandler: WGo.Board.drawHandlers."
		. $stones . ", background: \"" . $background . "\"";

	if ($limit != null) {
		$a = preg_split("/,/", $limit);
		$out .= ", section: {top: " . $a[0] . ", right: " . $a[1] . ", bottom: " . $a[2] . ", left: " . $a[3] . "}";
	}
	$out .= "'";

	if ($move != null) {
		$out .= " data-wgo-move='" . $move . "'";
	}

	if ($static != null) {
		$out .= " data-wgo-enablewheel='false' data-wgo-enablekeys='false'";
		$out .= " data-wgo-layout='top: [], right: [], left: [], bottom: []'";
	}


	$out .= " style='width: " . $width . "; max-width: " . $maxwidth;
	if ($float != null) {
		if ($float == "left") {
			$out .= "; float: left";
		}
		if ($float == "right") {
			$out .= "; float: right";
		}
	}
	$out .= "'";
	$out .= " data-wgo='" . str_replace(array("\r", "\r\n", "\n", "<br />", "<br/>", "<wbr />", "<wbr/>"), '', $content) . "'></div>";
	return $out;
}

// ---------------------------------------------------------------------------------------------------------------------

add_filter('attachment_fields_to_edit', 'igo_edit_attachment_fields', 10, 2);
function igo_edit_attachment_fields($form_fields, $attachment) {
	if (substr($attachment->post_mime_type, 0, 3) == 'sgf' ) {
		//$playertag =  $playertag = "[audio ".wp_get_attachment_url($attachment->ID)."]";
        // $form_fields["kifu"] = array(
        //     "label" => "Kifu",
        //     "input" => "html",
        //     "html" => "<button type='button' class='button' data-link-url='$playertag' audioplayer='audio-player-{$attachment->ID}'>Audio Player</button>",
        // );
    }

    return $form_fields;
}
?>

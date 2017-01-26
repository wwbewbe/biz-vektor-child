<?php

// エディタスタイルシート
add_editor_style( get_stylesheet_directory_uri() . '/editor-style.css?ver=' . date( 'U' ) );
add_editor_style( '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style') );
	wp_enqueue_style( 'child-fonts-google-css', 'https://fonts.googleapis.com/css?family=Averia+Serif+Libre:300,400,700|Oleo+Script:400,700|Open+Sans+Condensed:300,700|Open+Sans:300,400,700,800|Roboto+Condensed:300,400,700|Roboto+Slab:300,400,700|Roboto:300,400,700,900' );
	wp_enqueue_style( 'child-font-awesome-css', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
}

/* メニュー */
register_nav_menu( 'pickupnav', 'Pickup Posts' );	//おすすめ記事

// 編集画面の設定
function editor_setting($init) {
	$init[ 'block_formats' ] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre';

	$style_formats = array(
		array( 'title' => 'Tips',
			'block' => 'div',
			'classes' => 'tips',
			'wrapper' => true ),
		array( 'title' => 'Point',
			'block' => 'div',
			'classes' => 'point',
			'wrapper' => true ),
		array( 'title' => 'Attention',
			'block' => 'div',
			'classes' => 'attention',
			'wrapper' => true ),
		array( 'title' => 'Highlight',
			'inline' => 'span',
			'classes' => 'highlight') );
	$init[ 'style_formats' ] = json_encode( $style_formats );

	return $init;
}
add_filter( 'tiny_mce_before_init', 'editor_setting');

//スタイルメニューを有効化
function add_stylemenu( $buttons ) {
			array_splice( $buttons, 1, 0, 'styleselect' );
			return $buttons;
}
add_filter( 'mce_buttons_2', 'add_stylemenu' );

//投稿ページからタイトルをなくす
function remove_post_tile($posttitle) {
	if (is_single()) {
		$posttitle = NULL;
	}
	return $posttitle;
}
add_filter('bizvektor_pageTitHtml', 'remove_post_tile');

function showads($params = array()) {
	extract(shortcode_atts(array(
						'type' => 'rectangle',
    					), $params));
	$title = '<div style="font-size:8px">スポンサーリンク</div>';
	$adcode = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- magonote-本文下 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:250px"
     data-ad-client="ca-pub-6212569927869845"
     data-ad-slot="4342281613"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';

	$responsive = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- hitoriguide-サイドバー -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-6212569927869845"
     data-ad-slot="1734859217"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';

	if ($type == 'responsive') {
		return $title.$responsive;
	} else {
    	return $title.$adcode;
	}
}
add_shortcode('adsense', 'showads');

// カテゴリーリストを表示するショートコード
function set_catlist($params = array()) {
    extract(shortcode_atts(array(
        				'file' => 'catlist',
						'catname' => 0,
						'list' => -1,
    					), $params));
    ob_start();
    include(get_stylesheet_directory() . "/$file.php");
    return ob_get_clean();
}
add_shortcode('catlist', 'set_catlist');

/* 記事からサムネイル画像取得 */
function get_thumb( $size ) {
	global $post;

	if ( has_post_thumbnail() ) {
		$postthumb = wp_get_attachment_image_src( get_post_thumbnail_id(), $size );
		$url = $postthumb[0];
	} elseif( preg_match( '/wp-image-(\d+)/s', $post->post_content, $thumbid ) ) {
		$postthumb = wp_get_attachment_image_src( $thumbid[1], $size );
		$url = $postthumb[0];
	} else {
		$url = get_stylesheet_directory_uri() . '/noimage.png';
	}
	return $url;
}

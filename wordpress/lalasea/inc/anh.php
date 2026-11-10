<?php
/**
 * Gắn width/height cho mọi thẻ <img> chưa có — tương đương transform
 * "kichThuocAnh" trong eleventy.config.js.
 *
 * Trình duyệt chừa sẵn chỗ cho ảnh nên trang không bị nhảy khi ảnh tải xong.
 * Làm trên toàn bộ HTML để bắt được cả ảnh trong bài viết do chủ quán chèn sau này.
 *
 * @package Lalasea
 */

defined( 'ABSPATH' ) || exit;

/**
 * Kích thước thật của một ảnh nằm trên web, ví dụ "/anh/img1.jpg".
 *
 * @param string $duong_dan Đường dẫn bắt đầu bằng "/".
 * @return int[]|null [rộng, cao] hoặc null nếu không đọc được.
 */
function lalasea_kich_thuoc_anh( $duong_dan ) {
	static $da_doc = array();
	if ( ! array_key_exists( $duong_dan, $da_doc ) ) {
		$tep                  = ABSPATH . ltrim( rawurldecode( (string) strtok( $duong_dan, '?' ) ), '/' );
		$kich_thuoc           = is_readable( $tep ) ? wp_getimagesize( $tep ) : false;
		$da_doc[ $duong_dan ] = ( $kich_thuoc && $kich_thuoc[0] && $kich_thuoc[1] ) ? array( $kich_thuoc[0], $kich_thuoc[1] ) : null;
	}
	return $da_doc[ $duong_dan ];
}

/**
 * Thêm width/height vào các thẻ <img> trong HTML.
 *
 * @param string $html Toàn bộ HTML của trang.
 * @return string
 */
function lalasea_gan_kich_thuoc_anh( $html ) {
	return (string) preg_replace_callback(
		'/<img\b[^>]*>/',
		function ( $khop ) {
			$the = $khop[0];
			if ( preg_match( '/\b(width|height)=/', $the ) || ! preg_match( '/\bsrc="(\/[^"]+)"/', $the, $src ) ) {
				return $the;
			}
			$kich_thuoc = lalasea_kich_thuoc_anh( $src[1] );
			if ( ! $kich_thuoc ) {
				return $the;
			}
			return (string) preg_replace( '/<img\b/', '<img width="' . $kich_thuoc[0] . '" height="' . $kich_thuoc[1] . '"', $the, 1 );
		},
		$html
	);
}

/**
 * Bật bộ gắn kích thước cho các trang HTML (không áp dụng cho feed, robots, sitemap).
 */
function lalasea_bat_gan_kich_thuoc_anh() {
	if ( ! is_feed() && ! is_robots() && ! get_query_var( 'lalasea_sitemap' ) ) {
		ob_start( 'lalasea_gan_kich_thuoc_anh' );
	}
}
add_action( 'template_redirect', 'lalasea_bat_gan_kich_thuoc_anh', 99 );

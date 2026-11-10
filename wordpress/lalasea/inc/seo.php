<?php
/**
 * Chuyển hướng trang cũ (301 thật), sitemap.xml, robots.txt — thay cho
 * chuyen-huong.njk, sitemap.njk và robots.njk của bản Eleventy.
 *
 * @package Lalasea
 */

defined( 'ABSPATH' ) || exit;

/**
 * Chuyển hướng 301 các đường dẫn cũ (danh sách ở "Chuyển hướng trang cũ").
 *
 * Bản Eleventy chỉ làm được trang mồi meta-refresh (GitHub Pages không có 301);
 * WordPress làm 301 thật nên Google chuyển thứ hạng sang địa chỉ mới nhanh hơn.
 */
function lalasea_chuyen_huong_trang_cu() {
	$yeu_cau = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/';
	$duong   = '/' . trim( (string) wp_parse_url( $yeu_cau, PHP_URL_PATH ), '/' );
	$duong   = rtrim( $duong, '/' ) . '/';

	foreach ( lalasea_data( 'chuyenhuong' )['danh_sach'] as $cap ) {
		if ( rtrim( $cap['cu'], '/' ) . '/' === $duong && '' !== $cap['moi'] ) {
			wp_safe_redirect( home_url( $cap['moi'] ), 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'lalasea_chuyen_huong_trang_cu', 1 );

/*
 * Sitemap riêng ở /sitemap.xml (giữ đúng địa chỉ cũ đã khai báo với Google),
 * tắt sitemap mặc định /wp-sitemap.xml của WordPress để không trùng.
 */
add_filter( 'wp_sitemaps_enabled', '__return_false' );

/**
 * Đăng ký đường dẫn /sitemap.xml.
 */
function lalasea_dang_ky_sitemap() {
	add_rewrite_rule( '^sitemap\.xml$', 'index.php?lalasea_sitemap=1', 'top' );
}
add_action( 'init', 'lalasea_dang_ky_sitemap' );

/**
 * Cho WordPress biết biến truy vấn lalasea_sitemap.
 *
 * @param string[] $bien Các biến truy vấn công khai.
 * @return string[]
 */
function lalasea_bien_sitemap( $bien ) {
	$bien[] = 'lalasea_sitemap';
	return $bien;
}
add_filter( 'query_vars', 'lalasea_bien_sitemap' );

/**
 * Không tự thêm dấu "/" vào cuối /sitemap.xml.
 *
 * @param string|false $url Địa chỉ WordPress định chuyển tới.
 * @return string|false
 */
function lalasea_giu_dia_chi_sitemap( $url ) {
	return get_query_var( 'lalasea_sitemap' ) ? false : $url;
}
add_filter( 'redirect_canonical', 'lalasea_giu_dia_chi_sitemap' );

/**
 * In sitemap: mọi trang và bài đã xuất bản.
 */
function lalasea_in_sitemap() {
	if ( ! get_query_var( 'lalasea_sitemap' ) ) {
		return;
	}
	$goc       = rtrim( lalasea_data( 'thongtin' )['site_url'], '/' );
	$trang_chu = (int) get_option( 'page_on_front' );
	$cac_muc   = get_posts(
		array(
			'post_type'   => array( 'page', 'post' ),
			'post_status' => 'publish',
			'numberposts' => -1,
			'orderby'     => 'menu_order date',
			'order'       => 'ASC',
		)
	);

	header( 'Content-Type: application/xml; charset=UTF-8' );
	echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
	echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
	foreach ( $cac_muc as $muc ) {
		$url = $trang_chu === $muc->ID ? '/' : wp_make_link_relative( get_permalink( $muc ) );
		echo "  <url>\n    <loc>" . esc_url( $goc . $url ) . "</loc>\n";
		echo '    <lastmod>' . esc_html( substr( $muc->post_modified_gmt, 0, 10 ) ) . "</lastmod>\n  </url>\n";
	}
	echo "</urlset>\n";
	exit;
}
add_action( 'template_redirect', 'lalasea_in_sitemap' );

/**
 * Nội dung robots.txt.
 *
 * @return string
 */
function lalasea_robots_txt() {
	$goc = rtrim( lalasea_data( 'thongtin' )['site_url'], '/' );
	return "User-agent: *\nAllow: /\nDisallow: /wp-admin/\nAllow: /wp-admin/admin-ajax.php\nSitemap: {$goc}/sitemap.xml\n";
}
add_filter( 'robots_txt', 'lalasea_robots_txt', 99 );

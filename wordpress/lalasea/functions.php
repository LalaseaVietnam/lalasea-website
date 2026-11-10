<?php
/**
 * Lalasea Bistro — theme WordPress.
 *
 * Giao diện, HTML và CSS giữ nguyên như website Eleventy cũ. Đọc DESIGN.md
 * (ở thư mục gốc dự án) trước khi sửa CSS hoặc template.
 *
 * @package Lalasea
 */

defined( 'ABSPATH' ) || exit;

define( 'LALASEA_VERSION', '1.0.0' );

require_once __DIR__ . '/inc/ham-tro.php';
require_once __DIR__ . '/inc/du-lieu.php';
require_once __DIR__ . '/inc/trang.php';
require_once __DIR__ . '/inc/seo.php';
require_once __DIR__ . '/inc/don-dep.php';
require_once __DIR__ . '/inc/anh.php';
require_once __DIR__ . '/inc/quan-tri-bai-viet.php';
require_once __DIR__ . '/inc/quan-tri-du-lieu.php';
require_once __DIR__ . '/inc/quan-tri-giao-dien.php';
require_once __DIR__ . '/inc/cai-dat-mau.php';

/**
 * Khai báo tính năng của theme.
 */
function lalasea_thiet_lap() {
	// In thẻ <script>/<style> theo HTML5 (không kèm type="text/css" thừa).
	add_theme_support( 'html5', array( 'script', 'style' ) );
}
add_action( 'after_setup_theme', 'lalasea_thiet_lap' );

/**
 * Nạp phông chữ Google và CSS của web.
 *
 * Ba bộ chữ theo DESIGN.md §3: Playfair Display (tiêu đề), Be Vietnam Pro (thân bài),
 * Pacifico (chữ viết tay). Không thêm bộ chữ thứ tư.
 */
function lalasea_nap_css() {
	wp_enqueue_style(
		'lalasea-phong-chu',
		'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&family=Pacifico&display=swap',
		array(),
		null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- giữ nguyên địa chỉ Google Fonts, không gắn ?ver=.
	);
	wp_enqueue_style(
		'lalasea',
		get_theme_file_uri( 'css/style.css' ),
		array( 'lalasea-phong-chu' ),
		(string) filemtime( get_theme_file_path( 'css/style.css' ) )
	);
}
add_action( 'wp_enqueue_scripts', 'lalasea_nap_css' );

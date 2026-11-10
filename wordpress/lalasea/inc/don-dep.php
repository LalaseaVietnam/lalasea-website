<?php
/**
 * Dọn những thứ WordPress tự thêm vào trang (emoji, RSS, generator, CSS khối...)
 * để HTML ra đúng như web cũ, và đóng bớt các cửa tấn công hay bị dò.
 *
 * @package Lalasea
 */

defined( 'ABSPATH' ) || exit;

/**
 * Bỏ các thẻ WordPress tự in vào <head> và cuối trang mà web không dùng.
 */
function lalasea_bo_the_thua() {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	remove_action( 'wp_head', 'rel_canonical' ); // header.php tự in canonical.
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
	remove_action( 'wp_head', 'wp_resource_hints', 2 );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'wp_robots', 1 ); // header.php tự in robots khi cần.
	remove_action( 'template_redirect', 'rest_output_link_header', 11 );
	remove_action( 'template_redirect', 'wp_shortlink_header', 11 );

	// CSS khối Gutenberg và "global styles" — web không dùng.
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
	remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles' );
	remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

	// CSS sửa ảnh sizes="auto" và script tải trước trang — web không dùng.
	remove_action( 'wp_head', 'wp_enqueue_img_auto_sizes_contain_css_fix', 0 );
	remove_action( 'wp_head', 'wp_print_auto_sizes_contain_css_fix', 1 );
	remove_action( 'wp_footer', 'wp_print_speculation_rules' );
}
add_action( 'init', 'lalasea_bo_the_thua' );

/**
 * Bỏ các file CSS mặc định của trình soạn khối khỏi trang khách xem.
 */
function lalasea_bo_css_khoi() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'classic-theme-styles' );
	wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'lalasea_bo_css_khoi', 100 );

// Thanh công cụ nổi của WordPress làm lệch menu dính — chủ quán vào /wp-admin để sửa.
add_filter( 'show_admin_bar', '__return_false' );

// Web không có bình luận.
add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );

/**
 * Ẩn mục Bình luận trong menu quản trị.
 */
function lalasea_an_binh_luan() {
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'lalasea_an_binh_luan' );

// Bảo mật: tắt XML-RPC, kể cả pingback (hay bị dùng để tấn công từ chối dịch vụ).
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'xmlrpc_methods', '__return_empty_array' );

/**
 * Không để lộ danh sách tài khoản qua REST API khi chưa đăng nhập.
 *
 * @param array $cac_diem REST endpoints.
 * @return array
 */
function lalasea_an_danh_sach_tai_khoan( $cac_diem ) {
	if ( ! is_user_logged_in() ) {
		unset( $cac_diem['/wp/v2/users'], $cac_diem['/wp/v2/users/(?P<id>[\d]+)'] );
	}
	return $cac_diem;
}
add_filter( 'rest_endpoints', 'lalasea_an_danh_sach_tai_khoan' );

/**
 * Không để lộ tên đăng nhập qua ?author=1 và trang tác giả.
 */
function lalasea_chan_trang_tac_gia() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- chỉ kiểm tra có tham số hay không.
	if ( is_author() || isset( $_GET['author'] ) ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'lalasea_chan_trang_tac_gia', 0 );

/**
 * Báo lỗi đăng nhập chung chung, không cho biết tên đăng nhập có tồn tại hay không.
 *
 * @return string
 */
function lalasea_loi_dang_nhap() {
	return 'Tên đăng nhập hoặc mật khẩu không đúng.';
}
add_filter( 'login_errors', 'lalasea_loi_dang_nhap' );

/**
 * Trong trang quản trị, "Bài viết" gọi đúng tên của web: Tin tức & Ưu đãi.
 *
 * @param object $nhan Các nhãn của loại bài viết.
 * @return object
 */
function lalasea_doi_ten_bai_viet( $nhan ) {
	$nhan->name          = 'Tin tức & Ưu đãi';
	$nhan->menu_name     = 'Tin tức & Ưu đãi';
	$nhan->all_items     = 'Tất cả bài';
	$nhan->add_new       = 'Viết bài mới';
	$nhan->add_new_item  = 'Viết bài mới';
	$nhan->edit_item     = 'Sửa bài viết';
	$nhan->singular_name = 'Bài viết';
	return $nhan;
}
add_filter( 'post_type_labels_post', 'lalasea_doi_ten_bai_viet' );

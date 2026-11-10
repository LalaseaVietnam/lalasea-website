<?php
/**
 * Plugin Name: Lalasea — kích hoạt lần đầu (tự xoá)
 * Description: Bật theme Lalasea, dựng trang + bài, cài tiếng Việt, thiết lập đường dẫn. Xong việc thì tự xoá file này.
 *
 * Script triển khai (wordpress/tools/trien-khai.sh) đặt file này vào
 * wp-content/mu-plugins/ — WordPress tự chạy mọi file trong thư mục đó, không cần
 * đăng nhập trang quản trị để bật. Sau đó script mở trang chủ vài lần:
 *
 *   Lần 1: bật theme Lalasea.
 *   Lần 2: theme đã chạy → dựng trang, bài (lalasea_cai_dat_mau), cài tiếng Việt
 *          cho trang quản trị, ghi .htaccess, đánh dấu đã xong và tự xoá.
 *
 * Chạy lại nhiều lần vẫn an toàn: mọi bước đều bỏ qua phần đã làm.
 *
 * @package Lalasea
 */

defined( 'ABSPATH' ) || exit;

/**
 * Kích hoạt từng bước, mỗi lần có người mở trang.
 */
function lalasea_kich_hoat_lan_dau() {
	if ( get_option( 'lalasea_da_kich_hoat' ) ) {
		lalasea_kich_hoat_tu_xoa();
		return;
	}

	// Bước 1: bật theme. Theme mới chỉ chạy từ lần mở trang tiếp theo.
	if ( 'lalasea' !== get_stylesheet() ) {
		if ( ! wp_get_theme( 'lalasea' )->exists() ) {
			return;
		}
		switch_theme( 'lalasea' );

		// Lượt này đã nạp code của theme cũ, nhưng WordPress sẽ lấy template của theme
		// mới → lỗi 500. Mở lại ngay đúng địa chỉ đang xem để chạy hẳn bằng theme mới.
		$la_trang_khach = ! is_admin() && ! wp_doing_ajax() && ! wp_doing_cron() && ! ( defined( 'WP_CLI' ) && WP_CLI );
		if ( $la_trang_khach && isset( $_SERVER['REQUEST_URI'] ) ) {
			wp_safe_redirect( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
			exit;
		}
		return;
	}

	// Bước 2: theme đang chạy.
	if ( ! function_exists( 'lalasea_cai_dat_mau' ) ) {
		return;
	}
	lalasea_cai_dat_mau();

	// Trang quản trị bằng tiếng Việt.
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/translation-install.php';
	if ( 'vi' === wp_download_language_pack( 'vi' ) ) {
		update_option( 'WPLANG', 'vi' );
	}

	// Ghi luật đường dẫn đẹp vào .htaccess (nếu máy chủ cho phép).
	require_once ABSPATH . 'wp-admin/includes/misc.php';
	flush_rewrite_rules( true );

	update_option( 'lalasea_da_kich_hoat', gmdate( 'c' ), false );
	lalasea_kich_hoat_tu_xoa();
}
add_action( 'init', 'lalasea_kich_hoat_lan_dau', 100 );

/**
 * Xoá file này khỏi máy chủ (xong việc thì không để lại gì).
 */
function lalasea_kich_hoat_tu_xoa() {
	wp_delete_file( __FILE__ );
}

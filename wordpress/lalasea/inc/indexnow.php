<?php
/**
 * Báo IndexNow (Bing, và qua đó ChatGPT; Yandex...) mỗi khi web đổi nội dung.
 *
 * Web Eleventy làm việc này trong GitHub Actions sau mỗi lần xuất bản; sang
 * WordPress thì theme tự làm: khi đăng/sửa bài, sửa trang, hoặc lưu "Thông tin
 * nhà hàng". Khoá xác minh là file /cc97fe9ea5cf05654fd1deeed594a344.txt ở thư mục
 * gốc (script triển khai đã đưa lên).
 *
 * Chỉ gửi khi web chạy đúng tên miền thật (site_url trong Thông tin chung), để
 * bản thử trên máy khác không báo nhầm.
 *
 * @package Lalasea
 */

defined( 'ABSPATH' ) || exit;

const LALASEA_INDEXNOW_KHOA = 'cc97fe9ea5cf05654fd1deeed594a344';

/**
 * Gửi danh sách địa chỉ vừa đổi tới IndexNow (không chờ phản hồi).
 *
 * @param string[] $duong_dan Đường dẫn đầy đủ của các trang vừa đổi.
 * @return bool Có gửi hay không.
 */
function lalasea_bao_indexnow( $duong_dan ) {
	$goc      = rtrim( lalasea_data( 'thongtin' )['site_url'], '/' );
	$ten_mien = (string) wp_parse_url( $goc, PHP_URL_HOST );
	if ( '' === $ten_mien || wp_parse_url( home_url(), PHP_URL_HOST ) !== $ten_mien ) {
		return false;
	}
	$duong_dan = array_values( array_unique( array_filter( $duong_dan ) ) );
	if ( ! $duong_dan ) {
		return false;
	}

	wp_remote_post(
		'https://api.indexnow.org/indexnow',
		array(
			'blocking' => false,
			'timeout'  => 5,
			'headers'  => array( 'Content-Type' => 'application/json; charset=utf-8' ),
			'body'     => wp_json_encode(
				array(
					'host'        => $ten_mien,
					'key'         => LALASEA_INDEXNOW_KHOA,
					'keyLocation' => $goc . '/' . LALASEA_INDEXNOW_KHOA . '.txt',
					'urlList'     => $duong_dan,
				)
			),
		)
	);
	return true;
}

/**
 * Đường dẫn đầy đủ (theo tên miền thật) của một trang/bài.
 *
 * @param int|WP_Post $bai Bài hoặc trang.
 * @return string
 */
function lalasea_dia_chi_that( $bai ) {
	$goc   = rtrim( lalasea_data( 'thongtin' )['site_url'], '/' );
	$trang = (int) get_option( 'page_on_front' );
	$bai   = get_post( $bai );
	if ( $bai && 'publish' !== $bai->post_status ) {
		// Bài vừa bị gỡ: WordPress đã đổi đường dẫn thành ?p=123 và thêm đuôi "__trashed".
		// Lấy lại địa chỉ cũ để máy tìm kiếm biết đúng trang đó không còn nữa.
		$bai              = clone $bai;
		$bai->post_status = 'publish';
		$bai->post_name   = (string) preg_replace( '/__trashed$/', '', $bai->post_name );
	}
	return $goc . ( $bai && $trang === $bai->ID ? '/' : wp_make_link_relative( get_permalink( $bai ) ) );
}

/**
 * Đăng mới, sửa hoặc gỡ một bài/trang đã xuất bản → báo trang đó và các trang liệt kê bài.
 *
 * @param string  $trang_thai_moi Trạng thái mới.
 * @param string  $trang_thai_cu  Trạng thái cũ.
 * @param WP_Post $bai            Bài hoặc trang.
 */
function lalasea_indexnow_khi_doi_bai( $trang_thai_moi, $trang_thai_cu, $bai ) {
	if ( ! in_array( $bai->post_type, array( 'post', 'page' ), true ) || wp_is_post_revision( $bai ) || wp_is_post_autosave( $bai ) ) {
		return;
	}
	if ( 'publish' !== $trang_thai_moi && 'publish' !== $trang_thai_cu ) {
		return;
	}
	$goc     = rtrim( lalasea_data( 'thongtin' )['site_url'], '/' );
	$cac_dia = array( lalasea_dia_chi_that( $bai ) );
	if ( 'post' === $bai->post_type ) {
		$cac_dia[] = $goc . '/';
		$cac_dia[] = $goc . '/tin-tuc/';
	}
	lalasea_bao_indexnow( $cac_dia );
}
add_action( 'transition_post_status', 'lalasea_indexnow_khi_doi_bai', 10, 3 );

/**
 * Lưu "Thông tin nhà hàng" (giá, giờ, ảnh, menu...) → báo mọi trang chính.
 */
function lalasea_indexnow_khi_luu_du_lieu() {
	$cac_trang = get_posts(
		array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'numberposts' => -1,
			'fields'      => 'ids',
		)
	);
	lalasea_bao_indexnow( array_map( 'lalasea_dia_chi_that', $cac_trang ) );
}
add_action( 'lalasea_da_luu_du_lieu', 'lalasea_indexnow_khi_luu_du_lieu' );

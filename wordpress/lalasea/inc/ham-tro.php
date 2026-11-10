<?php
/**
 * Hàm định dạng dùng trong template: số điện thoại, giá tiền, ngày tháng, JSON.
 *
 * @package Lalasea
 */

defined( 'ABSPATH' ) || exit;

/**
 * In một giá trị vào schema JSON-LD hoặc vào JavaScript.
 *
 * Giữ nguyên tiếng Việt và dấu "/" cho dễ đọc; "<" ">" được mã hoá để dữ liệu
 * không bao giờ đóng được thẻ <script>.
 *
 * @param mixed $gia_tri Chuỗi, số hoặc mảng.
 * @return string JSON.
 */
function lalasea_json( $gia_tri ) {
	return (string) wp_json_encode( $gia_tri, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG );
}

/**
 * Số điện thoại hiển thị cho khách nước ngoài: "0907 896 949" → "+84 907 896 949".
 *
 * @param string $sdt Số điện thoại dạng Việt Nam.
 * @return string
 */
function lalasea_sdt_quoc_te( $sdt ) {
	return preg_replace( '/^0/', '+84 ', (string) $sdt );
}

/**
 * Số điện thoại để bấm gọi từ SIM nước ngoài: "0907896949" → "+84907896949".
 *
 * @param string $sdt_goi Số điện thoại viết liền.
 * @return string
 */
function lalasea_tel_quoc_te( $sdt_goi ) {
	return '+84' . preg_replace( '/^0/', '', (string) $sdt_goi );
}

/**
 * Giá kiểu Việt sang kiểu quốc tế, để chủ quán chỉ nhập giá một lần:
 * "690.000đ/người" → "690,000 VND per person".
 *
 * @param string $gia Giá tiền như chủ quán nhập.
 * @return string
 */
function lalasea_gia_en( $gia ) {
	$gia = preg_replace( '/(\d)\.(?=\d{3})/', '$1,', (string) $gia );
	$gia = preg_replace( '/\s*đ/u', ' VND', $gia );
	return preg_replace( '/\s*\/\s*người/u', ' per person', $gia );
}

/**
 * Chuyển thời điểm GMT ("2026-09-10 00:00:00") sang giờ của website.
 *
 * @param string $gmt Thời điểm theo giờ GMT.
 * @return DateTimeImmutable
 */
function lalasea_ngay_dia_phuong( $gmt ) {
	$thoi_diem = new DateTimeImmutable( $gmt, new DateTimeZone( 'UTC' ) );
	return $thoi_diem->setTimezone( wp_timezone() );
}

/**
 * Ngày kiểu Việt Nam: 10/09/2026.
 *
 * @param string $gmt Thời điểm theo giờ GMT.
 * @return string
 */
function lalasea_ngay_vn( $gmt ) {
	return lalasea_ngay_dia_phuong( $gmt )->format( 'd/m/Y' );
}

/**
 * Ngày dạng chữ: 10 tháng 9, 2026.
 *
 * @param string $gmt Thời điểm theo giờ GMT.
 * @return string
 */
function lalasea_ngay_chu( $gmt ) {
	$ngay = lalasea_ngay_dia_phuong( $gmt );
	return $ngay->format( 'j' ) . ' tháng ' . $ngay->format( 'n' ) . ', ' . $ngay->format( 'Y' );
}

/**
 * Thời điểm chuẩn ISO 8601 cho schema: 2026-09-10T00:00:00.000Z.
 *
 * @param string $gmt Thời điểm theo giờ GMT.
 * @return string
 */
function lalasea_iso( $gmt ) {
	return ( new DateTimeImmutable( $gmt, new DateTimeZone( 'UTC' ) ) )->format( 'Y-m-d\TH:i:s.000\Z' );
}

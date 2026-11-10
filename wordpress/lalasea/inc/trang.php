<?php
/**
 * Thông tin của trang đang hiển thị (tiêu đề, mô tả, bản dịch...) — tương ứng
 * "front matter" của file .njk cũ.
 *
 * Template gọi lalasea_dat_trang() trước get_header(); header.php và footer.php
 * đọc lại bằng lalasea_trang().
 *
 * @package Lalasea
 */

defined( 'ABSPATH' ) || exit;

/**
 * Ghi thông tin trang đang hiển thị.
 *
 * @param array $thong_tin {
 *     Các khoá có thể dùng (thiếu thì lấy mặc định).
 *
 *     @type string|null $title        Tiêu đề trang; null = trang chủ.
 *     @type string|null $mo_ta        Mô tả cho Google; null = mô tả chung của quán.
 *     @type string|null $anh_dai_dien Ảnh chia sẻ, dạng /duong-dan; null = /hero.jpg.
 *     @type string|null $ban_dich     Đường dẫn trang tương ứng ở ngôn ngữ kia.
 *     @type bool        $khong_index  true = chặn Google lập chỉ mục.
 *     @type bool        $an_footer    true = ẩn chân trang và nút liên hệ nhanh.
 *     @type string      $ngon_ngu     'vi' hoặc 'en'.
 *     @type string      $og_type      'website' hoặc 'article'.
 *     @type string|null $url          Đường dẫn của trang, ví dụ /thuc-don/.
 * }
 */
function lalasea_dat_trang( array $thong_tin ) {
	$GLOBALS['lalasea_trang'] = array_merge(
		array(
			'title'        => null,
			'mo_ta'        => null,
			'anh_dai_dien' => null,
			'ban_dich'     => null,
			'khong_index'  => false,
			'an_footer'    => false,
			'ngon_ngu'     => 'vi',
			'og_type'      => 'website',
			'url'          => null,
		),
		$thong_tin
	);
}

/**
 * Đọc thông tin trang đang hiển thị.
 *
 * @return array Xem lalasea_dat_trang().
 */
function lalasea_trang() {
	if ( ! isset( $GLOBALS['lalasea_trang'] ) ) {
		lalasea_dat_trang( array() );
	}
	return $GLOBALS['lalasea_trang'];
}

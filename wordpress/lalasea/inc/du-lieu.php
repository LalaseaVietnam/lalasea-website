<?php
/**
 * Dữ liệu dùng chung (tên quán, giờ mở cửa, menu, glamping...) và danh sách bài viết.
 *
 * Dữ liệu gốc nằm trong data/<tên>.json (đi kèm theme). Từ lần đầu chủ quán bấm Lưu
 * trong "Thông tin nhà hàng", bản lưu trong WordPress được dùng thay (xem quan-tri-du-lieu.php).
 *
 * @package Lalasea
 */

defined( 'ABSPATH' ) || exit;

/**
 * Cấu trúc các ô nhập liệu (data/bieu-mau.json): tên, nhãn, kiểu của từng trường.
 *
 * @return array[] Danh sách biểu mẫu, mỗi biểu mẫu ứng với một file dữ liệu.
 */
function lalasea_bieu_mau() {
	static $bieu_mau = null;
	if ( null === $bieu_mau ) {
		$bieu_mau = wp_json_file_decode( get_theme_file_path( 'data/bieu-mau.json' ), array( 'associative' => true ) );
		$bieu_mau = is_array( $bieu_mau ) ? $bieu_mau : array();
	}
	return $bieu_mau;
}

/**
 * Đọc một bộ dữ liệu, ví dụ lalasea_data( 'thongtin' ).
 *
 * Mọi trường khai báo trong biểu mẫu đều có mặt trong kết quả (thiếu thì là chuỗi
 * rỗng hoặc mảng rỗng), nên template đọc thẳng $lalasea_glamping['luu_y_en'] được.
 *
 * @param string $ten Tên bộ dữ liệu: thongtin, thuvien, menuanh, danhgia, glamping, chuyenhuong.
 * @return array
 */
function lalasea_data( $ten ) {
	static $cache = array();
	if ( ! isset( $cache[ $ten ] ) ) {
		$goc = wp_json_file_decode( get_theme_file_path( 'data/' . $ten . '.json' ), array( 'associative' => true ) );

		/**
		 * Cho phép thay dữ liệu gốc — dùng cho bản chủ quán đã lưu trong trang quản trị.
		 *
		 * @param array  $goc Dữ liệu từ file JSON đi kèm theme.
		 * @param string $ten Tên bộ dữ liệu.
		 */
		$du_lieu = apply_filters( 'lalasea_data', is_array( $goc ) ? $goc : array(), $ten );

		foreach ( lalasea_bieu_mau() as $bieu_mau ) {
			if ( $bieu_mau['name'] === $ten ) {
				$du_lieu = lalasea_chuan_hoa( $bieu_mau['fields'], $du_lieu );
			}
		}
		$cache[ $ten ] = $du_lieu;
	}
	return $cache[ $ten ];
}

/**
 * Bổ sung các trường còn thiếu theo biểu mẫu (giữ nguyên các trường đã có).
 *
 * @param array $cac_truong Danh sách trường trong biểu mẫu.
 * @param array $du_lieu    Dữ liệu cần bổ sung.
 * @return array
 */
function lalasea_chuan_hoa( $cac_truong, $du_lieu ) {
	$du_lieu = is_array( $du_lieu ) ? $du_lieu : array();
	foreach ( $cac_truong as $truong ) {
		$gia_tri = $du_lieu[ $truong['name'] ] ?? null;
		if ( 'list' === $truong['widget'] ) {
			$gia_tri = is_array( $gia_tri ) ? array_values( $gia_tri ) : array();
			if ( isset( $truong['fields'] ) ) {
				foreach ( $gia_tri as $i => $muc ) {
					$gia_tri[ $i ] = lalasea_chuan_hoa( $truong['fields'], $muc );
				}
			} else {
				$gia_tri = array_map( 'strval', $gia_tri );
			}
		} elseif ( 'number' === $truong['widget'] ) {
			$gia_tri = (int) ( $gia_tri ?? ( $truong['default'] ?? 0 ) );
		} else {
			$gia_tri = (string) $gia_tri;
		}
		$du_lieu[ $truong['name'] ] = $gia_tri;
	}
	return $du_lieu;
}

/**
 * Các bài tin tức đã xuất bản, mới nhất lên đầu.
 *
 * @return array[] Mỗi bài: url, date (giờ GMT), data[title, mo_ta, anh_dai_dien].
 */
function lalasea_danh_sach_bai() {
	static $danh_sach = null;
	if ( null === $danh_sach ) {
		$truy_van  = new WP_Query(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => -1, // phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page -- web chỉ có vài chục bài.
				'orderby'             => 'date',
				'order'               => 'DESC',
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
			)
		);
		$danh_sach = array_map( 'lalasea_thong_tin_bai', $truy_van->posts );
	}
	return $danh_sach;
}

/**
 * Thông tin hiển thị của một bài: đường dẫn, ngày, tiêu đề, mô tả ngắn, ảnh đại diện.
 *
 * @param WP_Post $bai Bài viết.
 * @return array
 */
function lalasea_thong_tin_bai( $bai ) {
	// Mô tả ngắn: ô "mô tả ngắn" (excerpt) có sẵn của WordPress.
	$mo_ta = '' !== $bai->post_excerpt ? $bai->post_excerpt : (string) get_post_meta( $bai->ID, '_lalasea_mo_ta', true );

	// Ảnh đại diện: ảnh chọn trong WordPress; bài nhập từ web cũ thì dùng đường dẫn cũ.
	$anh = has_post_thumbnail( $bai ) ? wp_make_link_relative( (string) wp_get_attachment_image_url( get_post_thumbnail_id( $bai ), 'full' ) ) : '';
	if ( '' === $anh ) {
		$anh = (string) get_post_meta( $bai->ID, '_lalasea_anh_dai_dien', true );
	}

	return array(
		'url'  => wp_make_link_relative( get_permalink( $bai ) ),
		'date' => $bai->post_date_gmt,
		'data' => array(
			// Tiêu đề gốc, không qua get_the_title(): hàm đó đổi "&" thành "&#038;" và
			// nháy thẳng thành nháy cong. Template tự escape khi in ra.
			'title'        => wp_specialchars_decode( $bai->post_title, ENT_QUOTES ),
			'mo_ta'        => $mo_ta,
			'anh_dai_dien' => $anh,
		),
	);
}

/**
 * Bài đang xem (dùng trong single.php).
 *
 * @return array title, mo_ta, anh_dai_dien, date, url, noi_dung.
 */
function lalasea_bai_hien_tai() {
	$bai       = get_post();
	$thong_tin = lalasea_thong_tin_bai( $bai );
	return array_merge(
		$thong_tin['data'],
		array(
			'date'     => $thong_tin['date'],
			'url'      => $thong_tin['url'],
			'noi_dung' => lalasea_noi_dung( $bai->post_content ),
		)
	);
}

/**
 * Nội dung bài: xử lý khối Gutenberg và shortcode, nhưng KHÔNG tự thêm <p>/<br>,
 * không đổi dấu nháy/gạch ngang, không gắn lazy-load — chữ ra đúng như tác giả viết.
 *
 * @param string $html Nội dung bài.
 * @return string
 */
function lalasea_noi_dung( $html ) {
	return do_shortcode( do_blocks( $html ) );
}

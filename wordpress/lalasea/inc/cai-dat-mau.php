<?php
/**
 * Dựng sẵn toàn bộ trang và bài viết khi kích hoạt theme lần đầu.
 *
 * - 5 trang tiếng Việt + 5 trang tiếng Anh (/en/...), gắn đúng template;
 * - các bài tin tức hiện có (seed/baiviet.json);
 * - trang chủ, trang tin tức, cấu trúc đường dẫn giống hệt web cũ (ghi luôn .htaccess);
 * - trang quản trị bằng tiếng Việt.
 *
 * Chạy lại nhiều lần vẫn an toàn: trang/bài đã có thì bỏ qua, không ghi đè.
 * Chạy tay bằng WP-CLI: wp eval 'lalasea_cai_dat_mau();'
 *
 * @package Lalasea
 */

defined( 'ABSPATH' ) || exit;

/**
 * Cây trang của website: đường dẫn, tên trong trang quản trị, template.
 *
 * @return array[]
 */
function lalasea_cay_trang() {
	return array(
		array(
			'slug'    => 'trang-chu',
			'title'   => 'Trang chủ',
			'tpl'     => 'templates/trang-chu.php',
			'vai_tro' => 'front',
		),
		array(
			'slug'  => 'thuc-don',
			'title' => 'Thực đơn',
			'tpl'   => 'templates/thuc-don.php',
		),
		array(
			'slug'  => 'glamping',
			'title' => 'Glamping',
			'tpl'   => 'templates/glamping.php',
		),
		array(
			'slug'  => 'tiec-su-kien',
			'title' => 'Tiệc & Sự kiện',
			'tpl'   => 'templates/tiec-su-kien.php',
		),
		array(
			'slug'  => 'lien-he',
			'title' => 'Liên hệ',
			'tpl'   => 'templates/lien-he.php',
		),
		array(
			'slug'    => 'tin-tuc',
			'title'   => 'Tin tức & Ưu đãi',
			'tpl'     => '',
			'vai_tro' => 'posts',
		),
		array(
			'slug'  => 'en',
			'title' => 'English — Home',
			'tpl'   => 'templates/en-trang-chu.php',
			'con'   => array(
				array(
					'slug'  => 'menu',
					'title' => 'English — Menu',
					'tpl'   => 'templates/en-menu.php',
				),
				array(
					'slug'  => 'glamping',
					'title' => 'English — Glamping',
					'tpl'   => 'templates/en-glamping.php',
				),
				array(
					'slug'  => 'events',
					'title' => 'English — Events',
					'tpl'   => 'templates/en-events.php',
				),
				array(
					'slug'  => 'contact',
					'title' => 'English — Contact',
					'tpl'   => 'templates/en-contact.php',
				),
			),
		),
	);
}

/**
 * Tạo một trang (và các trang con) nếu chưa có.
 *
 * @param array  $trang     Một mục trong lalasea_cay_trang().
 * @param int    $cha_id    ID trang cha, 0 nếu là trang gốc.
 * @param string $duong_cha Đường dẫn của trang cha.
 * @return int ID trang.
 */
function lalasea_tao_trang( $trang, $cha_id = 0, $duong_cha = '' ) {
	$duong  = ltrim( $duong_cha . '/' . $trang['slug'], '/' );
	$co_san = get_page_by_path( $duong );
	if ( $co_san ) {
		// Trang trùng tên đã có (ví dụ còn từ WordPress cũ): giữ trang, nhưng gắn đúng
		// template và xuất bản — không thì trang hiện bằng giao diện mặc định.
		$id = $co_san->ID;
		if ( 'publish' !== $co_san->post_status ) {
			wp_update_post(
				array(
					'ID'          => $id,
					'post_status' => 'publish',
				)
			);
		}
		if ( '' !== $trang['tpl'] ) {
			update_post_meta( $id, '_wp_page_template', $trang['tpl'] );
		}
	} else {
		$id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $trang['title'],
				'post_name'    => $trang['slug'],
				'post_parent'  => $cha_id,
				'post_content' => '',
				'post_author'  => lalasea_tac_gia_mac_dinh(),
				'meta_input'   => '' !== $trang['tpl'] ? array( '_wp_page_template' => $trang['tpl'] ) : array(),
			)
		);
	}
	foreach ( $trang['con'] ?? array() as $con ) {
		lalasea_tao_trang( $con, $id, $duong );
	}
	return (int) $id;
}

/**
 * Nạp các bài tin tức của web cũ (seed/baiviet.json), bài nào có rồi thì bỏ qua.
 */
function lalasea_nap_bai_viet() {
	$cac_bai = wp_json_file_decode( get_theme_file_path( 'seed/baiviet.json' ), array( 'associative' => true ) );
	foreach ( is_array( $cac_bai ) ? $cac_bai : array() as $bai ) {
		$da_co = get_posts(
			array(
				'name'        => $bai['slug'],
				'post_type'   => 'post',
				'post_status' => 'any',
				'numberposts' => 1,
			)
		);
		if ( $da_co ) {
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_type'     => 'post',
				'post_status'   => 'publish',
				'post_title'    => $bai['title'],
				'post_name'     => $bai['slug'],
				'post_content'  => $bai['noi_dung'],
				'post_excerpt'  => $bai['mo_ta'] ?? '',
				'post_author'   => lalasea_tac_gia_mac_dinh(),
				'post_date_gmt' => $bai['date'] . ' 00:00:00',
				'post_date'     => get_date_from_gmt( $bai['date'] . ' 00:00:00' ),
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			// Ảnh của web cũ là đường dẫn (/anh/...), không phải ảnh trong Thư viện.
			update_post_meta( $id, '_lalasea_anh_dai_dien', $bai['anh_dai_dien'] ?? '' );
		}
	}
}

/**
 * Dựng toàn bộ: thiết lập chung, trang, bài viết, dọn nội dung mẫu của WordPress.
 */
function lalasea_cai_dat_mau() {
	// Thiết lập chung làm trước, vì múi giờ ảnh hưởng ngày đăng bài.
	// Đường dẫn bài viết giữ nguyên /tin-tuc/<tên-bài>/ như web cũ.
	update_option( 'show_on_front', 'page' );
	// Dùng set_permalink_structure() chứ không update_option(): WordPress đã nạp cấu trúc
	// cũ vào bộ nhớ từ đầu lượt chạy, ghi thẳng vào cài đặt thì flush_rewrite_rules() bên
	// dưới vẫn sinh luật theo cấu trúc cũ → mọi bài viết 404 (gặp khi hosting có sẵn
	// WordPress dùng đường dẫn /%postname%/).
	global $wp_rewrite;
	$wp_rewrite->set_permalink_structure( '/tin-tuc/%postname%/' );
	update_option( 'timezone_string', 'Asia/Ho_Chi_Minh' );
	update_option( 'blogname', 'Lalasea Bistro' );
	update_option( 'blogdescription', '' );
	update_option( 'default_comment_status', 'closed' );
	update_option( 'default_ping_status', 'closed' );
	update_option( 'date_format', 'd/m/Y' );
	update_option( 'time_format', 'H:i' );
	update_option( 'start_of_week', 1 );

	foreach ( lalasea_cay_trang() as $trang ) {
		$id = lalasea_tao_trang( $trang );
		if ( 'front' === ( $trang['vai_tro'] ?? '' ) ) {
			update_option( 'page_on_front', $id );
		}
		if ( 'posts' === ( $trang['vai_tro'] ?? '' ) ) {
			update_option( 'page_for_posts', $id );
		}
	}

	lalasea_nap_bai_viet();

	// Dọn nội dung mẫu của WordPress.
	foreach ( array(
		'post' => 'hello-world',
		'page' => 'sample-page',
	) as $loai => $slug ) {
		$mau = get_posts(
			array(
				'name'        => $slug,
				'post_type'   => $loai,
				'numberposts' => 1,
			)
		);
		if ( $mau ) {
			wp_delete_post( $mau[0]->ID, true );
		}
	}

	lalasea_ghi_duong_dan();
	lalasea_cai_tieng_viet();
}

/**
 * Sinh lại luật đường dẫn và ghi vào .htaccess (nếu máy chủ là Apache/LiteSpeed và
 * file ghi được) — để không phải vào Cài đặt → Đường dẫn tĩnh bấm Lưu.
 *
 * Lúc kích hoạt theme, WordPress chưa nạp các hàm ghi .htaccess của trang quản trị,
 * nên phải nạp tay trước khi gọi flush_rewrite_rules().
 */
function lalasea_ghi_duong_dan() {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/misc.php';
	flush_rewrite_rules( true );
}

/**
 * Cài và bật tiếng Việt cho trang quản trị (tải gói ngôn ngữ từ wordpress.org).
 *
 * Hosting không cho tải (thiếu quyền ghi file) thì bỏ qua — chọn tay ở
 * Cài đặt → Tổng quan → Ngôn ngữ của trang.
 */
function lalasea_cai_tieng_viet() {
	if ( 'vi' === get_option( 'WPLANG' ) ) {
		return;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/translation-install.php';
	if ( 'vi' === wp_download_language_pack( 'vi' ) ) {
		update_option( 'WPLANG', 'vi' );
	}
}
add_action( 'after_switch_theme', 'lalasea_cai_dat_mau' );

/**
 * Tác giả cho trang/bài nhập từ web cũ: người đang kích hoạt theme, không thì
 * quản trị viên đầu tiên.
 *
 * @return int ID người dùng.
 */
function lalasea_tac_gia_mac_dinh() {
	if ( get_current_user_id() ) {
		return get_current_user_id();
	}
	$quan_tri = get_users(
		array(
			'role'    => 'administrator',
			'number'  => 1,
			'orderby' => 'ID',
			'fields'  => 'ID',
		)
	);
	return $quan_tri ? (int) $quan_tri[0] : 0;
}

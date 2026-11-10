<?php
/**
 * Làm gọn trang quản trị cho chủ quán.
 *
 * - Bảng tin: bỏ tin tức/sự kiện của WordPress, thay bằng các lối tắt việc hay làm.
 * - 11 trang chính (Thực đơn, Glamping...) dùng template cố định của theme: nội dung
 *   gõ vào khung soạn thảo sẽ KHÔNG hiện lên web. Tắt khung soạn thảo ở các trang này
 *   và chỉ rõ chỗ sửa đúng, để không ai mất công gõ vào chỗ vô tác dụng.
 *
 * @package Lalasea
 */

defined( 'ABSPATH' ) || exit;

/**
 * Bảng tin: bỏ các khung không liên quan, thêm khung lối tắt lên đầu.
 */
function lalasea_bang_tin() {
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );     // Tin tức & sự kiện WordPress.
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' ); // Bản nháp nhanh.
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' ); // Tổng quan.
	remove_action( 'welcome_panel', 'wp_welcome_panel' );

	// Ưu tiên 'high' = luôn nằm trên cùng Bảng tin.
	wp_add_dashboard_widget( 'lalasea_loi_tat', 'Lalasea Bistro — việc hay làm', 'lalasea_khung_loi_tat', null, null, 'normal', 'high' );
}
add_action( 'wp_dashboard_setup', 'lalasea_bang_tin', 20 );

/**
 * Nội dung khung "việc hay làm" trên Bảng tin.
 */
function lalasea_khung_loi_tat() {
	$cac_loi_tat = array(
		array( admin_url( 'post-new.php' ), '✏️ Viết bài tin tức / ưu đãi', 'Bài mới hiện ngay ở trang Tin tức và trang chủ.' ),
		array( admin_url( 'admin.php?page=ls-thongtin' ), '☎️ Số điện thoại, giờ mở cửa, địa chỉ', 'Đổi một lần, cập nhật trên mọi trang (cả bản tiếng Anh).' ),
		array( admin_url( 'admin.php?page=ls-glamping' ), '🏕️ Giá & ảnh glamping', 'Bảng giá ngày thường / cuối tuần, bữa sáng, BBQ, lưu ý.' ),
		array( admin_url( 'admin.php?page=ls-menuanh' ), '📖 Ảnh thực đơn', '14 trang menu, theo đúng thứ tự.' ),
		array( admin_url( 'admin.php?page=ls-thuvien' ), '📸 Thư viện ảnh trang chủ', 'Thêm, xoá, đổi thứ tự ảnh.' ),
		array( admin_url( 'admin.php?page=ls-danhgia' ), '⭐ Đánh giá của khách', 'Chép nguyên văn từ Google Maps.' ),
		array( home_url( '/' ), '🌐 Xem website', 'Mở trang chủ như khách thấy.' ),
	);
	echo '<ul class="ls-loi-tat">';
	foreach ( $cac_loi_tat as list( $url, $nhan, $mo_ta ) ) {
		printf(
			'<li><a href="%s"><strong>%s</strong></a><br><span class="description">%s</span></li>',
			esc_url( $url ),
			esc_html( $nhan ),
			esc_html( $mo_ta )
		);
	}
	echo '</ul>';
}

/**
 * Định dạng khung lối tắt (chỉ trên Bảng tin).
 *
 * @param string $man_hinh Tên màn hình quản trị đang mở.
 */
function lalasea_css_bang_tin( $man_hinh ) {
	if ( 'index.php' !== $man_hinh ) {
		return;
	}
	wp_add_inline_style(
		'dashboard',
		'.ls-loi-tat{margin:0}.ls-loi-tat li{padding:10px 0;border-bottom:1px solid #f0f0f1;margin:0}'
		. '.ls-loi-tat li:last-child{border:0}.ls-loi-tat a{font-size:14px;text-decoration:none}'
	);
}
add_action( 'admin_enqueue_scripts', 'lalasea_css_bang_tin' );

/**
 * Trang có dùng giao diện cố định của theme không (Thực đơn, Glamping, Tin tức...).
 *
 * @param int|WP_Post $trang ID hoặc đối tượng trang.
 * @return bool
 */
function lalasea_la_trang_co_dinh( $trang ) {
	$trang = get_post( $trang );
	if ( ! $trang || 'page' !== $trang->post_type ) {
		return false;
	}
	if ( (int) get_option( 'page_for_posts' ) === $trang->ID ) {
		return true; // Trang Tin tức tự liệt kê bài viết.
	}
	return str_starts_with( (string) get_page_template_slug( $trang ), 'templates/' );
}

/**
 * Danh sách Trang: ghi nhãn "Giao diện cố định" cạnh tên trang.
 *
 * @param string[] $nhan  Các nhãn trạng thái.
 * @param WP_Post  $trang Trang.
 * @return string[]
 */
function lalasea_nhan_trang_co_dinh( $nhan, $trang ) {
	if ( lalasea_la_trang_co_dinh( $trang ) ) {
		$nhan['lalasea_co_dinh'] = 'Giao diện cố định';
	}
	return $nhan;
}
add_filter( 'display_post_states', 'lalasea_nhan_trang_co_dinh', 10, 2 );

/**
 * Không mở trình soạn khối cho các trang cố định.
 *
 * @param bool    $dung  WordPress định dùng trình soạn khối hay không.
 * @param WP_Post $trang Trang.
 * @return bool
 */
function lalasea_tat_trinh_soan_khoi( $dung, $trang ) {
	return lalasea_la_trang_co_dinh( $trang ) ? false : $dung;
}
add_filter( 'use_block_editor_for_post', 'lalasea_tat_trinh_soan_khoi', 10, 2 );

/**
 * Bỏ khung soạn thảo cổ điển khi mở một trang cố định.
 */
function lalasea_bo_khung_soan_thao() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- chỉ đọc ID trang đang mở để ẩn khung soạn thảo.
	$id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
	if ( $id && lalasea_la_trang_co_dinh( $id ) ) {
		remove_post_type_support( 'page', 'editor' );
	}
}
add_action( 'load-post.php', 'lalasea_bo_khung_soan_thao' );

/**
 * Thay khung soạn thảo bằng hướng dẫn sửa đúng chỗ.
 *
 * @param WP_Post $trang Trang đang sửa.
 */
function lalasea_huong_dan_trang_co_dinh( $trang ) {
	if ( ! lalasea_la_trang_co_dinh( $trang ) ) {
		return;
	}
	?>
	<div class="notice notice-info inline" style="margin:16px 0;padding:12px 14px">
		<p style="font-size:14px;margin:0 0 6px"><strong>Trang này dùng giao diện cố định của website.</strong></p>
		<p style="margin:0">
			Chữ và bố cục được giữ y như thiết kế. Thông tin hay thay đổi (giá, giờ mở cửa, SĐT, ảnh, menu) sửa ở
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=ls-thongtin' ) ); ?>">Thông tin nhà hàng</a>;
			bài tin tức, ưu đãi viết ở <a href="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>">Tin tức &amp; Ưu đãi</a>.
			Muốn đổi câu chữ cố định trong trang thì nhờ người lập trình.
		</p>
	</div>
	<?php
}
add_action( 'edit_form_after_title', 'lalasea_huong_dan_trang_co_dinh' );

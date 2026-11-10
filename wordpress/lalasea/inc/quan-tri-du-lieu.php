<?php
/**
 * Trang quản trị "Thông tin nhà hàng" trong /wp-admin.
 *
 * Các ô nhập được dựng tự động từ data/bieu-mau.json (sinh từ cấu hình trang
 * quản trị cũ src/quan-tri/config.yml) — cùng nhãn, cùng thứ tự như trước.
 * Dữ liệu lưu vào bảng tuỳ chọn của WordPress (lalasea_du_lieu_<tên>) và thay cho
 * file data/<tên>.json kể từ lần lưu đầu tiên.
 *
 * @package Lalasea
 */

defined( 'ABSPATH' ) || exit;

/**
 * Quyền cần có để sửa thông tin nhà hàng: Quản trị viên và Biên tập viên.
 */
const LALASEA_QUYEN_SUA = 'edit_pages';

/**
 * Dữ liệu đã lưu trong WordPress được ưu tiên hơn file JSON đi kèm theme.
 *
 * @param array  $du_lieu Dữ liệu từ file JSON.
 * @param string $ten     Tên bộ dữ liệu.
 * @return array
 */
function lalasea_du_lieu_da_luu( $du_lieu, $ten ) {
	$da_luu = get_option( 'lalasea_du_lieu_' . $ten, null );
	return is_array( $da_luu ) ? $da_luu : $du_lieu;
}
add_filter( 'lalasea_data', 'lalasea_du_lieu_da_luu', 10, 2 );

/**
 * Thêm mục "Thông tin nhà hàng" và các trang con vào menu quản trị.
 */
function lalasea_menu_du_lieu() {
	$cac_bieu_mau = lalasea_bieu_mau();
	if ( ! $cac_bieu_mau ) {
		return;
	}
	$trang_dau = 'ls-' . $cac_bieu_mau[0]['name'];
	add_menu_page( 'Thông tin nhà hàng', 'Thông tin nhà hàng', LALASEA_QUYEN_SUA, $trang_dau, 'lalasea_man_hinh_du_lieu', 'dashicons-store', 3 );
	foreach ( $cac_bieu_mau as $bieu_mau ) {
		add_submenu_page( $trang_dau, $bieu_mau['label'], $bieu_mau['label'], LALASEA_QUYEN_SUA, 'ls-' . $bieu_mau['name'], 'lalasea_man_hinh_du_lieu' );
	}
}
add_action( 'admin_menu', 'lalasea_menu_du_lieu' );

/**
 * Biểu mẫu của màn hình quản trị đang mở (theo tham số ?page=ls-<tên>).
 *
 * @return array|null
 */
function lalasea_bieu_mau_dang_mo() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- chỉ đọc tên màn hình để hiển thị, không thay đổi dữ liệu.
	$trang = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
	return lalasea_tim_bieu_mau( (string) preg_replace( '/^ls-/', '', $trang ) );
}

/**
 * Tìm biểu mẫu theo tên bộ dữ liệu.
 *
 * @param string $ten Tên bộ dữ liệu.
 * @return array|null
 */
function lalasea_tim_bieu_mau( $ten ) {
	foreach ( lalasea_bieu_mau() as $bieu_mau ) {
		if ( $bieu_mau['name'] === $ten ) {
			return $bieu_mau;
		}
	}
	return null;
}

/**
 * Vẽ một ô nhập theo kiểu của trường (chữ, đoạn văn, số, ảnh, danh sách).
 *
 * @param array  $truong  Khai báo trường trong biểu mẫu.
 * @param string $ten_o   Thuộc tính name của ô, ví dụ ls[bang_gia][0][ten].
 * @param mixed  $gia_tri Giá trị hiện tại.
 * @param int    $do_sau  Độ sâu lồng nhau của danh sách (để đặt mã thay thế riêng).
 * @return string HTML đã escape.
 */
function lalasea_ve_o( $truong, $ten_o, $gia_tri, $do_sau ) {
	$id   = 'o' . substr( md5( $ten_o . wp_rand() ), 0, 8 );
	$nhan = '<label for="' . esc_attr( $id ) . '"><strong>' . esc_html( $truong['label'] ) . '</strong></label>';

	switch ( $truong['widget'] ) {
		case 'list':
			return lalasea_ve_danh_sach( $truong, $ten_o, is_array( $gia_tri ) ? $gia_tri : array(), $do_sau );

		case 'text':
			return '<div class="ls-o">' . $nhan
				. '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $ten_o ) . '" rows="3" class="large-text">' . esc_textarea( (string) $gia_tri ) . '</textarea></div>';

		case 'number':
			$so = ( null === $gia_tri || '' === $gia_tri ) ? ( $truong['default'] ?? '' ) : $gia_tri;
			return '<div class="ls-o">' . $nhan
				. '<input type="number" id="' . esc_attr( $id ) . '" name="' . esc_attr( $ten_o ) . '" value="' . esc_attr( (string) $so ) . '"'
				. ( isset( $truong['min'] ) ? ' min="' . (int) $truong['min'] . '"' : '' )
				. ( isset( $truong['max'] ) ? ' max="' . (int) $truong['max'] . '"' : '' )
				. ' class="small-text"></div>';

		case 'image':
			$xem_truoc = $gia_tri ? '<img src="' . esc_url( home_url( (string) $gia_tri ) ) . '" alt="">' : '';
			return '<div class="ls-o ls-anh">' . $nhan . '<div class="ls-anh-hang"><span class="ls-xem">' . $xem_truoc . '</span>'
				. '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $ten_o ) . '" value="' . esc_attr( (string) $gia_tri ) . '" class="regular-text" placeholder="/anh/ten-anh.jpg">'
				. ' <button type="button" class="button ls-chon-anh">Chọn ảnh</button></div></div>';

		default:
			return '<div class="ls-o">' . $nhan
				. '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $ten_o ) . '" value="' . esc_attr( (string) $gia_tri ) . '" class="large-text"></div>';
	}
}

/**
 * Vẽ một danh sách có nút thêm / xoá / đổi thứ tự.
 *
 * Mẫu cho mục mới nằm trong <template>, với mã __i<độ sâu>__ ở chỗ số thứ tự;
 * JavaScript thay mã đó bằng một số duy nhất khi bấm "+ Thêm".
 *
 * @param array  $truong    Khai báo trường kiểu list.
 * @param string $ten_o     Thuộc tính name gốc của danh sách.
 * @param array  $danh_sach Các mục hiện có.
 * @param int    $do_sau    Độ sâu lồng nhau.
 * @return string HTML đã escape.
 */
function lalasea_ve_danh_sach( $truong, $ten_o, $danh_sach, $do_sau ) {
	$don_vi  = $truong['label_singular'] ?? 'mục';
	$ma_thay = '__i' . $do_sau . '__';

	$ve_muc = function ( $gia_tri, $chi_so ) use ( $truong, $ten_o, $do_sau, $don_vi ) {
		$html = '<div class="ls-muc"><div class="ls-muc-nut">'
			. '<button type="button" class="button-link ls-len" aria-label="Chuyển lên">▲</button>'
			. '<button type="button" class="button-link ls-xuong" aria-label="Chuyển xuống">▼</button>'
			. '<button type="button" class="button-link ls-xoa">Xoá ' . esc_html( $don_vi ) . '</button></div>';
		if ( isset( $truong['field'] ) ) {
			// Danh sách chữ đơn, ví dụ các món ăn sáng.
			$con   = array_merge( $truong['field'], array( 'widget' => $truong['field']['widget'] ?? 'string' ) );
			$html .= lalasea_ve_o( $con, $ten_o . '[' . $chi_so . ']', $gia_tri, $do_sau + 1 );
		} else {
			foreach ( $truong['fields'] as $con ) {
				$gia_tri_con = is_array( $gia_tri ) ? ( $gia_tri[ $con['name'] ] ?? null ) : null;
				$html       .= lalasea_ve_o( $con, $ten_o . '[' . $chi_so . '][' . $con['name'] . ']', $gia_tri_con, $do_sau + 1 );
			}
		}
		return $html . '</div>';
	};

	$html = '<fieldset class="ls-ds" data-ma="' . esc_attr( $ma_thay ) . '"><legend>' . esc_html( $truong['label'] ) . '</legend><div class="ls-cac-muc">';
	foreach ( array_values( $danh_sach ) as $chi_so => $gia_tri ) {
		$html .= $ve_muc( $gia_tri, $chi_so );
	}
	$html .= '</div><template>' . $ve_muc( null, $ma_thay ) . '</template>'
		. '<button type="button" class="button ls-them">+ Thêm ' . esc_html( $don_vi ) . '</button>'
		// Ô ẩn để danh sách rỗng vẫn được gửi lên: xoá hết mục thì lưu thành mảng rỗng.
		. '<input type="hidden" name="' . esc_attr( $ten_o ) . '[__rong__]" value="1"></fieldset>';
	return $html;
}

/**
 * Màn hình sửa một bộ dữ liệu.
 */
function lalasea_man_hinh_du_lieu() {
	$bieu_mau = lalasea_bieu_mau_dang_mo();
	if ( ! $bieu_mau || ! current_user_can( LALASEA_QUYEN_SUA ) ) {
		wp_die( 'Không có quyền truy cập.' );
	}
	$du_lieu = lalasea_data( $bieu_mau['name'] );
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- chỉ để hiện thông báo "Đã lưu".
	$da_luu = isset( $_GET['da-luu'] );
	?>
	<div class="wrap ls-quan-tri">
		<h1><?php echo esc_html( $bieu_mau['label'] ); ?></h1>
		<?php if ( $da_luu ) : ?>
			<div class="notice notice-success is-dismissible"><p>Đã lưu. Web cập nhật ngay — mở trang để xem.</p></div>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="lalasea_luu_du_lieu">
			<input type="hidden" name="lalasea_ten" value="<?php echo esc_attr( $bieu_mau['name'] ); ?>">
			<?php
			wp_nonce_field( 'lalasea_luu_' . $bieu_mau['name'] );
			foreach ( $bieu_mau['fields'] as $truong ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- lalasea_ve_o() đã escape từng phần.
				echo lalasea_ve_o( $truong, 'ls[' . $truong['name'] . ']', $du_lieu[ $truong['name'] ] ?? null, 0 );
			}
			submit_button( 'Lưu thay đổi' );
			?>
		</form>
	</div>
	<?php
}

/**
 * Nạp CSS/JS của màn hình nhập liệu (chỉ trên các màn hình này).
 */
function lalasea_nap_tai_nguyen_quan_tri() {
	if ( ! lalasea_bieu_mau_dang_mo() ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_style( 'lalasea-quan-tri', get_theme_file_uri( 'assets/quan-tri-du-lieu.css' ), array(), LALASEA_VERSION );
	wp_enqueue_script( 'lalasea-quan-tri', get_theme_file_uri( 'assets/quan-tri-du-lieu.js' ), array( 'jquery' ), LALASEA_VERSION, true );
	wp_add_inline_script( 'lalasea-quan-tri', 'window.lalaseaGoc = ' . wp_json_encode( home_url() ) . ';', 'before' );
}
add_action( 'admin_enqueue_scripts', 'lalasea_nap_tai_nguyen_quan_tri' );

/**
 * Làm sạch giá trị một ô theo kiểu của nó trước khi lưu.
 *
 * Không dùng sanitize_text_field(): hàm đó xoá các chuỗi kiểu "%20" làm hỏng link
 * Maps/Zalo. Thay vào đó bỏ thẻ HTML và khoảng trắng thừa.
 *
 * @param array $truong  Khai báo trường.
 * @param mixed $gia_tri Giá trị gửi lên (chưa unslash).
 * @return mixed
 */
function lalasea_lam_sach( $truong, $gia_tri ) {
	switch ( $truong['widget'] ) {
		case 'list':
			$gia_tri = is_array( $gia_tri ) ? $gia_tri : array();
			unset( $gia_tri['__rong__'] );
			$ket_qua = array();
			foreach ( $gia_tri as $muc ) {
				if ( isset( $truong['field'] ) ) {
					$con  = array_merge( $truong['field'], array( 'widget' => $truong['field']['widget'] ?? 'string' ) );
					$sach = lalasea_lam_sach( $con, $muc );
					if ( '' !== $sach ) {
						$ket_qua[] = $sach;
					}
					continue;
				}
				$dong   = array();
				$co_chu = false;
				foreach ( $truong['fields'] as $con ) {
					$dong[ $con['name'] ] = lalasea_lam_sach( $con, is_array( $muc ) ? ( $muc[ $con['name'] ] ?? null ) : null );
					if ( 'number' !== $con['widget'] && '' !== $dong[ $con['name'] ] && array() !== $dong[ $con['name'] ] ) {
						$co_chu = true;
					}
				}
				if ( $co_chu ) {
					$ket_qua[] = $dong; // Bỏ mục trống hoàn toàn.
				}
			}
			return $ket_qua;

		case 'number':
			return (int) $gia_tri;

		case 'text':
			return trim( wp_strip_all_tags( wp_unslash( (string) $gia_tri ), false ) );

		case 'image':
			$duong_dan = trim( wp_unslash( (string) $gia_tri ) );
			if ( '' !== $duong_dan && str_starts_with( $duong_dan, home_url() ) ) {
				$duong_dan = wp_make_link_relative( $duong_dan );
			}
			return '' === $duong_dan ? '' : esc_url_raw( $duong_dan );

		default:
			return trim( wp_strip_all_tags( wp_unslash( (string) $gia_tri ), true ) );
	}
}

/**
 * Lưu form "Thông tin nhà hàng".
 */
function lalasea_luu_du_lieu() {
	$ten      = isset( $_POST['lalasea_ten'] ) ? sanitize_key( wp_unslash( $_POST['lalasea_ten'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce kiểm tra ngay dưới, cần tên để biết nonce nào.
	$bieu_mau = lalasea_tim_bieu_mau( $ten );
	if ( ! $bieu_mau || ! current_user_can( LALASEA_QUYEN_SUA ) ) {
		wp_die( 'Không có quyền.' );
	}
	check_admin_referer( 'lalasea_luu_' . $ten );

	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- từng ô được unslash và làm sạch trong lalasea_lam_sach().
	$gui_len = isset( $_POST['ls'] ) && is_array( $_POST['ls'] ) ? $_POST['ls'] : array();

	// Giữ nguyên các khoá không có ô nhập (ví dụ toạ độ vi_do/kinh_do), chỉ ghi đè các ô trên form.
	$moi = lalasea_data( $ten );
	foreach ( $bieu_mau['fields'] as $truong ) {
		$moi[ $truong['name'] ] = lalasea_lam_sach( $truong, $gui_len[ $truong['name'] ] ?? null );
	}
	update_option( 'lalasea_du_lieu_' . $ten, $moi, false );

	wp_safe_redirect( admin_url( 'admin.php?page=ls-' . $ten . '&da-luu=1' ) );
	exit;
}
add_action( 'admin_post_lalasea_luu_du_lieu', 'lalasea_luu_du_lieu' );

<?php
/**
 * Làm gọn trang quản trị cho chủ quán:
 *  - Bảng tin: bỏ tin tức/sự kiện của WordPress, thay bằng các lối tắt việc hay làm.
 *  - 11 trang chính (Thực đơn, Glamping...) dùng template cố định của theme — nội dung
 *    gõ vào khung soạn thảo sẽ KHÔNG hiện lên web. Tắt khung soạn thảo ở các trang này
 *    và chỉ rõ chỗ sửa đúng, để không ai mất công gõ vào chỗ vô tác dụng.
 */
defined('ABSPATH') || exit;

/* ---------- Bảng tin ---------- */
add_action('wp_dashboard_setup', function () {
    remove_meta_box('dashboard_primary', 'dashboard', 'side');      // Tin tức & sự kiện WordPress
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');  // Bản nháp nhanh
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');  // Tổng quan
    remove_action('welcome_panel', 'wp_welcome_panel');

    wp_add_dashboard_widget('ls_loi_tat', 'Lalasea Bistro — việc hay làm', function () {
        $o = function ($url, $nhan, $mo_ta) {
            echo '<li><a href="' . esc_url($url) . '"><strong>' . esc_html($nhan) . '</strong></a><br><span class="description">' . esc_html($mo_ta) . '</span></li>';
        };
        echo '<ul class="ls-loi-tat">';
        $o(admin_url('post-new.php'), '✏️ Viết bài tin tức / ưu đãi', 'Bài mới hiện ngay ở trang Tin tức và trang chủ.');
        $o(admin_url('admin.php?page=ls-thongtin'), '☎️ Số điện thoại, giờ mở cửa, địa chỉ', 'Đổi một lần, cập nhật trên mọi trang (cả bản tiếng Anh).');
        $o(admin_url('admin.php?page=ls-glamping'), '🏕️ Giá & ảnh glamping', 'Bảng giá ngày thường / cuối tuần, bữa sáng, BBQ, lưu ý.');
        $o(admin_url('admin.php?page=ls-menuanh'), '📖 Ảnh thực đơn', '14 trang menu, theo đúng thứ tự.');
        $o(admin_url('admin.php?page=ls-thuvien'), '📸 Thư viện ảnh trang chủ', 'Thêm, xoá, đổi thứ tự ảnh.');
        $o(admin_url('admin.php?page=ls-danhgia'), '⭐ Đánh giá của khách', 'Chép nguyên văn từ Google Maps.');
        $o(home_url('/'), '🌐 Xem website', 'Mở trang chủ như khách thấy.');
        echo '</ul>';
    }, null, null, 'normal', 'high'); // 'high' = luôn nằm trên cùng Bảng tin
}, 20);

add_action('admin_head-index.php', function () {
    echo '<style>.ls-loi-tat{margin:0}.ls-loi-tat li{padding:10px 0;border-bottom:1px solid #f0f0f1;margin:0}.ls-loi-tat li:last-child{border:0}.ls-loi-tat a{font-size:14px;text-decoration:none}</style>';
});

/* ---------- Các trang dùng template cố định ---------- */
function ls_la_trang_co_dinh($post)
{
    $post = get_post($post);
    if (!$post || $post->post_type !== 'page') {
        return false;
    }
    if ((int) $post->ID === (int) get_option('page_for_posts')) {
        return true; // trang Tin tức tự liệt kê bài viết
    }
    return str_starts_with((string) get_page_template_slug($post), 'templates/');
}

// Danh sách Trang: ghi chú cạnh tên trang
add_filter('display_post_states', function ($nhan, $post) {
    if (ls_la_trang_co_dinh($post)) {
        $nhan['ls_co_dinh'] = 'Giao diện cố định';
    }
    return $nhan;
}, 10, 2);

// Không mở khung soạn thảo khối cho các trang này
add_filter('use_block_editor_for_post', function ($dung, $post) {
    return ls_la_trang_co_dinh($post) ? false : $dung;
}, 10, 2);

add_action('load-post.php', function () {
    $id = (int) ($_GET['post'] ?? 0);
    if ($id && ls_la_trang_co_dinh($id)) {
        remove_post_type_support('page', 'editor');
    }
});

add_action('edit_form_after_title', function ($post) {
    if (!ls_la_trang_co_dinh($post)) {
        return;
    }
    echo '<div class="notice notice-info inline" style="margin:16px 0;padding:12px 14px">'
        . '<p style="font-size:14px;margin:0 0 6px"><strong>Trang này dùng giao diện cố định của website.</strong></p>'
        . '<p style="margin:0">Chữ và bố cục được giữ y như thiết kế. Thông tin hay thay đổi (giá, giờ mở cửa, SĐT, ảnh, menu) sửa ở '
        . '<a href="' . esc_url(admin_url('admin.php?page=ls-thongtin')) . '">Thông tin nhà hàng</a>; '
        . 'bài tin tức, ưu đãi viết ở <a href="' . esc_url(admin_url('edit.php')) . '">Tin tức &amp; Ưu đãi</a>. '
        . 'Muốn đổi câu chữ cố định trong trang thì nhờ người lập trình.</p></div>';
});

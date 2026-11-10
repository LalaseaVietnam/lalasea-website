<?php
/**
 * Dữ liệu dùng chung (tên quán, giờ mở cửa, menu, glamping...).
 * Nằm trong data/*.json, mỗi file tương ứng một file trong src/_data/ của bản Eleventy.
 */
defined('ABSPATH') || exit;

function ls_data($ten)
{
    static $cache = [];
    if (!isset($cache[$ten])) {
        $duong_dan = get_theme_file_path('data/' . $ten . '.json');
        $json = is_readable($duong_dan) ? json_decode(file_get_contents($duong_dan), true) : null;
        $cache[$ten] = apply_filters('ls_data', is_array($json) ? $json : [], $ten);
    }
    return $cache[$ten];
}

// Các bài viết, mới nhất lên đầu — cùng dạng dữ liệu với collections.baiviet cũ
function ls_danh_sach_bai()
{
    static $ds = null;
    if ($ds === null) {
        $ds = [];
        $q = new WP_Query([
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'no_found_rows' => true,
            'ignore_sticky_posts' => true,
        ]);
        foreach ($q->posts as $p) {
            $ds[] = ls_mo_ta_bai($p);
        }
        wp_reset_postdata();
    }
    return $ds;
}

function ls_mo_ta_bai($p)
{
    // Mô tả ngắn = ô "Mô tả ngắn" (excerpt) có sẵn của WordPress
    $mo_ta = $p->post_excerpt !== '' ? $p->post_excerpt : (string) get_post_meta($p->ID, '_ls_mo_ta', true);

    // Ảnh đại diện: ảnh chọn trong WordPress, nếu không có thì đường dẫn của bài nhập từ web cũ
    $anh = has_post_thumbnail($p) ? wp_make_link_relative((string) wp_get_attachment_image_url(get_post_thumbnail_id($p), 'full')) : '';
    if ($anh === '') {
        $anh = (string) get_post_meta($p->ID, '_ls_anh_dai_dien', true);
    }
    return [
        'url' => wp_make_link_relative(get_permalink($p)),
        'date' => $p->post_date_gmt,
        'data' => [
            // Tiêu đề gốc, không qua get_the_title() — hàm đó đổi "&" thành "&#038;" và
            // nháy thẳng thành nháy cong. Template tự escape khi in ra.
            'title' => wp_specialchars_decode($p->post_title, ENT_QUOTES),
            'mo_ta' => $mo_ta,
            'anh_dai_dien' => $anh,
        ],
    ];
}

// Bài đang xem (single.php)
function ls_bai_hien_tai()
{
    $p = get_post();
    $bai = ls_mo_ta_bai($p);
    return [
        'title' => $bai['data']['title'],
        'mo_ta' => $bai['data']['mo_ta'],
        'anh_dai_dien' => $bai['data']['anh_dai_dien'],
        'date' => $bai['date'],
        'url' => $bai['url'],
        'noi_dung' => ls_noi_dung($p->post_content),
    ];
}

// Hiển thị nội dung bài: xử lý khối Gutenberg + shortcode nhưng KHÔNG tự thêm <p>/<br>,
// không đổi dấu nháy/gạch ngang, không gắn lazy-load — để chữ ra đúng như tác giả viết.
function ls_noi_dung($html)
{
    return do_shortcode(do_blocks($html));
}

// Biến dùng chung cho mọi template: $thongtin, $glamping, $menuanh, $danhgia, $thuvien, $baiviet
function ls_bien_toan_cuc()
{
    return [
        'thongtin' => ls_data('thongtin'),
        'glamping' => ls_data('glamping'),
        'menuanh' => ls_data('menuanh'),
        'danhgia' => ls_data('danhgia'),
        'thuvien' => ls_data('thuvien'),
        'baiviet' => ls_danh_sach_bai(),
    ];
}

<?php
/**
 * Dựng sẵn toàn bộ trang và bài viết khi kích hoạt theme lần đầu:
 *  - 5 trang tiếng Việt + 5 trang tiếng Anh (/en/...), gắn đúng template;
 *  - các bài tin tức hiện có (seed/baiviet.json);
 *  - đặt trang chủ, trang tin tức, cấu trúc đường dẫn giống hệt web cũ.
 * Chạy lại nhiều lần vẫn an toàn: trang/bài đã có thì bỏ qua, không ghi đè.
 *
 * Chạy tay:  wp eval 'ls_cai_dat_mau();'
 */
defined('ABSPATH') || exit;

function ls_cai_dat_mau()
{
    // Thiết lập chung (làm trước, vì múi giờ ảnh hưởng ngày đăng bài) — đường dẫn bài viết
    // giữ nguyên /tin-tuc/<slug>/ như web cũ
    update_option('show_on_front', 'page');
    update_option('permalink_structure', '/tin-tuc/%postname%/');
    update_option('timezone_string', 'Asia/Ho_Chi_Minh');
    update_option('blogname', 'Lalasea Bistro');
    update_option('blogdescription', '');
    update_option('default_comment_status', 'closed');
    update_option('default_ping_status', 'closed');
    update_option('date_format', 'd/m/Y');
    update_option('time_format', 'H:i');
    update_option('start_of_week', 1);
    $cay = [
        ['slug' => 'trang-chu', 'title' => 'Trang chủ', 'tpl' => 'templates/trang-chu.php', 'vai_tro' => 'front'],
        ['slug' => 'thuc-don', 'title' => 'Thực đơn', 'tpl' => 'templates/thuc-don.php'],
        ['slug' => 'glamping', 'title' => 'Glamping', 'tpl' => 'templates/glamping.php'],
        ['slug' => 'tiec-su-kien', 'title' => 'Tiệc & Sự kiện', 'tpl' => 'templates/tiec-su-kien.php'],
        ['slug' => 'lien-he', 'title' => 'Liên hệ', 'tpl' => 'templates/lien-he.php'],
        ['slug' => 'tin-tuc', 'title' => 'Tin tức & Ưu đãi', 'tpl' => '', 'vai_tro' => 'posts'],
        ['slug' => 'en', 'title' => 'English — Home', 'tpl' => 'templates/en-trang-chu.php', 'con' => [
            ['slug' => 'menu', 'title' => 'English — Menu', 'tpl' => 'templates/en-menu.php'],
            ['slug' => 'glamping', 'title' => 'English — Glamping', 'tpl' => 'templates/en-glamping.php'],
            ['slug' => 'events', 'title' => 'English — Events', 'tpl' => 'templates/en-events.php'],
            ['slug' => 'contact', 'title' => 'English — Contact', 'tpl' => 'templates/en-contact.php'],
        ]],
    ];

    $tao = function ($m, $cha_id, $duong_cha) use (&$tao) {
        $duong = ltrim($duong_cha . '/' . $m['slug'], '/');
        $co = get_page_by_path($duong);
        $id = $co ? $co->ID : wp_insert_post([
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_title' => $m['title'],
            'post_name' => $m['slug'],
            'post_parent' => $cha_id,
            'post_content' => '',
            'meta_input' => $m['tpl'] !== '' ? ['_wp_page_template' => $m['tpl']] : [],
        ]);
        foreach ($m['con'] ?? [] as $con) {
            $tao($con, $id, $duong);
        }
        return $id;
    };

    foreach ($cay as $m) {
        $id = $tao($m, 0, '');
        if (($m['vai_tro'] ?? '') === 'front') {
            update_option('page_on_front', $id);
        }
        if (($m['vai_tro'] ?? '') === 'posts') {
            update_option('page_for_posts', $id);
        }
    }

    // Bài viết
    $tep = get_theme_file_path('seed/baiviet.json');
    $bai = is_readable($tep) ? json_decode(file_get_contents($tep), true) : [];
    foreach ($bai as $b) {
        $da_co = get_posts(['name' => $b['slug'], 'post_type' => 'post', 'post_status' => 'any', 'numberposts' => 1]);
        if ($da_co) {
            continue;
        }
        $id = wp_insert_post([
            'post_type' => 'post',
            'post_status' => 'publish',
            'post_title' => $b['title'],
            'post_name' => $b['slug'],
            'post_content' => $b['noi_dung'],
            'post_date_gmt' => $b['date'] . ' 00:00:00',
            'post_date' => get_date_from_gmt($b['date'] . ' 00:00:00'),
        ]);
        if ($id && !is_wp_error($id)) {
            update_post_meta($id, '_ls_mo_ta', $b['mo_ta'] ?? '');
            update_post_meta($id, '_ls_anh_dai_dien', $b['anh_dai_dien'] ?? '');
        }
    }

    // Dọn nội dung mẫu của WordPress
    foreach ([['hello-world', 'post'], ['sample-page', 'page']] as [$slug, $loai]) {
        $mau = get_posts(['name' => $slug, 'post_type' => $loai, 'numberposts' => 1]);
        if ($mau) {
            wp_delete_post($mau[0]->ID, true);
        }
    }

    flush_rewrite_rules();
}

add_action('after_switch_theme', 'ls_cai_dat_mau');

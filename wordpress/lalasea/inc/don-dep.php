<?php
/**
 * Dọn những thứ WordPress tự thêm vào trang (emoji, RSS, generator, kiểu CSS khối...)
 * để HTML ra đúng như web cũ, và đóng bớt các cửa tấn công hay bị dò.
 */
defined('ABSPATH') || exit;

add_action('after_setup_theme', function () {
    // Chỉ để tag <title> do header.php tự in (có cấu trúc riêng theo ngôn ngữ)
    remove_theme_support('title-tag');
});

add_action('init', function () {
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_action('wp_head', 'rel_canonical');           // header.php tự in canonical
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
    remove_action('wp_head', 'wp_resource_hints', 2);
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'wp_robots', 1);             // header.php tự in robots khi cần
    remove_action('template_redirect', 'rest_output_link_header', 11);
    remove_action('template_redirect', 'wp_shortlink_header', 11);
    // CSS khối Gutenberg và "global styles" — web không dùng
    remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
    remove_action('wp_footer', 'wp_enqueue_global_styles', 1);
    remove_action('wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles');
    remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
    // CSS sửa ảnh sizes="auto" (web không dùng) và script tải trước trang của WordPress
    remove_action('wp_head', 'wp_enqueue_img_auto_sizes_contain_css_fix', 0);
    remove_action('wp_head', 'wp_print_auto_sizes_contain_css_fix', 1);
    remove_action('wp_footer', 'wp_print_speculation_rules');
});

add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('global-styles');
}, 100);

// Thanh công cụ nổi của WordPress làm lệch menu dính — chủ quán vào /wp-admin để sửa
add_filter('show_admin_bar', '__return_false');

// Web không có bình luận
add_filter('comments_open', '__return_false', 20);
add_filter('pings_open', '__return_false', 20);
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

// Bảo mật: tắt XML-RPC, không để lộ tên đăng nhập qua REST và ?author=1
add_filter('xmlrpc_enabled', '__return_false');
add_filter('xmlrpc_methods', '__return_empty_array'); // tắt cả pingback — hay bị dùng để tấn công DDoS
add_filter('rest_endpoints', function ($e) {
    if (!is_user_logged_in()) {
        unset($e['/wp/v2/users'], $e['/wp/v2/users/(?P<id>[\d]+)']);
    }
    return $e;
});
add_action('template_redirect', function () {
    if (is_author() || isset($_GET['author'])) {
        wp_redirect(home_url('/'), 301);
        exit;
    }
}, 0);
add_filter('login_errors', function () {
    return 'Tên đăng nhập hoặc mật khẩu không đúng.';
});

// Trong trang quản trị, "Bài viết" gọi đúng tên của web: Tin tức & Ưu đãi
add_filter('post_type_labels_post', function ($l) {
    $l->name = 'Tin tức & Ưu đãi';
    $l->menu_name = 'Tin tức & Ưu đãi';
    $l->all_items = 'Tất cả bài';
    $l->add_new = 'Viết bài mới';
    $l->add_new_item = 'Viết bài mới';
    $l->edit_item = 'Sửa bài viết';
    $l->singular_name = 'Bài viết';
    return $l;
});

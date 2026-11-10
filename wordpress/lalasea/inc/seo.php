<?php
/**
 * Chuyển hướng trang cũ (301 thật), sitemap.xml, robots.txt —
 * thay cho chuyen-huong.njk, sitemap.njk và robots.njk của bản Eleventy.
 */
defined('ABSPATH') || exit;

/* ---------- Chuyển hướng 301 các đường dẫn cũ ----------
 * Danh sách ở data/chuyenhuong.json. Bản Eleventy chỉ làm được trang mồi
 * meta-refresh (GitHub Pages không có 301); WordPress làm 301 thật nên
 * Google chuyển toàn bộ thứ hạng sang địa chỉ mới nhanh hơn. */
add_action('template_redirect', function () {
    $duong = '/' . trim((string) wp_parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
    $duong = rtrim($duong, '/') . '/';
    foreach (ls_data('chuyenhuong')['danh_sach'] ?? [] as $ch) {
        $cu = rtrim($ch['cu'], '/') . '/';
        if ($cu === $duong && !empty($ch['moi'])) {
            wp_redirect(home_url($ch['moi']), 301);
            exit;
        }
    }
}, 1);

/* ---------- sitemap.xml ---------- */
add_filter('wp_sitemaps_enabled', '__return_false');

add_action('init', function () {
    add_rewrite_rule('^sitemap\.xml$', 'index.php?ls_sitemap=1', 'top');
});
add_filter('query_vars', function ($v) {
    $v[] = 'ls_sitemap';
    return $v;
});
add_filter('redirect_canonical', function ($url) {
    return get_query_var('ls_sitemap') ? false : $url;
});

add_action('template_redirect', function () {
    if (!get_query_var('ls_sitemap')) {
        return;
    }
    $goc = rtrim(ls_data('thongtin')['site_url'] ?? home_url(), '/');
    $muc = get_posts([
        'post_type' => ['page', 'post'],
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => 'menu_order date',
        'order' => 'ASC',
    ]);
    $trang_chu = (int) get_option('page_on_front');
    header('Content-Type: application/xml; charset=UTF-8');
    echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($muc as $p) {
        $url = $p->ID === $trang_chu ? '/' : wp_make_link_relative(get_permalink($p));
        echo "  <url>\n    <loc>" . esc_html($goc . $url) . "</loc>\n";
        echo '    <lastmod>' . esc_html(substr($p->post_modified_gmt, 0, 10)) . "</lastmod>\n  </url>\n";
    }
    echo "</urlset>\n";
    exit;
});

/* ---------- robots.txt ---------- */
add_filter('robots_txt', function () {
    $goc = rtrim(ls_data('thongtin')['site_url'] ?? home_url(), '/');
    return "User-agent: *\nAllow: /\nDisallow: /wp-admin/\nAllow: /wp-admin/admin-ajax.php\nSitemap: {$goc}/sitemap.xml\n";
}, 99);

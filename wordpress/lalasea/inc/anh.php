<?php
/**
 * Gắn width/height cho mọi thẻ <img> chưa có — tương đương transform
 * "kichThuocAnh" trong eleventy.config.js. Trình duyệt chừa sẵn chỗ cho ảnh
 * nên trang không bị nhảy khi ảnh tải xong. Làm trên toàn bộ HTML để bắt được
 * cả ảnh trong bài viết do chủ quán chèn sau này.
 */
defined('ABSPATH') || exit;

function ls_kich_thuoc_anh($duong)
{
    static $cache = [];
    if (!array_key_exists($duong, $cache)) {
        $tep = ABSPATH . ltrim(rawurldecode(strtok($duong, '?')), '/');
        $kt = is_readable($tep) ? @getimagesize($tep) : false;
        $cache[$duong] = $kt && $kt[0] && $kt[1] ? [$kt[0], $kt[1]] : null;
    }
    return $cache[$duong];
}

function ls_gan_kich_thuoc_anh($html)
{
    return preg_replace_callback('/<img\b[^>]*>/', function ($m) {
        $the = $m[0];
        if (preg_match('/\b(width|height)=/', $the)) {
            return $the;
        }
        if (!preg_match('/\bsrc="(\/[^"]+)"/', $the, $s)) {
            return $the;
        }
        $kt = ls_kich_thuoc_anh($s[1]);
        if (!$kt) {
            return $the;
        }
        return preg_replace('/<img\b/', '<img width="' . $kt[0] . '" height="' . $kt[1] . '"', $the, 1);
    }, $html);
}

add_action('template_redirect', function () {
    if (!is_feed() && !is_robots() && !get_query_var('ls_sitemap')) {
        ob_start('ls_gan_kich_thuoc_anh');
    }
}, 99);

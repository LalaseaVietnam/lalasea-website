<?php
/**
 * Thông tin của trang đang hiển thị (tiêu đề, mô tả, bản dịch...) —
 * tương ứng "front matter" của file .njk cũ. Template trang gọi ls_dat_trang()
 * trước get_header(), header.php/footer.php đọc lại bằng ls_trang().
 */
defined('ABSPATH') || exit;

function ls_dat_trang(array $p)
{
    $GLOBALS['ls_trang'] = array_merge([
        'title' => null,
        'mo_ta' => null,
        'anh_dai_dien' => null,
        'ban_dich' => null,
        'khong_index' => false,
        'an_footer' => false,
        'ngon_ngu' => 'vi',
        'og_type' => 'website',
        'url' => null,
    ], $p);
}

function ls_trang()
{
    if (!isset($GLOBALS['ls_trang'])) {
        ls_dat_trang([]);
    }
    return $GLOBALS['ls_trang'];
}

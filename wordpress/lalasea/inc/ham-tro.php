<?php
/**
 * Hàm hỗ trợ cho các template sinh tự động từ Nunjucks.
 * Mỗi hàm tương ứng đúng một "bộ lọc" hoặc cú pháp trong bản Eleventy cũ
 * (xem eleventy.config.js) để HTML xuất ra giống nhau từng ký tự.
 */
defined('ABSPATH') || exit;

/* ---------- Xuất ra ---------- */

// Nunjucks autoescape: & < > " ' → thực thể HTML (dấu ' thành &#39;)
function ls_e($v)
{
    if ($v === null || $v === false) {
        return '';
    }
    return strtr((string) $v, ['&' => '&amp;', '<' => '&lt;', '>' => '&gt;', '"' => '&quot;', "'" => '&#39;']);
}

// Bộ lọc | safe — in nguyên văn
function ls_raw($v)
{
    return $v === null || $v === false ? '' : (string) $v;
}

/* ---------- Truy cập dữ liệu (an toàn như Nunjucks: thiếu thì trả rỗng) ---------- */

function ls_lay($v, $k)
{
    if ($k === 'length' && !(is_array($v) && array_key_exists('length', $v))) {
        if (is_array($v)) {
            return count($v);
        }
        if (is_string($v)) {
            return mb_strlen($v);
        }
        return null;
    }
    if (is_array($v)) {
        return $v[$k] ?? null;
    }
    return null;
}

// Gọi phương thức JavaScript mà template cũ có dùng
function ls_goi($obj, $ten, $args = [])
{
    switch ($ten) {
        case 'toISOString':
            return ls_iso($obj);
        case 'split':
            return explode($args[0], (string) $obj);
        case 'endsWith':
            return str_ends_with((string) $obj, (string) $args[0]);
    }
    return null;
}

function ls_lap($v)
{
    return is_array($v) ? array_values($v) : [];
}

function ls_range($a, $b)
{
    return $b > $a ? range($a, $b - 1) : [];
}

// Toán tử + của JavaScript: có chuỗi thì nối, không thì cộng số
function ls_cong($a, $b)
{
    if (is_string($a) || is_string($b)) {
        return $a . $b;
    }
    return $a + $b;
}

/* ---------- Bộ lọc ---------- */

function ls_replace($s, $tim, $thay, $so_lan = -1)
{
    return preg_replace('/' . preg_quote((string) $tim, '/') . '/', (string) $thay, (string) $s, (int) $so_lan);
}

// "0907 896 949" → "+84 907 896 949"
function ls_sdt_quoc_te($sdt)
{
    return preg_replace('/^0/', '+84 ', (string) $sdt);
}

// "690.000đ/người" → "690,000 VND per person"
function ls_gia_en($gia)
{
    $g = (string) $gia;
    $g = preg_replace('/(\d)\.(?=\d{3})/', '$1,', $g);
    $g = preg_replace('/\s*đ/u', ' VND', $g);
    $g = preg_replace('/\s*\/\s*người/u', ' per person', $g);
    return $g;
}

function ls_dump($v)
{
    return json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

/* ---------- Ngày tháng (đầu vào: chuỗi giờ GMT "2026-09-10 00:00:00") ---------- */

function ls_thoi_diem($d)
{
    $t = is_numeric($d) ? (int) $d : strtotime($d . ' UTC');
    return new DateTimeImmutable('@' . $t);
}

function ls_ngay_dia_phuong($d)
{
    return ls_thoi_diem($d)->setTimezone(wp_timezone());
}

function ls_ngay_vn($d)
{
    return ls_ngay_dia_phuong($d)->format('d/m/Y');
}

function ls_ngay_chu($d)
{
    $x = ls_ngay_dia_phuong($d);
    return (int) $x->format('j') . ' tháng ' . (int) $x->format('n') . ', ' . $x->format('Y');
}

// Giống Date.toISOString() của JavaScript
function ls_iso($d)
{
    return ls_thoi_diem($d)->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d\TH:i:s.000\Z');
}

<?php
/**
 * Trang quản trị "Thông tin nhà hàng" trong /wp-admin.
 *
 * Các ô nhập được dựng tự động từ data/bieu-mau.json (sinh từ cấu hình trang
 * quản trị cũ src/quan-tri/config.yml) — cùng nhãn, cùng thứ tự như trước.
 * Dữ liệu lưu vào bảng tuỳ chọn của WordPress (ls_du_lieu_<tên>) và thay cho
 * file data/<tên>.json kể từ lần lưu đầu tiên.
 */
defined('ABSPATH') || exit;

const LS_QUYEN_SUA = 'edit_pages'; // Quản trị viên và Biên tập viên

function ls_bieu_mau()
{
    static $bm = null;
    if ($bm === null) {
        $bm = json_decode((string) file_get_contents(get_theme_file_path('data/bieu-mau.json')), true) ?: [];
    }
    return $bm;
}

// Dữ liệu đã lưu trong WordPress được ưu tiên hơn file JSON đi kèm theme
add_filter('ls_data', function ($du_lieu, $ten) {
    $luu = get_option('ls_du_lieu_' . $ten, null);
    return is_array($luu) ? $luu : $du_lieu;
}, 10, 2);

/* ---------- Menu ---------- */
add_action('admin_menu', function () {
    $ds = ls_bieu_mau();
    if (!$ds) {
        return;
    }
    add_menu_page('Thông tin nhà hàng', 'Thông tin nhà hàng', LS_QUYEN_SUA, 'ls-' . $ds[0]['name'], 'ls_man_hinh_du_lieu', 'dashicons-store', 3);
    foreach ($ds as $bm) {
        add_submenu_page('ls-' . $ds[0]['name'], $bm['label'], $bm['label'], LS_QUYEN_SUA, 'ls-' . $bm['name'], 'ls_man_hinh_du_lieu');
    }
});

function ls_bm_hien_tai()
{
    $trang = sanitize_key($_GET['page'] ?? '');
    foreach (ls_bieu_mau() as $bm) {
        if ('ls-' . $bm['name'] === $trang) {
            return $bm;
        }
    }
    return null;
}

/* ---------- Vẽ ô nhập ---------- */
function ls_ve_o($f, $ten_o, $gia_tri, $sau)
{
    $id = 'o' . substr(md5($ten_o . wp_rand()), 0, 8);
    $nhan = '<label for="' . esc_attr($id) . '"><strong>' . esc_html($f['label']) . '</strong></label>';
    switch ($f['widget']) {
        case 'list':
            return ls_ve_danh_sach($f, $ten_o, is_array($gia_tri) ? $gia_tri : [], $sau);
        case 'text':
            return '<div class="ls-o">' . $nhan . '<textarea id="' . esc_attr($id) . '" name="' . esc_attr($ten_o) . '" rows="3" class="large-text">' . esc_textarea((string) $gia_tri) . '</textarea></div>';
        case 'number':
            $gt = $gia_tri === null || $gia_tri === '' ? ($f['default'] ?? '') : $gia_tri;
            return '<div class="ls-o">' . $nhan . '<input type="number" id="' . esc_attr($id) . '" name="' . esc_attr($ten_o) . '" value="' . esc_attr((string) $gt) . '"'
                . (isset($f['min']) ? ' min="' . (int) $f['min'] . '"' : '') . (isset($f['max']) ? ' max="' . (int) $f['max'] . '"' : '') . ' class="small-text"></div>';
        case 'image':
            $xem = $gia_tri ? '<img src="' . esc_url(home_url((string) $gia_tri)) . '" alt="">' : '';
            return '<div class="ls-o ls-anh">' . $nhan . '<div class="ls-anh-hang"><span class="ls-xem">' . $xem . '</span>'
                . '<input type="text" id="' . esc_attr($id) . '" name="' . esc_attr($ten_o) . '" value="' . esc_attr((string) $gia_tri) . '" class="regular-text" placeholder="/anh/ten-anh.jpg">'
                . ' <button type="button" class="button ls-chon-anh">Chọn ảnh</button></div></div>';
        default:
            return '<div class="ls-o">' . $nhan . '<input type="text" id="' . esc_attr($id) . '" name="' . esc_attr($ten_o) . '" value="' . esc_attr((string) $gia_tri) . '" class="large-text"></div>';
    }
}

function ls_ve_danh_sach($f, $ten_o, $ds, $sau)
{
    $don_vi = $f['label_singular'] ?? 'mục';
    $ma = '__i' . $sau . '__';
    $mot_muc = function ($gt, $chi_so) use ($f, $ten_o, $sau, $don_vi) {
        $h = '<div class="ls-muc"><div class="ls-muc-nut">'
            . '<button type="button" class="button-link ls-len" aria-label="Chuyển lên">▲</button>'
            . '<button type="button" class="button-link ls-xuong" aria-label="Chuyển xuống">▼</button>'
            . '<button type="button" class="button-link ls-xoa">Xoá ' . esc_html($don_vi) . '</button></div>';
        if (isset($f['field'])) {
            // Danh sách chữ đơn (ví dụ: các món ăn sáng)
            $h .= ls_ve_o(array_merge($f['field'], ['widget' => $f['field']['widget'] ?? 'string']), $ten_o . '[' . $chi_so . ']', $gt, $sau + 1);
        } else {
            foreach ($f['fields'] as $con) {
                $h .= ls_ve_o($con, $ten_o . '[' . $chi_so . '][' . $con['name'] . ']', is_array($gt) ? ($gt[$con['name']] ?? null) : null, $sau + 1);
            }
        }
        return $h . '</div>';
    };
    $h = '<fieldset class="ls-ds" data-ma="' . esc_attr($ma) . '"><legend>' . esc_html($f['label']) . '</legend><div class="ls-cac-muc">';
    foreach (array_values($ds) as $i => $gt) {
        $h .= $mot_muc($gt, $i);
    }
    $h .= '</div><template>' . $mot_muc(null, $ma) . '</template>'
        . '<button type="button" class="button ls-them">+ Thêm ' . esc_html($don_vi) . '</button>'
        // Ô ẩn để danh sách rỗng vẫn được gửi lên (xoá hết mục thì lưu thành [])
        . '<input type="hidden" name="' . esc_attr($ten_o) . '[__rong__]" value="1"></fieldset>';
    return $h;
}

/* ---------- Màn hình ---------- */
function ls_man_hinh_du_lieu()
{
    $bm = ls_bm_hien_tai();
    if (!$bm || !current_user_can(LS_QUYEN_SUA)) {
        wp_die('Không có quyền truy cập.');
    }
    $du_lieu = ls_data($bm['name']);
    ?>
    <div class="wrap ls-quan-tri">
        <h1><?php echo esc_html($bm['label']); ?></h1>
        <?php if (isset($_GET['da-luu'])) : ?>
            <div class="notice notice-success is-dismissible"><p>Đã lưu. Web cập nhật ngay — mở trang để xem.</p></div>
        <?php endif; ?>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="ls_luu_du_lieu">
            <input type="hidden" name="ls_ten" value="<?php echo esc_attr($bm['name']); ?>">
            <?php wp_nonce_field('ls_luu_' . $bm['name']); ?>
            <?php foreach ($bm['fields'] as $f) {
                echo ls_ve_o($f, 'ls[' . $f['name'] . ']', $du_lieu[$f['name']] ?? null, 0); // phpcs:ignore -- đã escape trong hàm
            } ?>
            <?php submit_button('Lưu thay đổi'); ?>
        </form>
    </div>
    <?php
}

add_action('admin_enqueue_scripts', function () {
    if (!ls_bm_hien_tai()) {
        return;
    }
    wp_enqueue_media();
    wp_add_inline_style('common', '
        .ls-quan-tri .ls-o{margin:0 0 14px}
        .ls-quan-tri .ls-o label{display:block;margin-bottom:4px}
        .ls-quan-tri .ls-ds{border:1px solid #c3c4c7;border-radius:6px;padding:10px 14px 14px;margin:0 0 18px;background:#fff}
        .ls-quan-tri .ls-ds legend{font-weight:600;padding:0 6px}
        .ls-quan-tri .ls-muc{border-left:3px solid #1b9aaa;background:#f6f7f7;padding:10px 12px;margin:0 0 10px;border-radius:4px}
        .ls-quan-tri .ls-muc .ls-ds{background:#fff}
        .ls-quan-tri .ls-muc-nut{display:flex;gap:12px;justify-content:flex-end;margin-bottom:4px}
        .ls-quan-tri .ls-xoa{color:#b32d2e}
        .ls-quan-tri .ls-anh-hang{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
        .ls-quan-tri .ls-xem img{width:64px;height:48px;object-fit:cover;border-radius:4px;display:block}
        .ls-quan-tri .ls-muc-nut button{padding:4px 6px}
        @media (max-width:782px){.ls-quan-tri .ls-muc-nut button{min-width:44px;min-height:44px;font-size:16px}}
    ');
    wp_add_inline_script('jquery-core', '
        jQuery(function ($) {
            var dem = 0;
            $(document).on("click", ".ls-them", function () {
                var ds = $(this).closest(".ls-ds"), ma = ds.data("ma");
                var html = ds.children("template").html().split(ma).join("m" + Date.now() + (dem++));
                ds.children(".ls-cac-muc").append(html);
            });
            $(document).on("click", ".ls-xoa", function () {
                if (confirm("Xoá mục này?")) $(this).closest(".ls-muc").remove();
            });
            $(document).on("click", ".ls-len", function () {
                var m = $(this).closest(".ls-muc"); m.prev(".ls-muc").before(m);
            });
            $(document).on("click", ".ls-xuong", function () {
                var m = $(this).closest(".ls-muc"); m.next(".ls-muc").after(m);
            });
            $(document).on("click", ".ls-chon-anh", function (e) {
                e.preventDefault();
                var hang = $(this).closest(".ls-anh-hang");
                var khung = wp.media({ title: "Chọn ảnh", multiple: false, library: { type: "image" } });
                khung.on("select", function () {
                    var url = khung.state().get("selection").first().toJSON().url;
                    var goc = ' . wp_json_encode(home_url()) . ';
                    if (url.indexOf(goc) === 0) url = url.slice(goc.length);
                    hang.find("input").val(url);
                    hang.find(".ls-xem").html($("<img alt>").attr("src", goc + url));
                });
                khung.open();
            });
        });
    ');
});

/* ---------- Lưu ---------- */
function ls_lam_sach($f, $gt)
{
    switch ($f['widget']) {
        case 'list':
            $gt = is_array($gt) ? $gt : [];
            unset($gt['__rong__']);
            $ra = [];
            foreach ($gt as $muc) {
                if (isset($f['field'])) {
                    $v = ls_lam_sach(array_merge($f['field'], ['widget' => $f['field']['widget'] ?? 'string']), $muc);
                    if ($v !== '') {
                        $ra[] = $v;
                    }
                } else {
                    $dong = [];
                    $co_chu = false;
                    foreach ($f['fields'] as $con) {
                        $dong[$con['name']] = ls_lam_sach($con, is_array($muc) ? ($muc[$con['name']] ?? null) : null);
                        $co_chu = $co_chu || ($dong[$con['name']] !== '' && $dong[$con['name']] !== [] && $con['widget'] !== 'number');
                    }
                    if ($co_chu) {
                        $ra[] = $dong; // bỏ mục trống hoàn toàn
                    }
                }
            }
            return $ra;
        case 'number':
            return (int) $gt;
        // Không dùng sanitize_text_field(): hàm đó xoá các chuỗi kiểu "%20" làm hỏng link Maps/Zalo
        case 'text':
            return trim(wp_strip_all_tags(wp_unslash((string) $gt), false));
        case 'image':
            $u = trim(wp_unslash((string) $gt));
            if ($u !== '' && strpos($u, home_url()) === 0) {
                $u = wp_make_link_relative($u);
            }
            return $u === '' ? '' : esc_url_raw($u);
        default:
            return trim(wp_strip_all_tags(wp_unslash((string) $gt), true));
    }
}

add_action('admin_post_ls_luu_du_lieu', function () {
    $ten = sanitize_key($_POST['ls_ten'] ?? '');
    $bm = null;
    foreach (ls_bieu_mau() as $b) {
        if ($b['name'] === $ten) {
            $bm = $b;
        }
    }
    if (!$bm || !current_user_can(LS_QUYEN_SUA)) {
        wp_die('Không có quyền.');
    }
    check_admin_referer('ls_luu_' . $ten);

    $vao = $_POST['ls'] ?? []; // phpcs:ignore -- được làm sạch từng ô trong ls_lam_sach()
    // Giữ nguyên các khoá không có ô nhập (ví dụ toạ độ vi_do/kinh_do), chỉ ghi đè các ô trên form
    $moi = ls_data($ten);
    foreach ($bm['fields'] as $f) {
        $moi[$f['name']] = ls_lam_sach($f, $vao[$f['name']] ?? null);
    }
    update_option('ls_du_lieu_' . $ten, $moi, false);
    wp_safe_redirect(admin_url('admin.php?page=ls-' . $ten . '&da-luu=1'));
    exit;
});

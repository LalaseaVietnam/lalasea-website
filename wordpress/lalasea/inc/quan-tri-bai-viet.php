<?php
/**
 * Hai ô nhập thêm khi viết bài Tin tức: Mô tả ngắn + Ảnh đại diện.
 * (Tương ứng hai trường mo_ta và anh_dai_dien của bài viết trong trang quản trị cũ.)
 * Mô tả ngắn hiện ở danh sách tin, kết quả Google và khi chia sẻ lên Facebook/Zalo.
 */
defined('ABSPATH') || exit;

add_action('add_meta_boxes', function () {
    add_meta_box('ls_thong_tin_bai', 'Hiển thị trên web & Google', 'ls_hop_thong_tin_bai', 'post', 'normal', 'high');
});

function ls_hop_thong_tin_bai($post)
{
    wp_nonce_field('ls_luu_bai', 'ls_nonce');
    $mo_ta = get_post_meta($post->ID, '_ls_mo_ta', true);
    $anh = get_post_meta($post->ID, '_ls_anh_dai_dien', true);
    ?>
    <p>
        <label for="ls_mo_ta"><strong>Mô tả ngắn</strong></label><br>
        <textarea id="ls_mo_ta" name="ls_mo_ta" rows="3" style="width:100%" maxlength="320"><?php echo esc_textarea($mo_ta); ?></textarea><br>
        <span class="description">1–2 câu tóm tắt bài (khoảng 150 ký tự). Hiện ở danh sách tin, trên Google và khi chia sẻ.</span>
    </p>
    <p>
        <label for="ls_anh_dai_dien"><strong>Ảnh đại diện</strong></label><br>
        <input type="text" id="ls_anh_dai_dien" name="ls_anh_dai_dien" value="<?php echo esc_attr($anh); ?>" style="width:75%" placeholder="/anh/ten-anh.jpg">
        <button type="button" class="button" id="ls_chon_anh">Chọn ảnh</button>
        <br><span class="description">Ảnh ngang tỉ lệ 4:3, rộng tối thiểu 1200px. Có thể để trống.</span>
    </p>
    <script>
    jQuery(function ($) {
        var khung;
        $('#ls_chon_anh').on('click', function (e) {
            e.preventDefault();
            if (!khung) {
                khung = wp.media({ title: 'Chọn ảnh đại diện', multiple: false, library: { type: 'image' } });
                khung.on('select', function () {
                    $('#ls_anh_dai_dien').val(khung.state().get('selection').first().toJSON().url);
                });
            }
            khung.open();
        });
    });
    </script>
    <?php
}

add_action('admin_enqueue_scripts', function ($hook) {
    if (in_array($hook, ['post.php', 'post-new.php'], true)) {
        wp_enqueue_media();
    }
});

add_action('save_post_post', function ($post_id) {
    if (!isset($_POST['ls_nonce']) || !wp_verify_nonce($_POST['ls_nonce'], 'ls_luu_bai')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    update_post_meta($post_id, '_ls_mo_ta', sanitize_textarea_field(wp_unslash($_POST['ls_mo_ta'] ?? '')));

    // Ảnh chọn từ thư viện có địa chỉ đầy đủ — đổi về dạng /duong-dan để dùng chung với schema
    $anh = esc_url_raw(wp_unslash($_POST['ls_anh_dai_dien'] ?? ''));
    if ($anh !== '' && strpos($anh, home_url()) === 0) {
        $anh = wp_make_link_relative($anh);
    }
    update_post_meta($post_id, '_ls_anh_dai_dien', $anh);
});

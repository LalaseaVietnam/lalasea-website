<?php
/**
 * Trang thường do chủ quán tự tạo thêm trong Trang → Thêm mới
 * (các trang chính của web dùng template riêng trong templates/).
 * Dùng lại khung tiêu đề + nội dung của bài viết cho đồng bộ giao diện.
 */
the_post();
extract(ls_bien_toan_cuc());
$tieu_de = get_the_title();
ls_dat_trang(['title' => $tieu_de, 'mo_ta' => wp_strip_all_tags(get_the_excerpt()) ?: null, 'url' => wp_make_link_relative(get_permalink())]);
get_header();
?>
<article>
  <div class="post-hero">
    <h1><?= ls_e($tieu_de) ?></h1>
  </div>
  <div class="post-body">
    <?= ls_noi_dung(get_the_content()) ?>
  </div>
</article>
<?php get_footer();

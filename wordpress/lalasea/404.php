<?php
// TỰ SINH từ src/404.njk bằng wordpress/tools/njk-sang-php.mjs — HTML giữ nguyên văn.
extract(ls_bien_toan_cuc());
$en = false;
$title = 'Không tìm thấy trang';
$mo_ta = null;
$anh_dai_dien = null;
ls_dat_trang(['title' => 'Không tìm thấy trang', 'mo_ta' => null, 'anh_dai_dien' => null, 'ban_dich' => null, 'khong_index' => true, 'an_footer' => false, 'ngon_ngu' => 'vi', 'url' => null]);
get_header();
?>
<div class="page-404">
  <h1>404</h1>
  <p>Trang bạn tìm không tồn tại hoặc đã được chuyển đi nơi khác.</p>
  <a href="/" class="btn">← Về trang chủ</a>
  <p lang="en" style="margin-top:20px;">Page not found. <a href="/en/">Go to the English homepage →</a></p>
</div>

<?php get_footer();

<?php
// TỰ SINH từ src/tin-tuc.njk bằng wordpress/tools/njk-sang-php.mjs — HTML giữ nguyên văn.
extract(ls_bien_toan_cuc());
$en = false;
$title = 'Tin tức & Ưu đãi';
$mo_ta = 'Tin tức, sự kiện, khuyến mãi mới nhất tại nhà hàng Lalasea Bistro Hà Tiên — ưu đãi đặt tiệc, sự kiện bên biển, món mới theo mùa.';
$anh_dai_dien = null;
ls_dat_trang(['title' => 'Tin tức & Ưu đãi', 'mo_ta' => 'Tin tức, sự kiện, khuyến mãi mới nhất tại nhà hàng Lalasea Bistro Hà Tiên — ưu đãi đặt tiệc, sự kiện bên biển, món mới theo mùa.', 'anh_dai_dien' => null, 'ban_dich' => null, 'khong_index' => false, 'an_footer' => false, 'ngon_ngu' => 'vi', 'url' => '/tin-tuc/']);
get_header();
?>
<div class="page-hero">
  <div class="kicker">Có gì mới ở Lalasea?</div>
  <h1>Tin tức &amp; Ưu đãi</h1>
  <p>Sự kiện, khuyến mãi và những câu chuyện mới nhất từ nhà hàng.</p>
</div>

<section class="news">
  <div class="container">
    <?php if ((ls_lay($baiviet, 'length') > 0)): ?>

    <div class="news-grid">
      <?php $__lp1 = $loop ?? null; foreach (ls_lap($baiviet) as $__k1 => $bai): $loop = ['index' => $__k1 + 1, 'index0' => $__k1]; ?>

      <a class="news-card" href="<?= ls_e(ls_lay($bai, 'url')) ?>">
        <div class="thumb"><?php if (ls_lay(ls_lay($bai, 'data'), 'anh_dai_dien')): ?><img src="<?= ls_e(ls_lay(ls_lay($bai, 'data'), 'anh_dai_dien')) ?>" alt="<?= ls_e(ls_lay(ls_lay($bai, 'data'), 'title')) ?>" loading="lazy"><?php endif; ?></div>
        <div class="body">
          <div class="date"><?= ls_e(ls_ngay_vn(ls_lay($bai, 'date'))) ?></div>
          <h3><?= ls_e(ls_lay(ls_lay($bai, 'data'), 'title')) ?></h3>
          <p><?= ls_e(ls_lay(ls_lay($bai, 'data'), 'mo_ta')) ?></p>
          <span class="more">Đọc tiếp →</span>
        </div>
      </a>
      <?php endforeach; $loop = $__lp1; ?>

    </div>
    <?php else: ?>

    <p style="text-align:center; color:var(--chu-nhat);">Chưa có bài viết nào. Hãy quay lại sau nhé!</p>
    <?php endif; ?>

  </div>
</section>

<?php get_footer();

<?php
/**
 * Template Name: Lalasea EN — Contact
 */
// TỰ SINH từ src/en/contact.njk bằng wordpress/tools/njk-sang-php.mjs — HTML giữ nguyên văn.
extract(ls_bien_toan_cuc());
$en = true;
$title = 'Contact & Directions';
$mo_ta = 'Where is Lalasea Bistro in Ha Tien? QL80 (National Road 80), To Chau, Ha Tien, An Giang, Vietnam. Open every day 08:00–23:00, from 07:00 at weekends. Map, directions and booking phone +84 907 896 949, Zalo, Facebook.';
$anh_dai_dien = null;
ls_dat_trang(['title' => 'Contact & Directions', 'mo_ta' => 'Where is Lalasea Bistro in Ha Tien? QL80 (National Road 80), To Chau, Ha Tien, An Giang, Vietnam. Open every day 08:00–23:00, from 07:00 at weekends. Map, directions and booking phone +84 907 896 949, Zalo, Facebook.', 'anh_dai_dien' => null, 'ban_dich' => '/lien-he/', 'khong_index' => false, 'an_footer' => false, 'ngon_ngu' => 'en', 'url' => '/en/contact/']);
get_header();
?>
<div class="page-hero">
  <div class="kicker">Come and see us</div>
  <h1>Contact &amp; Directions</h1>
  <p>Lalasea Bistro is always ready to welcome you on To Chau beach, Ha&nbsp;Tien.</p>
</div>

<section class="contact">
  <div class="container">
    <div class="contact-grid">
      <div>
        <div class="info-row">
          <div class="ic">📍</div>
          <div><strong>Address</strong><span><?= ls_e(ls_lay($thongtin, 'dia_chi')) ?>, Vietnam<br>(QL80 = National Road 80)</span></div>
        </div>
        <div class="info-row">
          <div class="ic">📞</div>
          <div><strong>Phone / Zalo</strong><span><?= ls_e(ls_sdt_quoc_te(ls_lay($thongtin, 'dien_thoai'))) ?></span></div>
        </div>
        <div class="info-row">
          <div class="ic">🕘</div>
          <div><strong>Opening hours</strong><span>Monday – Friday: <?= ls_e(ls_lay($thongtin, 'gio_mo_cua')) ?><br>Saturday &amp; Sunday: <?= ls_e(ls_lay($thongtin, 'gio_cuoi_tuan')) ?><br><?= ls_e((ls_lay($thongtin, 'gio_ghi_chu_en') ?: ls_lay($thongtin, 'gio_ghi_chu'))) ?></span></div>
        </div>
        <div class="info-row">
          <div class="ic">📘</div>
          <div><strong>Facebook</strong><span><a href="<?= ls_e(ls_lay($thongtin, 'facebook')) ?>" target="_blank" rel="noopener">Lalasea Bistro Ha Tien</a></span></div>
        </div>
        <div class="info-row">
          <div class="ic">🎵</div>
          <div><strong>TikTok</strong><span><a href="<?= ls_e(ls_lay($thongtin, 'tiktok')) ?>" target="_blank" rel="noopener">@lalasea_bistro</a></span></div>
        </div>
      </div>
      <div>
        <a href="tel:+84<?= ls_e(ls_replace(ls_lay($thongtin, 'dien_thoai_goi'), '0', '', 1)) ?>" class="btn" style="display:block; text-align:center; margin-bottom:14px;">📞 Call to book</a>
        <a href="<?= ls_e(ls_lay($thongtin, 'zalo')) ?>" target="_blank" rel="noopener" class="btn btn-ghost" style="display:block; text-align:center; margin-bottom:14px;">💬 Message on Zalo</a>
        <a href="<?= ls_e(ls_lay($thongtin, 'maps')) ?>" target="_blank" rel="noopener" class="btn btn-ghost" style="display:block; text-align:center; margin-bottom:14px;">🗺️ Open Google Maps</a>
        <a href="<?= ls_e(ls_lay($thongtin, 'google_review')) ?>" target="_blank" rel="noopener" class="btn btn-ghost" style="display:block; text-align:center;">⭐ Review us on Google</a>
      </div>
    </div>
    <div class="map-wrap">
      <iframe src="https://www.google.com/maps?q=Lalasea+Bistro+QL80+To+Chau+Ha+Tien&output=embed&hl=en" loading="lazy" title="Map showing the way to Lalasea Bistro" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
</section>

<?php get_footer();

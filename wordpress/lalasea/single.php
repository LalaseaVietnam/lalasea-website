<?php
// TỰ SINH từ src/_includes/layouts/post.njk bằng wordpress/tools/njk-sang-php.mjs
extract(ls_bien_toan_cuc());
the_post();
$bai = ls_bai_hien_tai();
$en = false; $title = $bai['title']; $mo_ta = $bai['mo_ta']; $anh_dai_dien = $bai['anh_dai_dien'];
$page = ['url' => $bai['url'], 'date' => $bai['date']]; $content = $bai['noi_dung'];
ls_dat_trang(['title' => $title, 'mo_ta' => $mo_ta, 'anh_dai_dien' => $anh_dai_dien, 'ban_dich' => null, 'khong_index' => false, 'an_footer' => false, 'ngon_ngu' => 'vi', 'og_type' => 'article', 'url' => $bai['url']]);
get_header();
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": <?= ls_raw(ls_dump($title)) ?>,
  "description": <?= ls_raw(ls_dump($mo_ta)) ?>,
  "image": "<?= ls_e(ls_lay($thongtin, 'site_url')) ?><?= ls_e(($anh_dai_dien ?: '/hero.jpg')) ?>",
  "datePublished": "<?= ls_e(ls_goi(ls_lay($page, 'date'), 'toISOString', [])) ?>",
  "author": { "@type": "Organization", "name": "<?= ls_e(ls_lay($thongtin, 'ten')) ?>" },
  "publisher": { "@type": "Organization", "name": "<?= ls_e(ls_lay($thongtin, 'ten')) ?>", "logo": { "@type": "ImageObject", "url": "<?= ls_e(ls_lay($thongtin, 'site_url')) ?>/favicon.svg" } },
  "mainEntityOfPage": "<?= ls_e(ls_lay($thongtin, 'site_url')) ?><?= ls_e(ls_lay($page, 'url')) ?>",
  "inLanguage": "vi-VN",
  "dateModified": "<?= ls_e(ls_goi(ls_lay($page, 'date'), 'toISOString', [])) ?>",
  "about": { "@id": "https://lalasea.vn/#nhahang" }
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Trang chủ", "item": "<?= ls_e(ls_lay($thongtin, 'site_url')) ?>/" },
    { "@type": "ListItem", "position": 2, "name": "Tin tức", "item": "<?= ls_e(ls_lay($thongtin, 'site_url')) ?>/tin-tuc/" },
    { "@type": "ListItem", "position": 3, "name": <?= ls_raw(ls_dump($title)) ?> }
  ]
}
</script>

<article>
  <div class="post-hero">
    <div class="date"><?= ls_e(ls_ngay_chu(ls_lay($page, 'date'))) ?></div>
    <h1><?= ls_e($title) ?></h1>
  </div>
  <?php if ($anh_dai_dien): ?>

  <div class="container" style="max-width:860px; margin-top:-30px;">
    <img src="<?= ls_e($anh_dai_dien) ?>" alt="<?= ls_e($title) ?>" style="border-radius:18px; box-shadow:0 18px 40px rgba(11,79,108,.18); width:100%; max-height:440px; object-fit:cover;">
  </div>
  <?php endif; ?>

  <div class="post-body">
    <?= ls_raw($content) ?>

  </div>
  <div class="post-back">
    <a href="/tin-tuc/" class="btn btn-ghost nen-sang">← Xem tin khác</a>
  </div>
</article>

<?php get_footer();

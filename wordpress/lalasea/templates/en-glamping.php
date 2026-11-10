<?php
/**
 * Template Name: Lalasea EN — Glamping
 */
// TỰ SINH từ src/en/glamping.njk bằng wordpress/tools/njk-sang-php.mjs — HTML giữ nguyên văn.
extract(ls_bien_toan_cuc());
$en = true;
$title = 'Glamping – Wooden Tent Prices';
$mo_ta = 'Ha Tien glamping at Lalasea — air-conditioned wooden tents in the grounds of a beachfront restaurant on To Chau beach, a few dozen metres from the sea. From 490,000 VND per person including breakfast + BBQ hotpot dinner. Check-in 14:00, call +84 907 896 949.';
$anh_dai_dien = '/uploads/585bccbb09ef89b1d0fe5.jpg';
ls_dat_trang(['title' => 'Glamping – Wooden Tent Prices', 'mo_ta' => 'Ha Tien glamping at Lalasea — air-conditioned wooden tents in the grounds of a beachfront restaurant on To Chau beach, a few dozen metres from the sea. From 490,000 VND per person including breakfast + BBQ hotpot dinner. Check-in 14:00, call +84 907 896 949.', 'anh_dai_dien' => '/uploads/585bccbb09ef89b1d0fe5.jpg', 'ban_dich' => '/glamping/', 'khong_index' => false, 'an_footer' => false, 'ngon_ngu' => 'en', 'url' => '/en/glamping/']);
get_header();
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Campground",
  "@id": "<?= ls_e(ls_lay($thongtin, 'site_url')) ?>/glamping/#glamping",
  "name": "Glamping Lalasea",
  "alternateName": ["Lalasea Bistro Camping", "Camping Lalasea Hà Tiên", "Lều gỗ Lalasea"],
  "description": "Glamping area with air-conditioned A-frame wooden tents inside the grounds of Lalasea Bistro, a few dozen metres from To Chau beach, Ha Tien. Packages include a BBQ – hotpot dinner and breakfast.",
  "url": "<?= ls_e(ls_lay($thongtin, 'site_url')) ?>/en/glamping/",
  "image": "<?= ls_e(ls_lay($thongtin, 'site_url')) ?><?= ls_e($anh_dai_dien) ?>",
  "telephone": "+84<?= ls_e(ls_replace(ls_lay($thongtin, 'dien_thoai_goi'), '0', '', 1)) ?>",
  "address": { "@type": "PostalAddress", "streetAddress": "QL80, Tô Châu", "addressLocality": "Hà Tiên", "addressRegion": "An Giang", "addressCountry": "VN" },
  "geo": { "@type": "GeoCoordinates", "latitude": <?= ls_e(ls_lay($thongtin, 'vi_do')) ?>, "longitude": <?= ls_e(ls_lay($thongtin, 'kinh_do')) ?> },
  "priceRange": "490,000 – 890,000 VND per person",
  "checkinTime": "14:00",
  "checkoutTime": "12:00",
  "petsAllowed": false,
  "amenityFeature": [
    { "@type": "LocationFeatureSpecification", "name": "Air-conditioned tents", "value": true },
    { "@type": "LocationFeatureSpecification", "name": "Breakfast", "value": true },
    { "@type": "LocationFeatureSpecification", "name": "BBQ – hotpot dinner", "value": true },
    { "@type": "LocationFeatureSpecification", "name": "On-site restaurant", "value": true },
    { "@type": "LocationFeatureSpecification", "name": "Near the beach", "value": true }
  ],
  "containedInPlace": { "@id": "<?= ls_e(ls_lay($thongtin, 'site_url')) ?>/#nhahang" },
  "sameAs": ["<?= ls_e(ls_lay($thongtin, 'facebook')) ?>", "<?= ls_e(ls_lay($thongtin, 'tiktok')) ?>", "<?= ls_e(ls_lay($thongtin, 'maps')) ?>"]
}
</script>

<div class="page-hero">
  <span class="script-accent" style="display:block; margin-bottom:4px;">Sleep among the trees — wake up with the sunrise</span>
  <h1>Glamping Lalasea</h1>
  <p>A-frame wooden tents with air conditioning, ventilation fans and comfy mattresses, inside the restaurant grounds in To Chau, Ha Tien — just a few dozen metres from the sea. Seafood in the afternoon, BBQ by the fire at night, and sunrise from the seaside dining area in the morning.</p>
</div>

<!-- ===== PRICES ===== -->
<section class="gprice">
  <div class="container">
    <div class="section-title reveal">
      <span class="script-accent">Eat – sleep – enjoy, all in one</span>
      <h2>Glamping prices</h2>
    </div>
    <p class="gprice-note reveal"><?= ls_e((ls_lay($glamping, 'gia_ghi_chu_en') ?: ls_lay($glamping, 'gia_ghi_chu'))) ?></p>
    <div class="gprice-grid reveal">
      <?php $__lp1 = $loop ?? null; foreach (ls_lap(ls_lay($glamping, 'bang_gia')) as $__k1 => $bang): $loop = ['index' => $__k1 + 1, 'index0' => $__k1]; ?>

      <div class="gprice-card<?php if ((ls_lay($loop, 'index') == 2)): ?> hot<?php endif; ?>">
        <div class="head">
          <h3><?= ls_e((ls_lay($bang, 'ten_en') ?: ls_lay($bang, 'ten'))) ?></h3>
          <span><?= ls_e((ls_lay($bang, 'ngay_en') ?: ls_lay($bang, 'ngay'))) ?></span>
        </div>
        <div class="rows">
          <?php $__lp2 = $loop ?? null; foreach (ls_lap(ls_lay($bang, 'muc')) as $__k2 => $m): $loop = ['index' => $__k2 + 1, 'index0' => $__k2]; ?>

          <div class="row"><span class="k"><?= ls_e((ls_lay($m, 'khach_en') ?: ls_lay($m, 'khach'))) ?></span><span class="g"><?= ls_e(ls_gia_en(ls_lay($m, 'gia'))) ?></span></div>
          <?php endforeach; $loop = $__lp2; ?>

        </div>
      </div>
      <?php endforeach; $loop = $__lp1; ?>

    </div>
    <p class="gprice-note reveal" style="margin-top:28px;">💡 The more of you, the more you save — groups of 5–6 from just <strong>490,000 VND per person</strong> on weekdays.</p>
  </div>
</section>

<!-- ===== WHAT'S INCLUDED ===== -->
<section class="gpack" style="background:#fff;">
  <div class="container">
    <div class="section-title reveal">
      <span class="script-accent">You won't go hungry</span>
      <h2>What the glamping package includes</h2>
    </div>
    <div class="gpack-grid">
      <div class="gpack-card reveal">
        <div class="ph">☀️ Breakfast</div>
        <p class="mota"><?= ls_e((ls_lay($glamping, 'bua_sang_mota_en') ?: ls_lay($glamping, 'bua_sang_mota'))) ?></p>
        <div class="cols">
          <div>
            <h3>Breakfast dishes</h3>
            <ul><?php $__lp3 = $loop ?? null; foreach (ls_lap(((((ls_lay($glamping, 'bua_sang_mon_en') && ls_lay(ls_lay($glamping, 'bua_sang_mon_en'), 'length'))) ? ls_lay($glamping, 'bua_sang_mon_en') : ls_lay($glamping, 'bua_sang_mon')))) as $__k3 => $m): $loop = ['index' => $__k3 + 1, 'index0' => $__k3]; ?><li><?= ls_e($m) ?></li><?php endforeach; $loop = $__lp3; ?></ul>
          </div>
          <div>
            <h3>Drinks</h3>
            <ul><?php $__lp4 = $loop ?? null; foreach (ls_lap(((((ls_lay($glamping, 'bua_sang_nuoc_en') && ls_lay(ls_lay($glamping, 'bua_sang_nuoc_en'), 'length'))) ? ls_lay($glamping, 'bua_sang_nuoc_en') : ls_lay($glamping, 'bua_sang_nuoc')))) as $__k4 => $m): $loop = ['index' => $__k4 + 1, 'index0' => $__k4]; ?><li><?= ls_e($m) ?></li><?php endforeach; $loop = $__lp4; ?></ul>
          </div>
        </div>
      </div>
      <div class="gpack-card reveal">
        <div class="ph">🔥 Dinner — BBQ &amp; Hotpot</div>
        <p class="mota"><?= ls_e((ls_lay($glamping, 'bua_toi_mota_en') ?: ls_lay($glamping, 'bua_toi_mota'))) ?></p>
        <div class="bbq-rows">
          <?php $__lp5 = $loop ?? null; foreach (ls_lap(ls_lay($glamping, 'bbq')) as $__k5 => $m): $loop = ['index' => $__k5 + 1, 'index0' => $__k5]; ?>

          <div class="row"><span class="k"><?= ls_e((ls_lay($m, 'mon_en') ?: ls_lay($m, 'mon'))) ?></span><span class="g"><?= ls_e((ls_lay($m, 'luong_en') ?: ls_lay($m, 'luong'))) ?></span></div>
          <?php endforeach; $loop = $__lp5; ?>

        </div>
        <p class="chu-thich">⭐ Dishes and portions are served according to the number of guests in the tent.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===== TENT PHOTOS ===== -->
<?php if ((ls_lay(ls_lay($glamping, 'anh'), 'length') > 0)): ?>

<section class="gallery">
  <div class="container">
    <div class="section-title reveal">
      <span class="script-accent">Inside your tent</span>
      <h2>Tent photos</h2>
    </div>
    <div class="gallery-grid reveal">
      <?php $__lp6 = $loop ?? null; foreach (ls_lap(ls_lay($glamping, 'anh')) as $__k6 => $item): $loop = ['index' => $__k6 + 1, 'index0' => $__k6]; ?>

      <div class="tile"><img src="<?= ls_e(ls_lay($item, 'file')) ?>" alt="<?= ls_e((ls_lay($item, 'mota_en') ?: 'Lalasea glamping tent, Ha Tien')) ?>" loading="lazy"><?php if (ls_lay($item, 'mota_en')): ?><div class="cap"><?= ls_e(ls_lay($item, 'mota_en')) ?></div><?php endif; ?></div>
      <?php endforeach; $loop = $__lp6; ?>

    </div>
  </div>
</section>
<?php endif; ?>


<!-- ===== CHECK-IN / CHECK-OUT ===== -->
<section class="grule">
  <div class="container">
    <div class="section-title reveal">
      <span class="script-accent">Arrival &amp; departure</span>
      <h2>Stay policy</h2>
    </div>
    <div class="grule-grid reveal">
      <div class="grule-card">
        <div class="ic">🌤️</div>
        <h3>Check-in</h3>
        <div class="time"><?= ls_e((ls_lay($glamping, 'checkin_en') ?: ls_lay($glamping, 'checkin'))) ?></div>
      </div>
      <div class="grule-card">
        <div class="ic">🌙</div>
        <h3>Check-out</h3>
        <div class="time"><?= ls_e((ls_lay($glamping, 'checkout_en') ?: ls_lay($glamping, 'checkout'))) ?></div>
      </div>
    </div>
    <ul class="gnote-list reveal">
      <?php $__lp7 = $loop ?? null; foreach (ls_lap(((((ls_lay($glamping, 'luu_y_en') && ls_lay(ls_lay($glamping, 'luu_y_en'), 'length'))) ? ls_lay($glamping, 'luu_y_en') : ls_lay($glamping, 'luu_y')))) as $__k7 => $n): $loop = ['index' => $__k7 + 1, 'index0' => $__k7]; ?><li><?= ls_e($n) ?></li><?php endforeach; $loop = $__lp7; ?>

    </ul>
  </div>
</section>

<!-- ===== RESERVE A TENT ===== -->
<section class="visit">
  <div class="container" style="text-align:center; max-width:720px;">
    <div class="section-title reveal">
      <span class="script-accent">Only a few tents</span>
      <h2>Reserve a tent for your trip</h2>
    </div>
    <p class="reveal" style="opacity:.9; margin:-20px 0 28px;">Weekends and holidays often book up — call or message us on Zalo to reserve ahead.</p>
    <div class="reveal">
      <a class="hotline" href="tel:+84<?= ls_e(ls_replace(ls_lay($thongtin, 'dien_thoai_goi'), '0', '', 1)) ?>" style="font-size: clamp(30px, 5vw, 44px);">📞 <?= ls_e(ls_sdt_quoc_te(ls_lay($thongtin, 'dien_thoai'))) ?></a>
      <div style="margin-top:22px;">
        <a href="tel:+84<?= ls_e(ls_replace(ls_lay($thongtin, 'dien_thoai_goi'), '0', '', 1)) ?>" class="btn btn-lg">Call to reserve</a>
        <a href="<?= ls_e(ls_lay($thongtin, 'zalo')) ?>" target="_blank" rel="noopener" class="btn btn-ghost" style="margin-left:12px;">💬 Message on Zalo</a>
      </div>
    </div>
  </div>
</section>

<?php get_footer();

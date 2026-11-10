<?php
/**
 * Template Name: Lalasea — Glamping
 */
// TỰ SINH từ src/glamping.njk bằng wordpress/tools/njk-sang-php.mjs — HTML giữ nguyên văn.
extract(ls_bien_toan_cuc());
$en = false;
$title = 'Glamping – Bảng giá ngủ lều gỗ tiện nghi';
$mo_ta = 'Glamping Hà Tiên tại Lalasea — lều gỗ máy lạnh trong khuôn viên nhà hàng bên bờ biển Tô Châu, cách biển vài chục mét. Giá từ 490.000đ/người đã gồm bữa sáng + BBQ lẩu tối. Check-in 14h, hotline 0907 896 949.';
$anh_dai_dien = '/uploads/585bccbb09ef89b1d0fe5.jpg';
ls_dat_trang(['title' => 'Glamping – Bảng giá ngủ lều gỗ tiện nghi', 'mo_ta' => 'Glamping Hà Tiên tại Lalasea — lều gỗ máy lạnh trong khuôn viên nhà hàng bên bờ biển Tô Châu, cách biển vài chục mét. Giá từ 490.000đ/người đã gồm bữa sáng + BBQ lẩu tối. Check-in 14h, hotline 0907 896 949.', 'anh_dai_dien' => '/uploads/585bccbb09ef89b1d0fe5.jpg', 'ban_dich' => '/en/glamping/', 'khong_index' => false, 'an_footer' => false, 'ngon_ngu' => 'vi', 'url' => '/glamping/']);
get_header();
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Campground",
  "@id": "<?= ls_e(ls_lay($thongtin, 'site_url')) ?>/glamping/#glamping",
  "name": "Glamping Lalasea",
  "alternateName": ["Lalasea Bistro Camping", "Camping Lalasea Hà Tiên", "Lều gỗ Lalasea"],
  "description": "Khu glamping lều gỗ chữ A có máy lạnh nằm trong khuôn viên nhà hàng Lalasea Bistro, cách bờ biển Tô Châu vài chục mét. Giá trọn gói đã gồm bữa tối BBQ – lẩu và bữa sáng.",
  "url": "<?= ls_e(ls_lay($thongtin, 'site_url')) ?>/glamping/",
  "image": "<?= ls_e(ls_lay($thongtin, 'site_url')) ?><?= ls_e($anh_dai_dien) ?>",
  "telephone": "+84<?= ls_e(ls_replace(ls_lay($thongtin, 'dien_thoai_goi'), '0', '', 1)) ?>",
  "address": { "@type": "PostalAddress", "streetAddress": "QL80, Tô Châu", "addressLocality": "Hà Tiên", "addressRegion": "An Giang", "addressCountry": "VN" },
  "geo": { "@type": "GeoCoordinates", "latitude": <?= ls_e(ls_lay($thongtin, 'vi_do')) ?>, "longitude": <?= ls_e(ls_lay($thongtin, 'kinh_do')) ?> },
  "priceRange": "490.000đ – 890.000đ / người",
  "checkinTime": "14:00",
  "checkoutTime": "12:00",
  "petsAllowed": false,
  "amenityFeature": [
    { "@type": "LocationFeatureSpecification", "name": "Máy lạnh trong lều", "value": true },
    { "@type": "LocationFeatureSpecification", "name": "Bữa sáng", "value": true },
    { "@type": "LocationFeatureSpecification", "name": "Bữa tối BBQ – lẩu", "value": true },
    { "@type": "LocationFeatureSpecification", "name": "Nhà hàng tại chỗ", "value": true },
    { "@type": "LocationFeatureSpecification", "name": "Sát bờ biển", "value": true }
  ],
  "containedInPlace": { "@id": "<?= ls_e(ls_lay($thongtin, 'site_url')) ?>/#nhahang" },
  "sameAs": ["<?= ls_e(ls_lay($thongtin, 'facebook')) ?>", "<?= ls_e(ls_lay($thongtin, 'tiktok')) ?>", "<?= ls_e(ls_lay($thongtin, 'maps')) ?>"]
}
</script>

<div class="page-hero">
  <span class="script-accent" style="display:block; margin-bottom:4px;">Ngủ giữa khuôn viên xanh — thức cùng bình minh</span>
  <h1>Glamping Lalasea</h1>
  <p>Lều gỗ chữ A đầy đủ máy lạnh, quạt thông gió, nệm êm trong khuôn viên nhà hàng ở Tô Châu, Hà Tiên — cách biển chỉ vài chục mét. Chiều ăn hải sản, tối BBQ bên lửa, sáng ra khu bàn ăn sát biển đón bình minh.</p>
</div>

<!-- ===== BẢNG GIÁ ===== -->
<section class="gprice">
  <div class="container">
    <div class="section-title reveal">
      <span class="script-accent">Trọn gói ăn – ngủ – trải nghiệm</span>
      <h2>Bảng giá Glamping</h2>
    </div>
    <p class="gprice-note reveal"><?= ls_e(ls_lay($glamping, 'gia_ghi_chu')) ?></p>
    <div class="gprice-grid reveal">
      <?php $__lp1 = $loop ?? null; foreach (ls_lap(ls_lay($glamping, 'bang_gia')) as $__k1 => $bang): $loop = ['index' => $__k1 + 1, 'index0' => $__k1]; ?>

      <div class="gprice-card<?php if ((ls_lay($loop, 'index') == 2)): ?> hot<?php endif; ?>">
        <div class="head">
          <h3><?= ls_e(ls_lay($bang, 'ten')) ?></h3>
          <span><?= ls_e(ls_lay($bang, 'ngay')) ?></span>
        </div>
        <div class="rows">
          <?php $__lp2 = $loop ?? null; foreach (ls_lap(ls_lay($bang, 'muc')) as $__k2 => $m): $loop = ['index' => $__k2 + 1, 'index0' => $__k2]; ?>

          <div class="row"><span class="k"><?= ls_e(ls_lay($m, 'khach')) ?></span><span class="g"><?= ls_e(ls_lay($m, 'gia')) ?></span></div>
          <?php endforeach; $loop = $__lp2; ?>

        </div>
      </div>
      <?php endforeach; $loop = $__lp1; ?>

    </div>
    <p class="gprice-note reveal" style="margin-top:28px;">💡 Đi càng đông càng tiết kiệm — nhóm 5–6 người chỉ từ <strong>490.000đ/người</strong> ngày thường.</p>
  </div>
</section>

<!-- ===== GÓI BAO GỒM ===== -->
<section class="gpack" style="background:#fff;">
  <div class="container">
    <div class="section-title reveal">
      <span class="script-accent">Không lo đói bụng</span>
      <h2>Gói Glamping bao gồm</h2>
    </div>
    <div class="gpack-grid">
      <div class="gpack-card reveal">
        <div class="ph">☀️ Bữa sáng</div>
        <p class="mota"><?= ls_e(ls_lay($glamping, 'bua_sang_mota')) ?></p>
        <div class="cols">
          <div>
            <h3>Món ăn sáng</h3>
            <ul><?php $__lp3 = $loop ?? null; foreach (ls_lap(ls_lay($glamping, 'bua_sang_mon')) as $__k3 => $m): $loop = ['index' => $__k3 + 1, 'index0' => $__k3]; ?><li><?= ls_e($m) ?></li><?php endforeach; $loop = $__lp3; ?></ul>
          </div>
          <div>
            <h3>Thức uống</h3>
            <ul><?php $__lp4 = $loop ?? null; foreach (ls_lap(ls_lay($glamping, 'bua_sang_nuoc')) as $__k4 => $m): $loop = ['index' => $__k4 + 1, 'index0' => $__k4]; ?><li><?= ls_e($m) ?></li><?php endforeach; $loop = $__lp4; ?></ul>
          </div>
        </div>
      </div>
      <div class="gpack-card reveal">
        <div class="ph">🔥 Bữa tối — BBQ &amp; Lẩu</div>
        <p class="mota"><?= ls_e(ls_lay($glamping, 'bua_toi_mota')) ?></p>
        <div class="bbq-rows">
          <?php $__lp5 = $loop ?? null; foreach (ls_lap(ls_lay($glamping, 'bbq')) as $__k5 => $m): $loop = ['index' => $__k5 + 1, 'index0' => $__k5]; ?>

          <div class="row"><span class="k"><?= ls_e(ls_lay($m, 'mon')) ?></span><span class="g"><?= ls_e(ls_lay($m, 'luong')) ?></span></div>
          <?php endforeach; $loop = $__lp5; ?>

        </div>
        <p class="chu-thich">⭐ Số lượng và định lượng món được phục vụ tương ứng với số khách trong lều.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===== HÌNH ẢNH LỀU ===== -->
<?php if ((ls_lay(ls_lay($glamping, 'anh'), 'length') > 0)): ?>

<section class="gallery">
  <div class="container">
    <div class="section-title reveal">
      <span class="script-accent">Bên trong chiếc lều của bạn</span>
      <h2>Hình ảnh lều</h2>
    </div>
    <div class="gallery-grid reveal">
      <?php $__lp6 = $loop ?? null; foreach (ls_lap(ls_lay($glamping, 'anh')) as $__k6 => $item): $loop = ['index' => $__k6 + 1, 'index0' => $__k6]; ?>

      <div class="tile"><img src="<?= ls_e(ls_lay($item, 'file')) ?>" alt="<?= ls_e((ls_lay($item, 'mota') ?: 'Lều glamping Lalasea Hà Tiên')) ?>" loading="lazy"><?php if (ls_lay($item, 'mota')): ?><div class="cap"><?= ls_e(ls_lay($item, 'mota')) ?></div><?php endif; ?></div>
      <?php endforeach; $loop = $__lp6; ?>

    </div>
  </div>
</section>
<?php endif; ?>


<!-- ===== QUY ĐỊNH NHẬN – TRẢ LỀU ===== -->
<section class="grule">
  <div class="container">
    <div class="section-title reveal">
      <span class="script-accent">Nhận – trả lều</span>
      <h2>Quy định lưu trú</h2>
    </div>
    <div class="grule-grid reveal">
      <div class="grule-card">
        <div class="ic">🌤️</div>
        <h3>Thời gian nhận lều (Check-in)</h3>
        <div class="time"><?= ls_e(ls_lay($glamping, 'checkin')) ?></div>
      </div>
      <div class="grule-card">
        <div class="ic">🌙</div>
        <h3>Thời gian trả lều (Check-out)</h3>
        <div class="time"><?= ls_e(ls_lay($glamping, 'checkout')) ?></div>
      </div>
    </div>
    <ul class="gnote-list reveal">
      <?php $__lp7 = $loop ?? null; foreach (ls_lap(ls_lay($glamping, 'luu_y')) as $__k7 => $n): $loop = ['index' => $__k7 + 1, 'index0' => $__k7]; ?><li><?= ls_e($n) ?></li><?php endforeach; $loop = $__lp7; ?>

    </ul>
  </div>
</section>

<!-- ===== GỌI GIỮ LỀU ===== -->
<section class="visit">
  <div class="container" style="text-align:center; max-width:720px;">
    <div class="section-title reveal">
      <span class="script-accent">Số lượng lều có hạn</span>
      <h2>Giữ lều cho chuyến đi của bạn</h2>
    </div>
    <p class="reveal" style="opacity:.9; margin:-20px 0 28px;">Cuối tuần và dịp lễ thường kín lều — bạn nên gọi hoặc nhắn Zalo đặt trước.</p>
    <div class="reveal">
      <a class="hotline" href="tel:<?= ls_e(ls_lay($thongtin, 'dien_thoai_goi')) ?>" style="font-size: clamp(30px, 5vw, 44px);">📞 <?= ls_e(ls_lay($thongtin, 'dien_thoai')) ?></a>
      <div style="margin-top:22px;">
        <a href="tel:<?= ls_e(ls_lay($thongtin, 'dien_thoai_goi')) ?>" class="btn btn-lg">Gọi giữ lều</a>
        <a href="<?= ls_e(ls_lay($thongtin, 'zalo')) ?>" target="_blank" rel="noopener" class="btn btn-ghost" style="margin-left:12px;">💬 Nhắn Zalo</a>
      </div>
    </div>
  </div>
</section>

<?php get_footer();

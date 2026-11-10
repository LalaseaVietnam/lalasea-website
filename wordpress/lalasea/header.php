<?php
// TỰ SINH từ src/_includes/layouts/base.njk bằng wordpress/tools/njk-sang-php.mjs
extract(ls_bien_toan_cuc());
$__p = ls_trang();
$ngon_ngu = $__p['ngon_ngu']; $title = $__p['title']; $mo_ta = $__p['mo_ta']; $anh_dai_dien = $__p['anh_dai_dien'];
$ban_dich = $__p['ban_dich']; $khong_index = $__p['khong_index']; $an_footer = $__p['an_footer']; $og_type = $__p['og_type'];
$page = ['url' => $__p['url']];
$en = $ngon_ngu === 'en';
?><?php $en = ($ngon_ngu == 'en'); ?><?php $mo_ta_trang = ($mo_ta ?: ((($en) ? ls_lay($thongtin, 'mo_ta_en') : ls_lay($thongtin, 'mo_ta')))); ?><!DOCTYPE html>
<html lang="<?= ls_e((($en) ? 'en' : 'vi')) ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"><?php ob_start(); ?><?php if ($en): ?><?php if ($title): ?><?= ls_e($title) ?> | <?= ls_e(ls_lay($thongtin, 'ten')) ?> Ha Tien<?php else: ?><?= ls_e(ls_lay($thongtin, 'ten')) ?> — Beachfront Restaurant in To Chau, Ha Tien<?php endif; ?><?php elseif ($title): ?><?= ls_e($title) ?> | <?= ls_e(ls_lay($thongtin, 'ten')) ?> Hà Tiên<?php else: ?><?= ls_e(ls_lay($thongtin, 'ten')) ?> — Nhà hàng bên biển Tô Châu, Hà Tiên<?php endif; ?><?php $tieu_de = ob_get_clean(); ?>

  <title><?= ls_raw($tieu_de) ?></title>
  <meta name="description" content="<?= ls_e($mo_ta_trang) ?>">
  <link rel="canonical" href="<?= ls_e(ls_lay($thongtin, 'site_url')) ?><?= ls_e(ls_lay($page, 'url')) ?>"><?php if ($ban_dich): ?>

  <link rel="alternate" hreflang="vi" href="<?= ls_e(ls_lay($thongtin, 'site_url')) ?><?= ls_e((($en) ? $ban_dich : ls_lay($page, 'url'))) ?>">
  <link rel="alternate" hreflang="en" href="<?= ls_e(ls_lay($thongtin, 'site_url')) ?><?= ls_e((($en) ? ls_lay($page, 'url') : $ban_dich)) ?>">
  <link rel="alternate" hreflang="x-default" href="<?= ls_e(ls_lay($thongtin, 'site_url')) ?><?= ls_e((($en) ? $ban_dich : ls_lay($page, 'url'))) ?>"><?php endif; ?><?php if ($khong_index): ?>

  <meta name="robots" content="noindex, nofollow"><?php endif; ?>

  <meta property="og:title" content="<?= ls_raw($tieu_de) ?>">
  <meta property="og:description" content="<?= ls_e($mo_ta_trang) ?>">
  <meta property="og:image" content="<?= ls_e(ls_lay($thongtin, 'site_url')) ?><?= ls_e(($anh_dai_dien ?: '/hero.jpg')) ?>">
  <meta property="og:url" content="<?= ls_e(ls_lay($thongtin, 'site_url')) ?><?= ls_e(ls_lay($page, 'url')) ?>">
  <meta property="og:type" content="<?= ls_e(($og_type ?: 'website')) ?>">
  <meta property="og:site_name" content="<?= ls_e(ls_lay($thongtin, 'ten')) ?>">
  <meta property="og:locale" content="<?= ls_e((($en) ? 'en_US' : 'vi_VN')) ?>"><?php if ($ban_dich): ?>

  <meta property="og:locale:alternate" content="<?= ls_e((($en) ? 'vi_VN' : 'en_US')) ?>"><?php endif; ?>

  <meta name="twitter:card" content="summary_large_image">
  
  <link rel="icon" href="/favicon.ico" sizes="16x16 32x32 48x48">
  <link rel="icon" type="image/png" sizes="192x192" href="/icon-192.png">
  <link rel="icon" type="image/svg+xml" href="/favicon.svg">
  <link rel="apple-touch-icon" href="/apple-touch-icon.png">
  <script>document.documentElement.classList.add('js');</script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&family=Pacifico&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= esc_url(get_theme_file_uri('css/style.css')) ?>?v=<?= (int) filemtime(get_theme_file_path('css/style.css')) ?>">
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Restaurant",
    "@id": "https://lalasea.vn/#nhahang",
    "name": "<?= ls_e(ls_lay($thongtin, 'ten')) ?>",
    "alternateName": ["Lalasea Bistro Hà Tiên", "QUÁN LALASEA", "LALASEA", "Quán Lalasea", "Lalasea Bistro Camping"],<?php if ($en): ?>

    "description": <?= ls_raw(ls_dump(ls_lay($thongtin, 'mo_ta_en'))) ?>,
    "servesCuisine": ["Vietnamese", "Seafood", "Barbecue", "Hotpot"],
    "priceRange": "100,000 – 300,000 VND",<?php else: ?>

    "description": "<?= ls_e(ls_lay($thongtin, 'mo_ta')) ?>",
    "servesCuisine": ["Ẩm thực Việt Nam", "Hải sản", "Đồ nướng", "Lẩu"],
    "priceRange": "100.000đ – 300.000đ",<?php endif; ?>

    "telephone": "+84<?= ls_e(ls_replace(ls_lay($thongtin, 'dien_thoai_goi'), '0', '', 1)) ?>",
    "address": { "@type": "PostalAddress", "streetAddress": "QL80, Tô Châu", "addressLocality": "Hà Tiên", "addressRegion": "An Giang", "addressCountry": "VN" },
    "geo": { "@type": "GeoCoordinates", "latitude": <?= ls_e(ls_lay($thongtin, 'vi_do')) ?>, "longitude": <?= ls_e(ls_lay($thongtin, 'kinh_do')) ?> },
    "hasMap": "<?= ls_e(ls_lay($thongtin, 'maps')) ?>",
    "openingHoursSpecification": [
      {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
        "opens": "08:00", "closes": "23:00"
      },
      {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Saturday", "Sunday"],
        "opens": "07:00", "closes": "23:00"
      }
    ],
    "acceptsReservations": "True",
    "hasMenu": "<?= ls_e(ls_lay($thongtin, 'site_url')) ?><?= ls_e((($en) ? '/en/menu/' : '/thuc-don/')) ?>",
    "containsPlace": { "@id": "<?= ls_e(ls_lay($thongtin, 'site_url')) ?>/glamping/#glamping" },

    "url": "<?= ls_e(ls_lay($thongtin, 'site_url')) ?>/",
    "image": "<?= ls_e(ls_lay($thongtin, 'site_url')) ?>/hero.jpg",
    "sameAs": ["<?= ls_e(ls_lay($thongtin, 'facebook')) ?>", "<?= ls_e(ls_lay($thongtin, 'tiktok')) ?>", "<?= ls_e(ls_lay($thongtin, 'zalo')) ?>", "<?= ls_e(ls_lay($thongtin, 'maps')) ?>"]
  }
  </script>
<?php wp_head(); ?>
</head>
<body>
<?php wp_body_open(); ?>

  
  <svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="lalasea-logo" viewBox="0 0 220 450">
      
      <g fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round">
        <path d="M 35 130 A 75 75 0 0 1 185 130"/>
        <path d="M 185 130 L 185 177 M 185 193 L 185 280"/>
        <path d="M 185 280 A 75 75 0 0 1 35 280"/>
        <path d="M 35 280 L 35 193 M 35 177 L 35 130"/>
        <path d="M 20 140 L 20 280 A 90 90 0 0 0 200 280 L 200 140"/>
      </g>
      
      <g fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round">
        <circle cx="110" cy="185" r="9" stroke-width="4"/>
        <line x1="110" y1="169" x2="110" y2="117"/>
        <line x1="110" y1="169" x2="110" y2="145" transform="rotate(22.5 110 185)"/>
        <line x1="110" y1="169" x2="110" y2="115" transform="rotate(45 110 185)"/>
        <line x1="110" y1="169" x2="110" y2="145" transform="rotate(67.5 110 185)"/>
        <line x1="110" y1="169" x2="110" y2="97" transform="rotate(90 110 185)"/>
        <line x1="110" y1="169" x2="110" y2="145" transform="rotate(112.5 110 185)"/>
        <line x1="110" y1="169" x2="110" y2="115" transform="rotate(135 110 185)"/>
        <line x1="110" y1="169" x2="110" y2="145" transform="rotate(157.5 110 185)"/>
        <line x1="110" y1="169" x2="110" y2="127" transform="rotate(180 110 185)"/>
        <line x1="110" y1="169" x2="110" y2="145" transform="rotate(202.5 110 185)"/>
        <line x1="110" y1="169" x2="110" y2="115" transform="rotate(225 110 185)"/>
        <line x1="110" y1="169" x2="110" y2="145" transform="rotate(247.5 110 185)"/>
        <line x1="110" y1="169" x2="110" y2="97" transform="rotate(270 110 185)"/>
        <line x1="110" y1="169" x2="110" y2="145" transform="rotate(292.5 110 185)"/>
        <line x1="110" y1="169" x2="110" y2="115" transform="rotate(315 110 185)"/>
        <line x1="110" y1="169" x2="110" y2="145" transform="rotate(337.5 110 185)"/>
      </g>
      
      <circle cx="80" cy="139" r="2.2" fill="currentColor"/>
      <circle cx="141" cy="232" r="2.2" fill="currentColor"/>
      
      <g fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round">
        <path d="M 44 258 q 11 -9 22 0 t 22 0 t 22 0 t 22 0 t 22 0 t 22 0"/>
        <path d="M 44 274 q 11 -9 22 0 t 22 0 t 22 0 t 22 0 t 22 0 t 22 0"/>
        <path d="M 44 290 q 11 -9 22 0 t 22 0 t 22 0 t 22 0 t 22 0 t 22 0"/>
        <path d="M 55 306 q 11 -9 22 0 t 22 0 t 22 0 t 22 0 t 22 0"/>
        <path d="M 56 322 q 10.8 -9 21.6 0 t 21.6 0 t 21.6 0 t 21.6 0 t 21.6 0"/>
        <path d="M 70 338 q 10 -8 20 0 t 20 0 t 20 0 t 20 0"/>
      </g>
      
      <g font-family="'Playfair Display', Georgia, serif" font-size="27" font-weight="700" fill="currentColor" text-anchor="middle">
        <text x="110" y="36" transform="rotate(-62 110 130)">L</text>
        <text x="110" y="36" transform="rotate(-41.3 110 130)">A</text>
        <text x="110" y="36" transform="rotate(-20.7 110 130)">L</text>
        <text x="110" y="36" transform="rotate(0 110 130)">A</text>
        <text x="110" y="36" transform="rotate(20.7 110 130)">S</text>
        <text x="110" y="36" transform="rotate(41.3 110 130)">E</text>
        <text x="110" y="36" transform="rotate(62 110 130)">A</text>
      </g>
      <text x="110" y="437" text-anchor="middle" font-family="'Playfair Display', Georgia, serif" font-size="40" letter-spacing="7" fill="currentColor">BISTRO</text>
    </symbol>
  </svg>

  <header class="site-header">
    <nav class="site-nav">
      <a href="<?= ls_e((($en) ? '/en/' : '/')) ?>" class="brand" aria-label="<?= ls_e((($en) ? 'Lalasea Bistro — Home' : 'Lalasea Bistro — Trang chủ')) ?>">
        <svg class="logo-svg" width="26" height="53"><use href="#lalasea-logo"/></svg>
        <span class="wordmark">LALASEA <span>BISTRO</span></span>
      </a>
      <div class="nav-links" id="nav-links"><?php if ($en): ?>

        <a href="/en/" class="chi-menu-gap">Home</a>
        <a href="/en/#about">About</a>
        <a href="/en/menu/">Menu</a>
        <a href="/en/glamping/">Glamping</a>
        <a href="/en/events/">Events</a>
        <a href="/en/contact/">Contact</a>
        <a href="/en/#book" class="btn btn-nav">Book a table</a><?php else: ?>

        <a href="/" class="chi-menu-gap">Trang chủ</a>
        <a href="/#gioi-thieu">Giới thiệu</a>
        <a href="/thuc-don/">Thực đơn</a>
        <a href="/glamping/">Glamping</a>
        <a href="/tiec-su-kien/">Tiệc &amp; Sự kiện</a>
        <a href="/tin-tuc/">Tin tức</a>
        <a href="/lien-he/">Liên hệ</a>
        <a href="/#dat-ban" class="btn btn-nav">Đặt bàn</a><?php endif; ?>

      </div>
      <a class="doi-ngon-ngu" href="<?= ls_e(($ban_dich ?: ((($en) ? '/' : '/en/')))) ?>" hreflang="<?= ls_e((($en) ? 'vi' : 'en')) ?>" lang="<?= ls_e((($en) ? 'vi' : 'en')) ?>" aria-label="<?= ls_e((($en) ? 'VI — Xem bằng tiếng Việt' : 'EN — View in English')) ?>">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.6 3.8 5.6 3.8 9s-1.3 6.4-3.8 9c-2.5-2.6-3.8-5.6-3.8-9S9.5 5.6 12 3z"/></svg>
        <?= ls_e((($en) ? 'VI' : 'EN')) ?>

      </a>
      <button class="nav-toggle" id="nav-toggle" aria-label="<?= ls_e((($en) ? 'Open menu' : 'Mở menu')) ?>" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </nav>
  </header>

  <main>
    
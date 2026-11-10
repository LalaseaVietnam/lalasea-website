<?php
/**
 * Phần đầu mọi trang: <head>, schema Restaurant, logo, thanh menu. Mở thẻ <main>.
 *
 * Song ngữ: trang tiếng Anh gọi lalasea_dat_trang() với 'ngon_ngu' => 'en';
 * 'ban_dich' là đường dẫn tới đúng trang đó ở ngôn ngữ kia (DESIGN.md §11).
 *
 * @package Lalasea
 */

$lalasea_thongtin = lalasea_data( 'thongtin' );
$lalasea_trang    = lalasea_trang();
$lalasea_en       = 'en' === $lalasea_trang['ngon_ngu'];

if ( $lalasea_en ) {
	$lalasea_tieu_de = $lalasea_trang['title']
		? $lalasea_trang['title'] . ' | ' . $lalasea_thongtin['ten'] . ' Ha Tien'
		: $lalasea_thongtin['ten'] . ' — Beachfront Restaurant in To Chau, Ha Tien';
} else {
	$lalasea_tieu_de = $lalasea_trang['title']
		? $lalasea_trang['title'] . ' | ' . $lalasea_thongtin['ten'] . ' Hà Tiên'
		: $lalasea_thongtin['ten'] . ' — Nhà hàng bên biển Tô Châu, Hà Tiên';
}
$lalasea_mo_ta_trang = $lalasea_trang['mo_ta'] ? $lalasea_trang['mo_ta'] : ( $lalasea_en ? $lalasea_thongtin['mo_ta_en'] : $lalasea_thongtin['mo_ta'] );

// Đường dẫn đầy đủ của trang này và của bản tiếng Việt (bản gốc, cũng là x-default).
$lalasea_dia_chi    = $lalasea_thongtin['site_url'] . $lalasea_trang['url'];
$lalasea_dia_chi_vi = $lalasea_thongtin['site_url'] . ( $lalasea_en ? $lalasea_trang['ban_dich'] : $lalasea_trang['url'] );
$lalasea_dia_chi_en = $lalasea_thongtin['site_url'] . ( $lalasea_en ? $lalasea_trang['url'] : $lalasea_trang['ban_dich'] );
?>
<!DOCTYPE html>
<html lang="<?php echo $lalasea_en ? 'en' : 'vi'; ?>">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo esc_html( $lalasea_tieu_de ); ?></title>
	<meta name="description" content="<?php echo esc_attr( $lalasea_mo_ta_trang ); ?>">
	<link rel="canonical" href="<?php echo esc_url( $lalasea_dia_chi ); ?>">
	<?php
	/*
	 * hreflang: báo Google hai trang là bản dịch của nhau, để khách tìm bằng tiếng Anh
	 * thấy trang /en/ và khách Việt thấy trang tiếng Việt. Mặc định (x-default) là tiếng Việt.
	 */
	if ( $lalasea_trang['ban_dich'] ) :
		?>
	<link rel="alternate" hreflang="vi" href="<?php echo esc_url( $lalasea_dia_chi_vi ); ?>">
	<link rel="alternate" hreflang="en" href="<?php echo esc_url( $lalasea_dia_chi_en ); ?>">
	<link rel="alternate" hreflang="x-default" href="<?php echo esc_url( $lalasea_dia_chi_vi ); ?>">
	<?php endif; ?>
	<?php if ( $lalasea_trang['khong_index'] ) : ?>
	<meta name="robots" content="noindex, nofollow">
	<?php endif; ?>
	<meta property="og:title" content="<?php echo esc_attr( $lalasea_tieu_de ); ?>">
	<meta property="og:description" content="<?php echo esc_attr( $lalasea_mo_ta_trang ); ?>">
	<meta property="og:image" content="<?php echo esc_url( $lalasea_thongtin['site_url'] . ( $lalasea_trang['anh_dai_dien'] ? $lalasea_trang['anh_dai_dien'] : '/hero.jpg' ) ); ?>">
	<meta property="og:url" content="<?php echo esc_url( $lalasea_dia_chi ); ?>">
	<meta property="og:type" content="<?php echo esc_attr( $lalasea_trang['og_type'] ); ?>">
	<meta property="og:site_name" content="<?php echo esc_attr( $lalasea_thongtin['ten'] ); ?>">
	<meta property="og:locale" content="<?php echo $lalasea_en ? 'en_US' : 'vi_VN'; ?>">
	<?php if ( $lalasea_trang['ban_dich'] ) : ?>
	<meta property="og:locale:alternate" content="<?php echo $lalasea_en ? 'vi_VN' : 'en_US'; ?>">
	<?php endif; ?>
	<meta name="twitter:card" content="summary_large_image">
	<?php
	/*
	 * Biểu tượng web. Google KHÔNG đọc được SVG (chỉ nhận BMP, GIF, ICO, PNG, JPEG,
	 * PPM, TIFF) nên bắt buộc phải có bản PNG/ICO, không thì kết quả tìm kiếm hiện
	 * biểu tượng mặc định. Ảnh phải vuông và nên lớn hơn 48x48.
	 */
	?>
	<link rel="icon" href="/favicon.ico" sizes="16x16 32x32 48x48">
	<link rel="icon" type="image/png" sizes="192x192" href="/icon-192.png">
	<link rel="icon" type="image/svg+xml" href="/favicon.svg">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">
	<script>document.documentElement.classList.add('js');</script>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php
	// Phông chữ + CSS của web (nạp trong functions.php), và mã của plugin (GTM, Pixel...).
	wp_head();
	?>
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "Restaurant",
		"@id": "https://lalasea.vn/#nhahang",
		"name": <?php echo lalasea_json( $lalasea_thongtin['ten'] ); ?>,
		"alternateName": ["Lalasea Bistro Hà Tiên", "QUÁN LALASEA", "LALASEA", "Quán Lalasea", "Lalasea Bistro Camping"],
		<?php if ( $lalasea_en ) : ?>
		"description": <?php echo lalasea_json( $lalasea_thongtin['mo_ta_en'] ); ?>,
		"servesCuisine": ["Vietnamese", "Seafood", "Barbecue", "Hotpot"],
		"priceRange": "100,000 – 300,000 VND",
		<?php else : ?>
		"description": <?php echo lalasea_json( $lalasea_thongtin['mo_ta'] ); ?>,
		"servesCuisine": ["Ẩm thực Việt Nam", "Hải sản", "Đồ nướng", "Lẩu"],
		"priceRange": "100.000đ – 300.000đ",
		<?php endif; ?>
		"telephone": <?php echo lalasea_json( lalasea_tel_quoc_te( $lalasea_thongtin['dien_thoai_goi'] ) ); ?>,
		"address": { "@type": "PostalAddress", "streetAddress": "QL80, Tô Châu", "addressLocality": "Hà Tiên", "addressRegion": "An Giang", "addressCountry": "VN" },
		"geo": { "@type": "GeoCoordinates", "latitude": <?php echo lalasea_json( $lalasea_thongtin['vi_do'] ); ?>, "longitude": <?php echo lalasea_json( $lalasea_thongtin['kinh_do'] ); ?> },
		"hasMap": <?php echo lalasea_json( $lalasea_thongtin['maps'] ); ?>,
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
		"hasMenu": <?php echo lalasea_json( $lalasea_thongtin['site_url'] . ( $lalasea_en ? '/en/menu/' : '/thuc-don/' ) ); ?>,
		"containsPlace": { "@id": <?php echo lalasea_json( $lalasea_thongtin['site_url'] . '/glamping/#glamping' ); ?> },
		"url": <?php echo lalasea_json( $lalasea_thongtin['site_url'] . '/' ); ?>,
		"image": <?php echo lalasea_json( $lalasea_thongtin['site_url'] . '/hero.jpg' ); ?>,
		"sameAs": <?php echo lalasea_json( array( $lalasea_thongtin['facebook'], $lalasea_thongtin['tiktok'], $lalasea_thongtin['zalo'], $lalasea_thongtin['maps'] ) ); ?>
	}
	</script>
</head>
<body>
<?php wp_body_open(); ?>

	<?php /* Logo Lalasea — định nghĩa một lần, dùng lại nhiều chỗ bằng <use href="#lalasea-logo"/>. */ ?>
	<svg width="0" height="0" style="position:absolute" aria-hidden="true">
		<symbol id="lalasea-logo" viewBox="0 0 220 450">
			<?php /* Khung viên thuốc — thành hai bên hở đúng chỗ tia nắng dài xuyên qua. */ ?>
			<g fill="none" stroke="currentColor" stroke-width="5" stroke-linecap="round">
				<path d="M 35 130 A 75 75 0 0 1 185 130"/>
				<path d="M 185 130 L 185 177 M 185 193 L 185 280"/>
				<path d="M 185 280 A 75 75 0 0 1 35 280"/>
				<path d="M 35 280 L 35 193 M 35 177 L 35 130"/>
				<path d="M 20 140 L 20 280 A 90 90 0 0 0 200 280 L 200 140"/>
			</g>
			<?php /* Mặt trời — tia dài ngắn xen kẽ, tia ngang dài xuyên thủng khung. */ ?>
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
			<?php /* Chấm trang trí nhỏ quanh mặt trời. */ ?>
			<circle cx="80" cy="139" r="2.2" fill="currentColor"/>
			<circle cx="141" cy="232" r="2.2" fill="currentColor"/>
			<?php /* Sóng biển 6 lớp, thu hẹp dần theo đáy khung. */ ?>
			<g fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round">
				<path d="M 44 258 q 11 -9 22 0 t 22 0 t 22 0 t 22 0 t 22 0 t 22 0"/>
				<path d="M 44 274 q 11 -9 22 0 t 22 0 t 22 0 t 22 0 t 22 0 t 22 0"/>
				<path d="M 44 290 q 11 -9 22 0 t 22 0 t 22 0 t 22 0 t 22 0 t 22 0"/>
				<path d="M 55 306 q 11 -9 22 0 t 22 0 t 22 0 t 22 0 t 22 0"/>
				<path d="M 56 322 q 10.8 -9 21.6 0 t 21.6 0 t 21.6 0 t 21.6 0 t 21.6 0"/>
				<path d="M 70 338 q 10 -8 20 0 t 20 0 t 20 0 t 20 0"/>
			</g>
			<?php /* Chữ LALASEA cong ôm vòm — chữ to, đặt và xoay từng chữ. */ ?>
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
			<a href="<?php echo $lalasea_en ? '/en/' : '/'; ?>" class="brand" aria-label="<?php echo $lalasea_en ? 'Lalasea Bistro — Home' : 'Lalasea Bistro — Trang chủ'; ?>">
				<svg class="logo-svg" width="26" height="53"><use href="#lalasea-logo"/></svg>
				<span class="wordmark">LALASEA <span>BISTRO</span></span>
			</a>
			<?php
			/*
			 * Mục "Trang chủ" chỉ hiện trong menu gập trên điện thoại. Trên máy tính, bấm logo
			 * là về trang chủ, và bỏ mục này mới đủ chỗ cho nút đổi ngôn ngữ (DESIGN.md §5).
			 * Menu viết cố định, không dùng menu kéo-thả của WordPress: thanh menu chỉ còn dư
			 * 25px ở khổ 1001px, thêm một mục là tràn.
			 */
			?>
			<div class="nav-links" id="nav-links">
				<?php if ( $lalasea_en ) : ?>
				<a href="/en/" class="chi-menu-gap">Home</a>
				<a href="/en/#about">About</a>
				<a href="/en/menu/">Menu</a>
				<a href="/en/glamping/">Glamping</a>
				<a href="/en/events/">Events</a>
				<a href="/en/contact/">Contact</a>
				<a href="/en/#book" class="btn btn-nav">Book a table</a>
				<?php else : ?>
				<a href="/" class="chi-menu-gap">Trang chủ</a>
				<a href="/#gioi-thieu">Giới thiệu</a>
				<a href="/thuc-don/">Thực đơn</a>
				<a href="/glamping/">Glamping</a>
				<a href="/tiec-su-kien/">Tiệc &amp; Sự kiện</a>
				<a href="/tin-tuc/">Tin tức</a>
				<a href="/lien-he/">Liên hệ</a>
				<a href="/#dat-ban" class="btn btn-nav">Đặt bàn</a>
				<?php endif; ?>
			</div>
			<?php
			/*
			 * Nút đổi ngôn ngữ — luôn hiện, kể cả trên điện thoại, để khách nước ngoài
			 * không phải mở menu mới tìm thấy. Chữ ghi tên ngôn ngữ ĐÍCH.
			 */
			?>
			<a class="doi-ngon-ngu" href="<?php echo esc_url( $lalasea_trang['ban_dich'] ? $lalasea_trang['ban_dich'] : ( $lalasea_en ? '/' : '/en/' ) ); ?>" hreflang="<?php echo $lalasea_en ? 'vi' : 'en'; ?>" lang="<?php echo $lalasea_en ? 'vi' : 'en'; ?>" aria-label="<?php echo $lalasea_en ? 'VI — Xem bằng tiếng Việt' : 'EN — View in English'; ?>">
				<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.6 3.8 5.6 3.8 9s-1.3 6.4-3.8 9c-2.5-2.6-3.8-5.6-3.8-9S9.5 5.6 12 3z"/></svg>
				<?php echo $lalasea_en ? 'VI' : 'EN'; ?>
			</a>
			<button class="nav-toggle" id="nav-toggle" aria-label="<?php echo $lalasea_en ? 'Open menu' : 'Mở menu'; ?>" aria-expanded="false">
				<span></span><span></span><span></span>
			</button>
		</nav>
	</header>

	<main>

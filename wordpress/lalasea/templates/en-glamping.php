<?php
/**
 * Template Name: Lalasea EN — Glamping
 *
 * English glamping page (/en/glamping/).
 *
 * @package Lalasea
 */

$lalasea_thongtin = lalasea_data( 'thongtin' );
$lalasea_glamping = lalasea_data( 'glamping' );

lalasea_dat_trang(
	array(
		'title'        => 'Glamping – Wooden Tent Prices',
		'mo_ta'        => 'Ha Tien glamping at Lalasea — air-conditioned wooden tents in the grounds of a beachfront restaurant on To Chau beach, a few dozen metres from the sea. From 490,000 VND per person including breakfast + BBQ hotpot dinner. Check-in 14:00, call +84 907 896 949.',
		'anh_dai_dien' => '/uploads/585bccbb09ef89b1d0fe5.jpg',
		'ban_dich'     => '/glamping/',
		'ngon_ngu'     => 'en',
		'url'          => '/en/glamping/',
	)
);
get_header();
?>
<script type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "Campground",
	"@id": <?php echo lalasea_json( $lalasea_thongtin['site_url'] . '/glamping/#glamping' ); ?>,
	"name": "Glamping Lalasea",
	"alternateName": ["Lalasea Bistro Camping", "Camping Lalasea Hà Tiên", "Lều gỗ Lalasea"],
	"description": "Glamping area with air-conditioned A-frame wooden tents inside the grounds of Lalasea Bistro, a few dozen metres from To Chau beach, Ha Tien. Packages include a BBQ – hotpot dinner and breakfast.",
	"url": <?php echo lalasea_json( $lalasea_thongtin['site_url'] . '/en/glamping/' ); ?>,
	"image": <?php echo lalasea_json( $lalasea_thongtin['site_url'] . '/uploads/585bccbb09ef89b1d0fe5.jpg' ); ?>,
	"telephone": <?php echo lalasea_json( lalasea_tel_quoc_te( $lalasea_thongtin['dien_thoai_goi'] ) ); ?>,
	"address": { "@type": "PostalAddress", "streetAddress": "QL80, Tô Châu", "addressLocality": "Hà Tiên", "addressRegion": "An Giang", "addressCountry": "VN" },
	"geo": { "@type": "GeoCoordinates", "latitude": <?php echo lalasea_json( $lalasea_thongtin['vi_do'] ); ?>, "longitude": <?php echo lalasea_json( $lalasea_thongtin['kinh_do'] ); ?> },
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
	"containedInPlace": { "@id": <?php echo lalasea_json( $lalasea_thongtin['site_url'] . '/#nhahang' ); ?> },
	"sameAs": [<?php echo lalasea_json( $lalasea_thongtin['facebook'] ); ?>, <?php echo lalasea_json( $lalasea_thongtin['tiktok'] ); ?>, <?php echo lalasea_json( $lalasea_thongtin['maps'] ); ?>]
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
	<p class="gprice-note reveal"><?php echo esc_html( $lalasea_glamping['gia_ghi_chu_en'] ? $lalasea_glamping['gia_ghi_chu_en'] : $lalasea_glamping['gia_ghi_chu'] ); ?></p>
	<div class="gprice-grid reveal">
		<?php foreach ( $lalasea_glamping['bang_gia'] as $lalasea_stt => $lalasea_bang ) : ?>
		<div class="gprice-card<?php echo 1 === $lalasea_stt ? ' hot' : ''; ?>">
		<div class="head">
			<h3><?php echo esc_html( $lalasea_bang['ten_en'] ? $lalasea_bang['ten_en'] : $lalasea_bang['ten'] ); ?></h3>
			<span><?php echo esc_html( $lalasea_bang['ngay_en'] ? $lalasea_bang['ngay_en'] : $lalasea_bang['ngay'] ); ?></span>
		</div>
		<div class="rows">
			<?php foreach ( $lalasea_bang['muc'] as $lalasea_muc ) : ?>
			<div class="row"><span class="k"><?php echo esc_html( $lalasea_muc['khach_en'] ? $lalasea_muc['khach_en'] : $lalasea_muc['khach'] ); ?></span><span class="g"><?php echo esc_html( lalasea_gia_en( $lalasea_muc['gia'] ) ); ?></span></div>
			<?php endforeach; ?>
		</div>
		</div>
		<?php endforeach; ?>
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
		<p class="mota"><?php echo esc_html( $lalasea_glamping['bua_sang_mota_en'] ? $lalasea_glamping['bua_sang_mota_en'] : $lalasea_glamping['bua_sang_mota'] ); ?></p>
		<div class="cols">
			<div>
			<h3>Breakfast dishes</h3>
			<ul>
			<?php foreach ( ( $lalasea_glamping['bua_sang_mon_en'] ? $lalasea_glamping['bua_sang_mon_en'] : $lalasea_glamping['bua_sang_mon'] ) as $lalasea_mon ) : ?>
				<li><?php echo esc_html( $lalasea_mon ); ?></li><?php endforeach; ?></ul>
			</div>
			<div>
			<h3>Drinks</h3>
			<ul>
			<?php foreach ( ( $lalasea_glamping['bua_sang_nuoc_en'] ? $lalasea_glamping['bua_sang_nuoc_en'] : $lalasea_glamping['bua_sang_nuoc'] ) as $lalasea_mon ) : ?>
				<li><?php echo esc_html( $lalasea_mon ); ?></li><?php endforeach; ?></ul>
			</div>
		</div>
		</div>
		<div class="gpack-card reveal">
		<div class="ph">🔥 Dinner — BBQ &amp; Hotpot</div>
		<p class="mota"><?php echo esc_html( $lalasea_glamping['bua_toi_mota_en'] ? $lalasea_glamping['bua_toi_mota_en'] : $lalasea_glamping['bua_toi_mota'] ); ?></p>
		<div class="bbq-rows">
			<?php foreach ( $lalasea_glamping['bbq'] as $lalasea_mon ) : ?>
			<div class="row"><span class="k"><?php echo esc_html( $lalasea_mon['mon_en'] ? $lalasea_mon['mon_en'] : $lalasea_mon['mon'] ); ?></span><span class="g"><?php echo esc_html( $lalasea_mon['luong_en'] ? $lalasea_mon['luong_en'] : $lalasea_mon['luong'] ); ?></span></div>
			<?php endforeach; ?>
		</div>
		<p class="chu-thich">⭐ Dishes and portions are served according to the number of guests in the tent.</p>
		</div>
	</div>
	</div>
</section>

<!-- ===== TENT PHOTOS ===== -->
<?php if ( ! empty( $lalasea_glamping['anh'] ) ) : ?>
<section class="gallery">
	<div class="container">
	<div class="section-title reveal">
		<span class="script-accent">Inside your tent</span>
		<h2>Tent photos</h2>
	</div>
	<div class="gallery-grid reveal">
		<?php foreach ( $lalasea_glamping['anh'] as $lalasea_anh ) : ?>
		<div class="tile"><img src="<?php echo esc_url( $lalasea_anh['file'] ); ?>" alt="<?php echo esc_attr( $lalasea_anh['mota_en'] ? $lalasea_anh['mota_en'] : 'Lalasea glamping tent, Ha Tien' ); ?>" loading="lazy">
			<?php if ( $lalasea_anh['mota_en'] ) : ?>
			<div class="cap"><?php echo esc_html( $lalasea_anh['mota_en'] ); ?></div><?php endif; ?></div>
		<?php endforeach; ?>
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
		<div class="time"><?php echo esc_html( $lalasea_glamping['checkin_en'] ? $lalasea_glamping['checkin_en'] : $lalasea_glamping['checkin'] ); ?></div>
		</div>
		<div class="grule-card">
		<div class="ic">🌙</div>
		<h3>Check-out</h3>
		<div class="time"><?php echo esc_html( $lalasea_glamping['checkout_en'] ? $lalasea_glamping['checkout_en'] : $lalasea_glamping['checkout'] ); ?></div>
		</div>
	</div>
	<ul class="gnote-list reveal">
		<?php foreach ( ( $lalasea_glamping['luu_y_en'] ? $lalasea_glamping['luu_y_en'] : $lalasea_glamping['luu_y'] ) as $lalasea_luu_y ) : ?>
			<li><?php echo esc_html( $lalasea_luu_y ); ?></li><?php endforeach; ?>
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
		<a class="hotline" href="<?php echo esc_url( 'tel:' . lalasea_tel_quoc_te( $lalasea_thongtin['dien_thoai_goi'] ) ); ?>" style="font-size: clamp(30px, 5vw, 44px);">📞 <?php echo esc_html( lalasea_sdt_quoc_te( $lalasea_thongtin['dien_thoai'] ) ); ?></a>
		<div style="margin-top:22px;">
		<a href="<?php echo esc_url( 'tel:' . lalasea_tel_quoc_te( $lalasea_thongtin['dien_thoai_goi'] ) ); ?>" class="btn btn-lg">Call to reserve</a>
		<a href="<?php echo esc_url( $lalasea_thongtin['zalo'] ); ?>" target="_blank" rel="noopener" class="btn btn-ghost" style="margin-left:12px;">💬 Message on Zalo</a>
		</div>
	</div>
	</div>
</section>

<?php
get_footer();

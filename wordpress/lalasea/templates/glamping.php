<?php
/**
 * Template Name: Lalasea — Glamping
 *
 * Trang Glamping (/glamping/): bảng giá, bữa sáng, BBQ, lưu ý.
 *
 * @package Lalasea
 */

$lalasea_thongtin = lalasea_data( 'thongtin' );
$lalasea_glamping = lalasea_data( 'glamping' );

lalasea_dat_trang(
	array(
		'title'        => 'Glamping – Bảng giá ngủ lều gỗ tiện nghi',
		'mo_ta'        => 'Glamping Hà Tiên tại Lalasea — lều gỗ máy lạnh trong khuôn viên nhà hàng bên bờ biển Tô Châu, cách biển vài chục mét. Giá từ 490.000đ/người đã gồm bữa sáng + BBQ lẩu tối. Check-in 14h, hotline 0907 896 949.',
		'anh_dai_dien' => '/uploads/585bccbb09ef89b1d0fe5.jpg',
		'ban_dich'     => '/en/glamping/',
		'url'          => '/glamping/',
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
	"description": "Khu glamping lều gỗ chữ A có máy lạnh nằm trong khuôn viên nhà hàng Lalasea Bistro, cách bờ biển Tô Châu vài chục mét. Giá trọn gói đã gồm bữa tối BBQ – lẩu và bữa sáng.",
	"url": <?php echo lalasea_json( $lalasea_thongtin['site_url'] . '/glamping/' ); ?>,
	"image": <?php echo lalasea_json( $lalasea_thongtin['site_url'] . '/uploads/585bccbb09ef89b1d0fe5.jpg' ); ?>,
	"telephone": <?php echo lalasea_json( lalasea_tel_quoc_te( $lalasea_thongtin['dien_thoai_goi'] ) ); ?>,
	"address": { "@type": "PostalAddress", "streetAddress": "QL80, Tô Châu", "addressLocality": "Hà Tiên", "addressRegion": "An Giang", "addressCountry": "VN" },
	"geo": { "@type": "GeoCoordinates", "latitude": <?php echo lalasea_json( $lalasea_thongtin['vi_do'] ); ?>, "longitude": <?php echo lalasea_json( $lalasea_thongtin['kinh_do'] ); ?> },
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
	"containedInPlace": { "@id": <?php echo lalasea_json( $lalasea_thongtin['site_url'] . '/#nhahang' ); ?> },
	"sameAs": [<?php echo lalasea_json( $lalasea_thongtin['facebook'] ); ?>, <?php echo lalasea_json( $lalasea_thongtin['tiktok'] ); ?>, <?php echo lalasea_json( $lalasea_thongtin['maps'] ); ?>]
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
	<p class="gprice-note reveal"><?php echo esc_html( $lalasea_glamping['gia_ghi_chu'] ); ?></p>
	<div class="gprice-grid reveal">
		<?php foreach ( $lalasea_glamping['bang_gia'] as $lalasea_stt => $lalasea_bang ) : ?>
		<div class="gprice-card<?php echo 1 === $lalasea_stt ? ' hot' : ''; ?>">
		<div class="head">
			<h3><?php echo esc_html( $lalasea_bang['ten'] ); ?></h3>
			<span><?php echo esc_html( $lalasea_bang['ngay'] ); ?></span>
		</div>
		<div class="rows">
			<?php foreach ( $lalasea_bang['muc'] as $lalasea_muc ) : ?>
			<div class="row"><span class="k"><?php echo esc_html( $lalasea_muc['khach'] ); ?></span><span class="g"><?php echo esc_html( $lalasea_muc['gia'] ); ?></span></div>
			<?php endforeach; ?>
		</div>
		</div>
		<?php endforeach; ?>
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
		<p class="mota"><?php echo esc_html( $lalasea_glamping['bua_sang_mota'] ); ?></p>
		<div class="cols">
			<div>
			<h3>Món ăn sáng</h3>
			<ul>
			<?php foreach ( $lalasea_glamping['bua_sang_mon'] as $lalasea_mon ) : ?>
				<li><?php echo esc_html( $lalasea_mon ); ?></li><?php endforeach; ?></ul>
			</div>
			<div>
			<h3>Thức uống</h3>
			<ul>
			<?php foreach ( $lalasea_glamping['bua_sang_nuoc'] as $lalasea_mon ) : ?>
				<li><?php echo esc_html( $lalasea_mon ); ?></li><?php endforeach; ?></ul>
			</div>
		</div>
		</div>
		<div class="gpack-card reveal">
		<div class="ph">🔥 Bữa tối — BBQ &amp; Lẩu</div>
		<p class="mota"><?php echo esc_html( $lalasea_glamping['bua_toi_mota'] ); ?></p>
		<div class="bbq-rows">
			<?php foreach ( $lalasea_glamping['bbq'] as $lalasea_mon ) : ?>
			<div class="row"><span class="k"><?php echo esc_html( $lalasea_mon['mon'] ); ?></span><span class="g"><?php echo esc_html( $lalasea_mon['luong'] ); ?></span></div>
			<?php endforeach; ?>
		</div>
		<p class="chu-thich">⭐ Số lượng và định lượng món được phục vụ tương ứng với số khách trong lều.</p>
		</div>
	</div>
	</div>
</section>

<!-- ===== HÌNH ẢNH LỀU ===== -->
<?php if ( ! empty( $lalasea_glamping['anh'] ) ) : ?>
<section class="gallery">
	<div class="container">
	<div class="section-title reveal">
		<span class="script-accent">Bên trong chiếc lều của bạn</span>
		<h2>Hình ảnh lều</h2>
	</div>
	<div class="gallery-grid reveal">
		<?php foreach ( $lalasea_glamping['anh'] as $lalasea_anh ) : ?>
		<div class="tile"><img src="<?php echo esc_url( $lalasea_anh['file'] ); ?>" alt="<?php echo esc_attr( $lalasea_anh['mota'] ? $lalasea_anh['mota'] : 'Lều glamping Lalasea Hà Tiên' ); ?>" loading="lazy">
			<?php if ( $lalasea_anh['mota'] ) : ?>
			<div class="cap"><?php echo esc_html( $lalasea_anh['mota'] ); ?></div><?php endif; ?></div>
		<?php endforeach; ?>
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
		<div class="time"><?php echo esc_html( $lalasea_glamping['checkin'] ); ?></div>
		</div>
		<div class="grule-card">
		<div class="ic">🌙</div>
		<h3>Thời gian trả lều (Check-out)</h3>
		<div class="time"><?php echo esc_html( $lalasea_glamping['checkout'] ); ?></div>
		</div>
	</div>
	<ul class="gnote-list reveal">
		<?php foreach ( $lalasea_glamping['luu_y'] as $lalasea_luu_y ) : ?>
			<li><?php echo esc_html( $lalasea_luu_y ); ?></li><?php endforeach; ?>
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
		<a class="hotline" href="<?php echo esc_url( 'tel:' . $lalasea_thongtin['dien_thoai_goi'] ); ?>" style="font-size: clamp(30px, 5vw, 44px);">📞 <?php echo esc_html( $lalasea_thongtin['dien_thoai'] ); ?></a>
		<div style="margin-top:22px;">
		<a href="<?php echo esc_url( 'tel:' . $lalasea_thongtin['dien_thoai_goi'] ); ?>" class="btn btn-lg">Gọi giữ lều</a>
		<a href="<?php echo esc_url( $lalasea_thongtin['zalo'] ); ?>" target="_blank" rel="noopener" class="btn btn-ghost" style="margin-left:12px;">💬 Nhắn Zalo</a>
		</div>
	</div>
	</div>
</section>

<?php
get_footer();

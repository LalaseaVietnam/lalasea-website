<?php
/**
 * Template Name: Lalasea — Liên hệ
 *
 * Trang Liên hệ & Đường đi (/lien-he/).
 *
 * @package Lalasea
 */

$lalasea_thongtin = lalasea_data( 'thongtin' );

lalasea_dat_trang(
	array(
		'title'    => 'Liên hệ & Đường đi',
		'mo_ta'    => 'Nhà hàng Lalasea Bistro ở đâu tại Hà Tiên? Địa chỉ QL80, Tô Châu, Hà Tiên (An Giang), mở cửa tất cả các ngày 08:00–23:00, cuối tuần từ 07:00. Bản đồ đường đi, SĐT đặt bàn 0907 896 949, Zalo, Facebook.',
		'ban_dich' => '/en/contact/',
		'url'      => '/lien-he/',
	)
);
get_header();
?>
<div class="page-hero">
	<div class="kicker">Ghé thăm chúng tôi</div>
	<h1>Liên hệ &amp; Đường đi</h1>
	<p>Lalasea Bistro luôn sẵn sàng chào đón bạn bên bờ biển Tô Châu, Hà&nbsp;Tiên.</p>
</div>

<section class="contact">
	<div class="container">
	<div class="contact-grid">
		<div>
		<div class="info-row">
			<div class="ic">📍</div>
			<div><strong>Địa chỉ</strong><span><?php echo esc_html( $lalasea_thongtin['dia_chi'] ); ?></span></div>
		</div>
		<div class="info-row">
			<div class="ic">📞</div>
			<div><strong>Điện thoại / Zalo</strong><span><?php echo esc_html( $lalasea_thongtin['dien_thoai'] ); ?></span></div>
		</div>
		<div class="info-row">
			<div class="ic">🕘</div>
			<div><strong>Giờ mở cửa</strong><span>Thứ Hai – Thứ Sáu: <?php echo esc_html( $lalasea_thongtin['gio_mo_cua'] ); ?><br>Thứ Bảy &amp; Chủ Nhật: <?php echo esc_html( $lalasea_thongtin['gio_cuoi_tuan'] ); ?><br><?php echo esc_html( $lalasea_thongtin['gio_ghi_chu'] ); ?></span></div>
		</div>
		<div class="info-row">
			<div class="ic">📘</div>
			<div><strong>Facebook</strong><span><a href="<?php echo esc_url( $lalasea_thongtin['facebook'] ); ?>" target="_blank" rel="noopener">Lalasea Bistro Hà Tiên</a></span></div>
		</div>
		<div class="info-row">
			<div class="ic">🎵</div>
			<div><strong>TikTok</strong><span><a href="<?php echo esc_url( $lalasea_thongtin['tiktok'] ); ?>" target="_blank" rel="noopener">@lalasea_bistro</a></span></div>
		</div>
		</div>
		<div>
		<a href="<?php echo esc_url( 'tel:' . $lalasea_thongtin['dien_thoai_goi'] ); ?>" class="btn" style="display:block; text-align:center; margin-bottom:14px;">📞 Gọi đặt bàn ngay</a>
		<a href="<?php echo esc_url( $lalasea_thongtin['zalo'] ); ?>" target="_blank" rel="noopener" class="btn btn-ghost" style="display:block; text-align:center; margin-bottom:14px;">💬 Nhắn Zalo</a>
		<a href="<?php echo esc_url( $lalasea_thongtin['maps'] ); ?>" target="_blank" rel="noopener" class="btn btn-ghost" style="display:block; text-align:center; margin-bottom:14px;">🗺️ Mở Google Maps</a>
		<a href="<?php echo esc_url( $lalasea_thongtin['google_review'] ); ?>" target="_blank" rel="noopener" class="btn btn-ghost" style="display:block; text-align:center;">⭐ Đánh giá trên Google</a>
		</div>
	</div>
	<div class="map-wrap">
		<iframe src="https://www.google.com/maps?q=Lalasea+Bistro+QL80+To+Chau+Ha+Tien&output=embed" loading="lazy" title="Bản đồ đường đi đến Lalasea Bistro" referrerpolicy="no-referrer-when-downgrade"></iframe>
	</div>
	</div>
</section>

<?php
get_footer();

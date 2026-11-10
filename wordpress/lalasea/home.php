<?php
/**
 * Trang danh sách Tin tức & Ưu đãi (/tin-tuc/) — WordPress tự dùng file này cho trang bài viết.
 *
 * @package Lalasea
 */

$lalasea_baiviet = lalasea_danh_sach_bai();

lalasea_dat_trang(
	array(
		'title' => 'Tin tức & Ưu đãi',
		'mo_ta' => 'Tin tức, sự kiện, khuyến mãi mới nhất tại nhà hàng Lalasea Bistro Hà Tiên — ưu đãi đặt tiệc, sự kiện bên biển, món mới theo mùa.',
		'url'   => '/tin-tuc/',
	)
);
get_header();
?>
<div class="page-hero">
	<div class="kicker">Có gì mới ở Lalasea?</div>
	<h1>Tin tức &amp; Ưu đãi</h1>
	<p>Sự kiện, khuyến mãi và những câu chuyện mới nhất từ nhà hàng.</p>
</div>

<section class="news">
	<div class="container">
	<?php if ( ! empty( $lalasea_baiviet ) ) : ?>
	<div class="news-grid">
		<?php foreach ( $lalasea_baiviet as $lalasea_bai ) : ?>
		<a class="news-card" href="<?php echo esc_url( $lalasea_bai['url'] ); ?>">
		<div class="thumb">
			<?php if ( $lalasea_bai['data']['anh_dai_dien'] ) : ?>
			<img src="<?php echo esc_url( $lalasea_bai['data']['anh_dai_dien'] ); ?>" alt="<?php echo esc_attr( $lalasea_bai['data']['title'] ); ?>" loading="lazy"><?php endif; ?></div>
		<div class="body">
			<div class="date"><?php echo esc_html( lalasea_ngay_vn( $lalasea_bai['date'] ) ); ?></div>
			<h3><?php echo esc_html( $lalasea_bai['data']['title'] ); ?></h3>
			<p><?php echo esc_html( $lalasea_bai['data']['mo_ta'] ); ?></p>
			<span class="more">Đọc tiếp →</span>
		</div>
		</a>
		<?php endforeach; ?>
	</div>
	<?php else : ?>
	<p style="text-align:center; color:var(--chu-nhat);">Chưa có bài viết nào. Hãy quay lại sau nhé!</p>
	<?php endif; ?>
	</div>
</section>

<?php
get_footer();

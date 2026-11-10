<?php
/**
 * Một bài tin tức (/tin-tuc/<tên-bài>/): schema Article + BreadcrumbList, ảnh đầu bài, nội dung.
 *
 * @package Lalasea
 */

the_post();
$lalasea_thongtin = lalasea_data( 'thongtin' );
$lalasea_bai      = lalasea_bai_hien_tai();

lalasea_dat_trang(
	array(
		'title'        => $lalasea_bai['title'],
		'mo_ta'        => $lalasea_bai['mo_ta'],
		'anh_dai_dien' => $lalasea_bai['anh_dai_dien'],
		'og_type'      => 'article',
		'url'          => $lalasea_bai['url'],
	)
);
get_header();
?>
<script type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "Article",
	"headline": <?php echo lalasea_json( $lalasea_bai['title'] ); ?>,
	"description": <?php echo lalasea_json( $lalasea_bai['mo_ta'] ); ?>,
	"image": <?php echo lalasea_json( $lalasea_thongtin['site_url'] . ( $lalasea_bai['anh_dai_dien'] ? $lalasea_bai['anh_dai_dien'] : '/hero.jpg' ) ); ?>,
	"datePublished": <?php echo lalasea_json( lalasea_iso( $lalasea_bai['date'] ) ); ?>,
	"author": { "@type": "Organization", "name": <?php echo lalasea_json( $lalasea_thongtin['ten'] ); ?> },
	"publisher": { "@type": "Organization", "name": <?php echo lalasea_json( $lalasea_thongtin['ten'] ); ?>, "logo": { "@type": "ImageObject", "url": <?php echo lalasea_json( $lalasea_thongtin['site_url'] . '/favicon.svg' ); ?> } },
	"mainEntityOfPage": <?php echo lalasea_json( $lalasea_thongtin['site_url'] . $lalasea_bai['url'] ); ?>,
	"inLanguage": "vi-VN",
	"dateModified": <?php echo lalasea_json( lalasea_iso( $lalasea_bai['date'] ) ); ?>,
	"about": { "@id": "https://lalasea.vn/#nhahang" }
}
</script>

<script type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "BreadcrumbList",
	"itemListElement": [
	{ "@type": "ListItem", "position": 1, "name": "Trang chủ", "item": <?php echo lalasea_json( $lalasea_thongtin['site_url'] . '/' ); ?> },
	{ "@type": "ListItem", "position": 2, "name": "Tin tức", "item": <?php echo lalasea_json( $lalasea_thongtin['site_url'] . '/tin-tuc/' ); ?> },
	{ "@type": "ListItem", "position": 3, "name": <?php echo lalasea_json( $lalasea_bai['title'] ); ?> }
	]
}
</script>

<article>
	<div class="post-hero">
	<div class="date"><?php echo esc_html( lalasea_ngay_chu( $lalasea_bai['date'] ) ); ?></div>
	<h1><?php echo esc_html( $lalasea_bai['title'] ); ?></h1>
	</div>
	<?php if ( $lalasea_bai['anh_dai_dien'] ) : ?>
	<div class="container" style="max-width:860px; margin-top:-30px;">
	<img src="<?php echo esc_url( $lalasea_bai['anh_dai_dien'] ); ?>" alt="<?php echo esc_attr( $lalasea_bai['title'] ); ?>" style="border-radius:18px; box-shadow:0 18px 40px rgba(11,79,108,.18); width:100%; max-height:440px; object-fit:cover;">
	</div>
	<?php endif; ?>
	<div class="post-body">
	<?php echo $lalasea_bai['noi_dung']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nội dung bài đã qua bộ lọc nội dung của WordPress. ?>
	</div>
	<div class="post-back">
	<a href="/tin-tuc/" class="btn btn-ghost nen-sang">← Xem tin khác</a>
	</div>
</article>

<?php
get_footer();

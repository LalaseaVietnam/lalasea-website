<?php
/**
 * Trang thường do chủ quán tự tạo thêm (Trang → Thêm trang mới).
 *
 * Các trang chính của web dùng template riêng trong templates/. Trang này dùng lại
 * khung tiêu đề + nội dung của bài viết để giao diện đồng bộ.
 *
 * @package Lalasea
 */

the_post();

lalasea_dat_trang(
	array(
		'title' => get_the_title(),
		'mo_ta' => has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : null,
		'url'   => wp_make_link_relative( get_permalink() ),
	)
);
get_header();
?>
<article>
	<div class="post-hero">
		<h1><?php the_title(); ?></h1>
	</div>
	<div class="post-body">
		<?php echo lalasea_noi_dung( get_the_content() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nội dung trang đã qua bộ lọc nội dung của WordPress. ?>
	</div>
</article>
<?php
get_footer();

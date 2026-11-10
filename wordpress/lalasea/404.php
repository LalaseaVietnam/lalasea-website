<?php
/**
 * Trang 404 — không tìm thấy.
 *
 * @package Lalasea
 */

lalasea_dat_trang(
	array(
		'title'       => 'Không tìm thấy trang',
		'khong_index' => true,
	)
);
get_header();
?>
<div class="page-404">
	<h1>404</h1>
	<p>Trang bạn tìm không tồn tại hoặc đã được chuyển đi nơi khác.</p>
	<a href="/" class="btn">← Về trang chủ</a>
	<p lang="en" style="margin-top:20px;">Page not found. <a href="/en/">Go to the English homepage →</a></p>
</div>

<?php
get_footer();

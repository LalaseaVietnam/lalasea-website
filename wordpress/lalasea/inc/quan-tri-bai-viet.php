<?php
/**
 * Bài tin tức dùng hai ô CÓ SẴN ở thanh bên phải khung soạn bài của WordPress.
 *
 * - "Mô tả ngắn" (excerpt): mô tả ở danh sách tin, trên Google và khi chia sẻ.
 * - "Ảnh đại diện": ảnh đầu bài, ảnh ở danh sách tin, ảnh chia sẻ Facebook/Zalo.
 *
 * Dùng ô có sẵn thay vì tự làm hộp nhập riêng: nằm ngay chỗ dễ thấy, và hướng dẫn
 * WordPress nào trên mạng cũng áp dụng được. Các bài nhập từ web cũ có ảnh đại diện
 * là đường dẫn (/anh/...) lưu ở trường ẩn _lalasea_anh_dai_dien — vẫn hiện bình
 * thường; chọn ảnh đại diện mới thì ảnh mới được dùng.
 *
 * @package Lalasea
 */

defined( 'ABSPATH' ) || exit;

/**
 * Bật ô "Ảnh đại diện" và "Mô tả ngắn" cho bài viết.
 */
function lalasea_ho_tro_bai_viet() {
	add_theme_support( 'post-thumbnails', array( 'post' ) );
	add_post_type_support( 'post', 'excerpt' );
}
add_action( 'after_setup_theme', 'lalasea_ho_tro_bai_viet' );

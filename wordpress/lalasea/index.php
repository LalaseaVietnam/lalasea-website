<?php
/**
 * Mẫu dự phòng bắt buộc của WordPress.
 *
 * Mọi màn hình không có template riêng (tìm kiếm, lưu trữ theo danh mục/tháng...)
 * đều hiện trang 404 của web — web này chỉ có các trang trong menu.
 *
 * @package Lalasea
 */

status_header( 404 );
get_template_part( '404' );

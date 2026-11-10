<?php
/**
 * Mẫu dự phòng: mọi màn hình WordPress không có template riêng
 * (tìm kiếm, lưu trữ theo danh mục/tháng...) đều hiện trang 404 của web —
 * web này chỉ có các trang đã liệt kê trong menu.
 */
status_header(404);
require __DIR__ . '/404.php';

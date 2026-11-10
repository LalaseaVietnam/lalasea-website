#!/usr/bin/env bash
# ============================================================
# ĐÓNG GÓI ĐỂ TẢI LÊN HOSTING WORDPRESS
#
#   bash wordpress/tools/dong-goi.sh
#
# Tạo 2 file trong wordpress/dist/:
#   1. lalasea-theme.zip      → WordPress: Giao diện → Thêm mới → Tải lên
#   2. thu-muc-goc-web.zip    → giải nén vào thư mục gốc của web (public_html),
#                               cùng chỗ với wp-config.php. Chứa ảnh, menu,
#                               biểu tượng... giữ nguyên đường dẫn cũ (/anh/..., /menu/...)
#                               để Google Ảnh và các link cũ không bị gãy.
# ============================================================
set -euo pipefail
cd "$(dirname "$0")/../.."

node wordpress/tools/chep-tu-eleventy.mjs

RA=wordpress/dist
mkdir -p "$RA"
rm -f "$RA/lalasea-theme.zip" "$RA/thu-muc-goc-web.zip"

(cd wordpress && zip -qr "dist/lalasea-theme.zip" lalasea -x '*.DS_Store')

TAM=$(mktemp -d)
cp -R anh menu uploads hero.jpg cc97fe9ea5cf05654fd1deeed594a344.txt "$TAM/"
cp src/favicon.svg src/favicon.ico src/icon-192.png src/apple-touch-icon.png "$TAM/"
(cd "$TAM" && zip -qr - .) > "$RA/thu-muc-goc-web.zip"
rm -r "$TAM"

ls -lh "$RA"

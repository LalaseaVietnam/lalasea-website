#!/usr/bin/env bash
# ============================================================
# TRIỂN KHAI THEME LALASEA LÊN HOSTING WORDPRESS (qua FTP / FTPS / SFTP)
#
# Chạy tự động trong GitHub Actions (.github/workflows/wordpress.yml), mật khẩu
# lấy từ GitHub Secrets. Chạy tay cũng được:
#
#   FTP_HOST=... FTP_USER=... FTP_PASS=... bash wordpress/tools/trien-khai.sh
#
# Biến môi trường:
#   FTP_HOST       (bắt buộc) máy chủ FTP, ví dụ ftp.lalasea.vn hoặc IP hosting
#   FTP_USER       (bắt buộc) tài khoản FTP
#   FTP_PASS       (bắt buộc) mật khẩu FTP — đọc qua biến môi trường, không lộ ra dòng lệnh
#   FTP_PORT       cổng (mặc định: 21, hoặc 22 với sftp)
#   FTP_GIAO_THUC  ftp (mặc định — tự dùng mã hoá TLS nếu máy chủ hỗ trợ) | ftps | sftp
#   WP_THU_MUC     thư mục gốc của WordPress trên hosting (nơi có wp-load.php).
#                  Để trống thì script tự dò.
#   WP_DIA_CHI     địa chỉ web (mặc định: site_url trong src/_data/thongtin.json)
#   WP_IP          IP hosting — dùng để kiểm tra TRƯỚC khi trỏ tên miền sang hosting
#   KIEM_TRA       1 (mặc định) = sau khi đưa lên thì so từng trang với web Eleventy
#
# Các bước:
#   1. Chép CSS / dữ liệu / bài viết mới nhất từ src/ vào theme.
#   2. Tìm thư mục WordPress trên hosting.
#   3. Đưa lên: theme, ảnh (/anh, /menu, /uploads), biểu tượng, plugin kích hoạt.
#   4. Bổ sung luật đường dẫn đẹp vào .htaccess nếu thiếu.
#   5. Mở trang chủ để plugin kích hoạt chạy (bật theme, dựng trang, tiếng Việt).
#   6. So từng trang trên hosting với web Eleventy — khác là báo lỗi.
# ============================================================
set -euo pipefail
cd "$(dirname "$0")/../.."

xanh() { printf '\033[32m%s\033[0m\n' "$*"; }
do_() { printf '\033[31m%s\033[0m\n' "$*" >&2; }
buoc() { printf '\n\033[1m▶ %s\033[0m\n' "$*"; }

: "${FTP_HOST:?Thiếu FTP_HOST}"
: "${FTP_USER:?Thiếu FTP_USER}"
: "${FTP_PASS:?Thiếu FTP_PASS}"
FTP_GIAO_THUC="${FTP_GIAO_THUC:-ftp}"
KIEM_TRA="${KIEM_TRA:-1}"
WP_DIA_CHI="${WP_DIA_CHI:-$(node -p "require('./src/_data/thongtin.json').site_url")}"
WP_DIA_CHI="${WP_DIA_CHI%/}"

case "$FTP_GIAO_THUC" in
  sftp) FTP_PORT="${FTP_PORT:-22}"; DIA_CHI_FTP="sftp://$FTP_HOST" ;;
  ftp | ftps) FTP_PORT="${FTP_PORT:-21}"; DIA_CHI_FTP="ftp://$FTP_HOST" ;;
  *) do_ "FTP_GIAO_THUC phải là ftp, ftps hoặc sftp"; exit 2 ;;
esac

# Mật khẩu đưa cho lftp qua biến môi trường (--env-password), không nằm trên dòng lệnh.
export LFTP_PASSWORD="$FTP_PASS"
CAI_DAT="set net:max-retries 3; set net:timeout 30; set net:reconnect-interval-base 5;
set ftp:passive-mode true; set ftp:ssl-allow true; set ssl:verify-certificate no;
set sftp:auto-confirm yes; set mirror:parallel-transfer-count 4;"
[ "$FTP_GIAO_THUC" = ftps ] && CAI_DAT="$CAI_DAT set ftp:ssl-force true; set ftp:ssl-protect-data true;"
ftp_() { lftp -c "$CAI_DAT open --env-password -u \"$FTP_USER\" -p $FTP_PORT $DIA_CHI_FTP; $*"; }

# ---------- 1. Dữ liệu mới nhất ----------
buoc "Chép CSS, dữ liệu, bài viết từ src/ vào theme"
node wordpress/tools/chep-tu-eleventy.mjs

# ---------- 2. Tìm thư mục WordPress ----------
buoc "Kết nối $FTP_GIAO_THUC://$FTP_HOST:$FTP_PORT và tìm thư mục WordPress"
if ! loi_ket_noi="$(ftp_ "cls -1 ." 2>&1 >/dev/null)"; then
  do_ "Không kết nối hoặc không đăng nhập được FTP: ${loi_ket_noi:-không rõ lý do}"
  do_ "→ Kiểm tra lại máy chủ, tài khoản, mật khẩu FTP (WP_FTP_HOST / WP_FTP_USER / WP_FTP_PASS),"
  do_ "  và giao thức: hosting chỉ cho SFTP thì đặt WP_FTP_GIAO_THUC=sftp."
  exit 1
fi
ten_mien="$(node -p "new URL('$WP_DIA_CHI').hostname")"
if [ -n "${WP_THU_MUC:-}" ]; then
  ung_vien=("$WP_THU_MUC")
else
  # Vị trí quen thuộc của các loại hosting: cPanel, DirectAdmin, Plesk, tài khoản FTP riêng cho web
  ung_vien=(public_html "domains/$ten_mien/public_html" httpdocs www htdocs html "$ten_mien" . )
fi
GOC=""
for d in "${ung_vien[@]}"; do
  if ftp_ "cls -1 $d/wp-load.php" >/dev/null 2>&1; then GOC="$d"; break; fi
done
if [ -z "$GOC" ]; then
  do_ "Không tìm thấy WordPress (file wp-load.php) trên hosting. Đã thử: ${ung_vien[*]}"
  do_ "→ Cài WordPress trên hosting trước, hoặc đặt biến WP_THU_MUC đúng thư mục."
  exit 1
fi
xanh "✓ WordPress nằm ở: $GOC/"

# ---------- 3. Đưa file lên ----------
buoc "Đưa theme lên $GOC/wp-content/themes/lalasea/"
ftp_ "mkdir -p -f $GOC/wp-content/themes; mirror -R --delete --no-perms --exclude-glob .DS_Store wordpress/lalasea $GOC/wp-content/themes/lalasea"
xanh "✓ Theme"

buoc "Đưa ảnh, menu, biểu tượng lên thư mục gốc (giữ nguyên đường dẫn /anh/..., /menu/...)"
# --ignore-time: chỉ gửi file khác kích thước — chạy lại không phải gửi lại 30MB ảnh
for d in anh menu uploads; do
  ftp_ "mirror -R --no-perms --ignore-time --exclude-glob .DS_Store $d $GOC/$d"
done
ftp_ "cd $GOC; put hero.jpg cc97fe9ea5cf05654fd1deeed594a344.txt src/favicon.svg src/favicon.ico src/icon-192.png src/apple-touch-icon.png"
xanh "✓ Ảnh và biểu tượng"

# ---------- 4. .htaccess ----------
buoc "Kiểm tra luật đường dẫn đẹp trong .htaccess"
TAM="$(mktemp -d)"
trap 'rm -rf "$TAM"' EXIT
ftp_ "get $GOC/.htaccess -o $TAM/htaccess" >/dev/null 2>&1 || : > "$TAM/htaccess"
if grep -q "# BEGIN WordPress" "$TAM/htaccess"; then
  xanh "✓ .htaccess đã có luật của WordPress"
else
  # Giữ nguyên mọi dòng hosting đã ghi (chọn phiên bản PHP...), chỉ thêm khối của WordPress
  cat >> "$TAM/htaccess" <<'HT'

# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
HT
  ftp_ "put $TAM/htaccess -o $GOC/.htaccess"
  xanh "✓ Đã thêm luật của WordPress vào .htaccess"
fi

# ---------- 5. Kích hoạt ----------
buoc "Kích hoạt theme (bật theme, dựng trang + bài, tiếng Việt)"
ftp_ "mkdir -p -f $GOC/wp-content/mu-plugins; put wordpress/kich-hoat/lalasea-kich-hoat.php -o $GOC/wp-content/mu-plugins/lalasea-kich-hoat.php"

host_port="$(node -p "const u=new URL('$WP_DIA_CHI'); u.hostname+':'+(u.port||'')")"
mo_trang() {
  local them=()
  if [ -n "${WP_IP:-}" ]; then
    # Trỏ tên miền về hosting chỉ cho lệnh này — dùng được khi tên miền vẫn còn trỏ chỗ khác
    local h="${host_port%%:*}" p="${host_port##*:}"
    if [ -n "$p" ]; then them+=(--resolve "$h:$p:$WP_IP"); else them+=(--resolve "$h:80:$WP_IP" --resolve "$h:443:$WP_IP"); fi
  fi
  curl -sSLk -o /dev/null -w '%{http_code}' --max-time 60 "${them[@]}" "$1" || echo "000"
}
xong=""
for lan in 1 2 3 4 5; do
  ma="$(mo_trang "$WP_DIA_CHI/")"
  echo "  lần $lan: trang chủ trả về $ma"
  if ! ftp_ "cls -1 $GOC/wp-content/mu-plugins/lalasea-kich-hoat.php" >/dev/null 2>&1; then xong=1; break; fi
  sleep 3
done
if [ -z "$xong" ]; then
  do_ "Plugin kích hoạt chưa chạy xong (file vẫn còn trên hosting)."
  if [ -z "${WP_IP:-}" ]; then
    do_ "→ Nếu tên miền chưa trỏ về hosting, đặt biến WP_IP = IP của hosting để script mở được trang."
  fi
  exit 1
fi
xanh "✓ Đã kích hoạt — plugin kích hoạt đã tự xoá"

# ---------- 6. So sánh ----------
if [ "$KIEM_TRA" = "1" ]; then
  buoc "So từng trang trên hosting với web Eleventy"
  [ -d _site ] || npx @11ty/eleventy --quiet
  WP_IP="${WP_IP:-}" node wordpress/tools/so-sanh-html.mjs _site "$WP_DIA_CHI"
fi

xanh "
Hoàn tất. Web WordPress: $WP_DIA_CHI/   Trang quản trị: $WP_DIA_CHI/wp-admin/"

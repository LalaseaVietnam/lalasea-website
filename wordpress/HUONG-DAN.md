# Chuyển website Lalasea sang WordPress

Thư mục này chứa theme WordPress **giữ nguyên 100% giao diện** của website hiện tại
(bản Eleventy trên GitHub Pages). Khi đổi sang, khách truy cập không thấy khác gì; chỉ
phần quản trị đổi từ `lalasea.vn/quan-tri` sang `lalasea.vn/wp-admin`.

## Đã kiểm chứng như thế nào

| Kiểm tra | Kết quả |
|---|---|
| So từng trang theo cách trình duyệt và Google đọc (16 trang: 5 trang Việt, 5 trang Anh, tin tức, 5 bài viết) | Giống hệt từng thẻ, từng chữ, từng giá trị schema |
| Chấm code theo WordPress Coding Standards | 0 lỗi, 0 cảnh báo |
| Bật `WP_DEBUG`, mở mọi trang khách + trang quản trị | Không một cảnh báo PHP |
| Chụp màn hình Chromium ở 390px và 1280px, so từng điểm ảnh (24 lần chụp) | Trùng khớp; chỉ lệch vài trăm điểm ở mép bo tròn của ảnh do trình duyệt khử răng cưa |
| Tràn ngang ở 390px và 1280px | Không trang nào tràn |
| Cài WordPress mới, tải theme lên, kích hoạt | Tự dựng đủ trang + bài, giống hệt web cũ |
| Lưu mọi màn hình "Thông tin nhà hàng" không sửa gì | Dữ liệu khớp từng ký tự với bản gốc |
| Đường dẫn cũ (`/gioi-thieu-menu/`…) | Chuyển hướng **301 thật** (tốt hơn trang mồi của GitHub Pages) |
| Triển khai tự động vào một "hosting giả" (WordPress vừa cài một chạm, cấu trúc cPanel, FTP) | Tự tìm thư mục, giữ dòng PHP của hosting trong `.htaccess`, kích hoạt xong ngay lượt đầu (không lỗi 500), 16/16 trang giống hệt; chạy lại lần 2 an toàn |
| Triển khai đè lên WordPress cũ có trang trùng tên | Trang cũ được gắn đúng giao diện; bài cũ còn lại được công cụ so sánh báo rõ |

Tự chạy lại các phép so sánh — xem mục "Cho người lập trình" ở cuối.

## Chủ quán sửa nội dung ở đâu

Đăng nhập `lalasea.vn/wp-admin`:

| Việc | Vào mục |
|---|---|
| Đăng / sửa bài tin tức, ưu đãi | **Tin tức & Ưu đãi** (ô "Đặt ảnh đại diện" và "mô tả ngắn" ở thanh bên phải khung soạn bài) |
| SĐT, địa chỉ, giờ mở cửa, link mạng xã hội | **Thông tin nhà hàng → Thông tin chung** |
| Thư viện ảnh trang chủ | **Thông tin nhà hàng → Thư viện ảnh trang chủ** |
| 14 trang ảnh thực đơn | **Thông tin nhà hàng → Các trang menu** |
| Đánh giá khách (chép từ Google) | **Thông tin nhà hàng → Đánh giá của khách** |
| Giá, gói, ảnh lều glamping | **Thông tin nhà hàng → Trang Glamping** |
| Chuyển hướng đường dẫn cũ | **Thông tin nhà hàng → Chuyển hướng trang cũ** |

Các ô nhập dùng đúng nhãn, đúng thứ tự như trang quản trị cũ. Bấm **Lưu thay đổi** là
web cập nhật ngay, không phải chờ 1–2 phút như trước.

Nội dung chữ trong 5 trang chính (giới thiệu, câu hỏi thường gặp, mô tả tiệc…) nằm
trong template của theme, giống như bản cũ nằm trong file `.njk`. Muốn sửa phần này thì
nhờ người lập trình, hoặc nhờ Claude. Trong mục **Trang**, các trang này có nhãn
"Giao diện cố định"; mở ra sẽ thấy hướng dẫn sửa đúng chỗ thay vì khung soạn thảo trống.

Trang **Bảng tin** (vào `/wp-admin` là thấy ngay) có sẵn lối tắt tới các việc hay làm.

## Các bước chuyển (không làm web gián đoạn)

Việc chuyển gần như tự động: GitHub Actions có sẵn workflow **"Triển khai WordPress"**
(`.github/workflows/wordpress.yml`) đưa theme, ảnh, biểu tượng lên hosting qua FTP, tự
kích hoạt (bật theme, dựng 11 trang + bài viết, cài tiếng Việt cho trang quản trị, ghi
`.htaccess`), rồi **so từng trang trên hosting với web hiện tại** — khác là báo đỏ.
Không cần tải zip, giải nén hay bấm kích hoạt bằng tay.

Chủ quán chỉ làm những việc cần **đăng nhập**. Trong suốt quá trình, web cũ trên GitHub
Pages vẫn chạy bình thường cho tới bước 4.

### Bước 1 — Cài WordPress trên hosting (chủ quán)

- Hosting cần: PHP 8.0 trở lên, MySQL/MariaDB, SSL miễn phí (Let's Encrypt). Gói
  "WordPress hosting" của Mắt Bão / iNET đều đủ.
- Trong trang quản lý hosting, dùng trình **cài WordPress một chạm** (Softaculous,
  WordPress Toolkit, "Cài đặt ứng dụng"...). Chọn tên miền `lalasea.vn`, giao thức
  **https://**, cài vào **thư mục gốc** (không điền thư mục con).
- Tên đăng nhập quản trị: **không** dùng `admin`; mật khẩu dài, ngẫu nhiên.
- Nếu hosting **còn WordPress cũ**: báo lại. Theme tự gắn đúng giao diện cho các trang
  trùng tên, nhưng bài viết cũ vẫn nằm trong danh sách tin — cần chủ quán quyết định
  giữ hay xoá.

### Bước 2 — Tạo tài khoản FTP và ghi lại IP hosting (chủ quán)

- Trong trang quản lý hosting → **FTP Accounts** (Tài khoản FTP): tạo một tài khoản
  riêng cho web, ví dụ `trienkhai@lalasea.vn`. Không dùng mật khẩu hosting chính.
- Ghi lại **máy chủ FTP** (thường là `ftp.lalasea.vn` hoặc IP) và **IP của hosting**
  (hiện ở trang thông tin gói hosting).

### Bước 3 — Khai báo vào GitHub (chủ quán) → Claude triển khai

GitHub → kho `lalasea-website` → **Settings → Secrets and variables → Actions**:

| Tab | Tên | Giá trị |
|---|---|---|
| Secrets | `WP_FTP_HOST` | máy chủ FTP |
| Secrets | `WP_FTP_USER` | tài khoản FTP |
| Secrets | `WP_FTP_PASS` | mật khẩu FTP |
| Variables | `WP_IP` | IP của hosting (cần trong lúc tên miền chưa trỏ sang) |

Secrets được GitHub mã hoá, không ai đọc lại được — kể cả Claude. **Đừng gửi mật khẩu
qua tin nhắn.** Nhập xong thì nhắn Claude "đã khai báo xong": Claude chạy workflow, đọc
kết quả, sửa lỗi nếu có, và báo khi cả 16 trang trên hosting giống hệt web hiện tại.

Nếu hosting chỉ cho SFTP (cổng 22) hoặc bắt buộc FTPS: thêm Variable `WP_FTP_GIAO_THUC`
= `sftp` / `ftps`. Nếu script không tự tìm ra thư mục WordPress: thêm `WP_THU_MUC`.

### Bước 4 — Trỏ tên miền (chủ quán) → Claude kiểm tra lần cuối

- Ở nơi quản lý tên miền `lalasea.vn` (iNET hoặc Mắt Bão): sửa bản ghi **A** của `@`
  và `www` sang IP hosting (thay cho 4 IP `185.199.108–111.153` của GitHub Pages).
  **Không đụng tới bản ghi MX/TXT** — đó là email.
- Sau 5–60 phút, vào trang quản lý hosting bật **SSL / AutoSSL / Let's Encrypt** cho
  `lalasea.vn` và `www.lalasea.vn`.
- Nhắn Claude: Claude kiểm tra trên tên miền thật (https, www → không www, chuyển hướng
  301, sitemap, robots, so lại 16 trang) và gỡ biến `WP_IP`.

### Sau khi chạy ổn (chủ quán)

- Google Search Console: gửi lại `https://lalasea.vn/sitemap.xml`.
- GitHub → Settings → Pages: gỡ tên miền riêng, để kho chỉ còn là nơi lưu mã nguồn.
- Báo IndexNow (Bing, ChatGPT) **đã tự động**: theme tự báo mỗi khi đăng/sửa bài hoặc
  lưu "Thông tin nhà hàng" — không cần cài thêm plugin.

### Mỗi lần sửa theme sau này

Đẩy lên nhánh `main` là workflow tự đưa bản mới lên hosting và so lại từng trang. Dữ
liệu chủ quán đã lưu trong `/wp-admin` không bị ghi đè — file JSON trong theme chỉ là
dữ liệu ban đầu.

## Cấp quyền cho bên marketing (TOMAZ)

- **Không đưa tài khoản của chủ quán.** Vào Thành viên → Thêm mới, tạo một tài khoản
  riêng cho họ, vai trò *Quản trị viên*, email của họ. Hết hợp đồng thì xoá tài khoản đó;
  mọi việc họ làm đều ghi tên họ.
- **cPanel:** tạo một tài khoản **FTP riêng** (cPanel → FTP Accounts) chỉ vào thư mục
  `public_html`, thay vì đưa mật khẩu cPanel chính (mật khẩu đó quản lý cả email, tên
  miền và thanh toán).
- **Pixel Facebook / Google:** theme có sẵn các móc `wp_head`, `wp_body_open`,
  `wp_footer`, nên họ cài plugin *GTM4WP* hoặc *Site Kit by Google* là gắn được ngay,
  **không cần sửa mã nguồn theme**.
- **Schema "800+ đánh giá":** web đã có schema `Restaurant` đầy đủ trong theme. Đừng để
  họ cài thêm plugin SEO (Yoast, Rank Math…) xuất schema/tiêu đề thứ hai, vì sẽ trùng.
  Riêng sao đánh giá: Google **không hiện sao** cho đánh giá do nhà hàng tự gắn lên web
  của mình, và nếu đưa số "800+" không có trên trang thì web có thể bị Google phạt. Số
  đánh giá thật nằm ở Google Maps / Google Business Profile.

## Bảo trì (việc mới phải làm khi dùng WordPress)

- Mỗi tháng vào **Bảng tin → Cập nhật**, cập nhật WordPress và plugin.
- Cài plugin sao lưu (ví dụ *UpdraftPlus*), đặt sao lưu hằng tuần lên Google Drive.
- Bật xác thực 2 bước cho tài khoản quản trị (plugin *Two Factor*).
- Theme đã tự tắt XML-RPC/pingback, ẩn tên đăng nhập khỏi REST API và `?author=`, bỏ
  các dòng lộ phiên bản WordPress.

## Thay đổi so với web cũ

| Trước | Sau |
|---|---|
| Quản trị ở `/quan-tri` (đăng nhập GitHub) | `/wp-admin` (tài khoản + mật khẩu) |
| Đẩy lên `main` → tự xuất bản sau 1–2 phút | Lưu là có ngay |
| Chuyển hướng bằng trang mồi meta-refresh | Chuyển hướng 301 thật |
| IndexNow qua GitHub Actions | Theme tự báo IndexNow khi đăng/sửa bài, lưu thông tin quán |
| Trang `/huong-dan-dang-bai/` (hướng dẫn trang quản trị cũ) | Bỏ — không còn đúng với WordPress |
| Miễn phí (GitHub Pages) | Tốn phí hosting + phải cập nhật định kỳ |

Form đặt bàn vẫn gửi vào Google Sheet như cũ.

---

## Cho người lập trình

Theme viết theo **WordPress Coding Standards** (bộ quy tắc chính thức mà lõi WordPress
dùng) và đạt **0 lỗi, 0 cảnh báo** với bộ quy tắc đầy đủ `WordPress` (Core + Docs + Extra).
Chạy thử với `WP_DEBUG` bật: không một cảnh báo PHP nào ở cả trang khách lẫn trang quản trị.

Quy ước trong code:

- Hàm, hằng số, biến toàn cục, tên dữ liệu lưu đều bắt đầu bằng `lalasea_`
  (biến trong template cũng vậy — WordPress nạp template ở phạm vi toàn cục, đặt tên
  `$page` hay `$title` là ghi đè biến của chính WordPress).
- In ra luôn qua hàm escape đúng ngữ cảnh: `esc_html()` cho chữ, `esc_attr()` cho thuộc
  tính, `esc_url()` cho đường dẫn, `esc_js()` trong chuỗi JavaScript, `lalasea_json()`
  (= `wp_json_encode()` kèm `JSON_HEX_TAG`) cho schema JSON-LD và giá trị JavaScript.
- CSS nạp bằng `wp_enqueue_style()`; trang có đủ `wp_head()`, `wp_body_open()`,
  `wp_footer()` để plugin (GTM, Pixel, tăng tốc) hoạt động.
- Template đọc dữ liệu bằng `lalasea_data( 'thongtin' )`. Mọi trường khai báo trong
  biểu mẫu đều luôn có mặt (thiếu thì là chuỗi/mảng rỗng), nên đọc thẳng
  `$lalasea_glamping['luu_y_en']` không cần `??`.

```
wordpress/
  phpcs.xml.dist           ← cấu hình chấm code theo WordPress Coding Standards
  lalasea/                 ← theme (thư mục này được nén thành lalasea-theme.zip)
    templates/*.php        ← 5 trang Việt + 5 trang Anh
    header.php footer.php  ← khung chung: <head>, schema Restaurant, menu, chân trang
    single.php home.php    ← một bài viết, danh sách tin tức
    404.php page.php index.php
    inc/                   ← dữ liệu, SEO, quản trị, dọn WordPress, dựng trang khi kích hoạt
    assets/                ← CSS/JS của màn hình "Thông tin nhà hàng"
    css/style.css          ← CSS của web          (chép từ src/css/style.css)
    data/*.json            ← dữ liệu ban đầu       (chép từ src/_data/)
    data/bieu-mau.json     ← các ô nhập trong /wp-admin (từ src/quan-tri/config.yml)
    seed/baiviet.json      ← bài viết Markdown đã đổi sang HTML (từ src/posts/)
  tools/
    chep-tu-eleventy.mjs   ← chép CSS, dữ liệu, bài viết từ src/ sang theme
    so-sanh-html.mjs       ← so từng trang Eleventy ↔ WordPress theo cách trình duyệt đọc
    so-sanh-anh.mjs        ← chụp 390px/1280px, so điểm ảnh, kiểm tra tràn ngang
    trien-khai.sh          ← đưa lên hosting qua FTP, kích hoạt, so sánh (workflow gọi script này)
    dong-goi.sh            ← tạo 2 file zip — chỉ cần khi muốn cài bằng tay
  kich-hoat/
    lalasea-kich-hoat.php  ← plugin kích hoạt lần đầu, chạy xong tự xoá khỏi hosting
.github/workflows/wordpress.yml ← workflow "Triển khai WordPress"
```

**Template PHP là bản gốc** — viết tay, không sinh tự động. Trong giai đoạn chuyển tiếp
(web Eleventy vẫn chạy): sửa CSS / JSON / bài viết trong `src/` thì chạy
`node wordpress/tools/chep-tu-eleventy.mjs`; sửa chữ trong một file `.njk` thì sửa tay file
PHP tương ứng, rồi chạy `so-sanh-html.mjs` để chắc hai bản vẫn giống nhau.

**Giá trị trong schema JSON-LD phải được mã hoá kiểu JSON, không phải kiểu HTML.**
Nunjucks tự escape HTML cả bên trong `<script>`: `"{{ thongtin.maps }}"` ra
`...?api=1&amp;query=...`, mà trong JSON thì `&amp;` không được giải mã nên Google đọc ra
link hỏng (lỗi này từng có trên web, đã sửa). Bên Eleventy viết `{{ x | dump | safe }}`
(không kèm dấu ngoặc kép), bên WordPress viết `lalasea_json( $x )`.

Chấm code (cần `composer global require wp-coding-standards/wpcs`):

```bash
cd wordpress && phpcs
```

Kiểm tra lại độ giống nhau (cần một bản WordPress đang chạy, ví dụ ở cổng 8081):

```bash
npx @11ty/eleventy
```

```bash
node wordpress/tools/so-sanh-html.mjs _site http://127.0.0.1:8081
```

```bash
NODE_PATH=$(npm root -g) node wordpress/tools/so-sanh-anh.mjs http://127.0.0.1:8080 http://127.0.0.1:8081 /tmp/anh-so-sanh
```

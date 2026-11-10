# Chuyển website Lalasea sang WordPress

Thư mục này chứa theme WordPress **giữ nguyên 100% giao diện** của website hiện tại
(bản Eleventy trên GitHub Pages). Khi đổi sang, khách truy cập không thấy khác gì; chỉ
phần quản trị đổi từ `lalasea.vn/quan-tri` sang `lalasea.vn/wp-admin`.

## Đã kiểm chứng như thế nào

| Kiểm tra | Kết quả |
|---|---|
| So HTML từng trang (16 trang: 5 trang Việt, 5 trang Anh, tin tức, 5 bài viết) | Giống hệt từng thẻ |
| Chụp màn hình Chromium ở 390px và 1280px, so từng điểm ảnh (24 lần chụp) | Trùng khớp; chỉ lệch vài trăm điểm ở mép bo tròn của ảnh do trình duyệt khử răng cưa |
| Tràn ngang ở 390px và 1280px | Không trang nào tràn |
| Cài WordPress mới, tải theme lên, kích hoạt | Tự dựng đủ trang + bài, giống hệt web cũ |
| Lưu mọi màn hình "Thông tin nhà hàng" không sửa gì | Dữ liệu khớp từng ký tự với bản gốc |
| Đường dẫn cũ (`/gioi-thieu-menu/`…) | Chuyển hướng **301 thật** (tốt hơn trang mồi của GitHub Pages) |

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

**Chuẩn bị:** hosting có cPanel, PHP 8.0 trở lên, MySQL/MariaDB, chứng chỉ SSL miễn phí
(Let's Encrypt — hầu hết hosting Việt Nam đều có).

1. **Cài WordPress trên hosting** bằng Softaculous / "WordPress Installer" trong cPanel.
   Cài vào **thư mục gốc** (`public_html`), không cài vào thư mục con. Trong lúc làm,
   web cũ trên GitHub Pages vẫn chạy bình thường vì tên miền chưa trỏ sang.
2. **Tạo 2 gói** trên máy có mã nguồn: `bash wordpress/tools/dong-goi.sh`, ra 2 file
   trong `wordpress/dist/`:
   - `thu-muc-goc-web.zip`: vào cPanel → File Manager → `public_html`, tải lên rồi
     **Extract**. File này chứa ảnh, menu và biểu tượng, giữ nguyên đường dẫn `/anh/...`,
     `/menu/...` để link cũ và Google Ảnh không bị gãy.
   - `lalasea-theme.zip`: vào WordPress → Giao diện → Thêm mới → Tải lên → Kích hoạt.
     Theme **tự tạo đủ trang** (Việt + Anh), nạp 5 bài tin tức, đặt trang chủ và đường
     dẫn `/tin-tuc/<tên-bài>/` giống web cũ.
3. **Cài đặt → Tổng quan → Ngôn ngữ của trang:** chọn *Tiếng Việt*, bấm Lưu.
   **Cài đặt → Đường dẫn tĩnh:** bấm **Lưu** một lần (không cần sửa gì) để WordPress
   tạo file `.htaccess`.
4. **Kiểm tra** bằng tên miền tạm của hosting (hoặc sửa file hosts): mở từng trang ở
   điện thoại và máy tính.
5. **Trỏ tên miền:** ở nơi quản lý tên miền `lalasea.vn`, đổi bản ghi **A** từ GitHub
   Pages sang IP của hosting. Sau 5–60 phút web chạy trên WordPress. Bật SSL ngay.
6. **Sau khi chạy ổn:**
   - Google Search Console: gửi lại `https://lalasea.vn/sitemap.xml`.
   - Cài plugin **IndexNow** (của Microsoft) để vẫn báo Bing/ChatGPT mỗi khi có bài mới
     (GitHub Actions trước đây làm việc này, sang WordPress thì không còn).
   - Trong GitHub: Settings → Pages → gỡ tên miền riêng, để repo chỉ còn là kho lưu trữ.

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
| IndexNow qua GitHub Actions | Plugin IndexNow |
| Trang `/huong-dan-dang-bai/` (hướng dẫn trang quản trị cũ) | Bỏ — không còn đúng với WordPress |
| Miễn phí (GitHub Pages) | Tốn phí hosting + phải cập nhật định kỳ |

Form đặt bàn vẫn gửi vào Google Sheet như cũ.

---

## Cho người lập trình

```
wordpress/
  lalasea/                 ← theme (thư mục này được nén thành lalasea-theme.zip)
    templates/*.php        ← 5 trang Việt + 5 trang Anh   (TỰ SINH)
    header.php footer.php  ← từ layouts/base.njk          (TỰ SINH)
    single.php home.php 404.php                           (TỰ SINH)
    page.php index.php     ← viết tay: trang tự thêm, trang dự phòng
    css/style.css          ← chép từ src/css/style.css    (TỰ SINH)
    data/*.json            ← chép từ src/_data/ + bieu-mau.json từ src/quan-tri/config.yml
    seed/baiviet.json      ← bài viết Markdown đã đổi sang HTML
    inc/                   ← logic PHP viết tay (dữ liệu, SEO, quản trị, dọn WordPress)
  tools/
    njk-sang-php.mjs       ← chuyển .njk → .php, giữ nguyên văn HTML
    so-sanh-html.mjs       ← so HTML từng trang: Eleventy ↔ WordPress
    so-sanh-anh.mjs        ← chụp 390px/1280px, so điểm ảnh, kiểm tra tràn ngang
    dong-goi.sh            ← tạo 2 file zip để tải lên hosting
```

**Trong giai đoạn chuyển tiếp,** `src/` vẫn là bản gốc. Sửa `.njk` / CSS / JSON như
bình thường rồi chạy lại `node wordpress/tools/njk-sang-php.mjs` để sinh lại theme.
**Sau khi đã đổi tên miền sang WordPress,** theme là bản gốc: sửa trực tiếp file PHP
và ngừng chạy bộ chuyển đổi (nếu chạy lại, nó sẽ ghi đè các file TỰ SINH).

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

# DESIGN.md — Hệ thiết kế Lalasea Bistro

File này mô tả **cách website Lalasea đang trông như thế nào và vì sao**.
Bất kỳ ai — người hay AI — sửa giao diện web này đều phải đọc file này trước,
và dùng lại thứ đã có thay vì tự nghĩ ra thứ mới.

Nguồn sự thật của code là `src/css/style.css`. File này giải thích **ý định**
đằng sau các con số trong đó.

---

## 1. Bản sắc trong một câu

> Nhà hàng không gian mở sát biển ở Hà Tiên — mặn mòi, ấm, mộc, không sang chảnh.

Mọi quyết định thiết kế đều quy về câu này. Màu lấy từ biển và cát. Chữ tiêu đề
có chân để gợi sự chỉn chu, chữ thân bài không chân để dễ đọc trên điện thoại.
Không dùng gradient tím-xanh, không dùng nền đen bóng bẩy, không dùng emoji thay
tiêu đề mục.

---

## 2. Màu

Khai báo một lần ở `:root` trong `src/css/style.css`. **Không viết mã màu
trực tiếp trong component** — luôn dùng biến.

| Biến | Mã màu | Dùng cho |
|---|---|---|
| `--xanh-bien` | `#0b4f6c` | Màu chủ đạo: tiêu đề mục, chữ nhấn |
| `--xanh-dam` | `#072d3d` | Nền thanh menu, chân trang, khối tối |
| `--xanh-ngoc` | `#1b9aaa` | Liên kết trong bài viết, viền nhấn |
| `--ngoc-nhat` | `#7fe3ef` | Chữ nhỏ trên nền tối (kicker, khẩu hiệu) |
| `--san-ho` | `#ff6b6b` | **Chỉ dùng cho nút hành động** (đặt bàn, gọi điện) |
| `--san-ho-dam` | `#e85555` | Trạng thái hover của nút |
| `--cat` | `#f6f1e7` | Nền trang — không dùng trắng tinh |
| `--chu-dam` | `#12333f` | Chữ thân bài |
| `--chu-nhat` | `#5a6b72` | Chữ phụ, chú thích |

**Quy tắc màu san hô.** `--san-ho` là màu đắt nhất trên trang. Mỗi màn hình
chỉ nên có **một** thứ màu san hô. Dùng nhiều thì không còn chỗ nào nổi bật nữa.

**Nền không bao giờ là `#fff` thuần** cho cả trang. Nền trang là `--cat`.
Các khối nội dung có thể dùng `#fff` để tách khỏi nền cát.

---

## 3. Chữ

Ba bộ chữ, tải từ Google Fonts:

- `--font-tieu-de`: **Playfair Display** (có chân) — chỉ dùng cho `h1`–`h4`
- `--font-chu`: **Be Vietnam Pro** (không chân) — mọi thứ còn lại
- `"Pacifico"` (viết tay) — **chỉ** dùng cho `.script-accent` và `.hero .script`,
  tức dòng chữ nhỏ kiểu viết tay phía trên tiêu đề mục. Không dùng cho gì khác.

Cả ba đều có đủ dấu tiếng Việt. Không thêm bộ chữ thứ tư.

**Cỡ chữ luôn co giãn bằng `clamp()`** — không đặt một con số cứng cho tiêu đề.
Thang đang dùng:

| Vai trò | Giá trị |
|---|---|
| Tiêu đề banner (`h1`) | `clamp(36px, 6.5vw, 60px)` |
| Tiêu đề mục (`h2`) | `clamp(30px, 4.5vw, 42px)` |
| Tiêu đề bài viết (`h2` trong bài) | `27px` |
| Chữ thân bài | `16px`, bài viết `17px` |
| Chú thích, chữ phụ | `13–14px` |

`line-height` thân bài là `1.75`, tiêu đề là `1.25`. Tiếng Việt có dấu chồng
lên nhau nên thoáng dòng hơn tiếng Anh một chút.

---

## 4. Bố cục và khoảng cách

| Thứ | Giá trị | Ghi chú |
|---|---|---|
| Bề ngang tối đa | `1120px` | `.container` |
| Lề hai bên | `24px` | Không bao giờ nhỏ hơn 16px |
| Khoảng cách giữa các mục | `clamp(64px, 9vw, 100px)` | `section` |
| Bo góc | `--bo-tron: 18px` | Thẻ nhỏ dùng `12–14px` |
| Đổ bóng nhẹ | `--bong-nhe` | Thẻ ở trạng thái thường |
| Đổ bóng đậm | `--bong-dam` | Chỉ khi hover hoặc ảnh lớn |

**Xếp hàng bằng `gap`, không bằng `margin` từng phần tử.** Dùng flex hoặc grid.

**Không phải thứ gì cũng là thẻ có viền + bóng.** Viền, nền, bo góc và bóng mỗi
thứ đều nói "đây là một vật thể riêng". Đóng dấu tất cả lên mọi khối thì trang
phẳng lì, không còn thứ tự quan trọng.

---

## 5. Điểm ngắt màn hình

**Hiện trạng cần sửa dần:** file CSS đang có **14 mốc `max-width` khác nhau**
(460, 540, 560, 600, 620, 640, 680, 700, 720, 780, 820, 860, 900, 1000px) trải trên 23 khối `@media`.
Đây là hệ quả của việc mỗi lần thêm khối mới lại chọn một con số mới.

**Từ nay chỉ dùng ba mốc:**

| Mốc | Dùng khi |
|---|---|
| `max-width: 560px` | Điện thoại — xếp về một cột |
| `max-width: 820px` | Máy tính bảng — giảm số cột |
| `min-width: 821px` | Máy tính — bố cục đầy đủ |

Khi sửa một khối cũ, đổi luôn điểm ngắt của khối đó sang mốc gần nhất trong ba
mốc trên. Không sửa hàng loạt một lần — dễ vỡ bố cục ở chỗ không ngờ.

---

## 6. Nút bấm

Hai **kiểu** nút, mỗi kiểu có thêm lớp phụ chỉnh cỡ hoặc ngữ cảnh:

| Lớp | Vai trò |
|---|---|
| `.btn` | Nền san hô, **chữ `--xanh-dam`**, bo tròn `40px`. Hành động chính. |
| `.btn-ghost` | Nền trong suốt, viền trắng mờ, chữ trắng. Hành động phụ **trên ảnh tối**. |
| `.btn-ghost.nen-sang` | Bản dùng **trên nền sáng**: chữ và viền `--xanh-bien`. |
| `.btn-lg` | Chỉ chỉnh cỡ — to hơn, in hoa. Dùng kèm `.btn`. |
| `.btn-nav` | Chỉ chỉnh cỡ — nhỏ hơn, cho thanh menu. Dùng kèm `.btn`. |

Nút nhấc lên `2px` khi hover. Không tạo kiểu nút thứ ba.

**Chữ trên nút chính là `--xanh-dam`, không phải trắng.** Chữ trắng trên nền
san hô chỉ đạt 2,78:1 — không đọc được dưới nắng, và không đạt chuẩn. Xanh đậm
đạt 5,21:1 mà vẫn giữ nguyên màu san hô của thương hiệu.

**Nút ghost đặt trên nền sáng phải thêm lớp `.nen-sang`.** Quên lớp này thì chữ
trắng nằm trên nền cát, tỷ lệ 1,13:1 — khách nhìn không ra nút. Đã từng xảy ra
thật ở trang Tiệc. Không chữa bằng `style` inline từng chỗ.

---

## 7. Tương phản màu và vùng bấm

Mọi chữ phải đạt chuẩn **WCAG AA**: tỷ lệ tương phản **≥ 4,5:1** với nền, hoặc
**≥ 3:1** nếu chữ lớn (từ 24px, hoặc từ 18,66px và in đậm).

Các cặp màu **đã đo và đạt**:

| Chữ | Trên nền | Tỷ lệ |
|---|---|---|
| `--xanh-dam` | `--san-ho` (nút chính) | 5,21:1 |
| `--xanh-bien` | `--cat` (nút ghost nền sáng) | 7,94:1 |
| `--chu-nhat` | trắng | 5,55:1 |
| trắng | `--xanh-bien` | 8,94:1 |

Các cặp **chưa đạt, đừng dùng cho chữ nhỏ**:

| Chữ | Trên nền | Tỷ lệ |
|---|---|---|
| trắng | `--san-ho` | 2,78:1 ❌ |
| `--san-ho` | trắng hoặc `--cat` | 2,47–2,78:1 ❌ |
| `--xanh-ngoc` | `--cat` | 2,99:1 ❌ |
| trắng | `--cat` | 1,13:1 ❌ |

**Vùng bấm trên điện thoại tối thiểu 44×44px.** Nếu phần nhìn thấy nhỏ hơn
(ví dụ chấm chuyển ảnh chỉ 10px), thì giữ nguyên phần nhìn thấy và mở rộng vùng
bấm bằng `width/height: 44px` + `display: grid; place-items: center`, vẽ phần
nhìn thấy bằng `::before`.

---

## 8. Ảnh

**Nơi để ảnh.**

- `anh/` — ảnh **đang dùng** trên web. Mọi file trong đây đều được xuất bản.
- `luu-tru/anh/` — kho lưu trữ, **không xuất bản**. Đọc `luu-tru/anh/README.md`.

Quy tắc: ảnh nằm trong kho cho đến khi thật sự được dùng, lúc đó mới chép sang
`anh/`. Không bao giờ để ảnh không dùng trong `anh/`.

**Đặt tên file** theo nội dung + thương hiệu + địa danh, không dấu, nối bằng gạch
ngang: `hoang-hon-vang-lalasea-ha-tien.jpg`. Tên file là tín hiệu SEO thật.

**Kích thước.**

| Loại | Khổ | Định dạng |
|---|---|---|
| Ảnh trong bài viết | `1200×900` (4:3) | JPEG chất lượng 86 |
| Thẻ món ăn | `800×800` (1:1) | JPEG chất lượng 88 |
| Ảnh banner | rộng tối thiểu `1600px` | JPEG |

**Thẻ `<img>` phải có `width` và `height`** để trang không nhảy khi tải. Nhưng
khi đặt `aspect-ratio` trong CSS thì **bắt buộc thêm `height: auto`**, nếu không
thuộc tính `height="800"` sẽ ghi đè và làm ảnh cao vống lên.

**`alt` phải mô tả thật những gì có trong ảnh**, kèm tên quán và địa danh.
Không nhồi từ khóa. Không để `alt` trống trừ khi ảnh thuần trang trí.

---

## 9. Những điều KHÔNG được làm

Đây là các lỗi đã từng xảy ra trên chính website này.

**Về nội dung:**

- ❌ Gọi Lalasea là "nhà hàng hải sản". Quán **có** hải sản nhưng không chuyên —
  thực đơn đa dạng: nướng, lẩu, bò, gà, cơm. Cách gọi đúng:
  **"nhà hàng không gian mở sát biển"**.
- ❌ Nhắc giá "từ 20.000đ". Đó là giá cơm trắng. Khoảng giá đúng:
  **100.000đ – 300.000đ** tùy món.
- ❌ Ghi tỉnh là "Kiên Giang". Địa chỉ đúng hiện nay:
  **QL80, Tô Châu, Hà Tiên, An Giang**.
- ❌ Viết lều glamping "nằm sát biển". Khu ăn mới sát biển; lều nằm trong khuôn
  viên, **cách biển vài chục mét**.
- ❌ Xếp trang trí sinh nhật / cầu hôn vào trang Tiệc & Sự kiện. Đó là dịch vụ
  cho nhóm bạn, gia đình ăn uống bình thường.
- ❌ Ghi giờ mở cửa "09:00 – 23:00 tất cả các ngày". Giờ đúng:
  **T2–T6 08:00–23:00, T7 & CN 07:00–23:00**.

**Về ảnh:**

- ❌ Dùng ảnh có **mặt khách nhận diện rõ** khi chưa xin phép.
- ❌ Dùng ảnh có logo công ty khách, standee in mặt trẻ em.
- ❌ Dùng ảnh còn watermark của trang khác mà chưa cắt bỏ.
- ❌ Dùng ảnh có name card / bảng hiệu ghi tỉnh cũ "Kiên Giang".

**Về giao diện:**

- ❌ Bỏ `overflow-x: clip` trên `html, body`. Đây là thứ giữ cho trang không
  lắc trái phải khi vuốt trên điện thoại. Dùng `clip`, **không** dùng `hidden`
  — `hidden` sẽ phá thanh menu dính (sticky).
- ❌ Để bảng hoặc dải ảnh ngang làm trang cuộn ngang. Chúng phải nằm trong khối
  riêng có `overflow-x: auto`.
- ❌ Thêm bộ chữ thứ tư, màu thứ mười, hay kiểu nút thứ ba.
- ❌ Dùng `.btn-ghost` trên nền sáng mà quên lớp `.nen-sang`.
- ❌ Để chữ trắng trên nền `--san-ho`. Chữ nút chính là `--xanh-dam`.
- ❌ Viết `style="color: ..."` inline để chữa cháy màu. Sửa ở CSS bằng lớp dùng lại được.

---

## 10. Dữ liệu có cấu trúc (schema)

Web này được tối ưu cho cả Google lẫn các engine AI (ChatGPT, Gemini, Claude).
Mọi trang đều phải giữ được:

- `Restaurant` với `@id` là `https://lalasea.vn/#nhahang` — đây là **thực thể
  gốc**, mọi schema khác trỏ về đây.
- Trang chủ và trang Tiệc có thêm `FAQPage`.
- Trang Thực đơn có `Menu` (8 nhóm, 47 món kèm giá thật).
- Bài viết có `Article` + `BreadcrumbList`, và `about` trỏ về `#nhahang`.

Khi sửa `src/_data/thongtin.json`, nhớ rằng nó là **nguồn duy nhất** cho tên,
địa chỉ, điện thoại, giờ mở cửa, toạ độ. Không viết cứng các giá trị này vào
template.

---

## 11. Kiểm tra trước khi đẩy lên

Mỗi lần sửa giao diện, chạy đủ bốn bước:

1. `npx @11ty/eleventy` — build không lỗi.
2. Mở `_site` bằng trình duyệt thật ở **390px** và **1280px**.
3. Kiểm tra `document.documentElement.scrollWidth - clientWidth === 0`
   (không tràn ngang) ở cả hai khổ.
4. Parse lại JSON-LD của trang vừa sửa — schema phải còn hợp lệ.

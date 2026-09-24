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
| `--xanh-ngoc` | `#1b9aaa` | Viền nhấn, biểu tượng, và **chữ trên nền tối** |
| `--xanh-ngoc-dam` | `#13707b` | **Chữ trên nền sáng**: liên kết, chữ viết tay, chữ nhỏ in hoa |
| `--ngoc-nhat` | `#7fe3ef` | Chữ nhỏ trên nền tối (kicker, khẩu hiệu) |
| `--san-ho` | `#ff6b6b` | **Chỉ dùng cho nút hành động** (đặt bàn, gọi điện) |
| `--san-ho-dam` | `#e85555` | Trạng thái hover của nút |
| `--cat` | `#f6f1e7` | Nền trang — không dùng trắng tinh |
| `--chu-dam` | `#12333f` | Chữ thân bài |
| `--chu-nhat` | `#5a6b72` | Chữ phụ, chú thích |

Bốn biến nữa là **màu chức năng** — không mang bản sắc, chỉ để dựng viền và nền
nhạt. Trước đây chúng là năm mã be gần giống nhau viết cứng rải rác khắp file CSS.

| Biến | Mã màu | Dùng cho |
|---|---|---|
| `--vien-cat` | `#e3dccf` | Mọi viền và đường kẻ phân cách trên nền sáng |
| `--nen-cat-nhat` | `#fbf9f4` | Nền rất nhạt, ví dụ dòng xen kẽ trong bảng |
| `--sao-vang` | `#ffc94d` | Ngôi sao đánh giá |
| `--zalo` | `#0068ff` | Màu thương hiệu Zalo — của họ, không phải của quán |

`#fff` viết trực tiếp thì được: nó là chữ trắng trên nền tối và nền thẻ, không
phải một lựa chọn bảng màu. Mọi mã màu khác đều phải là biến.

**Quy tắc màu san hô.** `--san-ho` là màu đắt nhất trên trang. Mỗi màn hình
chỉ nên có **một** thứ màu san hô. Dùng nhiều thì không còn chỗ nào nổi bật nữa.

Cụ thể: san hô **chỉ** làm nền nút hành động. **Giá tiền dùng `--xanh-bien`**,
không dùng san hô — vừa đạt tương phản, vừa để dành san hô cho nút Đặt bàn.

**Sáng hay tối quyết định chọn `--xanh-ngoc` hay `--xanh-ngoc-dam`.** Trên nền
cát hoặc trắng thì dùng bản đậm; trong các khối nền tối (`.dgia`, `.visit`,
`.contact`, các `*-hero`) thì dùng `--ngoc-nhat`. CSS đã có sẵn quy tắc lật màu
cho `.script-accent` — thêm khối nền tối mới thì nhớ thêm tên lớp vào quy tắc đó.

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

CSS từng có **14 mốc `max-width` khác nhau** (460, 540, 560, 600, 620, 640, 680,
700, 720, 780, 820, 860, 900, 1000px) — mỗi lần thêm khối mới lại chọn một con số
mới. Nay gom còn **4 mốc**, mỗi mốc có lý do đo được:

| Mốc | Vì sao đúng con số này |
|---|---|
| `560px` | Điện thoại — lưới về một cột, chữ và khoảng cách thu gọn |
| `700px` | Ranh giới **dải vuốt ngang ↔ lưới**. Rộng hơn 700px thì lưới 2×2 dùng hết chỗ; hẹp hơn thì dải vuốt hợp hơn |
| `820px` | Máy tính bảng — giảm số cột của các lưới còn lại |
| `1000px` | Thanh menu gập thành nút ☰. Đo được: logo + 8 mục menu cần **939px**, gập sớm hơn là tràn |

**Vì sao không gom được về 3 mốc.** Mục tiêu ban đầu là 3, nhưng thử rồi đo lại
thì không đạt được mà không làm hỏng chỗ khác:

- Đẩy mốc dải vuốt từ 700 xuống 560: trang chủ ở 620px **dài thêm 66%**.
- Đẩy lên 820: máy tính bảng 780px mất lưới 2×2, chỉ còn một thẻ to chiếm gần
  hết màn hình — phí chỗ và giấu nội dung.
- Gập thanh menu ở 820 thay vì 1000: menu tràn ra ngoài, vì nó cần 939px.

Ba mốc 560 / 700 / 820 là **ba chế độ bố cục có thật** của web này, cộng một mốc
do nội dung thanh menu quyết định. Bốn con số có lý do vẫn tốt hơn ba con số ép.

**Khi thêm khối mới, chỉ được dùng bốn mốc này.** Muốn thêm mốc thứ năm thì phải
đo và ghi lý do vào bảng trên, như bốn mốc hiện có.

**Khối `@media` để ngay cạnh phần CSS nó sửa**, không gom hết về cuối file — đọc
tới đâu thấy quy tắc màn hình nhỏ tới đó, và không sợ đảo thứ tự làm đổi quy tắc
nào thắng.

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
| `--xanh-bien` | `--cat` (giá tiền, nút ghost nền sáng) | 7,94:1 |
| `--xanh-ngoc-dam` | `--cat` | 5,14:1 |
| `--xanh-ngoc-dam` | trắng | 5,78:1 |
| `--ngoc-nhat` | `--xanh-dam` | 9,74:1 |
| `--ngoc-nhat` | `--xanh-bien` | 6,02:1 |
| `--chu-nhat` | trắng | 5,55:1 |
| trắng | `--xanh-bien` | 8,94:1 |

Các cặp **chưa đạt, đừng dùng cho chữ nhỏ**:

| Chữ | Trên nền | Tỷ lệ |
|---|---|---|
| trắng | `--san-ho` | 2,78:1 ❌ |
| `--san-ho` | trắng hoặc `--cat` | 2,47–2,78:1 ❌ |
| `--xanh-ngoc` | `--cat` hoặc trắng | 2,99–3,36:1 ❌ |
| `--xanh-ngoc-dam` | nền tối | 2,50:1 ❌ |
| trắng | `--cat` | 1,13:1 ❌ |

**Vùng bấm.** Chuẩn WCAG 2.2 mức AA yêu cầu **24×24px**; **44×44px** là mức
khuyến nghị và là mục tiêu của web này cho mọi nút đứng riêng. Nếu phần nhìn thấy
nhỏ hơn (ví dụ chấm chuyển ảnh chỉ 10px), giữ nguyên phần nhìn thấy và mở rộng
vùng bấm bằng `width/height: 44px` + `display: grid; place-items: center`, vẽ
phần nhìn thấy bằng `::before`.

Liên kết nằm lọt trong câu văn được miễn — ví dụ đường dẫn "Trang chủ" trên thanh
điều hướng phụ. Đây là ngoại lệ chính WCAG 2.2 nêu, không phải lỗi bỏ sót.

**Viền focus.** CSS có sẵn một quy tắc `:focus-visible` dùng chung cho mọi thẻ
`a`, `button`, `input`, `textarea`, `select`, `summary`: viền sáng `--cat` bên
trong, quầng `--xanh-bien` bên ngoài. Hai lớp lồng nhau nên luôn nhìn thấy dù nền
sáng hay tối. **Đừng tắt `outline` ở bất kỳ phần tử nào** — người dùng bàn phím
sẽ lạc.

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

**Không cần gõ `width`/`height` bằng tay.** `eleventy.config.js` có một transform
tên `kichThuocAnh` tự đọc kích thước thật từ file ảnh rồi gắn vào mọi thẻ `<img>`
chưa có, kể cả ảnh viết bằng Markdown trong bài. Nếu build in ra cảnh báo
`[kichThuocAnh] khong doc duoc` thì nghĩa là đường dẫn ảnh sai.

Khi đặt `aspect-ratio` trong CSS thì **bắt buộc thêm `height: auto`**, nếu không
thuộc tính `height` do transform gắn vào sẽ ghi đè và làm ảnh cao vống lên.

**`alt` phải mô tả thật những gì có trong ảnh**, kèm tên quán và địa danh.
Không nhồi từ khóa. Không để `alt` trống trừ khi ảnh thuần trang trí.

Riêng 14 trang ảnh thực đơn, `alt` lấy từ trường `mota` trong
`src/_data/menuanh.json` — mỗi trang kể đúng nhóm món và vài mức giá có trên
trang đó. Chủ quán sửa được ở trang quản trị.

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
- ❌ Thêm bộ chữ thứ tư hay kiểu nút thứ ba.
- ❌ Thêm màu thương hiệu mới. Thêm **màu chức năng** thì được, nhưng phải đặt
  thành biến trong `:root` kèm chú thích, không viết cứng trong component.
- ❌ Đặt một mốc `@media` ngoài bốn mốc 560 / 700 / 820 / 1000.
- ❌ Dùng `.btn-ghost` trên nền sáng mà quên lớp `.nen-sang`.
- ❌ Để chữ trắng trên nền `--san-ho`. Chữ nút chính là `--xanh-dam`.
- ❌ Viết `style="color: ..."` inline để chữa cháy màu. Sửa ở CSS bằng lớp dùng lại được.
- ❌ Dùng `--san-ho` làm màu chữ cho giá tiền. Giá tiền là `--xanh-bien`.
- ❌ Dùng `--xanh-ngoc` làm màu chữ trên nền sáng. Bản chữ là `--xanh-ngoc-dam`.
- ❌ Đặt `outline: none` mà không thay bằng viền focus khác nhìn thấy được.
- ❌ Bỏ qua thứ tự tiêu đề: sau `h2` phải là `h3`, không nhảy thẳng xuống `h4`.

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

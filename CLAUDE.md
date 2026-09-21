# Lalasea Bistro — website

Trang web nhà hàng Lalasea Bistro, Hà Tiên. Toàn bộ nội dung bằng tiếng Việt,
tên biến và tên file cũng đặt bằng tiếng Việt không dấu.

## Đọc trước khi sửa giao diện

**[DESIGN.md](./DESIGN.md)** — hệ màu, chữ, bố cục, quy tắc ảnh, và danh sách
những điều KHÔNG được làm. Bắt buộc đọc trước khi động vào `src/css/style.css`
hoặc bất kỳ file `.njk` nào.

**[luu-tru/anh/README.md](./luu-tru/anh/README.md)** — catalog kho ảnh: ảnh nào
dùng được, ảnh nào không và vì sao.

## Cấu trúc

| Thư mục | Nội dung |
|---|---|
| `src/*.njk` | Các trang chính (chủ, thực đơn, tiệc, glamping, liên hệ) |
| `src/posts/*.md` | Bài viết, xuất ra `/tin-tuc/<slug>/` |
| `src/_data/*.json` | Dữ liệu: thông tin quán, thư viện ảnh, đánh giá, menu |
| `src/_includes/layouts/` | Khuôn mẫu chung + toàn bộ schema JSON-LD |
| `anh/` | Ảnh **đang dùng** — được xuất bản |
| `luu-tru/` | Kho lưu trữ — **không** xuất bản |

`src/_data/thongtin.json` là nguồn duy nhất cho tên, địa chỉ, điện thoại, giờ mở
cửa, toạ độ. Không viết cứng các giá trị này vào template.

## Lệnh

```bash
npx @11ty/eleventy              # build ra _site/
npx @11ty/eleventy --serve      # xem thử tại localhost:8080
```

Đẩy lên nhánh `main` là tự động deploy qua GitHub Actions, kèm báo IndexNow cho
Bing (và qua đó là ChatGPT).

## Quy ước làm việc

- Chủ quán tự sửa nội dung ở **lalasea.vn/quan-tri**. Thêm trường dữ liệu mới thì
  phải thêm ô nhập tương ứng trong `src/quan-tri/config.yml`.
- Sửa xong phải build lại và mở bằng trình duyệt thật ở **390px** và **1280px**,
  kiểm tra trang không tràn ngang.
- Không bịa thông tin về quán. Giá, giờ, món, đánh giá — chỉ dùng số liệu thật.

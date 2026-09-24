import { HtmlBasePlugin } from "@11ty/eleventy";
import fs from "node:fs";
import path from "node:path";

// ============================================================
// Đọc kích thước thật của ảnh từ vài byte đầu file.
// Mục đích: gắn width/height vào thẻ <img> để trang không nhảy
// khi ảnh tải xong. Chỉ cần JPEG và PNG — web này không dùng gì khác.
// Viết tay để khỏi thêm thư viện ngoài vào dự án.
// ============================================================
const kichThuocDaDoc = new Map();

function docKichThuoc(tep) {
  if (kichThuocDaDoc.has(tep)) return kichThuocDaDoc.get(tep);
  let kq = null;
  try {
    const b = fs.readFileSync(tep);

    // PNG: 8 byte chữ ký + 4 byte độ dài + 4 byte "IHDR", rồi tới rộng/cao
    if (b.length > 24 && b.readUInt32BE(0) === 0x89504e47) {
      kq = { rong: b.readUInt32BE(16), cao: b.readUInt32BE(20) };

    // JPEG: dò các đoạn đánh dấu tới khi gặp SOF (start of frame)
    } else if (b.length > 4 && b[0] === 0xff && b[1] === 0xd8) {
      let i = 2;
      while (i < b.length - 9) {
        if (b[i] !== 0xff) { i++; continue; }
        const ma = b[i + 1];
        // SOF0–SOF3, SOF5–SOF7, SOF9–SOF11, SOF13–SOF15 chứa kích thước
        const laSOF = ma >= 0xc0 && ma <= 0xcf &&
                      ma !== 0xc4 && ma !== 0xc8 && ma !== 0xcc;
        if (laSOF) { kq = { cao: b.readUInt16BE(i + 5), rong: b.readUInt16BE(i + 7) }; break; }
        if (ma === 0xd8 || ma === 0xd9 || (ma >= 0xd0 && ma <= 0xd7)) { i += 2; continue; }
        i += 2 + b.readUInt16BE(i + 2);
      }
    }
  } catch { /* thiếu file thì bỏ qua, không làm hỏng build */ }
  kichThuocDaDoc.set(tep, kq);
  return kq;
}


export default function (eleventyConfig) {
  // Ảnh và file tĩnh được copy nguyên vẹn sang trang web
  eleventyConfig.addPassthroughCopy({
    "anh": "anh",
    "menu": "menu",
    "uploads": "uploads",
    "hero.jpg": "hero.jpg",
    "cc97fe9ea5cf05654fd1deeed594a344.txt": "cc97fe9ea5cf05654fd1deeed594a344.txt",
    "src/css": "css",
    "src/admin": "admin",
    "src/quan-tri": "quan-tri",
    "src/favicon.svg": "favicon.svg"
  });

  // Hai trang quản trị chỉ copy nguyên trạng, không đưa vào sơ đồ trang web
  eleventyConfig.ignores.add("src/admin/**");
  eleventyConfig.ignores.add("src/quan-tri/**");

  // Tự thêm tiền tố đường dẫn khi chạy trên github.io (trước khi gắn tên miền riêng)
  eleventyConfig.addPlugin(HtmlBasePlugin);

  // Bài viết tin tức, mới nhất lên đầu
  eleventyConfig.addCollection("baiviet", (api) =>
    api.getFilteredByGlob("src/posts/*.md").sort((a, b) => b.date - a.date)
  );

  // Định dạng ngày kiểu Việt Nam: 10/09/2026
  eleventyConfig.addFilter("ngayVN", (d) => {
    const dt = new Date(d);
    return `${String(dt.getDate()).padStart(2, "0")}/${String(dt.getMonth() + 1).padStart(2, "0")}/${dt.getFullYear()}`;
  });

  // Tự gắn width/height cho MỌI thẻ <img> chưa có, ngay trên HTML đã dựng.
  // Làm ở đây thay vì trong template để bắt được cả ảnh viết bằng Markdown
  // trong bài viết, và để ảnh thêm sau này không cần nhớ gắn tay.
  eleventyConfig.addTransform("kichThuocAnh", function (noiDung) {
    if (!(this.page.outputPath || "").endsWith(".html")) return noiDung;

    return noiDung.replace(/<img\b[^>]*>/g, (the) => {
      if (/\bwidth=/.test(the) || /\bheight=/.test(the)) return the;
      const src = the.match(/\bsrc="([^"]+)"/);
      if (!src || !src[1].startsWith("/")) return the;

      const kt = docKichThuoc(path.join(process.cwd(), src[1].replace(/^\//, "")));
      if (!kt || !kt.rong || !kt.cao) {
        console.warn(`[kichThuocAnh] khong doc duoc: ${src[1]}`);
        return the;
      }
      return the.replace(/<img\b/, `<img width="${kt.rong}" height="${kt.cao}"`);
    });
  });

  // Ngày dạng chữ: 10 tháng 9, 2026
  eleventyConfig.addFilter("ngayChu", (d) => {
    const dt = new Date(d);
    return `${dt.getDate()} tháng ${dt.getMonth() + 1}, ${dt.getFullYear()}`;
  });

  return {
    dir: {
      input: "src",
      includes: "_includes",
      data: "_data",
      output: "_site"
    }
  };
}

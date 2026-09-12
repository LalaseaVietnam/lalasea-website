import { HtmlBasePlugin } from "@11ty/eleventy";

export default function (eleventyConfig) {
  // Ảnh và file tĩnh được copy nguyên vẹn sang trang web
  eleventyConfig.addPassthroughCopy({
    "anh": "anh",
    "menu": "menu",
    "uploads": "uploads",
    "hero.jpg": "hero.jpg",
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

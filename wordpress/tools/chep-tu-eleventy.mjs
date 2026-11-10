// ============================================================
// CHÉP DỮ LIỆU TỪ WEB ELEVENTY SANG THEME WORDPRESS
//
// Chạy trong giai đoạn chuyển tiếp, khi web Eleventy (src/) vẫn đang chạy và có
// thể được sửa. Script chép sang theme:
//   - src/css/style.css            → lalasea/css/style.css
//   - src/_data/*.json             → lalasea/data/*.json   (dữ liệu ban đầu)
//   - src/posts/*.md               → lalasea/seed/baiviet.json (nạp khi kích hoạt theme)
//   - src/quan-tri/config.yml      → lalasea/data/bieu-mau.json (các ô nhập trong /wp-admin)
//
// KHÔNG đụng tới template PHP: template trong lalasea/ là bản gốc, viết tay theo
// chuẩn WordPress. Sửa một file .njk thì sửa tay file PHP tương ứng, rồi chạy
// so-sanh-html.mjs để chắc hai bản vẫn giống nhau.
//
// Cách chạy (từ thư mục gốc dự án):
//   node wordpress/tools/chep-tu-eleventy.mjs
// ============================================================
import fs from "node:fs";
import path from "node:path";
import yaml from "js-yaml";

const GOC = process.cwd();
const RA = path.join(GOC, "wordpress/lalasea");

// ---------- Sao chép CSS và dữ liệu vào theme ----------
{
  fs.mkdirSync(path.join(RA, "css"), { recursive: true });
  fs.copyFileSync(path.join(GOC, "src/css/style.css"), path.join(RA, "css/style.css"));
  fs.mkdirSync(path.join(RA, "data"), { recursive: true });
  for (const t of fs.readdirSync(path.join(GOC, "src/_data"))) {
    if (t.endsWith(".json")) fs.copyFileSync(path.join(GOC, "src/_data", t), path.join(RA, "data", t));
  }
  console.log("✓ css/style.css + data/*.json");
}

// ---------- Bài viết Markdown → HTML (nạp vào WordPress khi kích hoạt theme) ----------
{
  const { default: matter } = await import("gray-matter");
  const { default: MarkdownIt } = await import("markdown-it");
  const md = new MarkdownIt({ html: true });
  const thu_muc = path.join(GOC, "src/posts");
  const ds = fs.readdirSync(thu_muc).filter((t) => t.endsWith(".md")).map((t) => {
    const g = matter(fs.readFileSync(path.join(thu_muc, t), "utf8"));
    const ngay = g.data.date instanceof Date ? g.data.date.toISOString().slice(0, 10) : String(g.data.date);
    return {
      slug: t.replace(/\.md$/, ""),
      title: g.data.title,
      date: ngay,
      mo_ta: g.data.mo_ta ?? "",
      anh_dai_dien: g.data.anh_dai_dien ?? "",
      noi_dung: md.render(g.content),
    };
  });
  fs.mkdirSync(path.join(RA, "seed"), { recursive: true });
  fs.writeFileSync(path.join(RA, "seed/baiviet.json"), JSON.stringify(ds, null, 2) + "\n");
  console.log(`✓ ${ds.length} bài viết → seed/baiviet.json`);
}

// ---------- Biểu mẫu nhập liệu cho trang quản trị WordPress ----------
// Lấy đúng nhãn, thứ tự, kiểu ô từ src/quan-tri/config.yml (trang quản trị cũ)
// để chủ quán sửa thông tin quán trong /wp-admin giống hệt như trước.
{
  const cfg = yaml.load(fs.readFileSync(path.join(GOC, "src/quan-tri/config.yml"), "utf8"));
  const caidat = cfg.collections.find((c) => c.name === "caidat");
  const gon = (f) => {
    const o = { name: f.name, label: f.label, widget: f.widget || "string" };
    if (f.label_singular) o.label_singular = f.label_singular;
    if (f.fields) o.fields = f.fields.map(gon);
    if (f.field) o.field = gon(f.field);
    if (f.default !== undefined) o.default = f.default;
    if (f.min !== undefined) o.min = f.min;
    if (f.max !== undefined) o.max = f.max;
    return o;
  };
  const bieu_mau = caidat.files.map((t) => ({ name: t.name, label: t.label, fields: t.fields.map(gon) }));
  fs.writeFileSync(path.join(RA, "data/bieu-mau.json"), JSON.stringify(bieu_mau, null, 2) + "\n");
  console.log(`✓ ${bieu_mau.length} biểu mẫu quản trị → data/bieu-mau.json`);
}

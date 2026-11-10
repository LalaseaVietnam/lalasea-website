// ============================================================
// CHUYỂN TEMPLATE NUNJUCKS (.njk) SANG PHP CHO THEME WORDPRESS
//
// Chạy một lần để sinh các file trong wordpress/lalasea/templates/.
// HTML trong template được giữ NGUYÊN VĂN — chỉ phần động ({{ }}, {% %})
// được dịch sang PHP, để giao diện WordPress giống web cũ từng pixel.
//
// Cách chạy (từ thư mục gốc dự án):
//   node wordpress/tools/njk-sang-php.mjs
// ============================================================
import fs from "node:fs";
import path from "node:path";
import yaml from "js-yaml";

const GOC = process.cwd();
const RA = path.join(GOC, "wordpress/lalasea");

// ---------- 1. Tách template thành text / {{ biểu thức }} / {% lệnh %} ----------
function tach(nguon) {
  const kq = [];
  const re = /\{\{-?([\s\S]*?)-?\}\}|\{%-?([\s\S]*?)-?%\}|\{#-?[\s\S]*?-?#\}/g;
  let cuoi = 0, m;
  while ((m = re.exec(nguon))) {
    if (m.index > cuoi) kq.push({ loai: "text", v: nguon.slice(cuoi, m.index) });
    const tho = m[0];
    const cat_trai = /^\{[{%#]-/.test(tho);
    const cat_phai = /-[}%#]\}$/.test(tho);
    let loai = "comment", v = "";
    if (tho.startsWith("{{")) { loai = "out"; v = m[1].trim(); }
    else if (tho.startsWith("{%")) { loai = "stmt"; v = m[2].trim(); }
    kq.push({ loai, v, cat_trai, cat_phai });
    cuoi = m.index + tho.length;
  }
  if (cuoi < nguon.length) kq.push({ loai: "text", v: nguon.slice(cuoi) });

  // Áp dụng {%- và -%}: cắt khoảng trắng của text kề bên
  for (let i = 0; i < kq.length; i++) {
    const t = kq[i];
    if (t.loai === "text") continue;
    if (t.cat_trai && kq[i - 1]?.loai === "text") kq[i - 1].v = kq[i - 1].v.replace(/\s+$/, "");
    if (t.cat_phai && kq[i + 1]?.loai === "text") kq[i + 1].v = kq[i + 1].v.replace(/^\s+/, "");
  }
  return kq.filter((t) => t.loai !== "comment");
}

// ---------- 2. Phân tích biểu thức Nunjucks ----------
function tokenize(s) {
  const out = [];
  const re = /\s*(?:('(?:[^'\\]|\\.)*'|"(?:[^"\\]|\\.)*")|(\d+(?:\.\d+)?)|([A-Za-z_][A-Za-z0-9_]*)|(==|!=|<=|>=|[()\[\],.|+<>-]))/gy;
  let m;
  while (re.lastIndex < s.length && (m = re.exec(s))) {
    if (m[1] !== undefined) out.push({ t: "str", v: m[1].slice(1, -1) });
    else if (m[2] !== undefined) out.push({ t: "num", v: m[2] });
    else if (m[3] !== undefined) out.push({ t: "id", v: m[3] });
    else out.push({ t: "op", v: m[4] });
  }
  if (re.lastIndex < s.trimEnd().length) throw new Error("Không đọc được biểu thức: " + s);
  return out;
}

const phpStr = (s) => "'" + s.replace(/\\/g, "\\\\").replace(/'/g, "\\'") + "'";
const BIEN_TOAN_CUC = new Set(["thongtin", "glamping", "menuanh", "danhgia", "thuvien", "baiviet"]);

function bienDich(s) {
  const tk = tokenize(s);
  let i = 0;
  let safe = false;
  const peek = () => tk[i];
  const an = (v) => { const t = tk[i]; if (t && t.v === v && t.t !== "str") { i++; return true; } return false; };
  const la = (v) => { const t = tk[i]; return t && t.v === v && t.t !== "str"; };

  function ternary() {
    const a = orE();
    if (la("if")) {
      i++;
      const c = orE();
      if (!an("else")) throw new Error("Thiếu else trong: " + s);
      const b = ternary();
      return `((${c}) ? ${a} : ${b})`;
    }
    return a;
  }
  function orE() { let a = andE(); while (la("or")) { i++; a = `(${a} ?: ${andE()})`; } return a; }
  function andE() { let a = notE(); while (la("and")) { i++; a = `(${a} && ${notE()})`; } return a; }
  function notE() { if (la("not")) { i++; return `!(${notE()})`; } return cmp(); }
  function cmp() {
    let a = add();
    while (peek() && peek().t === "op" && ["==", "!=", "<", ">", "<=", ">="].includes(peek().v)) {
      const op = tk[i++].v;
      a = `(${a} ${op} ${add()})`;
    }
    return a;
  }
  function add() { let a = filt(); while (la("+")) { i++; a = `ls_cong(${a}, ${filt()})`; } return a; }
  function filt() {
    let a = post();
    while (la("|")) {
      i++;
      const ten = tk[i++].v;
      const args = [];
      if (an("(")) {
        while (!la(")")) { args.push(ternary()); an(","); }
        i++;
      }
      if (ten === "safe") { safe = true; continue; }
      const map = { replace: "ls_replace", sdtQuocTe: "ls_sdt_quoc_te", ngayVN: "ls_ngay_vn", ngayChu: "ls_ngay_chu", giaEn: "ls_gia_en", dump: "ls_dump" };
      if (!map[ten]) throw new Error("Bộ lọc chưa hỗ trợ: " + ten);
      a = `${map[ten]}(${[a, ...args].join(", ")})`;
    }
    return a;
  }
  function post() {
    let a = prim();
    for (;;) {
      if (la(".")) {
        i++;
        const ten = tk[i++].v;
        if (la("(")) {
          i++;
          const args = [];
          while (!la(")")) { args.push(ternary()); an(","); }
          i++;
          a = `ls_goi(${a}, ${phpStr(ten)}, [${args.join(", ")}])`;
        } else a = `ls_lay(${a}, ${phpStr(ten)})`;
      } else if (la("[")) {
        i++;
        const k = ternary();
        i++; // ]
        a = `ls_lay(${a}, ${k})`;
      } else break;
    }
    return a;
  }
  function prim() {
    const t = tk[i++];
    if (!t) throw new Error("Biểu thức cụt: " + s);
    if (t.t === "str") return phpStr(t.v);
    if (t.t === "num") return t.v;
    if (t.t === "op" && t.v === "(") { const e = ternary(); i++; return `(${e})`; }
    if (t.t === "id") {
      if (t.v === "true") return "true";
      if (t.v === "false") return "false";
      if (t.v === "range" && la("(")) {
        i++;
        const a = ternary(); an(","); const b = ternary(); i++;
        return `ls_range(${a}, ${b})`;
      }
      if (t.v === "loop") return "$loop";
      if (t.v === "collections" && la(".")) { i++; const n = tk[i++].v; return "$" + n; }
      return "$" + t.v;
    }
    throw new Error("Token lạ: " + JSON.stringify(t) + " trong " + s);
  }

  const code = ternary();
  if (i < tk.length) throw new Error("Còn thừa token trong: " + s);
  return { code, safe };
}

// ---------- 3. Dịch cả template ----------
function dich(nguon) {
  const toks = tach(nguon);
  let php = "";
  const ngan_xep_for = [];
  const ngan_set = [];
  let dem_for = 0;

  const dong = (code, kế) => {
    // PHP nuốt một dấu xuống dòng ngay sau "?>" — thêm lại để giữ khoảng trắng như cũ
    const them = kế && kế.loai === "text" && kế.v.startsWith("\n") ? "\n" : "";
    return `<?php ${code} ?>${them}`;
  };
  const dongOut = (code, kế) => {
    const them = kế && kế.loai === "text" && kế.v.startsWith("\n") ? "\n" : "";
    return `<?= ${code} ?>${them}`;
  };

  toks.forEach((t, idx) => {
    const ke = toks[idx + 1];
    if (t.loai === "text") { php += t.v; return; }
    if (t.loai === "out") {
      const { code, safe } = bienDich(t.v);
      php += dongOut(safe ? `ls_raw(${code})` : `ls_e(${code})`, ke);
      return;
    }
    // stmt
    const v = t.v;
    let m;
    if ((m = v.match(/^if\s+([\s\S]+)$/))) php += dong(`if (${bienDich(m[1]).code}):`, ke);
    else if ((m = v.match(/^elif\s+([\s\S]+)$/))) php += dong(`elseif (${bienDich(m[1]).code}):`, ke);
    else if (v === "else") php += dong("else:", ke);
    else if (v === "endif") php += dong("endif;", ke);
    else if ((m = v.match(/^for\s+(\w+)\s+in\s+([\s\S]+)$/))) {
      const n = ++dem_for;
      ngan_xep_for.push(n);
      php += dong(`$__lp${n} = $loop ?? null; foreach (ls_lap(${bienDich(m[2]).code}) as $__k${n} => $${m[1]}): $loop = ['index' => $__k${n} + 1, 'index0' => $__k${n}];`, ke);
    } else if (v === "endfor") {
      const n = ngan_xep_for.pop();
      php += dong(`endforeach; $loop = $__lp${n};`, ke);
    } else if ((m = v.match(/^set\s+(\w+)$/))) {
      ngan_set.push(m[1]);
      php += dong("ob_start();", ke);
    } else if (v === "endset") {
      php += dong(`$${ngan_set.pop()} = ob_get_clean();`, ke);
    } else if ((m = v.match(/^set\s+(\w+)\s*=\s*([\s\S]+)$/))) {
      php += dong(`$${m[1]} = ${bienDich(m[2]).code};`, ke);
    } else throw new Error("Lệnh chưa hỗ trợ: {% " + v + " %}");
  });
  return php;
}

// ---------- 4. Đọc front matter + sinh file PHP ----------
function docTep(duong_dan) {
  const tho = fs.readFileSync(duong_dan, "utf8");
  const m = tho.match(/^---\r?\n([\s\S]*?)\r?\n---\r?\n?([\s\S]*)$/);
  if (!m) return { fm: {}, thanh_phan: tho };
  return { fm: yaml.load(m[1]) || {}, thanh_phan: m[2] };
}

const TRANG = [
  // [file nguồn, file đích, tên template trong WP, ngôn ngữ]
  ["src/index.njk", "templates/trang-chu.php", "Lalasea — Trang chủ", "vi"],
  ["src/thuc-don.njk", "templates/thuc-don.php", "Lalasea — Thực đơn", "vi"],
  ["src/glamping.njk", "templates/glamping.php", "Lalasea — Glamping", "vi"],
  ["src/tiec-su-kien.njk", "templates/tiec-su-kien.php", "Lalasea — Tiệc & Sự kiện", "vi"],
  ["src/lien-he.njk", "templates/lien-he.php", "Lalasea — Liên hệ", "vi"],
  ["src/en/index.njk", "templates/en-trang-chu.php", "Lalasea EN — Home", "en"],
  ["src/en/menu.njk", "templates/en-menu.php", "Lalasea EN — Menu", "en"],
  ["src/en/glamping.njk", "templates/en-glamping.php", "Lalasea EN — Glamping", "en"],
  ["src/en/events.njk", "templates/en-events.php", "Lalasea EN — Events", "en"],
  ["src/en/contact.njk", "templates/en-contact.php", "Lalasea EN — Contact", "en"],
  // Trang danh sách tin tức và 404 do WordPress gọi thẳng theo tên file (không cần chọn template)
  ["src/tin-tuc.njk", "home.php", null, "vi"],
  ["src/404.njk", "404.php", null, "vi"],
];

function phpMang(o) {
  const g = (v) => (v === null || v === undefined ? "null" : typeof v === "boolean" ? String(v) : phpStr(String(v)));
  return "[" + Object.entries(o).map(([k, v]) => `${phpStr(k)} => ${g(v)}`).join(", ") + "]";
}

fs.mkdirSync(path.join(RA, "templates"), { recursive: true });
for (const [nguon, dich_den, ten_tpl, ngon_ngu] of TRANG) {
  const { fm, thanh_phan } = docTep(path.join(GOC, nguon));
  const meta = {
    title: fm.title ?? null,
    mo_ta: fm.mo_ta ?? null,
    anh_dai_dien: fm.anh_dai_dien ?? null,
    ban_dich: fm.ban_dich ?? null,
    khong_index: !!fm.khong_index,
    an_footer: !!fm.an_footer,
    ngon_ngu,
    url: fm.permalink === "/404.html" ? null : fm.permalink ?? null,
  };
  const dau =
    `<?php\n` +
    (ten_tpl ? `/**\n * Template Name: ${ten_tpl}\n */\n` : "") +
    `// TỰ SINH từ ${nguon} bằng wordpress/tools/njk-sang-php.mjs — HTML giữ nguyên văn.\n` +
    `extract(ls_bien_toan_cuc());\n` +
    `$en = ${ngon_ngu === "en" ? "true" : "false"};\n` +
    `$title = ${meta.title == null ? "null" : phpStr(meta.title)};\n` +
    `$mo_ta = ${meta.mo_ta == null ? "null" : phpStr(meta.mo_ta)};\n` +
    `$anh_dai_dien = ${meta.anh_dai_dien == null ? "null" : phpStr(meta.anh_dai_dien)};\n` +
    `ls_dat_trang(${phpMang(meta)});\n` +
    `get_header();\n` +
    `?>\n`;
  const cuoi = `\n<?php get_footer();\n`;
  const out = dau + dich(thanh_phan.replace(/^\n+/, "")) + cuoi;
  fs.mkdirSync(path.dirname(path.join(RA, dich_den)), { recursive: true });
  fs.writeFileSync(path.join(RA, dich_den), out);
  console.log("✓", nguon, "→", "wordpress/lalasea/" + dich_den);
}

// ---------- 5. Khung trang (base.njk) → header.php + footer.php ----------
{
  const { thanh_phan } = docTep(path.join(GOC, "src/_includes/layouts/base.njk"));
  const toks = tach(thanh_phan);
  const k = toks.findIndex((t) => t.loai === "out" && /^content\s*\|\s*safe$/.test(t.v));
  if (k < 0) throw new Error("base.njk thiếu {{ content | safe }}");
  // Ghép lại thành chuỗi njk rồi dịch (cắt khoảng trắng đã được áp dụng ở bước tách)
  const ghep = (ds) => ds.map((t) => (t.loai === "text" ? t.v : t.loai === "out" ? `{{ ${t.v} }}` : `{% ${t.v} %}`)).join("");
  const dau_base = ghep(toks.slice(0, k));
  const duoi_base = ghep(toks.slice(k + 1));

  const prelude =
    `<?php\n// TỰ SINH từ src/_includes/layouts/base.njk bằng wordpress/tools/njk-sang-php.mjs\n` +
    `extract(ls_bien_toan_cuc());\n` +
    `$__p = ls_trang();\n` +
    `$ngon_ngu = $__p['ngon_ngu']; $title = $__p['title']; $mo_ta = $__p['mo_ta']; $anh_dai_dien = $__p['anh_dai_dien'];\n` +
    `$ban_dich = $__p['ban_dich']; $khong_index = $__p['khong_index']; $an_footer = $__p['an_footer']; $og_type = $__p['og_type'];\n` +
    `$page = ['url' => $__p['url']];\n` +
    `$en = $ngon_ngu === 'en';\n?>`;
  fs.writeFileSync(path.join(RA, "header.php"), prelude + dich(dau_base));
  fs.writeFileSync(path.join(RA, "footer.php"), prelude + dich(duoi_base));
  console.log("✓ base.njk → header.php + footer.php");
}

// ---------- 6. Bài viết (post.njk) → single.php ----------
{
  const { thanh_phan } = docTep(path.join(GOC, "src/_includes/layouts/post.njk"));
  const dau =
    `<?php\n// TỰ SINH từ src/_includes/layouts/post.njk bằng wordpress/tools/njk-sang-php.mjs\n` +
    `extract(ls_bien_toan_cuc());\n` +
    `the_post();\n` +
    `$bai = ls_bai_hien_tai();\n` +
    `$en = false; $title = $bai['title']; $mo_ta = $bai['mo_ta']; $anh_dai_dien = $bai['anh_dai_dien'];\n` +
    `$page = ['url' => $bai['url'], 'date' => $bai['date']]; $content = $bai['noi_dung'];\n` +
    `ls_dat_trang(['title' => $title, 'mo_ta' => $mo_ta, 'anh_dai_dien' => $anh_dai_dien, 'ban_dich' => null, 'khong_index' => false, 'an_footer' => false, 'ngon_ngu' => 'vi', 'og_type' => 'article', 'url' => $bai['url']]);\n` +
    `get_header();\n?>\n`;
  fs.writeFileSync(path.join(RA, "single.php"), dau + dich(thanh_phan.replace(/^\n+/, "")) + "\n<?php get_footer();\n");
  console.log("✓ post.njk → single.php");
}

// ---------- 7. Chèn các móc của WordPress vào khung trang ----------
{
  const doc = (t) => fs.readFileSync(path.join(RA, t), "utf8");
  const ghi = (t, s) => fs.writeFileSync(path.join(RA, t), s);
  let h = doc("header.php");
  const co = (s, a) => { if (!s.includes(a)) throw new Error("Không thấy đoạn cần thay: " + a); };
  co(h, '<link rel="stylesheet" href="/css/style.css">');
  h = h.replace('<link rel="stylesheet" href="/css/style.css">',
    `<link rel="stylesheet" href="<?= esc_url(get_theme_file_uri('css/style.css')) ?>?v=<?= (int) filemtime(get_theme_file_path('css/style.css')) ?>">`);
  co(h, "</head>");
  // wp_head(): chỗ các plugin/GTM/Pixel chèn mã vào <head>
  h = h.replace("</head>", "<?php wp_head(); ?>\n</head>");
  co(h, "<body>");
  // wp_body_open(): chỗ GTM chèn thẻ <noscript> ngay sau <body>
  h = h.replace("<body>", "<body>\n<?php wp_body_open(); ?>");
  ghi("header.php", h);

  let f = doc("footer.php");
  co(f, "</body>");
  f = f.replace("</body>", "<?php wp_footer(); ?>\n</body>");
  ghi("footer.php", f);
}

// ---------- 8. Sao chép CSS và dữ liệu vào theme ----------
{
  fs.mkdirSync(path.join(RA, "css"), { recursive: true });
  fs.copyFileSync(path.join(GOC, "src/css/style.css"), path.join(RA, "css/style.css"));
  fs.mkdirSync(path.join(RA, "data"), { recursive: true });
  for (const t of fs.readdirSync(path.join(GOC, "src/_data"))) {
    if (t.endsWith(".json")) fs.copyFileSync(path.join(GOC, "src/_data", t), path.join(RA, "data", t));
  }
  console.log("✓ css/style.css + data/*.json");
}

// ---------- 9. Bài viết Markdown → HTML (nạp vào WordPress khi kích hoạt theme) ----------
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

// ---------- 10. Biểu mẫu nhập liệu cho trang quản trị WordPress ----------
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

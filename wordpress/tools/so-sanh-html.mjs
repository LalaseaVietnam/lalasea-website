// ============================================================
// SO SÁNH HTML: web Eleventy (đã build) ↔ WordPress (đang chạy)
//
//   node wordpress/tools/so-sanh-html.mjs <thư mục _site> <địa chỉ WordPress>
//   ví dụ: node wordpress/tools/so-sanh-html.mjs _site http://127.0.0.1:8081
//
// So theo cách TRÌNH DUYỆT và GOOGLE đọc trang, không so từng byte:
//  - ký tự đặc biệt được giải mã trước khi so (&#39; và &#039; đều là dấu ');
//  - khoảng trắng thừa giữa các thẻ bị bỏ qua;
//  - schema JSON-LD được đọc thành dữ liệu rồi mới so (cách viết JSON khác nhau
//    nhưng cùng nội dung thì coi là giống);
//  - đường dẫn file CSS được bỏ qua (WordPress để CSS trong thư mục theme).
// Mọi khác biệt còn lại — thẻ, thuộc tính, chữ, thứ tự — đều được in ra.
// ============================================================
import fs from "node:fs";
import path from "node:path";
import { Parser } from "htmlparser2";

const [thu_muc, wp] = process.argv.slice(2);
if (!thu_muc || !wp) {
  console.error("Cách dùng: node so-sanh-html.mjs <_site> <http://địa-chỉ-wordpress>");
  process.exit(2);
}

const TRANG = [
  "/", "/thuc-don/", "/glamping/", "/tiec-su-kien/", "/lien-he/", "/tin-tuc/",
  "/en/", "/en/menu/", "/en/glamping/", "/en/events/", "/en/contact/",
  ...fs.readdirSync(path.join(thu_muc, "tin-tuc"), { withFileTypes: true })
    .filter((d) => d.isDirectory()).map((d) => `/tin-tuc/${d.name}/`)
    // Bỏ trang mồi chuyển hướng (meta refresh) — WordPress thay bằng 301 thật
    .filter((u) => !fs.readFileSync(path.join(thu_muc, u, "index.html"), "utf8").includes('http-equiv="refresh"')),
];

// Khác biệt CÓ CHỦ Ý — lỗi của web cũ mà bản WordPress đã sửa. Luôn in ra cuối báo cáo.
//  1. Web cũ ghi link trong schema JSON-LD thành "...?api=1&amp;query=..." (Nunjucks tự
//     escape HTML cả bên trong <script>). Trong JSON, "&amp;" KHÔNG được giải mã nên Google
//     đọc ra link sai. WordPress dùng wp_json_encode() nên ghi đúng "&".
let so_lan_sua = 0;
const suaLoiCu = (v) => {
  if (typeof v === "string") {
    if (v.includes("&amp;")) { so_lan_sua++; return v.replaceAll("&amp;", "&"); }
    return v;
  }
  if (Array.isArray(v)) return v.map(suaLoiCu);
  if (v && typeof v === "object") return Object.fromEntries(Object.entries(v).map(([k, x]) => [k, suaLoiCu(x)]));
  return v;
};

// JSON đọc thành dữ liệu, in lại theo một cách duy nhất (khoá theo thứ tự gốc)
function jsonChuan(s, la_ban_cu) {
  try {
    const d = JSON.parse(s);
    return "JSON " + JSON.stringify(la_ban_cu ? suaLoiCu(d) : d);
  } catch {
    return "JSON-LỖI " + s.replace(/\s+/g, " ").trim();
  }
}

// HTML → danh sách dòng: mỗi thẻ mở/đóng và mỗi đoạn chữ một dòng
function chuanHoa(html, la_ban_cu = false) {
  const dong = [];
  let trong_script = null;
  let chu = "";
  const xa_chu = () => {
    const t = chu.replace(/\s+/g, " ").trim();
    if (t) dong.push("  " + t);
    chu = "";
  };
  const p = new Parser(
    {
      onopentag(ten, thuoc_tinh) {
        xa_chu();
        if (ten === "link" && thuoc_tinh.rel === "stylesheet") {
          // WordPress nạp CSS bằng wp_enqueue_style(): thẻ có thêm id="..." và media="all"
          // (mặc định của trình duyệt), CSS của theme nằm trong thư mục theme kèm ?ver=.
          const { id, media, ...con_lai } = thuoc_tinh;
          if (media && media !== "all") con_lai.media = media;
          if (/style\.css/.test(con_lai.href || "")) con_lai.href = "style.css";
          thuoc_tinh = con_lai;
        }
        // Thứ tự thuộc tính trong thẻ không có ý nghĩa
        const tt = Object.entries(thuoc_tinh).sort(([a], [b]) => a.localeCompare(b)).map(([k, v]) => `${k}="${v}"`).join(" ");
        dong.push(`<${ten}${tt ? " " + tt : ""}>`);
        if (ten === "script") trong_script = thuoc_tinh.type || "js";
      },
      ontext(t) {
        chu += t;
      },
      onclosetag(ten) {
        if (ten === "script" && trong_script) {
          const nd = chu;
          chu = "";
          if (trong_script === "application/ld+json") dong.push("  " + jsonChuan(nd, la_ban_cu));
          else if (nd.trim()) dong.push("  " + nd.replace(/\s+/g, " ").trim());
          trong_script = null;
        } else xa_chu();
        dong.push(`</${ten}>`);
      },
    },
    { decodeEntities: true, recognizeSelfClosing: true },
  );
  p.write(html);
  p.end();
  xa_chu();
  return dong;
}

let loi = 0;
for (const url of TRANG) {
  const cu = chuanHoa(fs.readFileSync(path.join(thu_muc, url, "index.html"), "utf8"), true);
  const moi = chuanHoa(await (await fetch(wp + url)).text());

  // Diff theo dòng (LCS) — đủ cho trang vài nghìn dòng
  const n = cu.length, m = moi.length;
  const dp = Array.from({ length: n + 1 }, () => new Uint32Array(m + 1));
  for (let i = n - 1; i >= 0; i--)
    for (let j = m - 1; j >= 0; j--)
      dp[i][j] = cu[i] === moi[j] ? dp[i + 1][j + 1] + 1 : Math.max(dp[i + 1][j], dp[i][j + 1]);
  const khac = [];
  let i = 0, j = 0;
  while (i < n || j < m) {
    if (i < n && j < m && cu[i] === moi[j]) { i++; j++; }
    else if (j < m && (i === n || dp[i][j + 1] >= dp[i + 1][j])) khac.push("+ WP : " + moi[j++]);
    else khac.push("- cũ : " + cu[i++]);
  }
  if (khac.length === 0) console.log(`✓ ${url} — giống hệt (${n} dòng)`);
  else {
    loi++;
    console.log(`✗ ${url} — ${khac.length} dòng khác:`);
    for (const k of khac.slice(0, 30)) console.log("    " + k.slice(0, 260));
  }
}
console.log(loi ? `\n${loi} trang còn khác.` : `\nTất cả ${TRANG.length} trang giống hệt.`);
if (so_lan_sua) {
  console.log(`(Đã bỏ qua ${so_lan_sua} chỗ khác có chủ ý: link trong schema JSON-LD của web cũ bị ghi "&amp;" — lỗi cũ, bản WordPress ghi đúng "&".)`);
}
process.exit(loi ? 1 : 0);

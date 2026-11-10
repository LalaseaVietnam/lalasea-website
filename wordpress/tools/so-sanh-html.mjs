// ============================================================
// SO SÁNH HTML: web Eleventy (đã build) ↔ WordPress (đang chạy)
//
//   node wordpress/tools/so-sanh-html.mjs <thư mục _site> <địa chỉ WordPress>
//   ví dụ: node wordpress/tools/so-sanh-html.mjs _site http://127.0.0.1:8081
//
// Bỏ qua khác biệt khoảng trắng và đường dẫn file CSS (trong WordPress CSS
// nằm trong thư mục theme). Mọi khác biệt còn lại đều được in ra.
// ============================================================
import fs from "node:fs";
import path from "node:path";

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

const chuan_hoa = (html) =>
  html
    .replace(/<link rel="stylesheet" href="[^"]*style\.css[^"]*">/g, '<link rel="stylesheet" href="style.css">')
    .replace(/>\s+</g, "><")
    .replace(/\s+/g, " ")
    .replace(/></g, ">\n<")
    .trim()
    .split("\n");

let loi = 0;
for (const url of TRANG) {
  const cu = chuan_hoa(fs.readFileSync(path.join(thu_muc, url, "index.html"), "utf8"));
  const moi = chuan_hoa(await (await fetch(wp + url)).text());

  // Diff đơn giản theo dòng (LCS) — đủ cho trang vài nghìn dòng
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
    for (const k of khac.slice(0, 30)) console.log("    " + k.slice(0, 220));
  }
}
console.log(loi ? `\n${loi} trang còn khác.` : `\nTất cả ${TRANG.length} trang giống hệt.`);
process.exit(loi ? 1 : 0);

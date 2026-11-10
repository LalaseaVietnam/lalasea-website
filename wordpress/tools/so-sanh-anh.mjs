// ============================================================
// SO SÁNH ẢNH CHỤP: web cũ ↔ WordPress, ở khổ 390px và 1280px
//
//   node wordpress/tools/so-sanh-anh.mjs <địa chỉ web cũ> <địa chỉ WordPress> <thư mục lưu ảnh>
//
// Chụp toàn trang bằng Chromium, so từng điểm ảnh, và kiểm tra trang không
// tràn ngang (scrollWidth - clientWidth === 0) như DESIGN.md §12 yêu cầu.
// ============================================================
import fs from "node:fs";
import path from "node:path";
import { createRequire } from "node:module";

// require() đọc được cả Playwright cài toàn cục (đặt NODE_PATH nếu cần)
const { chromium } = createRequire(import.meta.url)("playwright");

const [cu, wp, ra] = process.argv.slice(2);
fs.mkdirSync(ra, { recursive: true });

const TRANG = ["/", "/thuc-don/", "/glamping/", "/tiec-su-kien/", "/lien-he/", "/tin-tuc/",
  "/tin-tuc/an-gi-o-ha-tien/", "/en/", "/en/menu/", "/en/glamping/", "/en/events/", "/en/contact/"];
const KHO = [390, 1280];

const trinh_duyet = await chromium.launch();

async function chup(trang, goc, url, rong) {
  await trang.setViewportSize({ width: rong, height: 900 });
  await trang.goto(goc + url, { waitUntil: "networkidle" });
  // Tắt hiệu ứng: hiện hết khối .reveal, dừng ảnh trượt, chờ font
  await trang.addStyleTag({ content: "*,*::before,*::after{transition:none!important;animation:none!important;caret-color:transparent!important}" });
  await trang.evaluate(async () => {
    document.querySelectorAll(".reveal").forEach((e) => e.classList.add("in"));
    for (let i = 1; i < 99999; i++) clearInterval(i);
    // Bắt mọi ảnh lazy-load tải ngay và chờ xong, để hai bên chụp cùng một trạng thái
    const anh = [...document.images];
    anh.forEach((i) => (i.loading = "eager"));
    await Promise.all(anh.map((i) => (i.complete ? null : new Promise((ok) => { i.onload = i.onerror = ok; }))));
    await document.fonts.ready;
  });
  await trang.waitForTimeout(300);
  const tran = await trang.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
  return { png: await trang.screenshot({ fullPage: true }), tran };
}

const ket_qua = [];
const trang = await trinh_duyet.newPage({ deviceScaleFactor: 1 });
// Trang phụ dùng canvas để so điểm ảnh, khỏi cài thêm thư viện
const so = await trinh_duyet.newPage();
await so.setContent("<canvas id=a></canvas><canvas id=b></canvas>");

for (const url of TRANG) {
  for (const rong of KHO) {
    const A = await chup(trang, cu, url, rong);
    const B = await chup(trang, wp, url, rong);
    const ten = (url.replace(/\//g, "_") || "_") + rong;
    fs.writeFileSync(path.join(ra, ten + "-cu.png"), A.png);
    fs.writeFileSync(path.join(ra, ten + "-wp.png"), B.png);
    const kq = await so.evaluate(async ([a, b]) => {
      const tai = (s) => new Promise((ok) => { const i = new Image(); i.onload = () => ok(i); i.src = "data:image/png;base64," + s; });
      const [ia, ib] = await Promise.all([tai(a), tai(b)]);
      if (ia.width !== ib.width || ia.height !== ib.height)
        return { khac: -1, kt: `${ia.width}×${ia.height} vs ${ib.width}×${ib.height}` };
      const lay = (img) => { const c = document.createElement("canvas"); c.width = img.width; c.height = img.height; const x = c.getContext("2d"); x.drawImage(img, 0, 0); return x.getImageData(0, 0, c.width, c.height).data; };
      const da = lay(ia), db = lay(ib);
      let khac = 0;
      for (let i = 0; i < da.length; i += 4)
        if (Math.abs(da[i] - db[i]) + Math.abs(da[i + 1] - db[i + 1]) + Math.abs(da[i + 2] - db[i + 2]) > 30) khac++;
      return { khac, tong: da.length / 4, kt: `${ia.width}×${ia.height}` };
    }, [A.png.toString("base64"), B.png.toString("base64")]);
    const ti_le = kq.khac < 0 ? null : (kq.khac / kq.tong) * 100;
    ket_qua.push({ url, rong, ...kq, ti_le, tran_cu: A.tran, tran_wp: B.tran });
    console.log(`${kq.khac === 0 && B.tran === 0 ? "✓" : "✗"} ${url.padEnd(28)} ${String(rong).padStart(4)}px  ${kq.kt.padEnd(12)} điểm khác: ${kq.khac < 0 ? "khác kích thước" : kq.khac + " (" + ti_le.toFixed(4) + "%)"}  tràn ngang: cũ ${A.tran}, WP ${B.tran}`);
  }
}
await trinh_duyet.close();
fs.writeFileSync(path.join(ra, "ket-qua.json"), JSON.stringify(ket_qua, null, 2));

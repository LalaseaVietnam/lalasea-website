<?php
// TỰ SINH từ src/_includes/layouts/base.njk bằng wordpress/tools/njk-sang-php.mjs
extract(ls_bien_toan_cuc());
$__p = ls_trang();
$ngon_ngu = $__p['ngon_ngu']; $title = $__p['title']; $mo_ta = $__p['mo_ta']; $anh_dai_dien = $__p['anh_dai_dien'];
$ban_dich = $__p['ban_dich']; $khong_index = $__p['khong_index']; $an_footer = $__p['an_footer']; $og_type = $__p['og_type'];
$page = ['url' => $__p['url']];
$en = $ngon_ngu === 'en';
?>
  </main>

  <?php if (!($an_footer)): ?><?php $tel = (($en) ? (ls_cong('+84', (ls_replace(ls_lay($thongtin, 'dien_thoai_goi'), '0', '', 1)))) : ls_lay($thongtin, 'dien_thoai_goi')); ?>

  <footer class="site-footer">
    <svg class="logo-svg" width="44" height="90"><use href="#lalasea-logo"/></svg>
    <div class="socials">
      <a href="<?= ls_e(ls_lay($thongtin, 'facebook')) ?>" target="_blank" rel="noopener" aria-label="Facebook">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.25-1.5 1.55-1.5H17V3.6c-.3 0-1.3-.1-2.45-.1-2.4 0-4.05 1.5-4.05 4.2v2.2H7.8V13h2.7v8h3z"/></svg>
      </a>
      <a href="<?= ls_e(ls_lay($thongtin, 'tiktok')) ?>" target="_blank" rel="noopener" aria-label="TikTok">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M16.5 3c.3 2 1.6 3.5 3.5 3.8v2.6c-1.3 0-2.5-.4-3.5-1v6.1c0 3.3-2.4 5.5-5.4 5.5A5.3 5.3 0 0 1 6 14.5c0-3 2.4-5.3 5.6-5V12a2.7 2.7 0 0 0-.6-.1 2.7 2.7 0 1 0 2.7 2.7V3h2.8z"/></svg>
      </a>
      <a href="<?= ls_e(ls_lay($thongtin, 'zalo')) ?>" target="_blank" rel="noopener" aria-label="Zalo">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12 2C6.5 2 2 5.9 2 10.7c0 2.6 1.3 4.9 3.4 6.5-.1.8-.5 2.2-1.4 3.3-.2.2 0 .6.3.5 1.9-.3 3.5-1.1 4.4-1.7.4.1 2.1.4 3.3.4 5.5 0 10-3.9 10-8.7S17.5 2 12 2zM7.9 13.5H5.2c-.3 0-.5-.2-.5-.5s.1-.5.3-.7l2.3-3h-2c-.3 0-.5-.2-.5-.5s.2-.5.5-.5h2.6c.3 0 .5.2.5.5 0 .2-.1.4-.2.6l-2.3 3h2c.3 0 .5.2.5.5s-.2.6-.5.6zm3 0c0 .3-.2.5-.5.5s-.5-.2-.5-.5V8.8c0-.3.2-.5.5-.5s.5.2.5.5v4.7zm5.3 0c0 .3-.2.5-.5.5-.2 0-.3-.1-.4-.2-.4.2-.8.3-1.2.3-1.3 0-2.4-1.1-2.4-2.4s1.1-2.4 2.4-2.4c.4 0 .8.1 1.2.3.1-.1.2-.2.4-.2.3 0 .5.2.5.5v3.6zm2.7.5c-.3 0-.5-.2-.5-.5V8.8c0-.3.2-.5.5-.5s.5.2.5.5v4.7c0 .3-.2.5-.5.5zm-4.4-3.6c-.7 0-1.3.6-1.3 1.3s.6 1.3 1.3 1.3 1.3-.6 1.3-1.3-.6-1.3-1.3-1.3z"/></svg>
      </a>
    </div>
    <p>© 2026 <?= ls_e(ls_lay($thongtin, 'ten')) ?> · <?= ls_e(ls_lay($thongtin, 'dia_chi')) ?> · <?= ls_e((($en) ? ls_sdt_quoc_te(ls_lay($thongtin, 'dien_thoai')) : ls_lay($thongtin, 'dien_thoai'))) ?></p>
  </footer>

  <div class="float-actions">
    <a href="<?= ls_e(ls_lay($thongtin, 'zalo')) ?>" target="_blank" rel="noopener" class="fab fab-zalo" aria-label="<?= ls_e((($en) ? 'Message us on Zalo' : 'Nhắn Zalo')) ?>">Zalo</a>
    <a href="tel:<?= ls_e($tel) ?>" class="fab fab-phone" aria-label="<?= ls_e((($en) ? 'Call to book' : 'Gọi đặt bàn')) ?>">
      <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.2.2 2.4.6 3.6.1.3 0 .7-.2 1l-2.3 2.2z"/></svg>
    </a>
  </div>

  <nav class="quickbar" aria-label="<?= ls_e((($en) ? 'Quick contact' : 'Liên hệ nhanh')) ?>">
    <a href="tel:<?= ls_e($tel) ?>">
      <svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.2.2 2.4.6 3.6.1.3 0 .7-.2 1l-2.3 2.2z"/></svg>
      <?= ls_e((($en) ? 'Call' : 'Gọi')) ?>

    </a>
    <a href="<?= ls_e(ls_lay($thongtin, 'zalo')) ?>" target="_blank" rel="noopener">
      <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 3C7 3 3 6.6 3 11c0 2.4 1.2 4.5 3.1 6-.1.7-.5 2-1.3 3-.2.2 0 .5.3.5 1.7-.3 3.2-1 4-1.6.9.2 1.9.3 2.9.3 5 0 9-3.6 9-8.1S17 3 12 3z"/></svg>
      Zalo
    </a>
    <a href="<?= ls_e(ls_lay($thongtin, 'chi_duong')) ?>" target="_blank" rel="noopener">
      <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.1 2 5 5.1 5 9c0 5.2 7 13 7 13s7-7.8 7-13c0-3.9-3.1-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg>
      <?= ls_e((($en) ? 'Directions' : 'Chỉ đường')) ?>

    </a>
    <a href="<?= ls_e((($en) ? '/en/#book' : '/#dat-ban')) ?>" class="qb-main">
      <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17 3h-1V1h-2v2H10V1H8v2H7c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H7V9h10v10z"/></svg>
      <?= ls_e((($en) ? 'BOOK' : 'ĐẶT BÀN')) ?>

    </a>
  </nav>
  <?php endif; ?>


  <script>
    (function () {
      var t = document.getElementById('nav-toggle');
      var l = document.getElementById('nav-links');
      if (t && l) {
        t.addEventListener('click', function () {
          var open = l.classList.toggle('open');
          t.setAttribute('aria-expanded', open ? 'true' : 'false');
          t.classList.toggle('active', open);
        });
      }
      // Hiệu ứng hiện dần khi cuộn trang
      if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
          entries.forEach(function (e) {
            if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
          });
        }, { threshold: 0.12 });
        document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });
      } else {
        document.querySelectorAll('.reveal').forEach(function (el) { el.classList.add('in'); });
      }
    })();
  </script>

<?php wp_footer(); ?>
</body>
</html>

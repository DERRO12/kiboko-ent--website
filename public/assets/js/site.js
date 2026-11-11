(function () {
  const header = document.getElementById('siteHeader');
  if (header) {
    const onScroll = () => header.classList.toggle('scrolled', window.scrollY > 10);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // Brand logo carousel: arrows + gentle auto-scroll (pauses on hover/touch).
  document.querySelectorAll('[data-carousel]').forEach(c => {
    const track = c.querySelector('[data-track]');
    const step = () => (track.firstElementChild ? track.firstElementChild.offsetWidth + 18 : 200);
    const move = dir => {
      const max = track.scrollWidth - track.clientWidth - 2;
      if (dir > 0 && track.scrollLeft >= max) track.scrollTo({ left: 0 });
      else if (dir < 0 && track.scrollLeft <= 0) track.scrollTo({ left: max });
      else track.scrollBy({ left: dir * step() });
    };
    c.querySelectorAll('[data-dir]').forEach(b => b.addEventListener('click', () => move(+b.dataset.dir)));
    let paused = false;
    ['mouseenter', 'touchstart', 'focusin'].forEach(e => c.addEventListener(e, () => (paused = true), { passive: true }));
    ['mouseleave', 'touchend', 'focusout'].forEach(e => c.addEventListener(e, () => (paused = false), { passive: true }));
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) setInterval(() => { if (!paused) move(1); }, 3000);
  });

  // Coverage map (Leaflet + OpenStreetMap), points come from the database.
  const mapEl = document.querySelector('[data-map]');
  if (mapEl) {
    const init = () => {
      if (!window.L) return setTimeout(init, 100);
      const pts = JSON.parse(mapEl.dataset.map || '[]');
      const map = L.map(mapEl, { scrollWheelZoom: false }).setView([1.37, 32.29], 7);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18, attribution: '&copy; OpenStreetMap contributors' }).addTo(map);
      const esc = s => String(s ?? '').replace(/[&<>"']/g, ch => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ch]));
      const bounds = [];
      pts.forEach(p => {
        const size = p.t === 'hub' ? 22 : 16;
        const icon = L.divIcon({ className: 'map-pin ' + esc(p.t), iconSize: [size, size] });
        L.marker([p.lat, p.lng], { icon, title: p.n }).addTo(map).bindPopup(
          (p.img ? '<img src="' + esc(p.img) + '" alt="" style="width:180px;border-radius:8px;margin-bottom:6px"><br>' : '') +
          '<b>' + esc(p.n) + '</b><br><small>' + esc([p.d, p.r + ' Region'].filter(Boolean).join(', ')) + '</small>' + (p.x ? '<p style="margin:6px 0 0">' + esc(p.x) + '</p>' : ''));
        bounds.push([p.lat, p.lng]);
      });
      if (bounds.length > 1) map.fitBounds(bounds, { padding: [30, 30] });
    };
    init();
  }
})();



(function () {
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const fmt = n => n.toLocaleString('en-US');
  const countUp = el => {
    if (el.dataset.done) return; el.dataset.done = 1;
    const end = +el.dataset.count, t0 = performance.now(), dur = 1600;
    const tick = t => { const p = Math.min(1, (t - t0) / dur); el.textContent = fmt(Math.round(end * (1 - Math.pow(1 - p, 3)))); if (p < 1) requestAnimationFrame(tick); };
    requestAnimationFrame(tick);
  };

  // Reveal on scroll + count-up statistics
  if ('IntersectionObserver' in window && !reduce) {
    const targets = document.querySelectorAll('.section-title, .svc-card, .svc-tile, .value-card, .article-card, .stat-card, .brand-grid-item, .img-frame, .form-card, .contact-list li, .tl-item, .cta-band, .side-card');
    targets.forEach((el, i) => { el.classList.add('reveal'); el.style.transitionDelay = ((i % 4) * 80) + 'ms'; });
    const io = new IntersectionObserver(entries => entries.forEach(e => {
      if (!e.isIntersecting) return;
      e.target.classList.add('in');
      if (e.target.matches('[data-count]')) countUp(e.target);
      e.target.querySelectorAll('[data-count]').forEach(countUp);
      io.unobserve(e.target);
    }), { threshold: 0.15 });
    targets.forEach(el => io.observe(el));
    document.querySelectorAll('[data-count]').forEach(el => io.observe(el));
  }

  // Back to top
  const top = document.querySelector('[data-to-top]');
  if (top) {
    window.addEventListener('scroll', () => top.classList.toggle('show', window.scrollY > 600), { passive: true });
    top.addEventListener('click', () => window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' }));
  }

  // Brands page: search + category chips
  document.querySelectorAll('.sec-brands_tabs').forEach(sec => {
    const input = sec.querySelector('[data-brand-search]');
    let cat = '';
    const apply = () => {
      const q = (input ? input.value : '').trim().toLowerCase();
      sec.querySelectorAll('.tab-pane').forEach(pane => {
        let shown = 0;
        pane.querySelectorAll('.brand-grid-item[data-name]').forEach(it => {
          const ok = (!q || it.dataset.name.includes(q)) && (!cat || it.dataset.cat === cat);
          it.classList.toggle('hide', !ok); if (ok) shown++;
        });
        const more = pane.querySelector('.more-item'); if (more) more.classList.toggle('hide', !!(q || cat));
        const none = pane.querySelector('[data-no-results]'); if (none) none.classList.toggle('d-none', shown > 0);
      });
    };
    if (input) input.addEventListener('input', apply);
    sec.querySelectorAll('.cat-chips button').forEach(b => b.addEventListener('click', () => {
      b.parentElement.querySelectorAll('button').forEach(x => x.classList.remove('active'));
      b.classList.add('active'); cat = b.dataset.cat; apply();
    }));
    sec.querySelectorAll('[data-bs-toggle="pill"]').forEach(t => t.addEventListener('shown.bs.tab', () => {
      cat = ''; sec.querySelectorAll('.cat-chips button').forEach(x => x.classList.toggle('active', x.dataset.cat === '')); apply();
    }));
  });
})();

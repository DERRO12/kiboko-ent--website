(function () {
  const modalEl = document.getElementById('mediaPicker');
  if (!modalEl) return;
  const modal = new bootstrap.Modal(modalEl);
  const grid = document.getElementById('mpGrid'), more = document.getElementById('mpMore'), search = document.getElementById('mpSearch');
  let target = null, page = 1, timer;

  function load(reset) {
    if (reset) { page = 1; grid.innerHTML = ''; }
    fetch(window.MEDIA_PICKER_URL + '?page=' + page + '&q=' + encodeURIComponent(search.value), { headers: { Accept: 'application/json' } })
      .then(r => r.json()).then(res => {
        res.data.forEach(m => {
          const el = document.createElement('div');
          el.className = 'mp-item';
          el.innerHTML = '<img loading="lazy"><span></span>';
          el.querySelector('img').src = m.thumb;
          el.querySelector('span').textContent = m.title;
          el.onclick = () => { choose(m); };
          grid.appendChild(el);
        });
        if (!res.data.length && page === 1) grid.innerHTML = '<p class="text-muted">No images. Upload one with the file field instead.</p>';
        more.classList.toggle('d-none', !res.next);
      });
  }
  function choose(m) {
    target.querySelector('[data-media-id]').value = m.id;
    const img = target.querySelector('[data-media-preview]');
    img.src = m.thumb; img.classList.remove('d-none');
    target.querySelector('[data-media-clear]').classList.remove('d-none');
    modal.hide();
  }
  document.addEventListener('click', e => {
    const pick = e.target.closest('[data-media-pick]');
    if (pick) { target = pick.closest('[data-media-field]'); search.value = ''; load(true); modal.show(); }
    const clear = e.target.closest('[data-media-clear]');
    if (clear) {
      const f = clear.closest('[data-media-field]');
      f.querySelector('[data-media-id]').value = '';
      f.querySelector('[data-media-preview]').classList.add('d-none');
      clear.classList.add('d-none');
    }
  });
  more.onclick = () => { page++; load(false); };
  search.oninput = () => { clearTimeout(timer); timer = setTimeout(() => load(true), 300); };
  document.addEventListener('input', e => {
    if (e.target.matches('[data-icon-input]')) {
      const v = e.target.value.trim();
      e.target.closest('.input-group').querySelector('[data-icon-preview]').className = (v.includes(' ') ? v : 'fa-solid ' + (v || 'fa-circle')) + ' text-success';
    }
  });
})();


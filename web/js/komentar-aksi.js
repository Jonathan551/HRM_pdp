(function () {
  function fetchHtml(url, opts) {
    opts = opts || {};
    opts.headers = Object.assign({'X-Requested-With': 'XMLHttpRequest'}, opts.headers || {});
    return fetch(url, opts).then(function (r) {
      if (!r.ok) throw new Error(r.status + ' ' + r.statusText);
      return r.text();
    });
  }

  document.addEventListener('click', function (e) {
    var btn = e.target.closest('.btn-komen-edit');
    if (btn) {
      var id  = btn.getAttribute('data-id');
      var url = btn.getAttribute('data-url');
      var box = document.getElementById('edit-box-' + id);
      if (!box) return;

      box.classList.remove('d-none');
      if (!box.dataset.loaded) {
        box.innerHTML = '<div class="text-muted">Memuat form…</div>';
        fetchHtml(url)
          .then(function (html) {
            box.innerHTML = html;
            box.dataset.loaded = '1';
          })
          .catch(function (err) {
            box.innerHTML = '<div class="text-danger">Gagal memuat form (' + err.message + ').</div>';
            console.error('Edit load error:', err);
          });
      } else {
        var hidden = box.classList.contains('d-none');
        box.classList.toggle('d-none', !hidden);
      }
    }

    var cancel = e.target.closest('[data-cancel-edit]');
    if (cancel) {
      var idc = cancel.getAttribute('data-cancel-edit');
      var boxc = document.getElementById('edit-box-' + idc);
      if (boxc) boxc.classList.add('d-none');
    }
  });

  document.addEventListener('submit', function (e) {
    var form = e.target.closest('form.comment-edit-form');
    if (!form) return;
    e.preventDefault();

    var id  = form.getAttribute('data-id');
    var url = form.getAttribute('action');
    var fd  = new FormData(form);

    var saveBtn = form.querySelector('button[type="submit"]');
    if (saveBtn) { saveBtn.disabled = true; saveBtn.textContent = 'Saving…'; }

    fetchHtml(url, { method: 'POST', body: fd })
      .then(function (html) {
        var item = document.getElementById('comment-' + id);
        if (item) item.outerHTML = html; 
      })
      .catch(function (err) {
        alert('Gagal menyimpan komentar: ' + err.message);
        console.error('Edit save error:', err);
      });
  });
})();
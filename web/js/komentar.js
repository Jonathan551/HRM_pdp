(function () {
  function escapeHtml(s) {
    return (s || '')
      .replace(/&/g,'&amp;')
      .replace(/</g,'&lt;')
      .replace(/>/g,'&gt;');
  }
  function plainPreview(s) {
    return escapeHtml(s || '').replace(/\n/g, '<br>');
  }

  function initCommentComposer(root) {
    var writeBtn = root.querySelector('[data-role="tab-write"]');
    var prevBtn  = root.querySelector('[data-role="tab-preview"]');
    var writeBox = root.querySelector('.composer-write');
    var prevBox  = root.querySelector('.composer-preview');
    var ta       = root.querySelector('#komentar-deskripsi');
    var prevArea = root.querySelector('#komentar-preview');
    var cancel   = root.querySelector('#btn-cancel-comment');

    if (writeBtn && prevBtn && writeBox && prevBox && ta && prevArea) {
      writeBtn.addEventListener('click', function(){
        writeBtn.classList.add('active'); prevBtn.classList.remove('active');
        writeBox.classList.remove('d-none'); prevBox.classList.add('d-none');
      });
      prevBtn.addEventListener('click', function(){
        var v = ta.value.trim();
        prevArea.innerHTML = v ? plainPreview(v) : '<div class="text-muted small">Nothing to preview.</div>';
        prevBtn.classList.add('active'); writeBtn.classList.remove('active');
        writeBox.classList.add('d-none'); prevBox.classList.remove('d-none');
      });
    }
    if (cancel) {
      cancel.addEventListener('click', function(){
        var box = document.getElementById('comment-form');
        if (box) box.classList.add('d-none');
      });
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    var btn = document.getElementById('btn-create-comment');
    var box = document.getElementById('comment-form');
    if (!btn || !box) return;

    var loaded = false;

    btn.addEventListener('click', function () {
      box.classList.toggle('d-none');

      if (!loaded && !box.classList.contains('d-none')) {
        var url = btn.getAttribute('data-url');
        if (!url) {
          box.innerHTML = '<div class="text-danger">URL form tidak ditemukan.</div>';
          return;
        }
        box.innerHTML = '<div class="text-muted">Memuat formulir…</div>';

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
          .then(function (r) { return r.text(); })
          .then(function (html) {
            box.innerHTML = html;
            loaded = true;
            initCommentComposer(box);
          })
          .catch(function () {
            box.innerHTML = '<div class="text-danger">Gagal memuat form.</div>';
          });
      }
    });
  });
})();

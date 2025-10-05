(function ($, window) {
  'use strict';

  const CFG = (window.UserFormConfig || {});
  const URL_LEVEL  = CFG.urlLevel  || null;
  const URL_LATEST = CFG.urlLatest || null;

  function initDatepicker() {
    const opts = {
      dateFormat: 'd-m-Y',
      allowInput: true,
      clickOpens: true,
      disableMobile: true
    };
    if (window.flatpickr && window.flatpickr.l10ns && window.flatpickr.l10ns.id) {
      opts.locale = window.flatpickr.l10ns.id;
    }
    window.flatpickr && window.flatpickr('.datepicker', opts);
  }

  function bindLevelJabatan() {
    $('#user-id_jabatan').on('change', function () {
      const id = $(this).val();
      if (!id) { $('#user-level_jabatan').val(''); return; }
      if (!URL_LEVEL) { console.error('URL_LEVEL belum diset'); return; }

      $.getJSON(URL_LEVEL, { id: id })
        .done(function (res) {
          $('#user-level_jabatan').val(res && res.level_jabatan ? res.level_jabatan : '');
        })
        .fail(function () {
          console.error('Gagal memuat level jabatan');
        });
    });

    if ($('#user-id_jabatan').val()) {
      $('#user-id_jabatan').trigger('change');
    }
  }

  function fetchLatestPenilaian() {
    if (!URL_LATEST) return; 
    $.getJSON(URL_LATEST)
      .done(function (res) {
        if (res && res.penilaian_terakhir) {
          $('#user-penilaian_terakhir').val(res.penilaian_terakhir);
        }
      })
      .fail(function () {
        console.warn('Gagal memuat penilaian terakhir');
      });
  }

  $(function () {
    initDatepicker();
    bindLevelJabatan();
    fetchLatestPenilaian();
  });

})(jQuery, window);
(function () {

    function submitPost(url, csrfParam, csrfToken) {
        var form = document.createElement('form');
        form.method = 'post';
        form.action = url;

        if (csrfParam && csrfToken) {
            var hiddenCsrf = document.createElement('input');
            hiddenCsrf.type = 'hidden';
            hiddenCsrf.name = csrfParam;
            hiddenCsrf.value = csrfToken;
            form.appendChild(hiddenCsrf);
        }

        document.body.appendChild(form);
        form.submit();
    }

    function attachDeleteHandler() {
        document.addEventListener('click', function (e) {
            var btn = e.target && e.target.closest
                ? e.target.closest('.btn-delete-confirm')
                : null;

            if (!btn) return;

            e.preventDefault();

            var url            = btn.getAttribute('data-url');
            var title          = btn.getAttribute('data-title') || 'Yakin hapus data ini?';
            var text           = btn.getAttribute('data-text')  || 'Data akan dihapus permanen.';
            var confirmText    = btn.getAttribute('data-confirm') || 'Ya, hapus';
            var cancelText     = btn.getAttribute('data-cancel')  || 'Batal';
            var confirmColor   = btn.getAttribute('data-confirm-color') || '#e91e63';
            var cancelColor    = btn.getAttribute('data-cancel-color')  || '#9e9e9e';
            var csrfParam      = btn.getAttribute('data-csrf-param');
            var csrfToken      = btn.getAttribute('data-csrf-token');
            var method         = (btn.getAttribute('data-method') || 'post').toLowerCase();

            if (typeof Swal === 'undefined') {
                console.error('SweetAlert2 (Swal) tidak ditemukan. Pastikan asset SweetAlertAsset sudah diregister.');
                if (confirm(title + '\n' + text)) {
                    if (method === 'post') {
                        submitPost(url, csrfParam, csrfToken);
                    } else {
                        window.location.href = url;
                    }
                }
                return;
            }

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: cancelColor,
                confirmButtonText: confirmText,
                cancelButtonText: cancelText
            }).then(function (result) {
                if (result.isConfirmed) {
                    if (method === 'post') {
                        submitPost(url, csrfParam, csrfToken);
                    } else {
                        window.location.href = url;
                    }
                }
            });
        });
    }

    attachDeleteHandler();

})();

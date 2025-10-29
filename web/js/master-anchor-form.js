(function ($) {
    function snapshotCurrentValues() {
        var data = {};

        $('#levels-wrapper .card').each(function () {
            var level = $(this).data('level'); 
            var desc = $(this).find('textarea[name^="anchors"]').val();
            var val  = $(this).find('input[name$="[nilai_anchor]"]').val();

            data[level] = {
                deskripsi: desc,
                nilai_anchor: val
            };
        });

        return data;
    }

    function restoreValues(snapshot) {
        if (!snapshot) return;

        $('#levels-wrapper .card').each(function () {
            var level = $(this).data('level');
            if (snapshot[level]) {
                if (snapshot[level].deskripsi !== undefined) {
                    $(this).find('textarea[name^="anchors"]').val(snapshot[level].deskripsi);
                }
                if (snapshot[level].nilai_anchor !== undefined) {
                    $(this).find('input[name$="[nilai_anchor]"]').val(snapshot[level].nilai_anchor);
                }
            }
        });
    }

    function renderLevelFields(skala, { preserveExisting } = { preserveExisting: true }) {
        var $wrapper = $('#levels-wrapper');

        if (!skala || skala < 1) {
            skala = 1;
        }

        var snapshot = preserveExisting ? snapshotCurrentValues() : {};

        $wrapper.empty();

        for (var i = 1; i <= skala; i++) {
            var rowHtml = `
                <div class="card" data-level="` + i + `" style="border:1px solid #ddd; border-radius:4px; margin-bottom:15px;">
                    <div class="card-header" style="padding:8px 12px; background:#fafafa; border-bottom:1px solid #eee; font-weight:500;">
                        Level ` + i + `
                    </div>
                    <div class="card-body" style="padding:12px;">
                        <div class="form-group">
                            <label>Deskripsi Level ` + i + `</label>
                            <textarea
                                class="form-control"
                                name="anchors[` + i + `][deskripsi]"
                                rows="2"
                                placeholder="Jelaskan perilaku / indikator untuk level ` + i + `"
                                required
                            ></textarea>
                        </div>

                        <div class="form-group">
                            <label>Nilai Anchor Level ` + i + ` (maks ` + skala + `)</label>
                            <input
                                type="number"
                                step="0.001"
                                max="` + skala + `"
                                class="form-control nilai-anchor-input"
                                name="anchors[` + i + `][nilai_anchor]"
                                placeholder="Masukkan angka (contoh 2.500)"
                                required
                            />
                        </div>

                        <input type="hidden"
                            name="anchors[` + i + `][level_anchor]"
                            value="` + i + `"
                        />
                    </div>
                </div>
            `;

            $wrapper.append(rowHtml);
        }

        restoreValues(snapshot);
    }

    function bindDepartemenListener() {
        $('#departemen-select').on('change', function () {
            var depId = $(this).val();
            var $krit = $('#kriteria-select');
            var url   = $(this).data('kriteria-url');

            $krit.prop('disabled', true);
            $krit.html('<option value="">Loading...</option>');

            if (!depId) {
                $krit.html('<option value="">Pilih Kriteria...</option>');
                return;
            }

            $.getJSON(url, { id_departement: depId })
                .done(function (data) {
                    var opts = '<option value="">Pilih Kriteria...</option>';
                    data.forEach(function (row) {
                        opts += '<option value="' + row.id_kriteria + '">' + row.nama_kriteria + '</option>';
                    });
                    $krit.html(opts);
                    $krit.prop('disabled', false);
                })
                .fail(function (xhr, status, err) {
                    console.error('[getJSON FAIL]', status, err, xhr.responseText);
                    $krit.html('<option value="">(Gagal load kriteria)</option>');
                });
        });
    }

    function bindSkalaListener() {
        $('#input-skala').on('input', function () {
            var skalaBaru = parseInt($(this).val(), 10);
            if (isNaN(skalaBaru) || skalaBaru < 1) {
                skalaBaru = 1;
            }

            var skalaSekarang = $('#levels-wrapper .card').length;

            if (skalaBaru === skalaSekarang) {
                return;
            }

            renderLevelFields(skalaBaru, { preserveExisting: true });
        });

    }
    function bindSubmitValidation() {
        $('#anchor-batch-form').on('submit', function (e) {
            var skalaInput = parseInt($('#input-skala').val(), 10);
            if (isNaN(skalaInput) || skalaInput < 1) {
                skalaInput = 1;
            }

            var valid = true;
            var decimalRegex = /^\d+(\.\d{1,3})?$/;

            $('#levels-wrapper .card').each(function () {
                var $card = $(this);
                var $valInput = $card.find('.nilai-anchor-input');
                var rawVal = $valInput.val();

                $valInput.removeClass('is-invalid');

                if (!rawVal || rawVal === '') {
                    valid = false;
                    $valInput.addClass('is-invalid');
                    return; 
                }

                if (!decimalRegex.test(rawVal)) {
                    valid = false;
                    $valInput.addClass('is-invalid');
                    return;
                }

                var numericVal = parseFloat(rawVal);
                if (!isNaN(numericVal) && numericVal > skalaInput) {
                    valid = false;
                    $valInput.addClass('is-invalid');
                }
            });

            if (!valid) {
                alert('Periksa Nilai Anchor:\n- Harus angka dengan maksimal 3 angka di belakang koma\n- Tidak boleh lebih besar dari skala (' + skalaInput + ').');
                e.preventDefault();
                return false;
            }
        });
    }

    $(function () {
        bindDepartemenListener();
        bindSkalaListener();
        bindSubmitValidation(); 

        var defaultSkala = 1;
        if (window.MASTER_ANCHOR_INIT && window.MASTER_ANCHOR_INIT.defaultSkala) {
            defaultSkala = parseInt(window.MASTER_ANCHOR_INIT.defaultSkala, 10) || 1;
        } else {
            var currentVal = parseInt($('#input-skala').val(), 10);
            if (!isNaN(currentVal) && currentVal > 0) {
                defaultSkala = currentVal;
            }
        }
        renderLevelFields(defaultSkala, { preserveExisting: false });
    });

})(jQuery);

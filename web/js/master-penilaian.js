function initDatepicker() {
  flatpickr(".datepicker", {
    dateFormat: "d-m-Y",
    allowInput: true,
    locale: "id",
  });
}

var currentDepartemen = null;

function initDepartemenFromExisting() {
  var idUser = $("#masterpenilaian-id_users").val();
  if (!idUser) return;

  $.getJSON(urlGetDepartemen, { id_user: idUser }).done(function (data) {
    currentDepartemen = data.id_departement || null;
  });
}

var rowIndex = 0;

var urlListAnchor, urlGetDepartemen, urlListKriteria;

$(document).ready(function () {
  initDatepicker();
  initDepartemenFromExisting();

  $(document).on("change", "#masterpenilaian-id_users", function () {
    var idUser = $(this).val();
    if (idUser) {
      $.getJSON(urlGetDepartemen, { id_user: idUser }, function (data) {
        currentDepartemen = data.id_departement;
        $.getJSON(
          urlListKriteria,
          { id_departement: currentDepartemen },
          function (kriteria) {
            var options = '<option value="">Pilih Kriteria</option>';
            $.each(kriteria, function (key, value) {
              options += '<option value="' + key + '">' + value + "</option>";
            });
            $(".id-kriteria").each(function () {
              var $sel = $(this);
              var hasSelected = $sel.val();
              var hasOptions = $sel.find("option").length > 1;

              if (!hasSelected && !hasOptions) {
                $sel.html(options);
              }
            });

            $(".id-anchor").each(function () {
              var $sel = $(this);
              var hasSelected = $sel.val();
              var hasOptions = $sel.find("option").length > 1;
              if (!hasSelected && !hasOptions) {
                $sel.html('<option value="">Pilih Anchor</option>');
              }
            });
          }
        );
      });
    } else {
      currentDepartemen = null;
      $(".id-kriteria").html('<option value="">Pilih Kriteria</option>');
      $(".id-anchor").html('<option value="">Pilih Anchor</option>');
    }
  });

  $(document).on("change", ".id-kriteria", function () {
    var idKriteria = $(this).val();
    var anchorEl = $(this).closest("tr").find(".id-anchor");
    anchorEl.html('<option value="">Loading...</option>');

    if (idKriteria) {
      $.getJSON(urlListAnchor, { id_kriteria: idKriteria }, function (data) {
        anchorEl.empty().append('<option value="">Pilih Anchor</option>');
        $.each(data, function (key, value) {
          anchorEl.append(
            $("<option></option>").attr("value", key).text(value)
          );
        });
      }).fail(function () {
        anchorEl.html('<option value="">Gagal load anchor</option>');
      });
    } else {
      anchorEl.html('<option value="">Pilih Anchor</option>');
    }
  });

  $("#add-row").on("click", function () {
    if (!currentDepartemen) {
      alert("Pilih karyawan dulu!");
      return;
    }
    $.getJSON(
      urlListKriteria,
      { id_departement: currentDepartemen },
      function (data) {
        var options = '<option value="">Pilih Kriteria</option>';
        $.each(data, function (key, value) {
          options += '<option value="' + key + '">' + value + "</option>";
        });

        var newRow = `<tr>
          <td>
              <input type="hidden" name="DetailPenilaian[${rowIndex}][id_detailpenilaian]" value="">
              <select class="form-control id-kriteria" name="DetailPenilaian[${rowIndex}][id_kriteria]">
                  ${options}
              </select>
          </td>
          <td>
              <select class="form-control id-anchor" name="DetailPenilaian[${rowIndex}][id_anchor]">
                  <option value="">Pilih Anchor</option>
              </select>
          </td>
          <td class="text-center">
              <button type="button" class="btn btn-danger btn-sm remove-row">-</button>
          </td>
      </tr>`;
        $("#detail-table tbody").append(newRow);
        rowIndex++;
      }
    );
  });

  $(document).on("click", ".remove-row", function () {
    $(this).closest("tr").remove();
  });
});

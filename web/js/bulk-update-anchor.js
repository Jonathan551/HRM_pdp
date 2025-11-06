(function () {
  var anchors   = window.ANCHORS_DATA || {};
  var skalaNow  = parseInt(window.SKALA_NOW || 1, 10);
  var inputSkala = document.getElementById('input-skala');
  var wrap       = document.getElementById('anchors-wrapper');
  var warn       = document.getElementById('warning-truncate');

  function collectCurrentData() {
    var collected = {};
    var textareas = wrap.querySelectorAll('textarea[name^="BatchAnchorInput[anchors]"]');
    var inputs = wrap.querySelectorAll('input[type="number"][name^="BatchAnchorInput[anchors]"]');
    
    textareas.forEach(function(textarea) {
      var match = textarea.name.match(/\[(\d+)\]\[deskripsi\]/);
      if (match) {
        var level = parseInt(match[1], 10);
        if (!collected[level]) collected[level] = {};
        collected[level].deskripsi = textarea.value;
      }
    });
    
    inputs.forEach(function(input) {
      var match = input.name.match(/\[(\d+)\]\[nilai_anchor\]/);
      if (match) {
        var level = parseInt(match[1], 10);
        if (!collected[level]) collected[level] = {};
        collected[level].nilai_anchor = input.value;
      }
    });
    
    return collected;
  }

  function render(skala) {
    if (!skala || skala < 1) skala = 1;
    var currentData = collectCurrentData();
    
    for (var key in currentData) {
      if (currentData.hasOwnProperty(key)) {
        if (!anchors[key]) anchors[key] = {};
        if (currentData[key].deskripsi !== undefined) {
          anchors[key].deskripsi = currentData[key].deskripsi;
        }
        if (currentData[key].nilai_anchor !== undefined) {
          anchors[key].nilai_anchor = currentData[key].nilai_anchor;
        }
      }
    }

    var html = '<div class="table-responsive"><table class="table table-bordered" style="background:#fff"><thead><tr>' +
      '<th style="width:90px">Level</th><th>Deskripsi</th><th style="width:200px">Nilai</th>' +
      '</tr></thead><tbody>';

    for (var i = 1; i <= skala; i++) {
      var row  = anchors[i] || {};
      var desc = row['deskripsi'] || '';
      var val  = (row['nilai_anchor'] !== undefined && row['nilai_anchor'] !== '') ? row['nilai_anchor'] : i;

      html += '<tr>' +
        '<td class="text-center align-middle"><strong>' + i + '</strong></td>' +
        '<td><textarea class="form-control" name="BatchAnchorInput[anchors][' + i + '][deskripsi]" rows="2" ' +
        'placeholder="Uraian perilaku untuk level ' + i + '">' + escapeHtml(desc) + '</textarea></td>' +
        '<td><input type="number" step="0.001" min="0" max="' + skala + '" class="form-control" ' +
        'name="BatchAnchorInput[anchors][' + i + '][nilai_anchor]" value="' + escapeHtml(val) + '">' +
        '<small class="text-muted">Maksimal = skala saat ini.</small>' +
        '<input type="hidden" name="BatchAnchorInput[anchors][' + i + '][level_anchor]" value="' + i + '"></td>' +
        '</tr>';
    }

    html += '</tbody></table></div>';

    if (wrap) wrap.innerHTML = html;
    if (warn) warn.style.display = (skala < skalaNow) ? 'block' : 'none';
  }
  function escapeHtml(text) {
    var map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
  }

  if (inputSkala) {
    inputSkala.addEventListener('change', function () {
      var v = parseInt(this.value || '1', 10);
      if (v < 1) v = 1;
      render(v);
    });
    render(parseInt(inputSkala.value || skalaNow || 1, 10));
  }
})();
(function () {
  function $(sel) { return document.querySelector(sel); }
  function escapeHtml(s){ if(s==null) return ''; return String(s).replace(/[&<>"']/g, m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m])); }

  function setLoading(state){
    var wrap = $('#notifItems');
    if(!wrap) return;
    if(state){
      wrap.innerHTML = '<a class="dropdown-item disabled" href="javascript:void(0)">Memuat...</a>';
    }
  }

  function renderNotifItems(items, listUrl){
    var wrap = $('#notifItems');
    if(!wrap) return;

    if (!items || items.length === 0) {
      wrap.innerHTML = '<a class="dropdown-item disabled" href="javascript:void(0)">Tidak ada notifikasi</a>';
      return;
    }

    var html = items.map(function(n){
      var isUnread = parseInt(n.dibaca,10) === 0;
      var t = n.created_at ? new Date(String(n.created_at).replace(' ','T')).toLocaleString() : '';
      return (
        '<a class="dropdown-item" href="'+escapeHtml(listUrl||"#")+'">' +
          '<div class="d-flex flex-column">' +
            '<strong'+(isUnread?' style="font-weight:700"':'')+'>'+escapeHtml(n.judul||'')+'</strong>' +
            (n.deskripsi ? '<small class="text-muted">'+escapeHtml(n.deskripsi)+'</small>' : '') +
            (t ? '<small class="text-muted">'+t+'</small>' : '') +
          '</div>' +
        '</a>'
      );
    }).join('');
    wrap.innerHTML = html;
  }

  function updateBadge(unread){
    var badge = $('#notifBadge');
    if(!badge) return;
    unread = parseInt(unread||0,10);
    if(unread > 0){
      badge.style.display = 'inline-block';
      badge.textContent = unread;
    } else {
      badge.style.display = 'none';
      badge.textContent = '0';
    }
  }

  function refreshNotif(){
    var trigger = $('#notifDropdown');
    if(!trigger) return;

    var pollUrl = trigger.getAttribute('data-poll-url');
    var listUrl = trigger.getAttribute('data-list-url');
    if(!pollUrl) return;

    setLoading(true);

    fetch(pollUrl, { credentials:'same-origin' })
      .then(function(r){ if(!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
      .then(function(d){
        updateBadge(d && d.unread);
        renderNotifItems((d && d.items) ? d.items : [], listUrl);
      })
      .catch(function(err){
        console.error('[notif] poll error:', err);
        var wrap = $('#notifItems');
        if(wrap){
          wrap.innerHTML = '<a class="dropdown-item disabled text-danger" href="javascript:void(0)">Gagal memuat</a>';
        }
        updateBadge(0);
      });
  }

  window.NotifInit = function(){
    refreshNotif();
    if(!window.__notifInterval){
      window.__notifInterval = setInterval(refreshNotif, 30000);
    }
  };
  if(document.readyState === 'loading') document.addEventListener('DOMContentLoaded', window.NotifInit);
  else window.NotifInit();
  document.addEventListener('pjax:end', window.NotifInit);
})();

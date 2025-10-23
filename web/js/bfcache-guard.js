(function(){
  window.__bfGuardLoaded = true; // indikator

  function hasLogoutMarker(){
    return document.cookie.split(';').some(function (raw) {
      var c = raw.trim();
      return /^was_logged_out[^=]*=/.test(c);
    });
  }
  function redirectToLogin(){
    var url = (typeof window.appLoginUrl !== 'undefined' && window.appLoginUrl)
      ? window.appLoginUrl
      : '/index.php?r=site/login';
    location.replace(url);
  }
  var acted = false;
  function actOnce(fn){ if (acted) return; acted = true; fn(); }

  function shouldActNow(){
    if (!hasLogoutMarker()) return false;
    var nav = performance.getEntriesByType && performance.getEntriesByType('navigation')[0];
    return !!(nav && nav.type === 'back_forward');
  }

  window.addEventListener('pageshow', function(e){
    if (e.persisted && hasLogoutMarker()) return actOnce(redirectToLogin);
    if (shouldActNow()) return actOnce(redirectToLogin);
  });

  document.addEventListener('visibilitychange', function(){
    if (document.visibilityState === 'visible' && shouldActNow()) actOnce(redirectToLogin);
  });

  window.addEventListener('unload', function(){});
})();

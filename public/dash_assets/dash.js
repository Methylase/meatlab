(function () {
  var burger = document.getElementById('burger');
  var side = document.getElementById('side');
  var scrim = document.getElementById('scrim');

  function setMenu(open) {
    document.body.classList.toggle('menu-open', open);
    if (burger) burger.setAttribute('aria-expanded', open ? 'true' : 'false');
  }
  if (burger && side) {
    burger.addEventListener('click', function () {
      closeProfile();
      setMenu(!document.body.classList.contains('menu-open'));
    });
    if (scrim) scrim.addEventListener('click', function () { setMenu(false); });
    // Close the drawer when a link inside it is tapped
    side.addEventListener('click', function (e) { if (e.target.closest('a')) setMenu(false); });
    // If the screen grows to desktop size, make sure the drawer state is reset
    window.addEventListener('resize', function () { if (window.innerWidth >= 900) setMenu(false); });
  }

  // Profile dropdown (top right)
  var profile = document.getElementById('profile');
  var pbtn = document.getElementById('profileBtn');
  var pmenu = document.getElementById('profileMenu');
  function closeProfile() {
    if (!pbtn || !pmenu) return;
    pmenu.hidden = true;
    pbtn.setAttribute('aria-expanded', 'false');
  }
  if (pbtn && pmenu) {
    pbtn.addEventListener('click', function (e) {
      e.stopPropagation();
      var open = pmenu.hidden;
      if (open) setMenu(false);
      pmenu.hidden = !open;
      pbtn.setAttribute('aria-expanded', open ? 'true' : 'false');
      if (open) { var first = pmenu.querySelector('a'); if (first) first.focus(); }
    });
    document.addEventListener('click', function (e) { if (!profile.contains(e.target)) closeProfile(); });
    pmenu.addEventListener('keydown', function (e) { if (e.key === 'Escape') { closeProfile(); pbtn.focus(); } });
  }
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') { closeProfile(); setMenu(false); }
  });

  // Live preview for image pickers: <input type="file" data-preview="#id">
  document.querySelectorAll('input[type=file][data-preview]').forEach(function (input) {
    input.addEventListener('change', function () {
      var target = document.querySelector(input.getAttribute('data-preview'));
      var file = input.files && input.files[0];
      if (target && file) { target.style.backgroundImage = 'url(' + URL.createObjectURL(file) + ')'; target.textContent = ''; }
    });
  });

  // Confirm before destructive actions: <form data-confirm="Delete this product?">
  document.querySelectorAll('form[data-confirm]').forEach(function (f) {
    f.addEventListener('submit', function (e) { if (!window.confirm(f.getAttribute('data-confirm'))) e.preventDefault(); });
  });
})();

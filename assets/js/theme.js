document.addEventListener('DOMContentLoaded', function () {
  var header = document.getElementById('site-header');
  if (!header) {
    return;
  }

  var toggle = header.querySelector('.site-header__toggle');
  var mobileMenu = header.querySelector('#site-mobile-menu');

  if (!toggle || !mobileMenu) {
    return;
  }

  var closeMenu = function () {
    header.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('menu-open');
  };

  toggle.addEventListener('click', function () {
    var willOpen = !header.classList.contains('is-open');
    header.classList.toggle('is-open', willOpen);
    toggle.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
    document.body.classList.toggle('menu-open', willOpen);
  });

  mobileMenu.querySelectorAll('a').forEach(function (link) {
    link.addEventListener('click', closeMenu);
  });

  window.addEventListener('resize', function () {
    if (window.innerWidth > 960) {
      closeMenu();
    }
  });
});

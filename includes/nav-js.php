<script>
  const hamburger  = document.getElementById('hamburgerBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  const overlay    = document.getElementById('mobileOverlay');
  hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('open');
    mobileMenu.classList.toggle('open');
    overlay.classList.toggle('open');
  });
  overlay.addEventListener('click', () => {
    hamburger.classList.remove('open');
    mobileMenu.classList.remove('open');
    overlay.classList.remove('open');
  });
</script>

<script>
/* ── Profile sidebar nav highlight ── */
(function () {
    const links = document.querySelectorAll('.profile-nav-link');
    if (!links.length) return;

    const sectionIds = [...links].map(l => l.getAttribute('href').replace('#', ''));

    function getActiveId() {
    const scrollY     = window.scrollY + 200;
    const pageBottom  = window.scrollY + window.innerHeight;
    const docHeight   = document.documentElement.scrollHeight;
    let activeId      = sectionIds[0];

    // Nëse jemi në fund të faqes, aktivo të fundit
    if (pageBottom >= docHeight - 20) {
        return sectionIds[sectionIds.length - 1];
    }

    sectionIds.forEach(id => {
        const el = document.getElementById(id);
        if (el && el.offsetTop - 250 <= scrollY) activeId = id;
    });

    return activeId;
}

    function updateActive() {
        const activeId = getActiveId();
        links.forEach(l => {
            l.classList.toggle('active', l.getAttribute('href') === '#' + activeId);
        });
    }

    // Scroll listener
    window.addEventListener('scroll', updateActive, { passive: true });

    // Klik direkt
    links.forEach(l => {
        l.addEventListener('click', () => {
            setTimeout(updateActive, 500);
        });
    });

    // Initialize
    updateActive();
})();
</script>

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
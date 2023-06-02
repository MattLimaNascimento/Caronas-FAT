document.addEventListener('infosCarregadas', () => {
  const tap = document.querySelector('.profile_user');
  tap.addEventListener('click', () => {
    const toggleMenu = document.querySelector('.menu_user');
    toggleMenu.classList.toggle('active');
  });
});
document.querySelectorAll('[data-confirm]').forEach((el) => {
  el.addEventListener('click', (event) => {
    if (!confirm(el.dataset.confirm || 'Yakin ingin melanjutkan?')) event.preventDefault();
  });
});

// Reveal on scroll
const io = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('in');
      io.unobserve(e.target);
    }
  });
}, { threshold: 0.12 });
document.querySelectorAll('.reveal').forEach(el => io.observe(el));

// Close mobile nav on link click
document.querySelectorAll('.main-nav a').forEach(a => {
  a.addEventListener('click', () => document.body.classList.remove('nav-open'));
});

// Terminal typing effect on hero kicker
const kicker = document.querySelector('.hero-kicker[data-type]');
if (kicker) {
  const text = kicker.dataset.type;
  kicker.textContent = '';
  let i = 0;
  const tick = () => {
    if (i <= text.length) {
      kicker.textContent = text.slice(0, i++);
      setTimeout(tick, 55);
    }
  };
  setTimeout(tick, 300);
}

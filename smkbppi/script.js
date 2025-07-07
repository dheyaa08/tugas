// script.js

// Scroll reveal animation (opsional jika ingin smooth effect)
document.addEventListener("DOMContentLoaded", () => {
  const elements = document.querySelectorAll(".card, .contact-box, .gallery img");
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("show");
      }
    });
  }, {
    threshold: 0.1,
  });

  elements.forEach(el => {
    el.classList.add("hidden");
    observer.observe(el);
  });
});

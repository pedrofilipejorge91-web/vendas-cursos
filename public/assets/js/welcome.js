(function () {
  "use strict";

  const header = document.querySelector("[data-site-header]");
  const navToggle = document.querySelector("[data-nav-toggle]");
  const mainNav = document.querySelector("[data-main-nav]");
  const navActions = document.querySelector(".nav-actions");

  const syncHeader = () => {
    if (!header) {
      return;
    }

    header.classList.toggle("is-scrolled", window.scrollY > 20);
  };

  syncHeader();
  window.addEventListener("scroll", syncHeader, { passive: true });

  if (navToggle && mainNav) {
    navToggle.addEventListener("click", () => {
      mainNav.classList.toggle("is-open");
      navActions?.classList.toggle("is-open");
      const icon = navToggle.querySelector("i");
      if (icon) {
        icon.className = mainNav.classList.contains("is-open") ? "bi bi-x-lg" : "bi bi-list";
      }
    });

    mainNav.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => {
        mainNav.classList.remove("is-open");
        navActions?.classList.remove("is-open");
        const icon = navToggle.querySelector("i");
        if (icon) {
          icon.className = "bi bi-list";
        }
      });
    });
  }

  const sections = [...document.querySelectorAll("main section[id]")];
  const navLinks = [...document.querySelectorAll(".main-nav a[href*='#']")];

  const markActiveSection = () => {
    const current = sections.findLast((section) => section.offsetTop <= window.scrollY + 160);
    navLinks.forEach((link) => {
      const hash = new URL(link.href, window.location.href).hash;
      link.classList.toggle("active", Boolean(current && hash === `#${current.id}`));
    });
  };

  if (sections.length) {
    markActiveSection();
    window.addEventListener("scroll", markActiveSection, { passive: true });
  }

  const gallery = document.querySelector("[data-gallery]");
  const stageImage = gallery?.querySelector(".gallery-stage img");
  const stageTitle = gallery?.querySelector(".gallery-stage h3");
  const stageCopy = gallery?.querySelector(".gallery-stage p");
  const thumbs = [...document.querySelectorAll("[data-gallery-thumb]")];
  const slides = thumbs.map((thumb, index) => ({
    image: thumb.querySelector("img")?.getAttribute("src") || "",
    title: thumb.dataset.title || (index % 2 === 0 ? "Paruana Comercial" : "Cursos e eventos"),
    copy: thumb.dataset.copy || (index % 2 === 0 ? "Formação, cursos e eventos profissionais" : "Momentos de aprendizagem e contacto com o mercado"),
  }));
  let activeSlide = 0;

  const renderGallery = (index) => {
    if (!stageImage || !stageTitle || !stageCopy || !slides.length) {
      return;
    }

    activeSlide = (index + slides.length) % slides.length;
    const slide = slides[activeSlide];
    stageImage.src = slide.image;
    stageTitle.textContent = slide.title;
    stageCopy.textContent = slide.copy;
    thumbs.forEach((thumb, thumbIndex) => thumb.classList.toggle("active", thumbIndex === activeSlide));
  };

  document.querySelector("[data-gallery-prev]")?.addEventListener("click", () => renderGallery(activeSlide - 1));
  document.querySelector("[data-gallery-next]")?.addEventListener("click", () => renderGallery(activeSlide + 1));
  thumbs.forEach((thumb, index) => thumb.addEventListener("click", () => renderGallery(index)));

  document.querySelector(".newsletter-card")?.addEventListener("submit", (event) => {
    event.preventDefault();
    const button = event.currentTarget.querySelector("button");
    if (!button) {
      return;
    }
    button.innerHTML = "Obrigado <i class=\"bi bi-check2\"></i>";
    setTimeout(() => {
      button.innerHTML = "Assinar <i class=\"bi bi-arrow-right\"></i>";
    }, 2200);
  });
})();

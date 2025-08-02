document.addEventListener("DOMContentLoaded", function () {
  const menuToggle = document.getElementById("menu-toggle");
  const navPrimary = document.getElementById("primary");

  if (!menuToggle || !navPrimary) return;

  function isMobileMenu() {
    return window.getComputedStyle(menuToggle).display !== "none";
  }

  // Ensure nav is hidden initially on mobile
  if (isMobileMenu()) {
    navPrimary.hidden = true;
    menuToggle.setAttribute("aria-expanded", "false");
  }

  menuToggle.addEventListener("click", function () {
    if (!isMobileMenu()) return;
    const isOpen = navPrimary.hidden;
    navPrimary.hidden = !isOpen;
    menuToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
  });

  window.addEventListener("resize", function () {
    if (!isMobileMenu()) {
      navPrimary.hidden = false;
      menuToggle.setAttribute("aria-expanded", "false");
    } else {
      navPrimary.hidden = true;
    }
  });
});

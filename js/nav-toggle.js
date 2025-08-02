document.addEventListener("DOMContentLoaded", function () {
  const menuToggle = document.getElementById("menu-toggle");
  const navPrimary = document.getElementById("primary");

  if (!menuToggle || !navPrimary) return;

  // Only run toggle logic if the menu button is visible (mobile)
  function isMobileMenu() {
    return window.getComputedStyle(menuToggle).display !== "none";
  }

  // Remove inline styles, rely on CSS for display
  navPrimary.classList.remove("open");

  menuToggle.hidden = false;
  menuToggle.setAttribute("aria-expanded", "false");

  menuToggle.addEventListener("click", function () {
    if (!isMobileMenu()) return;
    const isOpen = navPrimary.classList.toggle("open");
    menuToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
  });

  // Close nav if resizing from mobile to desktop
  window.addEventListener("resize", function () {
    if (!isMobileMenu()) {
      navPrimary.classList.remove("open");
      menuToggle.setAttribute("aria-expanded", "false");
    }
  });
});

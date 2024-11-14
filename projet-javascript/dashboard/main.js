document.addEventListener("DOMContentLoaded", () => {
  const menuIcon = document.getElementById("menu-icon");
  const navbarMenu = document.getElementById("navbar-menu");

  // Basculer le menu mobile
  menuIcon?.addEventListener("click", () => {
      navbarMenu.classList.toggle("active");
  });

  // Fermer le menu en cliquant à l'extérieur - Nav Bar Mobie
  document.addEventListener("click", (event) => {
      if (!menuIcon?.contains(event.target) && !navbarMenu.contains(event.target)) {
          navbarMenu.classList.remove("active");
      }
  });

  // Pour exporter et importer des listes
  document.getElementById("export-btn").addEventListener("click", function () {
      window.location.href = "export_lists.php";
  });

  document.getElementById("import-btn").addEventListener("click", function () {
      window.location.href = "import_lists.php";
  });

  // Fonctionnalité de basculement de l'arrière-plan
  const checkboxInput = document.getElementById("checkboxInput");
  let iframe;

  function setParallaxBackground() {
      iframe = document.createElement("iframe");
      iframe.src = "on.html";
      iframe.classList.add("background-iframe");
      document.body.appendChild(iframe);

      // Ajouter un effet de parallaxe - 3d
      document.addEventListener("mousemove", handleParallax);
  }

  function setChillBackground() {
      if (iframe) {
          iframe.remove();
          document.removeEventListener("mousemove", handleParallax);
      }
      iframe = document.createElement("iframe");
      iframe.src = "off.html";
      iframe.classList.add("background-iframe");
      document.body.appendChild(iframe);
  }

  // Effet de parallaxe - déplacer les étoiles en fonction de la position de la souris
  function handleParallax(e) {
      const x = (e.clientX / window.innerWidth - 0.5) * 2;
      const y = (e.clientY / window.innerHeight - 0.5) * 2;

      if (iframe && iframe.contentWindow) {
          const stars = iframe.contentWindow.document.querySelector(".stars");
          const stars1 = iframe.contentWindow.document.querySelector(".stars1");
          const stars2 = iframe.contentWindow.document.querySelector(".stars2");

          if (stars) stars.style.transform = `translate(${x * 40}px, ${y * 40}px)`;
          if (stars1) stars1.style.transform = `translate(${x * 20}px, ${y * 20}px)`;
          if (stars2) stars2.style.transform = `translate(${x * 10}px, ${y * 10}px)`;
      }
  }

  // Définir l'arrière-plan initial en mode "Chill"
  setChillBackground();

  // Basculer l'arrière-plan lorsque le bouton est cliqué
  checkboxInput.addEventListener("change", () => {
      document.querySelectorAll(".background-iframe").forEach(el => el.remove());
      if (checkboxInput.checked) {
          setParallaxBackground();
      } else {
          setChillBackground();
      }
  });
});

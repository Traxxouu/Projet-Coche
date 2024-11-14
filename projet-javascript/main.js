document.addEventListener("DOMContentLoaded", function () {
  // Animation du texte de bienvenue
  const landingContent = document.querySelector(".landing-content");
  landingContent.style.opacity = 0;
  landingContent.style.transform = "translateY(-20px)";
  setTimeout(() => {
    landingContent.style.transition =
      "opacity 1s ease-out, transform 1s ease-out";
    landingContent.style.opacity = 1;
    landingContent.style.transform = "translateY(0)";
  }, 300);

  // Message de bienvenue dynamique
  const greetingMessage = document.createElement("p");
  greetingMessage.className = "dynamic-greeting";
  const currentHour = new Date().getHours();
  let greetingText;
  if (currentHour < 12) {
    greetingText = "Bonne journée, n'oubliez pas de remplir vos tâches.";
  } else if (currentHour < 18) {
    greetingText = "Bon après-midi ! Continuez à cocher vos tâches !";
  } else {
    greetingText = "Je vous souhaite une excellente soirée.";
  }
  greetingMessage.textContent = greetingText;
  landingContent.insertBefore(
    greetingMessage,
    landingContent.querySelector(".buttons")
  );

  // Animation des boutons au survol
  const buttons = document.querySelectorAll(".btn");
  buttons.forEach((button) => {
    button.addEventListener("mouseover", () => {
      button.style.transform = "scale(1.1)";
      button.style.boxShadow = "0 4px 15px rgba(0, 0, 0, 0.2)";
    });
    button.addEventListener("mouseout", () => {
      button.style.transform = "scale(1)";
      button.style.boxShadow = "none";
    });
  });

  // Message incitatif en bas de la page - style bandeau de pub
  const encouragementMessage = document.createElement("div");
  encouragementMessage.className = "encouragement-message";
  encouragementMessage.textContent =
    "Rejoignez-nous dès maintenant et transformez la gestion de vos tâches en une expérience simple et efficace !";
  encouragementMessage.style.position = "fixed";
  encouragementMessage.style.bottom = "20px";
  encouragementMessage.style.width = "100%";
  encouragementMessage.style.textAlign = "center";
  encouragementMessage.style.fontSize = "18px";
  encouragementMessage.style.backgroundColor = "rgba(0, 0, 0, 0.5)";
  encouragementMessage.style.padding = "10px";
  encouragementMessage.style.color = "#fff";
  document.body.appendChild(encouragementMessage);

  // Ajout d'un joli suivi de souris (rond autour de la souris pour la version PC - petit probleme sur mobile)
  const cursorFollower = document.createElement("div");
  cursorFollower.className = "cursor-follower";
  document.body.appendChild(cursorFollower);

  document.addEventListener("mousemove", (e) => {
    cursorFollower.style.top = e.clientY + "px";
    cursorFollower.style.left = e.clientX + "px";
  });
});

// Styles pour le suivi de souris
const style = document.createElement("style");
style.innerHTML = `
    .cursor-follower {
        position: fixed;
        top: 0;
        left: 0;
        width: 20px;
        height: 20px;
        background-color: rgba(255, 215, 0, 0.5);
        border-radius: 50%;
        pointer-events: none;
        transform: translate(-50%, -50%);
        transition: transform 0.1s ease-out;
    }
`;
document.head.appendChild(style);

async function chargerDonnees() {
  try {
    const reponse = await fetch("horaires.json");

    if (!reponse.ok) {
      throw new Error("Erreur : " + reponse.status);
    }

    const donnees = await reponse.json();

    const liste = document.getElementById("horaires");

    donnees.forEach((element) => {
      const li = document.createElement("li");

      if (element.ouverture === null) {
        li.textContent = element.jour + " : Fermé";
      } else {
        li.textContent =
          element.jour + " : " + element.ouverture + " - " + element.fermeture;
      }

      liste.appendChild(li);
    });
    // ===== Badge Ouvert / Fermé =====
    const badge = document.getElementById("badge");
    const maintenant = new Date();

    // getDay() : 1 = lundi
    const index = (maintenant.getDay() + 6) % 7;
    const aujourdhui = donnees[index];

    // heure actuelle
    const heures = String(maintenant.getHours()).padStart(2, "0");
    const minutes = String(maintenant.getMinutes()).padStart(2, "0");
    const heureActuelle = heures + ":" + minutes;

    const estOuvert =
      aujourdhui.ouverture !== null &&
      heureActuelle >= aujourdhui.ouverture &&
      heureActuelle < aujourdhui.fermeture;

    if (estOuvert) {
      badge.textContent = "Ouvert maintenant";
      badge.classList.add("ouvert");
    } else {
      badge.textContent = "Fermé";
      badge.classList.add("ferme");
    }
  } catch (erreur) {
    console.error(erreur);

    const liste = document.getElementById("horaires");
    liste.textContent =
      "Les horaires ne peuvent pas être affichés pour le moment. Contactez-nous pour plus d'informations.";
  }
}

chargerDonnees();

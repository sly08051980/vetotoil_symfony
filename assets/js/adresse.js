console.log("script adresse chargé");

document.addEventListener("DOMContentLoaded", function () {
  //#################################################################################################
  //se lance sur page inscription
  //#################################################################################################

  let adresse = document.getElementById("patient_adresse_patient");
  let nbrLetter = 0;
  let ulListe = document.getElementById("list");

  adresse.addEventListener("input", async (eventInput) => {
    nbrLetter = eventInput.target.value.length;
    const rue = eventInput.target.value.split(" ").join("+");

    await findAdress(rue, nbrLetter);
  });

  //appel de l api et affiche le resultat

  async function findAdress(rue, nbrLetter) {
    if (nbrLetter > 4) {
      console.log("ok");
      const response = await fetch(
        "https://api-adresse.data.gouv.fr/search/?q=" + rue + "&limit=50"
      );
      const adressResult = await response.json();
      console.log(adressResult);
      ulListe.style.display = "block";
      findData(adressResult);
    }
  }

  function findData(adressResult) {
    const labels = [];
    const city = [];
    const cityCode = [];
    const cityName = [];

    for (const recherche of adressResult.features) {
      labels.push(recherche.properties.label);
      city.push(recherche.properties.city);
      cityCode.push(recherche.properties.postcode);
      cityName.push(recherche.properties.name);
    }

    listeLi(labels, city, cityCode, cityName);
  }

  function listeLi(labels, city, cityCode, cityName) {
    ulListe.innerHTML = "";
    for (let i = 0; i < labels.length; i++) {
      const liListe = document.createElement("li");
      liListe.innerHTML = labels[i];
      liListe.setAttribute("id", i);
      liListe.classList.add("form-list-item");

      ulListe.appendChild(liListe);

      clickLi(liListe, city, cityCode, cityName);
    }
  }

  function clickLi(liListe, city, cityCode, cityName) {
    liListe.addEventListener("click", (event) => {
      const li = event.target;
      document.getElementById("patient_code_postal_patient").value =
        cityCode[li.id];
      document.getElementById("patient_ville_patient").value = city[li.id];
      adresse.value = cityName[li.id];

      ulListe.innerHTML = "";
      ulListe.style.display = "none";
    });
  }
  //#################################################################################################
  //verifie le click en dehors du champs de la liste
  //#################################################################################################

  document.addEventListener("click", function (event) {
    if (!ulListe.contains(event.target) && event.target !== adresse) {
      ulListe.style.display = "none";
    }
  });
});

console.log("siret chargé");

document.addEventListener("DOMContentLoaded", function () {
    const url = "https://api.insee.fr/entreprises/sirene/V3/siret/";
    const accessToken = "75c7b058-1c1c-3cdd-8ce2-dba0a65362df";
    const button = document.getElementById("validerSiret");
    button.addEventListener("click", async function () {   
        const siret = document.getElementById("societe_siret");
        // console.log("test : ", siret.value);
        
   siret
    .addEventListener("keydown", function (event) {
      let keyCode = event.which || event.keyCode;

      //#########################################
      //autoriser que les chiffres et certaines touche comme supprimer
      //#########################################
      if (
        !(
          (keyCode >= 48 && keyCode <= 57) ||
          (keyCode >= 96 && keyCode <= 105) ||
          [8, 9, 13, 27, 46].includes(keyCode)
        )
      ) {
        event.preventDefault();
      }
      //#########################################
      //limite a 14chiffres et supprime les lettres
      //#########################################
      let inputValue = event.target.value.replace(/\D/g, "");
      if (inputValue.length >= 14 && ![8, 46].includes(keyCode)) {
        event.preventDefault();
      }
    });
        //#########################################
        //recherche ds l api siret de l insee les info
        //#########################################
        const headers = {
          "Content-Type": "application/x-www-form-urlencoded",
          Accept: "application/json",
          Authorization: `Bearer ${accessToken}`,
        };
        try {
          const response = await fetch(url + siret.value, {
            method: "GET",
            headers: headers,
          });
          if (!response.ok) {
            throw new Error("Réponse réseau non OK");
          }
          const resultat = await response.json();
        //   console.log("resultat : ", resultat);
          searchInfo(resultat);
        } catch (error) {
          console.error("Erreur :", error);
        }
      });
      function searchInfo(resultat) {
        const numeroVoie =
          resultat.etablissement.adresseEtablissement.numeroVoieEtablissement;
        const typeVoie =
          resultat.etablissement.adresseEtablissement.typeVoieEtablissement;
        const libelleVoie =
          resultat.etablissement.adresseEtablissement.libelleVoieEtablissement;
        const complementAdresse =
          resultat.etablissement.adresseEtablissement
            .complementAdresseEtablissement;
        const libelleCommune =
          resultat.etablissement.adresseEtablissement.libelleCommuneEtablissement;
        const codePostal =
          resultat.etablissement.adresseEtablissement.codePostalEtablissement;
        const denominationUsuelleEtablissement =
          resultat.etablissement.periodesEtablissement[0]
            .denominationUsuelleEtablissement;
        // console.log('denominationUsuelleEtablissement : ',denominationUsuelleEtablissement);
        const nomUniteLegale = resultat.etablissement.uniteLegale.nomUniteLegale;
        document.getElementById("societe_adresse_societe").value =
          numeroVoie + " " + typeVoie + " " + libelleVoie;
        document.getElementById("societe_complement_adresse_societe").value = complementAdresse;
        document.getElementById("societe_code_postal_societe").value = codePostal;
        document.getElementById("societe_ville_societe").value = libelleCommune;
        document.getElementById("societe_nom_societe").value =
          denominationUsuelleEtablissement;
  
      }

});
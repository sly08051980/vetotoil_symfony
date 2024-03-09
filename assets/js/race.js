console.log("script chargé");

document.addEventListener("DOMContentLoaded", function () {
  let boutonAnimal = document.getElementById("boutonAnimal");

  boutonAnimal.addEventListener("click", function () {
    document.getElementById("afficherAnimal").classList.toggle("invisible"); // affiche la classe invisible
  });

  const typeSelect = document.getElementById("animal_type");
  const raceSelect = document.getElementById("animal_race");

  if (typeSelect) {
    typeSelect.addEventListener("change", function () {
      
      fetch(`/get-races?typeId=${this.value}`)
        .then((response) => response.json())
        .then((data) => {
          raceSelect.innerHTML = ""; 
          data.forEach((race) => {
            const option = new Option(race.name, race.id);
            raceSelect.add(option); 
          });
        })
        .catch((error) => console.error("Error:", error));
    });
  } else {
    console.error("Element #animal_type not found");
  }
});

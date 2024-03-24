// console.log("home script chargé");


document.addEventListener("DOMContentLoaded", function () {

    document.getElementById('registerEmployerLink').addEventListener('click', function(event) {
        event.preventDefault(); 
        document.getElementById('registerEmployerForm').submit(); 
    });
});

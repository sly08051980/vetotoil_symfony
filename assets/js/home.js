// console.log("home script chargé");


document.addEventListener("DOMContentLoaded", function () {
    let test=document.getElementById('test');
    if(test){

    document.getElementById('registerEmployerLink').addEventListener('click', function(event) {
        event.preventDefault(); 
        document.getElementById('registerEmployerForm').submit(); 
    });
}

});

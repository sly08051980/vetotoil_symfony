console.log("home script chargé");

let registerEmployer=document.getElementById('registerEmployer');

if(registerEmployer){
    document.getElementById('registerEmployerLink').addEventListener('click', function(event) {
        event.preventDefault(); 
        document.getElementById('registerEmployerForm').submit(); 
    });
}

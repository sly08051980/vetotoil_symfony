console.log("password chargé");
document.addEventListener("DOMContentLoaded", function () {
    let verification =document.getElementById("registration_form_submit");
    
    verification.addEventListener("click", function(event){
        let mdp = document.getElementById("registration_form_plainPassword_first").value;
        let remdp = document.getElementById("registration_form_plainPassword_second").value;
        let errors = []; 
        
        if (mdp !== remdp) {
            errors.push('Les mots de passe saisis sont différents.');
        }
    
        if (mdp.length < 8) {
            errors.push('Le mot de passe doit contenir au moins 8 caractères.');
        }
    
        if (!mdp.match(/[A-Z]/)) {
            errors.push('Le mot de passe doit contenir au moins une majuscule.');
        }
    
        let expression = /\d/;
        if (!expression.test(mdp)) {
            errors.push('Le mot de passe doit contenir au moins un chiffre.');
        }
    
        let special = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
        if (!special.test(mdp)){
            errors.push('Le mot de passe doit contenir au moins un caractère spécial.');
        }
        
      
        if (errors.length > 0) {
            alert(errors.join('\n'));
            event.preventDefault();
            return;
        }
    });
      
    let mdp = document.getElementById("registration_form_plainPassword_first");
    let chiffre = document.getElementById("chiffre");
    let majuscule=document.getElementById("majuscule");
    let caractere=document.getElementById("caractere");
    
    mdp.addEventListener('input', function() {
        let specialDocument =document.getElementById("special");
        let mdpValue = mdp.value;
        if (mdpValue.length > 0) {
            ulVisible.classList.remove("d-none");
    
            let expression = /\d/;
            let regex1 = /[a-z]/;
            let special = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/; 
            if (expression.test(mdpValue) && regex1.test(mdpValue)) {
                chiffre.classList.remove("d-none");
            } else {
                chiffre.classList.add("d-none");
            }
    
            if(mdpValue.match(/[A-Z]/, 'g')){
                majuscule.classList.remove("d-none");
            }else{
                majuscule.classList.add("d-none");
            }
            if(mdpValue.length>7){
    caractere.classList.remove("d-none");
            }else{
                caractere.classList.add("d-none");
            }
            if (special.test(mdpValue)){
                specialDocument.classList.remove("d-none");
            }else{
                specialDocument.classList.add("d-none");
            }
    
        } else {
            ulVisible.classList.add("d-none");
        }
    });
    
    
    
    
    });
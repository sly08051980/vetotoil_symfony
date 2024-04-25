document.addEventListener('DOMContentLoaded', function() {

    let valider = document.getElementById('valider1');
 
    let modalActions = document.querySelectorAll('.modalAction');
  
    if (valider && modalActions) {
     
      modalActions.forEach(function(modalAction) {
        modalAction.addEventListener('click', function(event) {
  
          let count = 5;
  
          function updateTimer() {
            if (count > 0) {
               
         
              valider.value =  "Valider("+count+")";
              count--; 
           
            } else if(count===0) {
              valider.value = "Valider";
              valider.disabled = false;
    
            }
          }
        
          updateTimer();
          let intervalId = setInterval(updateTimer, 1000);
        });
      });
    }
  });
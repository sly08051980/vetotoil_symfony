document.addEventListener('DOMContentLoaded', function() {
    const switchInput = document.getElementById('flexSwitchCheckDefault');

    if (!switchInput) {
        console.log('Le bouton bascule n\'a pas été trouvé.');
        return;
    }

   
    const saved = localStorage.getItem('isGrayscale');
    const isGrayscale = saved === 'true';
    document.body.style.filter = isGrayscale ? 'grayscale(100%)' : 'none';
    switchInput.checked = isGrayscale;

  
    switchInput.addEventListener('change', function() {
        if (this.checked) {
            document.body.style.filter = 'grayscale(100%)';
            localStorage.setItem('isGrayscale', 'true');
        } else {
            document.body.style.filter = 'none';
            localStorage.setItem('isGrayscale', 'false');
        }
    });
});
// Navigation entre les pages
document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
            
// Retirer la class active de tous les élements
    document.querySelectorAll('.nav-item').forEach(i => {
        i.classList.remove('active');
    });
    
// Ajouter la classe active de l'élément cliquer
    this.classList.add('active');
            
// Masquer tout le contenue
    document.querySelectorAll('.main-content').forEach(content => {
        content.classList.remove('active');
    });
            
// Afficher le contenue séléctionné
    const pageId = this.getAttribute('data-page');
        document.getElementById(pageId).classList.add('active');
    });
});
    
// Animation
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const bars = document.querySelectorAll('.bar');
            bars.forEach(bar => {
                const height = bar.style.height;
                bar.style.height = '0px';
                setTimeout(() => {
                    bar.style.height = height;
                }, 300);
            });
    }, 500);
});
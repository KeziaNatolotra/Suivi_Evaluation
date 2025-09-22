// Récupération des éléments
const openModalBtn = document.getElementById('openModal');
const closeModalBtn = document.getElementById('closeModal');
const modalOverlay = document.getElementById('modalOverlay');

// Ouvrir la modale
openModalBtn.addEventListener('click', () => {
    modalOverlay.classList.add('active');
    document.body.style.overflow = 'hidden'; // Empêche le défilement
});

// Fermer la modale
closeModalBtn.addEventListener('click', () => {
    modalOverlay.classList.remove('active');
    document.body.style.overflow = 'auto'; // Rétablit le défilement
});

// Fermer la modale en cliquant à l'extérieur
modalOverlay.addEventListener('click', (e) => {
    if (e.target === modalOverlay) {
        modalOverlay.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
});

// Fermer la modale avec la touche Échap
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modalOverlay.classList.contains('active')) {
        modalOverlay.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
});
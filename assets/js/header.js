document.addEventListener('DOMContentLoaded', function() {
    // Active ou désactive le menu du notification
    const notifications = document.querySelector('.notifications');
    notifications.addEventListener('click', function(e) {
        e.stopPropagation();
        this.classList.toggle('active');
    });    
    // Active ou desactive le menu de l'utilisateur
    const userProfile = document.querySelector('.user-profile');
    userProfile.addEventListener('click', function(e) {
        e.stopPropagation();
        this.classList.toggle('active');
    });
    
    // Fermer lorsqu'on clique à l'extérieure
    document.addEventListener('click', function() {
        notifications.classList.remove('active');
        userProfile.classList.remove('active');
    });
    
    // Empêcher la fermeture lorsqu'on clique à l'interieur
        document.querySelectorAll('.notification-dropdown, .user-dropdown').forEach(function(dropdown) {
        dropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    
    // Tout marquer comme lu
    const clearButton = document.querySelector('.notification-clear');
    clearButton.addEventListener('click', function() {
        document.querySelectorAll('.notification-item.unread').forEach(function(item) {
            item.classList.remove('unread');
        });
        document.querySelector('.notification-badge').style.display = 'none';
    });
});
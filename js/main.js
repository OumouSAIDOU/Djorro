// Ouverture / Fermeture du menu Hamburger sur mobile
document.addEventListener('DOMContentLoaded', () => {
    const burgerBtn = document.getElementById('burgerBtn');
    const navLinks = document.getElementById('navLinks');

    if (burgerBtn && navLinks) {
        burgerBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const heroSection = document.getElementById("homeHero");
    
    if (heroSection) {
        // Liste de vos 3 ou 4 photos de fond pour l'accueil
        const images = [
            "img/plats/Alloco.jpg",
            "img/plats/pastel.jpg",
            "img/plats/grill.jpg",
            "img/plats/reserver.jpg" // Vous pouvez mettre vos propres images ici
        ];

        let currentIndex = 0;

      // Fonction pour appliquer le fond
        function updateBackground() {
            heroSection.style.background = `linear-gradient(to bottom, rgba(43, 29, 20, 0.35), rgba(43, 29, 20, 0.55)), url('${images[currentIndex]}') center/cover no-repeat fixed`;
        }

        // 1. Appliquer l'image IMMÉDIATEMENT à l'ouverture de la page (plus de blanc)
        updateBackground();

        // 2. Passer à l'image suivante pour le prochain changement
        currentIndex = (currentIndex + 1) % images.length;

        // 3. Lancer le défilement automatique toutes les 3 secondes (3000 ms)
        setInterval(function() {
            updateBackground();
            currentIndex = (currentIndex + 1) % images.length;
        }, 3000);
    }
});
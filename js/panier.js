document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialiser l'affichage du compteur et de l'état des boutons sur la page
    updateCartCount();
    updateMenuButtons();

    // 2. Écouter les clics sur les boutons d'ajout initiaux
    document.addEventListener('click', (e) => {
        // Clic sur "Ajouter au panier"
        if (e.target.classList.contains('btn-ajouter')) {
            e.preventDefault();
            const id = e.target.getAttribute('data-id');
            const nom = e.target.getAttribute('data-nom');
            const prix = parseFloat(e.target.getAttribute('data-prix'));

            let panier = JSON.parse(localStorage.getItem('djorro_panier')) || [];
            let item = panier.find(i => i.id === id);

            if (item) {
                item.quantite += 1;
            } else {
                panier.push({ id, nom, prix, quantite: 1 });
            }

            localStorage.setItem('djorro_panier', JSON.stringify(panier));
            updateCartCount();
            updateMenuButtons();
        }

        // Clic sur le bouton "+" dans la carte du menu
        if (e.target.classList.contains('btn-menu-plus')) {
            e.preventDefault();
            const id = e.target.getAttribute('data-id');
            modifierQuantiteMenu(id, 1);
        }

        // Clic sur le bouton "-" dans la carte du menu
        if (e.target.classList.contains('btn-menu-moins')) {
            e.preventDefault();
            const id = e.target.getAttribute('data-id');
            modifierQuantiteMenu(id, -1);
        }
    });
});

// Modifier la quantité directement depuis la page menu
function modifierQuantiteMenu(id, delta) {
    let panier = JSON.parse(localStorage.getItem('djorro_panier')) || [];
    let index = panier.findIndex(i => i.id === id);

    if (index !== -1) {
        panier[index].quantite += delta;
        if (panier[index].quantite <= 0) {
            panier.splice(index, 1); // Supprime si ça tombe à 0
        }
    }

    localStorage.setItem('djorro_panier', JSON.stringify(panier));
    updateCartCount();
    updateMenuButtons();
}

// Mettre à jour le compteur global dans la navbar (Masqué si vide)
function updateCartCount() {
    let panier = JSON.parse(localStorage.getItem('djorro_panier')) || [];
    let totalItems = panier.reduce((sum, item) => sum + item.quantite, 0);
    const badge = document.getElementById('cartCount');
    
    if (badge) {
        if (totalItems > 0) {
            badge.textContent = totalItems;
            badge.style.display = "inline-block"; // Affiche le badge quand il y a au moins 1 article
        } else {
            badge.style.display = "none"; // Masque complètement le badge quand le panier est vide (0)
        }
    }
}

// Parcourir tous les plats du menu et transformer le bouton si le plat est dans le panier
function updateMenuButtons() {
    let panier = JSON.parse(localStorage.getItem('djorro_panier')) || [];
    const conteneursBoutons = document.querySelectorAll('.plat-action-container');

    conteneursBoutons.forEach(container => {
        const id = container.getAttribute('data-id');
        const nom = container.getAttribute('data-nom');
        const prix = container.getAttribute('data-prix');

        let itemInCart = panier.find(i => i.id === id);

        if (itemInCart) {
            // Le plat est déjà dans le panier -> Afficher le sélecteur de quantité (- [Quantité] +)
            container.innerHTML = `
                <div style="display: flex; align-items: center; justify-content: space-between; background: #fdfaf5; border: 1px solid var(--terracotta); border-radius: 8px; padding: 6px 12px;">
                    <button type="button" class="btn-menu-moins" data-id="${id}" style="background: none; border: none; font-size: 1.2rem; font-weight: bold; color: var(--terracotta); cursor: pointer;">-</button>
                    <span style="font-weight: 600; font-size: 0.95rem;">${itemInCart.quantite} dans le panier</span>
                    <button type="button" class="btn-menu-plus" data-id="${id}" style="background: none; border: none; font-size: 1.2rem; font-weight: bold; color: var(--terracotta); cursor: pointer;">+</button>
                </div>
            `;
        } else {
            // Le plat n'y est pas -> Afficher le bouton classique "Commander"
            container.innerHTML = `
                <button type="button" class="btn-primary btn-ajouter" 
                        data-id="${id}"
                        data-nom="${nom.replace(/"/g, '&quot;')}"
                        data-prix="${prix}"
                        style="width: 100%; padding: 10px; font-size: 0.9rem; cursor: pointer;">
                     Commander
                </button>
            `;
        }
    });
}
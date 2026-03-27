import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    // On définit la cible (le menu qui doit apparaître/disparaître)
    static targets = ['menu']

    // Méthode pour afficher/cacher
    toggle() {
        this.menuTarget.classList.toggle('hidden');
    }

    // Méthode pour fermer si on clique en dehors
    close(event) {
        // Si l'élément cliqué n'est pas à l'intérieur de notre contrôleur
        if (!this.element.contains(event.target)) {
            this.menuTarget.classList.add('hidden');
        }
    }
}

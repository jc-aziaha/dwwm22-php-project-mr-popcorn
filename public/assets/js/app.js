// Si la page est chargée,
document.addEventListener("DOMContentLoaded", () => {

    // Récupérons, le textarea et le small
    const textarea = document.getElementById('comment');
    const counter  = document.getElementById('comment-counter');

    const maxLength = 1000;

    // Si y a un changement au niveau de la valeur du textarea,
    textarea.addEventListener('input', () => {

        // alors, récupérons la longueur de la valeur du textarea.
        const length = textarea.value.length;

        // Préparons le text dynamique du compteur
        counter.textContent = `${length} / ${maxLength} caractères`;

        // Si la longueur de la valeur du textarea est supérieur à la lobgueur totale prévue,
        if (length > maxLength) {

            // Alorsn, rajoutons ces classes
            textarea.classList.add('is-invalid');
            counter.classList.add('text-danger');
        } else {
            // Sinon, retirons ces classes.
            textarea.classList.remove('is-invalid');
            counter.classList.remove('text-danger');
        }
    });
});
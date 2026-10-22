import "./styles/app.css";

/* Menu burger : ouverture / fermeture */
(function () {
    const toggle = document.getElementById("menu-toggle");
    const menu = document.getElementById("mobile-menu");

    // Si le header n'est pas sur la page, on ne fait rien
    if (!toggle || !menu) {
        return;
    }

    const iconOpen = toggle.querySelector('[data-menu-icon="open"]');
    const iconClose = toggle.querySelector('[data-menu-icon="close"]');

    function setOpen(open) {
        toggle.setAttribute("aria-expanded", String(open));
        toggle.setAttribute(
            "aria-label",
            open ? "Fermer le menu" : "Ouvrir le menu",
        );

        menu.classList.toggle("hidden", !open);
        iconOpen.classList.toggle("hidden", open);
        iconClose.classList.toggle("hidden", !open);
    }

    // Clic sur le burger
    toggle.addEventListener("click", function () {
        setOpen(toggle.getAttribute("aria-expanded") !== "true");
    });

    // Fermeture avec la touche Échap
    document.addEventListener("keydown", function (event) {
        if (
            event.key === "Escape" &&
            toggle.getAttribute("aria-expanded") === "true"
        ) {
            setOpen(false);
            toggle.focus();
        }
    });

    // Fermeture au clic sur un lien du menu
    menu.querySelectorAll("a").forEach(function (link) {
        link.addEventListener("click", function () {
            setOpen(false);
        });
    });

    // Fermeture si on repasse en desktop
    window
        .matchMedia("(min-width: 768px)")
        .addEventListener("change", function (event) {
            if (event.matches) {
                setOpen(false);
            }
        });
})();

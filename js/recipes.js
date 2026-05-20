document.addEventListener("DOMContentLoaded", () => {
    const filter = document.querySelector(".filter");
    
    // 1. Hvata dugme za otvaranje koje je već na dnu HTML-a
    const openBtn = document.querySelector(".filter-toggle");

    // 2. Hvata dugme za zatvaranje koje je već u HTML-u (klasa je close-filter)
    const closeBtn = document.querySelector(".close-filter");

    // Otvaranje filtera na klik (mobilni mod)
    if (openBtn && filter) {
        openBtn.addEventListener("click", (e) => {
            e.preventDefault(); // Sprečava bilo kakvo ludo ponašanje
            filter.classList.add("open");
            document.body.style.overflow = "hidden";
        });
    }

    // Zatvaranje filtera na klik
    if (closeBtn && filter) {
        closeBtn.addEventListener("click", (e) => {
            e.preventDefault(); // Jako bitno da ne submituje formu kad klikneš X!
            filter.classList.remove("open");
            document.body.style.overflow = "";
        });
    }
});
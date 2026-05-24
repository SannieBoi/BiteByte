document.addEventListener("DOMContentLoaded", () => {
    const filter = document.querySelector(".filter");
    
    const openBtn = document.querySelector(".filter-toggle");

    const closeBtn = document.querySelector(".close-filter");

    if (openBtn && filter) {
        openBtn.addEventListener("click", (e) => {
            e.preventDefault();
            filter.classList.add("open");
            document.body.style.overflow = "hidden";
        });
    }

    if (closeBtn && filter) {
        closeBtn.addEventListener("click", (e) => {
            e.preventDefault();
            filter.classList.remove("open");
            document.body.style.overflow = "";
        });
    }
});
document.addEventListener("DOMContentLoaded",()=>{

    const filter=document.querySelector(".filter");

    const openBtn=document.createElement("button");
    openBtn.className="filter-toggle";
    openBtn.innerHTML='<i class="fa-solid fa-sliders"></i>';

    document.body.appendChild(openBtn);

    const closeBtn=document.createElement("button");
    closeBtn.className="filter-close";
    closeBtn.innerHTML='<i class="fa-solid fa-xmark"></i>';

    filter.appendChild(closeBtn);

    openBtn.addEventListener("click",()=>{
        filter.classList.add("open");
        document.body.style.overflow="hidden";
    });

    closeBtn.addEventListener("click",()=>{
        filter.classList.remove("open");
        document.body.style.overflow="";
    });

    
document.addEventListener("DOMContentLoaded",()=>{

    const filter=document.querySelector(".filter");

    const openBtn=document.createElement("button");
    openBtn.className="filter-toggle";
    openBtn.innerHTML='<i class="fa-solid fa-sliders"></i>';

    document.body.appendChild(openBtn);

    const closeBtn=document.createElement("button");
    closeBtn.className="filter-close";
    closeBtn.innerHTML='<i class="fa-solid fa-xmark"></i>';

    filter.appendChild(closeBtn);

    openBtn.addEventListener("click",()=>{
        filter.classList.add("open");
        document.body.style.overflow="hidden";
    });

    closeBtn.addEventListener("click",()=>{
        filter.classList.remove("open");
        document.body.style.overflow="";
    });

    
});
document.addEventListener("DOMContentLoaded",()=>{

    const menuBtn=document.querySelector(".phone-only");
    const mobileNav=document.querySelector(".mobile-nav");

    if(menuBtn && mobileNav){

        menuBtn.addEventListener("click",()=>{

            mobileNav.classList.toggle("open");

        });

        mobileNav.querySelectorAll("a").forEach(link=>{

            link.addEventListener("click",()=>{

                mobileNav.classList.remove("open");

            });

        });

    }

document.addEventListener("DOMContentLoaded",()=>{

    const menuBtn=document.querySelector(".phone-only");
    const mobileNav=document.querySelector(".mobile-nav");

    if(menuBtn && mobileNav){

        menuBtn.addEventListener("click",()=>{

            mobileNav.classList.toggle("open");

        });

        mobileNav.querySelectorAll("a").forEach(link=>{

            link.addEventListener("click",()=>{

                mobileNav.classList.remove("open");

            });

        });

    }

})});
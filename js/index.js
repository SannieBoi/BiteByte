const image = document.getElementById("week-food");
const heroImages = ["../img/img-foods/food1.jpg","../img/img-foods/food2.jpg","../img/img-foods/food3.jpg","../img/img-foods/food4.jpg","../img/img-foods/food5.jpg","../img/img-foods/food6.jpg","../img/img-foods/food7.jpg","../img/img-foods/food8.jpg","../img/img-foods/food9.jpg","../img/img-foods/food10.jpg"];
image.src = heroImages[Math.floor(Math.random() * heroImages.length)];

const showcase = document.querySelector(".showcase");
const track = document.querySelector(".showcase-track");

const SRCS = [
    "../img/img-foods/food10.jpg",
    "../img/img-foods/food8.jpg",
    "../img/img-foods/food1.jpg",
    "../img/img-foods/food9.jpg",
    "../img/img-foods/food2.jpg",
    "../img/img-foods/food4.jpg",
    "../img/img-foods/food6.jpg",
    "../img/img-foods/food3.jpg",
];
const N = SRCS.length;

const ACTIVE_W   = 500;
const GAP_REM    = 6;

function inactiveW() { return window.innerWidth * 0.16; }
function gapPx()     { return GAP_REM * parseFloat(getComputedStyle(document.documentElement).fontSize); }

const BUFFER = 3;
const SLOTS  = BUFFER * 2 + 1;

let cursor = 2;

function buildDOM() {
    track.innerHTML = "";
    for (let i = 0; i < SLOTS; i++) {
        const srcIdx = ((cursor - BUFFER + i) % N + N) % N;
        const div = document.createElement("div");
        div.className = "rotate" + (i === BUFFER ? " active" : "");
        div.innerHTML = `<img src="${SRCS[srcIdx]}" alt="">`;
        track.appendChild(div);
    }
}

function computeTranslate(slotIdx) {
    const showcaseRect = showcase.getBoundingClientRect();
    const visualCenter = (window.innerWidth / 2) - showcaseRect.left;

    const gap = gapPx();
    const iw  = inactiveW();

    let leftEdge = 0;
    for (let i = 0; i < slotIdx; i++) {
        leftEdge += iw + gap;
    }
    const center = leftEdge + ACTIVE_W / 2;
    return visualCenter - center;
}

let isAnimating = false;
let autoTimer   = null;
const AUTO_DELAY = 3500;

function isMobile() { return window.innerWidth <= 768; }

function advance(dir) {
    if (isAnimating) return;
    isAnimating = true;

    cursor = ((cursor + dir) % N + N) % N;

    if (isMobile()) {
        const inClass  = dir === 1 ? "slide-from-right" : "slide-from-left";
        const outClass = dir === 1 ? "slide-out-left"   : "slide-out-right";

        const currentActive = track.querySelector(".rotate.active");
        const outgoing = currentActive ? currentActive.cloneNode(true) : null;

        buildDOM();
        track.style.transition = "none";
        track.style.transform  = "translateX(0)";
        track.getBoundingClientRect();
        track.style.transition = "";

        const incoming = track.querySelector(".rotate.active");
        if (incoming) {
            incoming.classList.add(inClass);
            incoming.addEventListener("animationend", () => incoming.classList.remove(inClass), { once: true });
        }

        if (outgoing) {
            outgoing.classList.remove("active");
            outgoing.classList.add(outClass);
            track.appendChild(outgoing);
            outgoing.addEventListener("animationend", () => outgoing.remove(), { once: true });
        }

        isAnimating = false;
        resetAuto();
        return;
    }

    cursor = ((cursor - dir) % N + N) % N;

    const gap = gapPx();
    const iw  = inactiveW();

    const extraSrcIdx = dir === 1
        ? ((cursor + BUFFER + 1) % N + N) % N
        : ((cursor - BUFFER - 1) % N + N) % N;

    const div = document.createElement("div");
    div.className = "rotate";
    div.innerHTML = `<img src="${SRCS[extraSrcIdx]}" alt="">`;

    if (dir === 1) {
        track.appendChild(div);
    } else {
        track.prepend(div);
        track.style.transition = "none";
        track.style.transform  = `translateX(${computeTranslate(BUFFER + 1)}px)`;
        track.getBoundingClientRect();
        track.style.transition = "";
    }

    const items = Array.from(track.querySelectorAll(".rotate"));
    const currentActive = items.findIndex(el => el.classList.contains("active"));
    const nextActive = currentActive + dir;

    items.forEach((el, i) => el.classList.toggle("active", i === nextActive));
    track.style.transform = `translateX(${computeTranslate(nextActive)}px)`;

    cursor = ((cursor + dir) % N + N) % N;

    track.addEventListener("transitionend", function onEnd(e) {
        if (e.propertyName !== "transform") return;
        track.removeEventListener("transitionend", onEnd);

        buildDOM();
        track.style.transition = "none";
        track.style.transform  = `translateX(${computeTranslate(BUFFER)}px)`;
        track.getBoundingClientRect();
        track.style.transition = "";

        isAnimating = false;
    });

    resetAuto();
}

function resetAuto() {
    clearTimeout(autoTimer);
    autoTimer = setTimeout(() => advance(1), AUTO_DELAY);
}

document.getElementById("showcasePrev").addEventListener("click", () => advance(-1));
document.getElementById("showcaseNext").addEventListener("click", () => advance(1));

track.addEventListener("click", (e) => {
    const item = e.target.closest(".rotate");
    if (!item || item.classList.contains("active") || isAnimating) return;
    const items = Array.from(track.querySelectorAll(".rotate"));
    const idx = items.indexOf(item);
    if (idx < BUFFER) advance(-1);
    else advance(1);
});

window.addEventListener("load", () => {
    buildDOM();
    track.style.transition = "none";
    if (isMobile()) {
        track.style.transform = "translateX(0)";
    } else {
        track.style.transform = `translateX(${computeTranslate(BUFFER)}px)`;
    }
    track.getBoundingClientRect();
    track.style.transition = "";
    resetAuto();
});

window.addEventListener("resize", () => {
    track.style.transition = "none";
    if (isMobile()) {
        track.style.transform = "translateX(0)";
    } else {
        track.style.transform = `translateX(${computeTranslate(BUFFER)}px)`;
    }
    track.getBoundingClientRect();
    track.style.transition = "";
});
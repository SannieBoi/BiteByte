// --- Hero image ---
const image = document.getElementById("week-food");
const heroImages = ["../img/img-foods/food1.jpg","../img/img-foods/food2.jpg","../img/img-foods/food3.jpg","../img/img-foods/food4.jpg","../img/img-foods/food5.jpg","../img/img-foods/food6.jpg","../img/img-foods/food7.jpg","../img/img-foods/food8.jpg","../img/img-foods/food9.jpg","../img/img-foods/food10.jpg"];
image.src = heroImages[Math.floor(Math.random() * heroImages.length)];

// --- Showcase infinite carousel ---
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

// CSS constants — must match index.css exactly
const ACTIVE_W   = 500;   // px
const GAP_REM    = 6;

function inactiveW() { return window.innerWidth * 0.16; }
function gapPx()     { return GAP_REM * parseFloat(getComputedStyle(document.documentElement).fontSize); }

// DOM window: BUFFER items left, active in middle, BUFFER items right
const BUFFER = 3;
const SLOTS  = BUFFER * 2 + 1; // 7

// `cursor` = index into SRCS of the currently active image
let cursor = 2; // food1 starts active

// Build DOM for the current window around cursor
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

// Compute the translateX that perfectly centers slot `slotIdx` in the showcase
function computeTranslate(slotIdx) {
    // The showcase is 200vw wide with left:-50%, so its left edge is at -100vw from page left.
    // The visual page center is at 50vw from page left = 150vw from showcase left edge.
    // But simpler: use window.innerWidth / 2 as the visual center offset from showcase left edge
    // since showcase.getBoundingClientRect().left will be negative (off screen left).
    const showcaseRect = showcase.getBoundingClientRect();
    // Visual center on screen in showcase-local coords:
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

        // Grab the current active element and clone it to animate out
        const currentActive = track.querySelector(".rotate.active");
        const outgoing = currentActive ? currentActive.cloneNode(true) : null;

        // Rebuild DOM with new cursor
        buildDOM();
        track.style.transition = "none";
        track.style.transform  = "translateX(0)";
        track.getBoundingClientRect();
        track.style.transition = "";

        // Animate new active sliding in
        const incoming = track.querySelector(".rotate.active");
        if (incoming) {
            incoming.classList.add(inClass);
            incoming.addEventListener("animationend", () => incoming.classList.remove(inClass), { once: true });
        }

        // Insert outgoing clone and animate it sliding out
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

    // Revert cursor to compute animation from old state
    cursor = ((cursor - dir) % N + N) % N;

    const gap = gapPx();
    const iw  = inactiveW();

    // Step 1: add an extra item on the incoming side (no animation yet)
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

    // Step 2: toggle active to the next slot and animate the slide
    const items = Array.from(track.querySelectorAll(".rotate"));
    const currentActive = items.findIndex(el => el.classList.contains("active"));
    const nextActive = currentActive + dir;

    items.forEach((el, i) => el.classList.toggle("active", i === nextActive));
    track.style.transform = `translateX(${computeTranslate(nextActive)}px)`;

    // Step 3: after transition, rebuild cleanly
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
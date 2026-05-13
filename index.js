let image = document.getElementById("week-food");
let images = ["img/img-foods/food1.jpg", "img/img-foods/food2.jpg", "img/img-foods/food3.jpg", "img/img-foods/food4.jpg", "img/img-foods/food5.jpg", "img/img-foods/food6.jpg", "img/img-foods/food7.jpg", "img/img-foods/food8.jpg", "img/img-foods/food9.jpg", "img/img-foods/food10.jpg"];
let pick = Math.floor(Math.random() * images.length);
image.src = images[pick];
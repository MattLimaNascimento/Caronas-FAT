const button = document.getElementById("menu-toggle");
const openCubes = Array.from(document.querySelectorAll("#open-cubes rect"));
const closeCubes = Array.from(document.querySelectorAll("#close-cubes rect"));
let locked = false;

const openEffect = (cubes) => {
  cubes.forEach((element, index) => {
    setTimeout(() => {
      element.classList.remove("spin-in");
      element.classList.add("spin-out");
    }, index * 10);
  });
};

const closeEffect = (cubes) => {
  cubes.forEach((element, index) => {
    setTimeout(() => {
      element.classList.remove("spin-out");
      element.classList.add("spin-in");
    }, index * 10);
  });
};

button.addEventListener("click", (e) => {
  if (!locked) {
    locked = true;
    button.classList.toggle("active");
    const length = openCubes.length;

    if (button.classList.contains("active")) {
      openEffect(openCubes);
      closeEffect(closeCubes);
    } else {
      closeEffect(openCubes);
      openEffect(closeCubes);
    }

    setTimeout(() => {
      locked = false;
    }, 1300 + length * 10);
  }
});

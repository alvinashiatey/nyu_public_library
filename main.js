import "./src/css/style.css";
import "./src/css/reset.css";

function toggleHypothesis() {
  const iframe = document.querySelector("iframe.hypothesis__embed");
  const button = document.querySelector("button#toggle");
  button.addEventListener("click", () => {
    const src = iframe.src;
    if (src.includes("https://via.hypothes.is/")) {
      iframe.src = src.replace("https://via.hypothes.is/", "");
    } else {
      iframe.src = "https://via.hypothes.is/" + src;
    }
  });
}

window.onload = function () {
  toggleHypothesis();
};

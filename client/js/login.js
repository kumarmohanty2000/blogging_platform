import { process } from "./process.js";

document
  .getElementById("loginForm")
  .addEventListener("submit", async function (event) {
    event.preventDefault();

    const formData = new FormData(this);

    try {
      const response = await fetch(`${process.backend_server}/api/login.php`, {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (response.ok) {
        window.location.href = "blog_home.html";
      } else {
        document.getElementById("errorMessage").textContent = result.error;
      }
    } catch (error) {
      console.error("Error:", error);
    }
  });

document.addEventListener("DOMContentLoaded", function () {
  const navLinks = document.querySelectorAll(".nav-link");
  const dynamicContent = document.getElementById("dynamic-content");
  const mainHeader = document.querySelector(".main-header");

  function loadContent(url) {
    dynamicContent.innerHTML = "<p>Carregando...</p>";

    fetch(url)
      .then((response) => {
        if (!response.ok) {
          throw new Error("Erro na rede ou na requisição.");
        }
        return response.text();
      })
      .then((html) => {
        if (
          url.includes("/cliente/cadastro") ||
          url.includes("/produto/cadastro")
        ) {
          const tempDiv = document.createElement("div");
          tempDiv.innerHTML = html;
          const box = tempDiv.querySelector(".box-compra");

          if (box) {
            dynamicContent.innerHTML = box.outerHTML;
          } else {
            dynamicContent.innerHTML = html;
          }

          if (url.includes("/cliente/cadastro")) {
            mainHeader.innerHTML = "";
          } else if (url.includes("/produto/cadastro")) {
            mainHeader.innerHTML = "";
          }
        } else {
          window.location.href = url;
        }
      })
      .catch((error) => {
        console.error("Erro ao carregar o conteúdo:", error);
        dynamicContent.innerHTML =
          '<p style="color: red;">Não foi possível carregar o conteúdo solicitado.</p>';
      });
  }

  navLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();

      const targetUrl = this.getAttribute("data-target");

      if (targetUrl === "/dashboard") {
        window.location.href = targetUrl;
        return;
      }

      loadContent(targetUrl);
    });
  });
});

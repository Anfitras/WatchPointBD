function validarEmail(email) {
  const regex = /\S+@\S+\.\S+/;
  return regex.test(email);
}

function validarFormularioLogin(ids) {
  const email = document.getElementById(ids.email).value.trim();
  const senha = document.getElementById(ids.senha).value;

  if (email === "" || !validarEmail(email)) {
    alert("Por favor, insira um e-mail válido.");
    return false;
  }

  if (senha === "" || senha.length < 6 || senha.length > 8) {
    alert("Senha inválida. A senha deve conter entre 6 e 8 caracteres.");
    return false;
  }

  return true;
}

function processarLogin(event) {
  event.preventDefault();

  const idsLogin = {
    email: "email",
    senha: "senha",
  };

  if (!validarFormularioLogin(idsLogin)) {
    return;
  }

  const form = event.target;
  const formData = new FormData(form);
  formData.append("ajax", "1");

  fetch(form.action, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data && data.success) {
        window.location.href = "index.html";
      } else {
        const msg =
          data && data.message
            ? data.message
            : "Email ou senha inválidos. Tente novamente.";
        alert(msg);
      }
    })
    .catch((err) => {
      console.error("Erro na requisição de login:", err);
      alert(
        "Ocorreu um erro ao conectar com o servidor. Tente novamente mais tarde."
      );
    });
}

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("formulario");
  form.addEventListener("submit", processarLogin);

  const params = new URLSearchParams(window.location.search);
  if (params.get("erro") === "1") {
    alert("Email ou senha inválidos. Tente novamente.");
    params.delete("erro");
    const newUrl =
      window.location.pathname +
      (params.toString() ? "?" + params.toString() : "");
    window.history.replaceState({}, document.title, newUrl);
  }
});

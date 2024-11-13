// import '/var/www/html/share/assets/css/toggle-mdp.css';

document.addEventListener('DOMContentLoaded', () => {
    let mdp = document.getElementById("form_plainPassword");
    let confirmMdp = document.getElementById("form_confirmPassword");
    let toggleBtn = document.getElementById("toggle-mdp");
    let toggleConfirmBtn = document.getElementById("toggle-confirmMdp");

    toggleBtn.addEventListener("click", toggleBtnClick, "false");
    toggleConfirmBtn.addEventListener("click", toggleConfirmBtnClick, "false");

    function toggleBtnClick() {
        if (mdp.type === "password") {
            mdp.type = "text";
            toggleBtn.innerHTML = `<i class="bi bi-eye-slash-fill"></i>`;
        } else {
            mdp.type = "password";
            toggleBtn.innerHTML = `<i class="bi bi-eye-fill"></i>`;
        }
    }

    function toggleConfirmBtnClick() {
        if (confirmMdp.type === "password") {
            confirmMdp.type = "text";
            toggleConfirmBtn.innerHTML = `<i class="bi bi-eye-slash-fill"></i>`;
        } else {
            confirmMdp.type = "password";
            toggleConfirmBtn.innerHTML = `<i class="bi bi-eye-fill"></i>`;
        }
    }
})
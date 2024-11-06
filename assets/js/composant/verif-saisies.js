document.addEventListener('DOMContentLoaded', () => {
    let mdp = document.getElementById("registration_form_plainPassword");
    mdp.addEventListener("input", verifMdp, "false");
    
    function verifMdp() {
        if (mdp.value.length >= 12) {
            document.getElementById("longueur").classList.replace("text-danger", "text-success")
        } else {
            document.getElementById("longueur").classList.replace("text-success", "text-danger")
        }
        if (mdp.value.match(/[a-z]/)) {
            document.getElementById("minuscule").classList.replace("text-danger", "text-success")
        } else {
            document.getElementById("minuscule").classList.replace("text-success", "text-danger")
        }
        if (mdp.value.match(/[A-Z]/)) {
            document.getElementById("majuscule").classList.replace("text-danger", "text-success")
        } else {
            document.getElementById("majuscule").classList.replace("text-success", "text-danger")
        }
        if (mdp.value.match(/\d/)) {
            document.getElementById("chiffre").classList.replace("text-danger", "text-success")
        } else {
            document.getElementById("chiffre").classList.replace("text-success", "text-danger")
        }
        if (mdp.value.match(/[#?!@$%^&*-]/)) {
            document.getElementById("caractere").classList.replace("text-danger", "text-success")
        } else {
            document.getElementById("caractere").classList.replace("text-success", "text-danger")
        }
    }
})
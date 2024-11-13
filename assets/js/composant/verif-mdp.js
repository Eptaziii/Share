document.addEventListener('DOMContentLoaded', () => {
    let mdp = document.getElementById("form_plainPassword");
    mdp.addEventListener("input", verifMdp, "false");
    let pb = document.getElementById("pb");
    let nb = 0;
    let longueur = false
    let minuscule = false
    let majuscule = false
    let chiffre = false
    let caractere = false

    function verifMdp() {
        if (mdp.value.length >= 12) {
            document.getElementById("longueur").classList.replace("text-danger", "text-success")
            if (!longueur) {
                nb+=20
                longueur = true
            }
        } else {
            document.getElementById("longueur").classList.replace("text-success", "text-danger")
            if (longueur) {
                nb-=20
                longueur = false
            }
        }
        if (mdp.value.match(/[a-z]/)) {
            document.getElementById("minuscule").classList.replace("text-danger", "text-success")
            if (!minuscule) {
                nb+=20
                minuscule = true
            }
        } else {
            document.getElementById("minuscule").classList.replace("text-success", "text-danger")
            if (minuscule) {
                nb-=20
                minuscule = false
            }
        }
        if (mdp.value.match(/[A-Z]/)) {
            document.getElementById("majuscule").classList.replace("text-danger", "text-success")
            if (!majuscule) {
                nb+=20
                majuscule = true
            }
        } else {
            document.getElementById("majuscule").classList.replace("text-success", "text-danger")
            if (majuscule) {
                nb-=20
                majuscule = false
            }
        }
        if (mdp.value.match(/\d/)) {
            document.getElementById("chiffre").classList.replace("text-danger", "text-success")
            if (!chiffre) {
                nb+=20
                chiffre = true
            }
        } else {
            document.getElementById("chiffre").classList.replace("text-success", "text-danger")
            if (chiffre) {
                nb-=20
                chiffre = false
            }
        }
        if (mdp.value.match(/[#?!@$%^&*-]/)) {
            document.getElementById("caractere").classList.replace("text-danger", "text-success")
            if (!caractere) {
                nb+=20
                caractere = true
            }
        } else {
            document.getElementById("caractere").classList.replace("text-success", "text-danger")
            if (caractere) {
                nb-=20
                caractere = false
            }
            
        }
        pb.style.width = `${nb}%`
    }
})

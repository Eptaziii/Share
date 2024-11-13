document.addEventListener("DOMContentLoaded", () => {
    const captchaCanvas = document.getElementById('captcha');
    const captchaContext = captchaCanvas.getContext('2d');
    let lettres = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
    let captchaVal = "";
    console.log("kk");
    // Générer 10 caractères aléatoires
    for (let i = 0; i < 10; i++) {
        let lettresRand = Math.floor(Math.random() * 26); // Prendre un caractère aléatoire
        let lettresMinRand = Math.floor(Math.random() * 2); // Décider si la lettre est en minuscule ou majuscule
        let lettre = "";
        
        if (lettresMinRand == 0) {
            lettre = lettres[lettresRand].toLowerCase(); // Lettre en minuscule
        } else {
            lettre = lettres[lettresRand]; // Lettre en majuscule
        }
        
        captchaVal += lettre; // Ajouter la lettre au texte CAPTCHA
    }

    let captchaText = captchaVal;
    captchaContext.clearRect(0, 0, captchaCanvas.width, captchaCanvas.height); // Effacer le canvas avant de dessiner

    // Fond du CAPTCHA
    captchaContext.fillStyle = "#f1f1f1";
    captchaContext.fillRect(0, 0, captchaCanvas.width, captchaCanvas.height);

    // Texte CAPTCHA
    captchaContext.font = "40px Arial"; // Augmenter la taille du texte pour qu'il soit bien visible
    captchaContext.fillStyle = "#333";
    captchaContext.textAlign = "center";
    captchaContext.textBaseline = "middle";
    captchaContext.fillText(captchaText, captchaCanvas.width / 2, captchaCanvas.height / 2); // Placer le texte au centre du canvas

    // Lignes de perturbation
    for (let i = 0; i < 5; i++) {
        captchaContext.beginPath();
        captchaContext.moveTo(Math.random() * captchaCanvas.width, Math.random() * captchaCanvas.height);
        captchaContext.lineTo(Math.random() * captchaCanvas.width, Math.random() * captchaCanvas.height);
        captchaContext.strokeStyle = "rgba(0, 0, 0, 1)";
        captchaContext.stroke();
    }

    // Points de perturbation
    for (let i = 0; i < 30; i++) {
        captchaContext.beginPath();
        captchaContext.arc(Math.random() * captchaCanvas.width, Math.random() * captchaCanvas.height, 1, 0, Math.PI * 2, false);
        captchaContext.fillStyle = "rgba(0, 0, 0, 1)";
        captchaContext.fill();
    }

    // Vérification du CAPTCHA
    let verifCap = document.getElementById('form_captcha');
    verifCap.addEventListener('input', verif); // Écoute des entrées de l'utilisateur

    function verif() {
        if (verifCap.value === captchaVal) {
            document.getElementById('btInscrire').classList.remove('disabled');
            document.getElementById('btInscrire').disabled = false;
        } else {
            document.getElementById('btInscrire').classList.add('disabled');
            document.getElementById('btInscrire').disabled = true;
        }
    }
});
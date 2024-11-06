document.addEventListener("DOMContentLoaded", () => {
    let lettres = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
    let captcha = "";
    let captchaVal = "";
    
    for (let i = 0; i < 10; i++) {
        let lettresRand = Math.floor(Math.random() * (26 - 0))
        let lettresMinRand = Math.floor(Math.random() * (2 - 0))
        let lettre = "";
        console.log(lettresMinRand);
        if(lettresMinRand == 0){
            lettre = lettres[lettresRand].toLowerCase();
        }else{
            lettre = lettres[lettresRand];
        }
        captcha = document.getElementById('captcha');
        captcha.textContent = captcha.textContent + lettre;
        captchaVal = captchaVal+lettre;
    }

    let verifCap = document.getElementById('form_captcha');
    verifCap.addEventListener('input', verif, "false");

    function verif (){
        if(verifCap.value === captchaVal){
            document.getElementById('btInscrire').classList.remove('disabled');
        }else{
            document.getElementById('btInscrire').classList.add('disabled');
        }

        
    }




})
document.getElementById("paste").addEventListener("submit", function(e){
    e.preventDefault();
    if (document.getElementById('encrypt').checked) {
        const password = document.getElementById("password").value;
        const content = document.getElementById("content").value;
        const ciphertext = CryptoJS.AES.encrypt(content, password).toString();
        document.getElementById("content").value = ciphertext;
    }
    this.submit();
});


document.getElementById("encrypt").onclick = function() {
    if (document.getElementById('encrypt').checked) {
        document.getElementById('password-box').style.display = 'flex';
     } else {
        document.getElementById('password-box').style.display = 'none';
     }
}


document.getElementById("generate").onclick = function() {
    var chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()[]{}=+-_;:|,.?";
    var passwordLength = Math.random() * (30 - 18) + 18;
    var password = "";
    for (var i = 0; i <= passwordLength; i++) {
        var randomNumber = Math.floor(Math.random() * chars.length);
        password += chars.substring(randomNumber, randomNumber +1);
    }
    document.getElementById("password").value = password;
}
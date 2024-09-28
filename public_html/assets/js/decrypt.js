document.getElementById("decrypt").onclick = function() {
    const ciphertext = document.getElementById("content").value;
    const password = document.getElementById("password").value;
    const originalText  = CryptoJS.AES.decrypt(ciphertext, password).toString(CryptoJS.enc.Utf8);
    document.getElementById("content").value = originalText;
}
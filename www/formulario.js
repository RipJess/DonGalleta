document.getElementById("contactForm").addEventListener("submit", function (event) {
    event.preventDefault(); 
    document.getElementById("contactForm").style.display = "none";
    document.getElementById("confirmationMessage").style.display = "block";
});

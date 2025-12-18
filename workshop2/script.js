// Wait until the DOM is fully loaded
document.addEventListener("DOMContentLoaded", function() {

    const form = document.getElementById("contactForm");

    form.addEventListener("submit", function(e) {
        e.preventDefault(); // prevent actual form submission

        // Clear previous error messages
        document.getElementById("nameError").innerText = "";
        document.getElementById("emailError").innerText = "";
        document.getElementById("messageError").innerText = "";

        let isValid = true;

        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const message = document.getElementById("message").value.trim();

        // Name validation
        if(name === "") {
            document.getElementById("nameError").innerText = "Name cannot be empty";
            isValid = false;
        }

        // Email validation
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(email === "") {
            document.getElementById("emailError").innerText = "Email cannot be empty";
            isValid = false;
        } else if(!emailPattern.test(email)) {
            document.getElementById("emailError").innerText = "Enter a valid email";
            isValid = false;
        }

        // Message validation
        if(message === "") {
            document.getElementById("messageError").innerText = "Message cannot be empty";
            isValid = false;
        }

        // If all valid, show success message
        if(isValid) {
            alert("Form submitted successfully!");
            form.reset(); // Reset form fields
        }
    });

});
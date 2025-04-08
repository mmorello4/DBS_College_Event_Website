// JavaScript to Toggle Password Visibility 
function togglePassword() 
{
    const passwordField = document.getElementById('password');
    const passwordIcon = document.querySelector('.toggle-password');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordIcon.textContent = 'X'; // Change to "hide" icon
    } else {
        passwordField.type = 'password';
        passwordIcon.textContent = '👁'; // Change back to "show" icon
    }
}

// Listen for submit
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("signupForm");

    form.addEventListener("submit", async function (e) {
        e.preventDefault(); // Stop normal form submission

        // Get values from the form
        const name = document.getElementById("name").value;
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;
        const university = document.getElementById("university").value;

        // Optionally get role from dropdown (if you add it), or default
        const role = "Student";

        const userData = {
            name,
            email,
            password,
            university,
            role
        };

        try {
            const response = await fetch("../../backend/register.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(userData)
            });

            const data = await response.json();

            if (response.ok) {
                alert("Registration successful!");
                window.location.href = "login-page.php"; // Redirect to login
            } else {
                alert("Registration failed: " + (data.error || "Unknown error"));
            }
        } catch (err) {
            console.error("Error:", err);
            alert("Something went wrong. Please try again later.");
        }
    });
});

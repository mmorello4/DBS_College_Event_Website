
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

// Listen for log in
document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault(); // Prevent default form submission
    
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    const formData = new FormData();
    formData.append('username', username);
    formData.append('password', password);

    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'dashboard.php'; // Redirect to dashboard
        } else {
            alert('Login failed: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});

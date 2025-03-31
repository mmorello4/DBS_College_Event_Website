
// JavaScript to Toggle Password Visibility 
function togglePassword() 
{
    const passwordField = document.getElementById('password');
    const passwordIcon = document.querySelector('.toggle-password');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordIcon.textContent = '🙈'; // Change to "hide" icon
    } else {
        passwordField.type = 'password';
        passwordIcon.textContent = '👁'; // Change back to "show" icon
    }
}
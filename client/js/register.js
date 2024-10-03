import { process } from "./process.js";

document.getElementById('registerForm').addEventListener('submit', async function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    try {
        const response = await fetch(`${process.backend_server}/api/register.php`, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (response.ok) {
            alert(result.message);  
            window.location.href = 'login.html';
        } else {
            console.log("error");
            
            alert(result.error);
        }
    } catch (error) {
        console.error('Error:', error);
    }
});

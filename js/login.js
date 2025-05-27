// Para el ojo de contraseña
function togglePassword() {
    const passwordInput = document.getElementById('clave');
    const eyeIcon = document.querySelector('.eye-icon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash'); // Icono de ojo abierto
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye'); // Ojo cerrado
    }
}

document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const usuario = document.getElementById('usuario').value.trim();
    const clave = document.getElementById('clave').value.trim();
    const loginBtn = document.getElementById('loginBtn');
    const errorMsg = document.getElementById('errorMessage');
    const successMsg = document.getElementById('successMessage');
    
    // Limpiar mensajes anteriores
    errorMsg.style.display = 'none';
    successMsg.style.display = 'none';
    
    if (!usuario || !clave) {
        showError('Por favor, complete todos los campos');
        return;
    }
    
    // Mostrar estado de carga
    loginBtn.disabled = true;
    loginBtn.innerHTML = '<div class="loading"></div>Iniciando sesión...';
    
    try {
        const response = await fetch('api/process_login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                usuario: usuario,
                clave: clave
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess(data.message);
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else {
            showError(data.message);
        }
    } catch (error) {
        showError('Error de conexión. Intente nuevamente.');
    } finally {
        loginBtn.disabled = false;
        loginBtn.innerHTML = 'Iniciar Sesión';
    }
});

function showError(message) {
    const errorMsg = document.getElementById('errorMessage');
    errorMsg.textContent = message;
    errorMsg.style.display = 'block';
}

function showSuccess(message) {
    const successMsg = document.getElementById('successMessage');
    successMsg.textContent = message;
    successMsg.style.display = 'block';
}

// Enfocar el campo usuario al cargar la página
document.getElementById('usuario').focus();
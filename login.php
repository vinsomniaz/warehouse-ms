<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <title>Ingreso | Acuarius Logistic</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="img/logo.png" alt="Logo Acuarius Logistic" class="logo-acuarius">
            <h1>Sistema de Almacén</h1>
            <p>Ingreso – Acuarius Logistic</p>
        </div>
        
        <div id="errorMessage" class="error-message"></div>
        <div id="successMessage" class="success-message"></div>
        
        <form id="loginForm">
            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" required>
            </div>
            
            <div class="form-group password-container">
                <label for="clave">Contraseña</label>
                <div class="password-input-wrapper">
                <input type="password" id="clave" name="clave" required>
                <span class="toggle-password" onclick="togglePassword()">
                <i class="fas fa-eye eye-icon"></i>
                </span>
                </div>
            </div>
            
            <button type="submit" class="login-btn" id="loginBtn">
                Iniciar Sesión
            </button>
        </form>
    </div>

    <script src="js/login.js"></script>

</body>
</html>
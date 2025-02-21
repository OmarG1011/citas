<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <!-- Enlace a Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Archivo con animación -->
</head>
<body>
<div class="container-fluid fondo text-dark d-flex justify-content-center align-items-center">
<div class="container mt-5">
        <h2 class="text-center">Registrese </h2>
        <form action="index.php?action=registro" method="POST" class="d-flex flex-column align-items-center">
        <div class="mb-3 w-50">
                <label for="username" class="form-label">Nombre de usuario:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="mb-3 w-50">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrarse</button>
        </form>
        <p class="mt-3 text-center">¿Ya tienes una cuenta? <a class="text-dark" href="index.php?action=login">Iniciar sesión</a></p>
    </div>

    <!-- Enlace a Bootstrap 5 JS (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <?php if (isset($_SESSION['sweet_alert'])): ?>
    <script>
        Swal.fire({
            icon: '<?php echo $_SESSION['sweet_alert']['type']; ?>',
            title: '<?php echo $_SESSION['sweet_alert']['message']; ?>',
            showConfirmButton: true,
            timer: 3000
        });
    </script>
    <?php unset($_SESSION['sweet_alert']); endif; ?>
</body>
</html>
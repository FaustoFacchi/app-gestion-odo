<?php
// login.php
session_start();

// Si ya hay una sesión activa, lo mandamos directo al panel principal
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

// Procesar el login cuando la petición viene por AJAX (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    require_once 'conexion.php';

    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Por favor, complete todos los campos.']);
        exit;
    }

    try {
        // Incluimos el campo 'rol' que confirmamos que tenés en la base de datos
        $stmt = $conexion->prepare("SELECT id, nombre, apellido, password, rol FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            // Guardamos la información del profesional en la sesión
            $_SESSION['usuario_id']     = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'] . ' ' . $usuario['apellido'];
            $_SESSION['usuario_rol']    = $usuario['rol'];

            echo json_encode(['status' => 'success', 'message' => '¡Bienvenido al sistema!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'El correo o la contraseña son incorrectos.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error de servidor: ' . $e->getMessage()]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - OdontoApp</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { 
            background-color: #0f172a; 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-family: system-ui, -apple-system, sans-serif;
        }
        .card-login { 
            width: 100%; 
            max-width: 420px; 
            border-radius: 16px; 
            border: none;
        }
    </style>
</head>
<body>

<div class="card card-login shadow-lg p-4 bg-white">
    <div class="text-center mb-4">
        <div class="bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width: 50px; height: 50px;">
            <i class="bi bi-hospital fs-3"></i>
        </div>
        <h3 class="fw-bold text-dark mb-1">OdontoApp</h3>
        <p class="text-muted small">Ingresá tus datos para acceder al sistema</p>
    </div>
    
    <form id="formLogin">
        <div class="mb-3">
            <label class="form-label fw-bold small text-secondary">Correo Electrónico</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                <input type="email" name="email" class="form-control bg-light border-start-0" required placeholder="ejemplo@odontoapp.com">
            </div>
        </div>
        
        <div class="mb-4">
            <label class="form-label fw-bold small text-secondary">Contraseña</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                <input type="password" name="password" class="form-control bg-light border-start-0" required placeholder="••••••••">
            </div>
        </div>

        <button type="submit" id="btnSubmit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
            <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
        </button>
    </form>
</div>

<script>
document.getElementById('formLogin').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Ingresando...';

    const formData = new FormData(this);

    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            window.location.href = 'index.php';
        } else {
            alert('⚠️ ' + data.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión';
        }
    })
    .catch(err => {
        console.error(err);
        alert('Ocurrió un error en la conexión.');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión';
    });
});
</script>
</body>
</html>
<?php
require_once '../conexion.php';

$project_ref = "gaerzvsvjbkzfpznkvye";
$apiKey      = "sb_publishable_SEYe6-WdhwUF7iXkkGF2Fg_V00qUFrS";
$baseUrl     = "https://{$project_ref}.supabase.co/rest/v1/";

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nombre'       => $_POST['nombre'] ?? '',
        'dni'          => $_POST['dni'] ?? '',
        'especialidad' => $_POST['especialidad'] ?? ''
    ];

    if (!empty($data['nombre'])) {
        $resultado = supabase_post('desarrollador', $data, $baseUrl, $apiKey);
        if ($resultado) {
            header("Location: desarrolladores.php");
            exit;
        } else {
            $mensaje = "Error al guardar el desarrollador.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arqué Proyects — Añadir Desarrollador</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-shell">
        <main class="main-content" style="max-width: 600px; margin: 40px auto; width: 100%;">
            <section class="panel">
                <div class="panel-header">
                    <h2>Nuevo Desarrollador</h2>
                    <a href="desarrolladores.php" style="color: var(--text-muted, #888); text-decoration: none;">✕ Cancelar</a>
                </div>

                <?php if ($mensaje): ?>
                    <p style="color: red; padding: 10px 0;"><?= htmlspecialchars($mensaje) ?></p>
                <?php endif; ?>

                <form method="POST" style="display: flex; flex-direction: column; gap: 16px; padding: 20px 0;">
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label for="nombre"><strong>Nombre Completo</strong></label>
                        <input type="text" id="nombre" name="nombre" placeholder="Ej: Lucas Martínez" required style="padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label for="dni"><strong>DNI / Documento</strong></label>
                        <input type="text" id="dni" name="dni" placeholder="Ej: 45892011" required style="padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label for="especialidad"><strong>Especialidad</strong></label>
                        <input type="text" id="especialidad" name="especialidad" placeholder="Ej: Frontend / Backend / Fullstack" required style="padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                        <a href="desarrolladores.php" class="filter-chip" style="text-decoration: none;">Cancelar</a>
                        <button type="submit" class="btn-primary">Guardar Desarrollador</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
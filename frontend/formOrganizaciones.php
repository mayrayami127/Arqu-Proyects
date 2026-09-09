<?php
require_once '../conexion.php';

$project_ref = "gaerzvsvjbkzfpznkvye";
$apiKey      = "sb_publishable_SEYe6-WdhwUF7iXkkGF2Fg_V00qUFrS";
$baseUrl     = "https://{$project_ref}.supabase.co/rest/v1/";

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nombreorganizacion' => $_POST['nombreorganizacion'] ?? '',
        'tipodeorganizacion' => $_POST['tipodeorganizacion'] ?? ''
    ];

    if (!empty($data['nombreorganizacion'])) {
        $resultado = supabase_post('organizacion', $data, $baseUrl, $apiKey);
        if ($resultado) {
            header("Location: organizaciones.php");
            exit;
        } else {
            $mensaje = "Error al guardar la organización.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arqué Proyects — Añadir Organización</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-shell">
        <main class="main-content" style="max-width: 600px; margin: 40px auto; width: 100%;">
            <section class="panel">
                <div class="panel-header">
                    <h2>Nueva Organización</h2>
                    <a href="organizaciones.php" style="color: var(--text-muted, #888); text-decoration: none;">✕ Cancelar</a>
                </div>
                
                <?php if ($mensaje): ?>
                    <p style="color: red; padding: 10px 0;"><?= htmlspecialchars($mensaje) ?></p>
                <?php endif; ?>

                <form method="POST" style="display: flex; flex-direction: column; gap: 16px; padding: 20px 0;">
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label for="nombreorganizacion"><strong>Nombre de la Organización</strong></label>
                        <input type="text" id="nombreorganizacion" name="nombreorganizacion" placeholder="Ej: Ministerio de Economía" required style="padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label for="tipodeorganizacion"><strong>Tipo de Organización</strong></label>
                        <select id="tipodeorganizacion" name="tipodeorganizacion" required style="padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
                            <option value="">Seleccione tipo...</option>
                            <option value="Sector Público">Sector Público</option>
                            <option value="Sector Privado">Sector Privado</option>
                            <option value="ONG">ONG</option>
                        </select>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                        <a href="organizaciones.php" class="filter-chip" style="text-decoration: none;">Cancelar</a>
                        <button type="submit" class="btn-primary">Guardar Organización</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
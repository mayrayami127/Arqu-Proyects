<?php

require_once '../conexion.php';

// Consulta para traer las relaciones
$endpoint = "proyecto_desarrollador?select=*,proyecto(*),desarrollador(*),tecnica(*)";

$asignaciones = supabase_get($endpoint, $baseUrl, $apiKey);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Proyectos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #2c3e50; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>

    <h2>Asignaciones: Proyecto - Desarrollador</h2>

    <?php if ($asignaciones && count($asignaciones) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Proyecto</th>
                    <th>Desarrollador</th>
                    <th>Técnica / Rol</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($asignaciones as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['id_proyecto_desarrollador'] ?? '') ?></td>
                        <td><?= htmlspecialchars($item['proyecto']['nombreproyecto'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($item['desarrollador']['nombre'] ?? 'N/A') ?></td>
                        <td>
                            <?= htmlspecialchars($item['tecnica']['descripcion'] ?? 'N/A') ?>
                            <?= isset($item['tecnica']['rol']) ? " (" . htmlspecialchars($item['tecnica']['rol']) . ")" : '' ?>
                        </td>
                        <td><?= htmlspecialchars($item['fecha_inicio'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($item['fecha_fin'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No se encontraron registros o ocurrió un problema en la consulta.</p>
    <?php endif; ?>

</body>
</html>
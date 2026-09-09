<?php

require_once '../conexion.php';
$project_ref = "gaerzvsvjbkzfpznkvye";
$apiKey      = "sb_publishable_SEYe6-WdhwUF7iXkkGF2Fg_V00qUFrS"; // Revisa esta API Key en Supabase
$baseUrl     = "https://{$project_ref}.supabase.co/rest/v1/";

// Consultar lista de proyectos
$proyectos = supabase_get("proyecto?select=*", $baseUrl, $apiKey);
$totalProyectos = (is_array($proyectos)) ? count($proyectos) : 0;

// Consultar relaciones entre tablas
$endpoint = "proyecto_desarrollador?select=*,proyecto(*),desarrollador(*),tecnica(*)";
$asignaciones = supabase_get($endpoint, $baseUrl, $apiKey);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arqué Proyects — Asignaciones</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand"><span class="brand-mark">&gt;_</span><span>Arqué Proyects</span></div>
            <nav class="main-nav" aria-label="Navegación principal">
                <a class="nav-item" href="inicio.php"><span>Dashboard</span></a>
                <a class="nav-item active" href="proyectos.php"><span>Proyectos</span></a>
                <a class="nav-item" href="lideres.php"><span>Líderes Técnicos</span></a>
                <a class="nav-item" href="desarrolladores.php"><span>Desarrolladores</span></a>
                <a class="nav-item" href="organizaciones.php"><span>Organizaciones</span></a>
                <a class="nav-item" href="tecnicas.php"><span>Técnicas</span></a>
            </nav>
            <div class="sidebar-footer">
                <div class="support-card"><strong>¿Necesitas ayuda?</strong><span>Contacta a soporte</span><a href="mailto:soporte@devconsult.com">Abrir soporte <b>↗</b></a></div>
                <div class="profile"><span class="profile-avatar">MG</span><span class="profile-info"><strong>María González</strong><small>Administrador</small></span></div>
            </div>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <button class="mobile-menu" aria-label="Abrir menú">☰</button>
                <h1>Asignaciones de Proyectos</h1>
                <label class="search-box">
                    <input type="search" placeholder="Buscar asignaciones...">
                </label>
            </header>

            <div class="page-content">
                <div class="page-toolbar">
                    <div class="filter-chips">
                        <button class="filter-chip active">Total Proyectos <b><?= $totalProyectos ?></b></button>
                    </div>
                    <button class="btn-primary">+ Nueva Asignación</button>
                </div>

                <section class="panel">
                    <div class="panel-header"><h2>Gestión de Proyectos y Desarrolladores</h2></div>
                    <div class="table-wrap">
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
                                <?php if (is_array($asignaciones) && count($asignaciones) > 0): ?>
                                    <?php foreach ($asignaciones as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['id_proyecto_desarrollador'] ?? '-') ?></td>
                                            <td class="project-name"><?= htmlspecialchars($item['proyecto']['nombreproyecto'] ?? 'Sin asignar') ?></td>
                                            <td><?= htmlspecialchars($item['desarrollador']['nombre'] ?? 'Sin asignar') ?></td>
                                            <td>
                                                <?= htmlspecialchars($item['tecnica']['descripcion'] ?? 'Sin técnica') ?>
                                                <?= !empty($item['tecnica']['rol']) ? " (" . htmlspecialchars($item['tecnica']['rol']) . ")" : '' ?>
                                            </td>
                                            <td><?= htmlspecialchars($item['fecha_inicio'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($item['fecha_fin'] ?? '-') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 20px;">No se encontraron registros o la base de datos está vacía.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>
</html>
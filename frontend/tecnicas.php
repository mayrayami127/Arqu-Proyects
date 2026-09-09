<?php
require_once '../conexion.php';

$project_ref = "gaerzvsvjbkzfpznkvye";
$apiKey      = "sb_publishable_SEYe6-WdhwUF7iXkkGF2Fg_V00qUFrS";
$baseUrl     = "https://{$project_ref}.supabase.co/rest/v1/";

// Traer las técnicas y sus relaciones mediante proyecto_desarrollador
$endpoint = "tecnica?select=*,proyecto_desarrollador(*,proyecto(*),desarrollador(*))";
$tecnicas = supabase_get($endpoint, $baseUrl, $apiKey);

// Contar el total de registros
$totalTecnicas = (is_array($tecnicas)) ? count($tecnicas) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arqué Proyects — Técnicas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body> 
    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand"><span class="brand-mark">&gt;_</span><span>Arqué Proyects</span></div>
            <nav class="main-nav" aria-label="Navegación principal">
                <a class="nav-item" href="inicio.php"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 13h6V4H4v9Zm0 7h6v-4H4v4Zm10 0h6v-9h-6v9Zm0-16v4h6V4h-6Z"/></svg><span>Dashboard</span></a>
                <a class="nav-item" href="proyectos.php"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3.5 6.5A2.5 2.5 0 0 1 6 4h4l2 2h6a2.5 2.5 0 0 1 2.5 2.5v8A2.5 2.5 0 0 1 18 19H6a2.5 2.5 0 0 1-2.5-2.5v-10Z"/><path d="M4 9h16"/></svg><span>Proyectos</span></a>
                <a class="nav-item" href="lideres.php"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3"/><path d="M5 19c.8-3.1 3.1-5 7-5s6.2 1.9 7 5M18 5.5a2.4 2.4 0 0 1 0 4.8M20 14c1.2.5 1.9 1.4 2.2 2.6"/></svg><span>Líderes Técnicos</span></a>
                <a class="nav-item" href="desarrolladores.php"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3 19c.6-3.1 2.6-5 6-5s5.4 1.9 6 5M16 6.2a2.8 2.8 0 0 1 0 5.6M17 14.3c2.2.7 3.5 2.3 4 4.7"/></svg><span>Desarrolladores</span></a>
                <a class="nav-item" href="organizaciones.php"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="3" width="14" height="18" rx="1.5"/><path d="M8 7h2m4 0h2M8 11h2m4 0h2M8 15h2m4 0h2M10 21v-3h4v3"/></svg><span>Organizaciones</span></a>
                <a class="nav-item active" href="tecnicas.php"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg><span>Técnicas</span></a>
            </nav>
            <div class="sidebar-footer">
                <div class="support-card"><strong>¿Necesitas ayuda?</strong><span>Contacta a soporte</span><a href="mailto:soporte@devconsult.com">Abrir soporte <b>↗</b></a></div>
                <div class="profile"><span class="profile-avatar">MG</span><span class="profile-info"><strong>María González</strong><small>Administrador</small></span><span class="profile-menu">•••</span></div>
            </div>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <button class="mobile-menu" aria-label="Abrir menú">☰</button>
                <h1>Técnicas</h1>
                <label class="search-box">
                    <input type="search" placeholder="Buscar tecnologías...">
                </label>
            </header>

            <div class="page-content">
                <div class="page-toolbar">
                    <div class="filter-chips">
                        <button class="filter-chip active">Todas <b><?= $totalTecnicas ?></b></button>
                    </div>
                      <button class="btn-primary"><a href="formOrganizaciones.php" class="btn-primary">+ Nuevo Tecnología</a></button>
                    </div>

                <section class="panel">
                    <div class="panel-header"><h2>Catálogo de Tecnologías</h2></div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tecnología (Descripción)</th>
                                    <th>Categoría / Rol</th>
                                    <th>Asignaciones Activas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (is_array($tecnicas) && count($tecnicas) > 0): ?>
                                    <?php foreach ($tecnicas as $tec): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($tec['id_tecnica'] ?? '-') ?></td>
                                            <td class="project-name"><?= htmlspecialchars($tec['descripcion'] ?? 'Sin nombre') ?></td>
                                            <td><?= htmlspecialchars($tec['rol'] ?? 'Sin rol') ?></td>
                                            <td><?= isset($tec['proyecto_desarrollador']) ? count($tec['proyecto_desarrollador']) : 0 ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4">No se encontraron técnicas registradas.</td>
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
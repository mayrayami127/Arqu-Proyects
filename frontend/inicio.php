<?php
require_once '../conexion.php';

$project_ref = "gaerzvsvjbkzfpznkvye";
$apiKey      = "sb_publishable_SEYe6-WdhwUF7iXkkGF2Fg_V00qUFrS";
$baseUrl     = "https://{$project_ref}.supabase.co/rest/v1/";

// 1. Obtener datos reales para las tarjetas de estadísticas
$proyectosData = supabase_get("proyecto?select=*", $baseUrl, $apiKey);
$lideresData   = supabase_get("lider?select=*", $baseUrl, $apiKey);
$devsData      = supabase_get("desarrollador?select=*", $baseUrl, $apiKey);
$orgsData      = supabase_get("organizacion?select=*", $baseUrl, $apiKey);

$totalProyectos = is_array($proyectosData) ? count($proyectosData) : 0;
$totalLideres   = is_array($lideresData) ? count($lideresData) : 0;
$totalDevs      = is_array($devsData) ? count($devsData) : 0;
$totalOrgs      = is_array($orgsData) ? count($orgsData) : 0;

$stats = [
    ['label' => 'Proyectos Activos', 'value' => $totalProyectos, 'trend' => 'Registrados', 'icon' => 'folder'],
    ['label' => 'Líderes Técnicos',  'value' => $totalLideres,   'trend' => 'Registrados', 'icon' => 'leader'],
    ['label' => 'Desarrolladores',   'value' => $totalDevs,      'trend' => 'Registrados', 'icon' => 'users'],
    ['label' => 'Organizaciones',    'value' => $totalOrgs,      'trend' => 'Registradas', 'icon' => 'building'],
];

// 2. Consultar proyectos con relación a Organización y Líder
$projects = supabase_get("proyecto?select=*,organizacion(*),lider(*)", $baseUrl, $apiKey);

// 3. Consultar líderes con sus proyectos asignados
$leaders = supabase_get("lider?select=*,proyecto(*)", $baseUrl, $apiKey);

function icon(string $name): string {
    $icons = [
        'dashboard' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 13h6V4H4v9Zm0 7h6v-4H4v4Zm10 0h6v-9h-6v9Zm0-16v4h6V4h-6Z"/></svg>',
        'folder'    => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3.5 6.5A2.5 2.5 0 0 1 6 4h4l2 2h6a2.5 2.5 0 0 1 2.5 2.5v8A2.5 2.5 0 0 1 18 19H6a2.5 2.5 0 0 1-2.5-2.5v-10Z"/><path d="M4 9h16"/></svg>',
        'leader'    => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3"/><path d="M5 19c.8-3.1 3.1-5 7-5s6.2 1.9 7 5M18 5.5a2.4 2.4 0 0 1 0 4.8M20 14c1.2.5 1.9 1.4 2.2 2.6"/></svg>',
        'users'     => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3 19c.6-3.1 2.6-5 6-5s5.4 1.9 6 5M16 6.2a2.8 2.8 0 0 1 0 5.6M17 14.3c2.2.7 3.5 2.3 4 4.7"/></svg>',
        'building'  => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="3" width="14" height="18" rx="1.5"/><path d="M8 7h2m4 0h2M8 11h2m4 0h2M8 15h2m4 0h2M10 21v-3h4v3"/></svg>',
        'search'    => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.5 4.5"/></svg>',
        'chevron'   => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>',
    ];
    return $icons[$name] ?? '';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arqué Proyects — Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand"><span class="brand-mark">&gt;_</span><span>Arqué Proyects</span></div>
            <nav class="main-nav" aria-label="Navegación principal">
                <a class="nav-item active" href="inicio.php"><?php echo icon('dashboard'); ?><span>Dashboard</span></a>
                <a class="nav-item" href="proyectos.php"><?php echo icon('folder'); ?><span>Proyectos</span></a>
                <a class="nav-item" href="lideres.php"><?php echo icon('leader'); ?><span>Líderes Técnicos</span></a>
                <a class="nav-item" href="desarrolladores.php"><?php echo icon('users'); ?><span>Desarrolladores</span></a>
                <a class="nav-item" href="organizaciones.php"><?php echo icon('building'); ?><span>Organizaciones</span></a>
                <a class="nav-item" href="tecnicas.php"><?php echo icon('chevron'); ?><span>Técnicas</span></a>
            </nav>
            <div class="sidebar-footer">
                <div class="support-card"><strong>¿Necesitas ayuda?</strong><span>Contacta a soporte</span><a href="mailto:soporte@devconsult.com">Abrir soporte <b>↗</b></a></div>
                <div class="profile"><span class="profile-avatar">MG</span><span class="profile-info"><strong>María González</strong><small>Administrador</small></span><span class="profile-menu">•••</span></div>
            </div>
        </aside>

        <main class="main-content" id="dashboard">
            <header class="topbar">
                <button class="mobile-menu" aria-label="Abrir menú">☰</button>
                <h1>Dashboard</h1>
                <label class="search-box"><?php echo icon('search'); ?><input type="search" placeholder="Buscar proyectos, líderes..."></label>
            </header>

            <div class="page-content">
                <section class="stats-grid" aria-label="Resumen">
                    <?php foreach ($stats as $stat): ?>
                        <article class="stat-card">
                            <div class="stat-heading"><span><?php echo htmlspecialchars($stat['label']); ?></span><span class="stat-icon"><?php echo icon($stat['icon']); ?></span></div>
                            <strong class="stat-value"><?php echo htmlspecialchars($stat['value']); ?></strong>
                            <span class="stat-trend"><b>↗</b> <?php echo htmlspecialchars($stat['trend']); ?></span>
                        </article>
                    <?php endforeach; ?>
                </section>

                <section class="dashboard-grid">
                    <article class="panel projects-panel" id="proyectos">
                        <div class="panel-header"><h2>Proyectos Recientes</h2><a href="proyectos.php">Ver todos</a></div>
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nombre Proyecto</th>
                                        <th>Organización</th>
                                        <th>Líder Técnico</th>
                                        <th>Fecha Inicio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (is_array($projects) && count($projects) > 0): ?>
                                        <?php foreach ($projects as $project): ?>
                                            <tr>
                                                <td class="project-name"><?php echo htmlspecialchars($project['nombreproyecto'] ?? 'Sin nombre'); ?></td>
                                                <td><?php echo htmlspecialchars($project['organizacion']['nombreorganizacion'] ?? 'Sin organización'); ?></td>
                                                <td><?php echo htmlspecialchars($project['lider']['nombre'] ?? 'Sin líder'); ?></td>
                                                <td><?php echo htmlspecialchars($project['fechainicio'] ?? '-'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="4">No hay proyectos registrados.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </article>

                    <article class="panel leaders-panel" id="lideres">
                        <div class="panel-header"><h2>Líderes Técnicos</h2></div>
                        <div class="leader-list">
                            <?php if (is_array($leaders) && count($leaders) > 0): ?>
                                <?php foreach ($leaders as $leader): ?>
                                    <?php 
                                        $nombre = $leader['nombre'] ?? 'Sin Nombre';
                                        $partes = explode(' ', trim($nombre));
                                        $iniciales = strtoupper(substr($partes[0], 0, 1) . (isset($partes[1]) ? substr($partes[1], 0, 1) : ''));
                                        $cantProyectos = isset($leader['proyecto']) && is_array($leader['proyecto']) ? count($leader['proyecto']) : 0;
                                    ?>
                                    <div class="leader-row">
                                        <span class="leader-avatar"><?php echo htmlspecialchars($iniciales); ?></span>
                                        <span class="leader-details">
                                            <strong><?php echo htmlspecialchars($nombre); ?></strong>
                                            <small>DNI: <?php echo htmlspecialchars($leader['dni'] ?? '-'); ?></small>
                                        </span>
                                        <span class="leader-projects"><?php echo $cantProyectos; ?> proyecto(s)</span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p style="padding: 15px;">No hay líderes registrados.</p>
                            <?php endif; ?>
                        </div>
                    </article>
                </section>
            </div>
        </main>
    </div>
</body>
</html>
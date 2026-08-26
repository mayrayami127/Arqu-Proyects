<?php
$stats = [
    ['label' => 'Proyectos Activos', 'value' => '12', 'trend' => '+2 este mes', 'icon' => 'folder'],
    ['label' => 'Líderes Técnicos', 'value' => '5', 'trend' => 'Todos asignados', 'icon' => 'leader'],
    ['label' => 'Desarrolladores', 'value' => '28', 'trend' => '94% asignación', 'icon' => 'users'],
    ['label' => 'Organizaciones', 'value' => '9', 'trend' => '+1 nueva', 'icon' => 'building'],
];

$projects = [
    ['name' => 'Portal de Comercio Exterior', 'organization' => 'Min. de Economía', 'leader' => 'Sofía Valenzuela', 'status' => 'En Progreso', 'status_class' => 'progress', 'date' => '15 Ene 2024'],
    ['name' => 'Sistema de Gestión Hospitalaria', 'organization' => 'Salud Nacional', 'leader' => 'Alejandro Ruiz', 'status' => 'Planificación', 'status_class' => 'planning', 'date' => '02 Feb 2024'],
    ['name' => 'App Móvil Banca Digital', 'organization' => 'Banco Continental', 'leader' => 'Mateo Silva', 'status' => 'En Progreso', 'status_class' => 'progress', 'date' => '20 Dic 2023'],
    ['name' => 'Plataforma E-Learning', 'organization' => 'Univ. Metropolitana', 'leader' => 'Sofía Valenzuela', 'status' => 'Completado', 'status_class' => 'complete', 'date' => '10 Ago 2023'],
    ['name' => 'Motor de Facturación Electrónica', 'organization' => 'Retail Global S.A.', 'leader' => 'Alejandro Ruiz', 'status' => 'En Progreso', 'status_class' => 'progress', 'date' => '05 Ene 2024'],
];

$leaders = [
    ['name' => 'Sofía Valenzuela', 'role' => 'Solutions Architect', 'projects' => '3 proyectos', 'status' => 'Ocupado', 'status_class' => 'busy', 'avatar' => 'SV'],
    ['name' => 'Alejandro Ruiz', 'role' => 'Principal Dev', 'projects' => '2 proyectos', 'status' => 'Ocupado', 'status_class' => 'busy', 'avatar' => 'AR'],
    ['name' => 'Mateo Silva', 'role' => 'Senior Lead', 'projects' => '1 proyecto', 'status' => 'Disponible', 'status_class' => 'available', 'avatar' => 'MS'],
    ['name' => 'Lucía Santos', 'role' => 'DevOps Lead', 'projects' => '0 proyectos', 'status' => 'Disponible', 'status_class' => 'available', 'avatar' => 'LS'],
    ['name' => 'Daniel Ortega', 'role' => 'Fullstack Lead', 'projects' => '2 proyectos', 'status' => 'Ocupado', 'status_class' => 'busy', 'avatar' => 'DO'],
];

function icon(string $name): string {
    $icons = [
        'dashboard' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 13h6V4H4v9Zm0 7h6v-4H4v4Zm10 0h6v-9h-6v9Zm0-16v4h6V4h-6Z"/></svg>',
        'folder' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3.5 6.5A2.5 2.5 0 0 1 6 4h4l2 2h6a2.5 2.5 0 0 1 2.5 2.5v8A2.5 2.5 0 0 1 18 19H6a2.5 2.5 0 0 1-2.5-2.5v-10Z"/><path d="M4 9h16"/></svg>',
        'leader' => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3"/><path d="M5 19c.8-3.1 3.1-5 7-5s6.2 1.9 7 5M18 5.5a2.4 2.4 0 0 1 0 4.8M20 14c1.2.5 1.9 1.4 2.2 2.6"/></svg>',
        'users' => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3 19c.6-3.1 2.6-5 6-5s5.4 1.9 6 5M16 6.2a2.8 2.8 0 0 1 0 5.6M17 14.3c2.2.7 3.5 2.3 4 4.7"/></svg>',
        'building' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="3" width="14" height="18" rx="1.5"/><path d="M8 7h2m4 0h2M8 11h2m4 0h2M8 15h2m4 0h2M10 21v-3h4v3"/></svg>',
        'search' => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.5 4.5"/></svg>',
        'chevron' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>',
    ];
    return $icons[$name] ?? '';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevConsult — Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand"><span class="brand-mark">&gt;_</span><span>DevConsult</span></div>
            <nav class="main-nav" aria-label="Navegación principal">
                <a class="nav-item active" href="#dashboard"><?php echo icon('dashboard'); ?><span>Dashboard</span></a>
                <a class="nav-item" href="#proyectos"><?php echo icon('folder'); ?><span>Proyectos</span></a>
                <a class="nav-item" href="#lideres"><?php echo icon('leader'); ?><span>Líderes Técnicos</span></a>
                <a class="nav-item" href="#desarrolladores"><?php echo icon('users'); ?><span>Desarrolladores</span></a>
                <a class="nav-item" href="#organizaciones"><?php echo icon('building'); ?><span>Organizaciones</span></a>
                <a class="nav-item" href="#tecnicas"><?php echo icon('chevron'); ?><span>Técnicas</span></a>
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
                        <div class="panel-header"><h2>Proyectos Recientes</h2><a href="#proyectos">Ver todos</a></div>
                        <div class="table-wrap"><table><thead><tr><th>Nombre Proyecto</th><th>Organización</th><th>Líder Técnico</th><th>Estado</th><th>Fecha Inicio</th></tr></thead><tbody>
                            <?php foreach ($projects as $project): ?>
                                <tr><td class="project-name"><?php echo htmlspecialchars($project['name']); ?></td><td><?php echo htmlspecialchars($project['organization']); ?></td><td><?php echo htmlspecialchars($project['leader']); ?></td><td><span class="status <?php echo htmlspecialchars($project['status_class']); ?>"><?php echo htmlspecialchars($project['status']); ?></span></td><td><?php echo htmlspecialchars($project['date']); ?></td></tr>
                            <?php endforeach; ?>
                        </tbody></table></div>
                    </article>

                    <article class="panel leaders-panel" id="lideres">
                        <div class="panel-header"><h2>Disponibilidad de Líderes</h2></div>
                        <div class="leader-list">
                            <?php foreach ($leaders as $leader): ?>
                                <div class="leader-row"><span class="leader-avatar avatar-<?php echo strtolower(substr($leader['avatar'], 0, 1)); ?>"><?php echo htmlspecialchars($leader['avatar']); ?></span><span class="leader-details"><strong><?php echo htmlspecialchars($leader['name']); ?></strong><small><?php echo htmlspecialchars($leader['role']); ?></small></span><span class="leader-projects"><?php echo htmlspecialchars($leader['projects']); ?></span><span class="availability <?php echo htmlspecialchars($leader['status_class']); ?>"><?php echo htmlspecialchars($leader['status']); ?></span></div>
                            <?php endforeach; ?>
                        </div>
                    </article>
                </section>

                <section class="bottom-grid">
                    <article class="panel progress-panel"><div class="panel-header"><h2>Resumen de Proyectos</h2><span class="period">Este mes⌄</span></div><div class="progress-content"><div class="donut"><span>12<strong>Proyectos</strong></span></div><div class="legend"><span><i class="dot blue"></i>En progreso <b>6</b></span><span><i class="dot yellow"></i>Planificación <b>3</b></span><span><i class="dot green"></i>Completados <b>3</b></span></div></div></article>
                    <article class="panel activity-panel"><div class="panel-header"><h2>Actividad Reciente</h2><a href="#actividad">Ver historial</a></div><div class="activity-list"><div><span class="activity-dot"></span><p><strong>Sofía Valenzuela</strong> actualizó el proyecto <b>Portal de Comercio Exterior</b><small>Hace 2 horas</small></p></div><div><span class="activity-dot"></span><p><strong>María González</strong> asignó un nuevo líder técnico<small>Hace 5 horas</small></p></div><div><span class="activity-dot"></span><p><strong>Banco Continental</strong> creó un nuevo proyecto<small>Ayer, 16:45</small></p></div></div></article>
                </section>
            </div>
        </main>
    </div>
</body>
</html>

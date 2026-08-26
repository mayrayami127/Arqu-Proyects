<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevConsult — Líderes Técnicos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand"><span class="brand-mark">&gt;_</span><span>DevConsult</span></div>
            <nav class="main-nav" aria-label="Navegación principal">
                <a class="nav-item" href="main.html"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 13h6V4H4v9Zm0 7h6v-4H4v4Zm10 0h6v-9h-6v9Zm0-16v4h6V4h-6Z"/></svg><span>Dashboard</span></a>
                <a class="nav-item" href="proyectos.html"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3.5 6.5A2.5 2.5 0 0 1 6 4h4l2 2h6a2.5 2.5 0 0 1 2.5 2.5v8A2.5 2.5 0 0 1 18 19H6a2.5 2.5 0 0 1-2.5-2.5v-10Z"/><path d="M4 9h16"/></svg><span>Proyectos</span></a>
                <a class="nav-item active" href="lideres.html"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3"/><path d="M5 19c.8-3.1 3.1-5 7-5s6.2 1.9 7 5M18 5.5a2.4 2.4 0 0 1 0 4.8M20 14c1.2.5 1.9 1.4 2.2 2.6"/></svg><span>Líderes Técnicos</span></a>
                <a class="nav-item" href="desarrolladores.html"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3 19c.6-3.1 2.6-5 6-5s5.4 1.9 6 5M16 6.2a2.8 2.8 0 0 1 0 5.6M17 14.3c2.2.7 3.5 2.3 4 4.7"/></svg><span>Desarrolladores</span></a>
                <a class="nav-item" href="organizaciones.html"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="3" width="14" height="18" rx="1.5"/><path d="M8 7h2m4 0h2M8 11h2m4 0h2M8 15h2m4 0h2M10 21v-3h4v3"/></svg><span>Organizaciones</span></a>
                <a class="nav-item" href="tecnicas.html"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg><span>Técnicas</span></a>
            </nav>
            <div class="sidebar-footer">
                <div class="support-card"><strong>¿Necesitas ayuda?</strong><span>Contacta a soporte</span><a href="mailto:soporte@devconsult.com">Abrir soporte <b>↗</b></a></div>
                <div class="profile"><span class="profile-avatar">MG</span><span class="profile-info"><strong>María González</strong><small>Administrador</small></span><span class="profile-menu">•••</span></div>
            </div>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <button class="mobile-menu" aria-label="Abrir menú">☰</button>
                <h1>Líderes Técnicos</h1>
                <label class="search-box"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.5 4.5"/></svg><input type="search" placeholder="Buscar líderes..."></label>
            </header>

            <div class="page-content">
                <div class="page-toolbar">
                    <div class="filter-chips">
                        <button class="filter-chip active">Todos <b>5</b></button>
                        <button class="filter-chip">Disponibles <b>2</b></button>
                        <button class="filter-chip">Ocupados <b>3</b></button>
                    </div>
                    <button class="btn-primary">+ Asignar Líder</button>
                </div>

                <section class="panel">
                    <div class="panel-header"><h2>Líderes Técnicos</h2></div>
                    <div class="table-wrap"><table><thead><tr><th>Líder</th><th>Especialidad</th><th>Proyectos Activos</th><th>Disponibilidad</th><th>Tecnologías</th></tr></thead><tbody>
                        <tr><td><div class="leader-cell"><span class="leader-avatar avatar-s">SV</span><span class="leader-details"><strong>Sofía Valenzuela</strong><small>sofia.v@devconsult.com</small></span></div></td><td>Solutions Architect</td><td>3 proyectos</td><td><span class="availability busy">Ocupado</span></td><td><span class="tech-tag">React</span> <span class="tech-tag">Node.js</span> <span class="tech-tag">AWS</span></td></tr>
                        <tr><td><div class="leader-cell"><span class="leader-avatar avatar-a">AR</span><span class="leader-details"><strong>Alejandro Ruiz</strong><small>alejandro.r@devconsult.com</small></span></div></td><td>Principal Dev</td><td>2 proyectos</td><td><span class="availability busy">Ocupado</span></td><td><span class="tech-tag">Java</span> <span class="tech-tag">Spring</span> <span class="tech-tag">PostgreSQL</span></td></tr>
                        <tr><td><div class="leader-cell"><span class="leader-avatar avatar-m">MS</span><span class="leader-details"><strong>Mateo Silva</strong><small>mateo.s@devconsult.com</small></span></div></td><td>Senior Lead</td><td>1 proyecto</td><td><span class="availability available">Disponible</span></td><td><span class="tech-tag">React Native</span> <span class="tech-tag">GraphQL</span></td></tr>
                        <tr><td><div class="leader-cell"><span class="leader-avatar avatar-l">LS</span><span class="leader-details"><strong>Lucía Santos</strong><small>lucia.s@devconsult.com</small></span></div></td><td>DevOps Lead</td><td>0 proyectos</td><td><span class="availability available">Disponible</span></td><td><span class="tech-tag">Docker</span> <span class="tech-tag">Kubernetes</span> <span class="tech-tag">CI/CD</span></td></tr>
                        <tr><td><div class="leader-cell"><span class="leader-avatar avatar-d">DO</span><span class="leader-details"><strong>Daniel Ortega</strong><small>daniel.o@devconsult.com</small></span></div></td><td>Fullstack Lead</td><td>2 proyectos</td><td><span class="availability busy">Ocupado</span></td><td><span class="tech-tag">Vue</span> <span class="tech-tag">Python</span> <span class="tech-tag">Django</span></td></tr>
                    </tbody></table></div>
                </section>
            </div>
        </main>
    </div>
</body>
</html>

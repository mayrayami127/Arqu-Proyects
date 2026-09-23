<?php
require_once '../conexion.php';

// Variables de configuración
$project_ref = getenv('SUPABASE_PROJECT_REF') ?: "gaerzvsvjbkzfpznkvye";
$apiKey      = getenv('SUPABASE_API_KEY')      ?: "sb_publishable_SEYe6-WdhwUF7iXkkGF2Fg_V00qUFrS";
$baseUrl     = "https://{$project_ref}.supabase.co/rest/v1/";

// 1. Obtener solo las asignaciones activas desde la tabla intermedia trayendo organización y líder
$endpointAsignaciones = "proyecto_desarrollador?select=*,proyecto(*,organizacion(*),lider(*))";
$asignaciones = supabase_get($endpointAsignaciones, $baseUrl,$apiKey);

// 2. Extraer y agrupar los proyectos asignados de forma única
$proyectosUnicos = [];
if (is_array($asignaciones)) {
    foreach ($asignaciones as$item) {
        if (isset($item['proyecto']['id_proyecto']) || isset($item['proyecto']['id'])) {$projObj = $item['proyecto'];$idProj  = $projObj['id_proyecto'] ?? $projObj['id'];
            
            if (!isset($proyectosUnicos[$idProj])) {
                // Mapear el nombre de la organización asegurando el campo 'nombredeorganizacion'
                $orgObj    =$projObj['organizacion'] ?? null;
                $nombreOrg =$orgObj['nombredeorganizacion'] 
                          ?? $orgObj['nombreorganizacion'] 
                          ?? $orgObj['nombre_organizacion'] 
                          ?? $orgObj['nombre'] 
                          ?? 'Sin organización';

                $proyectosUnicos[$idProj] = [
                    'nombre'       => $projObj['nombreproyecto'] ?? $projObj['nombre'] ?? 'Sin nombre',
                    'organizacion' => $nombreOrg,
                    'lider'        => $projObj['lider']['nombre'] ?? 'Sin líder',
                    'fecha_inicio' => $item['fecha_inicio'] ?? $projObj['fechainicio'] ?? '-'
                ];
            }
        }
    }
}

// 3. Totales reales
$totalProyectos = count($proyectosUnicos);

$lideresData = supabase_get("lider?select=*", $baseUrl, $apiKey);$totalLideres = is_array($lideresData) ? count($lideresData) : 0;

$devsData = supabase_get("desarrollador?select=*", $baseUrl, $apiKey);$totalDevs = is_array($devsData) ? count($devsData) : 0;

$orgsData = supabase_get("organizacion?select=*", $baseUrl, $apiKey);$totalOrgs = is_array($orgsData) ? count($orgsData) : 0;

$stats = [
    ['label' => 'Proyectos Activos', 'value' => $totalProyectos, 'trend' => 'Registrados', 'icon' => 'folder'],
    ['label' => 'Líderes Técnicos',  'value' => $totalLideres,   'trend' => 'Registrados', 'icon' => 'leader'],
    ['label' => 'Desarrolladores',   'value' => $totalDevs,      'trend' => 'Registrados', 'icon' => 'users'],
    ['label' => 'Organizaciones',    'value' => $totalOrgs,      'trend' => 'Registradas', 'icon' => 'building'],
];

// 4. Consultar líderes con sus proyectos para el panel lateral
$leaders = supabase_get("lider?select=*,proyecto(*)", $baseUrl,$apiKey);

function icon(string $name): string {$icons = [
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
    <title>Arqué Projects — Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand"><span class="brand-mark">&gt;_</span><span>Arqué Projects</span></div>
            <nav class="main-nav" aria-label="Navegación principal">
                <a class="nav-item active" href="inicio.php"><?php echo icon('dashboard'); ?><span>Dashboard</span></a>
                <a class="nav-item" href="proyectos.php"><?php echo icon('folder'); ?><span>Proyectos</span></a>
                <a class="nav-item" href="lideres.php"><?php echo icon('leader'); ?><span>Líderes Técnicos</span></a>
                <a class="nav-item" href="desarrolladores.php"><?php echo icon('users'); ?><span>Desarrolladores</span></a>
                <a class="nav-item" href="organizaciones.php"><?php echo icon('building'); ?><span>Organizaciones</span></a>
                <a class="nav-item" href="tecnicas.php"><?php echo icon('chevron'); ?><span>Técnicas</span></a>
            </nav>
            <div class="sidebar-footer">
                <div class="support-card">
                    <strong>¿Necesitas ayuda?</strong>
                    <span>Contacta a soporte</span>
                    <a href="#" onclick="abrirModalSoporte(event)">Abrir soporte <b>↗</b></a>
                </div>
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
                    <?php foreach ($stats as$stat): ?>
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
                                    <?php if ($totalProyectos > 0): ?>
                                        <?php foreach ($proyectosUnicos as$project): ?>
                                            <tr>
                                                <td class="project-name"><?php echo htmlspecialchars($project['nombre']); ?></td>
                                                <td><?php echo htmlspecialchars($project['organizacion']); ?></td>
                                                <td><?php echo htmlspecialchars($project['lider']); ?></td>
                                                <td><?php echo htmlspecialchars($project['fecha_inicio']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="4" style="text-align: center; padding: 20px;">No hay proyectos activos registrados.</td></tr>
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
                $p0 = mb_substr($partes[0], 0, 1, 'UTF-8');
                $p1 = isset($partes[1]) ? mb_substr($partes[1], 0, 1, 'UTF-8') : '';
                $iniciales = mb_strtoupper($p0 . $p1, 'UTF-8');

                // Declaramos la variable antes de iniciar el ciclo
                $cantProyectos = 0;

                // Contar proyectos asignados
                if (is_array($proyectosUnicos)) {
                    foreach ($proyectosUnicos as $pActive) {
                        if (isset($pActive['lider']) && mb_strtolower(trim($pActive['lider'])) === mb_strtolower(trim($nombre))) {
                            $cantProyectos++;
                        }
                    }
                }
            ?>
            <div class="leader-row">
                <span class="leader-avatar"><?php echo htmlspecialchars($iniciales); ?></span>
                <span class="leader-details">
                    <strong><?php echo htmlspecialchars($nombre); ?></strong>
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

    <!-- MODAL POPUP DE SOPORTE -->
    <div id="modalSoporte" class="modal-overlay">
        <div class="modal-container" style="max-width: 500px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <h2 style="margin: 0; font-size: 1.25rem;">Enviar Correo a Soporte</h2>
                <button onclick="cerrarModalSoporte()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #888;">✕</button>
            </div>
            
            <p style="font-size: 0.85rem; color: #666; margin-bottom: 12px;">
                Envía tu mensaje a <strong>sabrinaalanoca@gmail.com</strong>. Puedes copiar la plantilla a tu portapapeles o abrir directamente en Gmail.
            </p>

            <textarea id="plantillaCorreo" rows="9" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc; font-family: inherit; font-size: 0.85rem; resize: vertical;">
Hola, equipo de soporte:

Tengo una consulta / reporte sobre la plataforma Arqué Projects:

- Módulo / Sección: Dashboard
- Descripción del problema: 

- Pasos para reproducirlo:
  1. 
  2. 

Atentamente,
Nombre Apellido
            </textarea>

            <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 14px;">
                <button type="button" onclick="copiarPlantilla()" class="filter-chip" style="cursor: pointer; background: #e0e0e0;">Copiar Texto</button>
                <a id="btnLinkMailto" href="#" target="_blank" class="btn-primary" style="text-decoration: none; display: inline-block; text-align: center;">Abrir en Gmail</a>
            </div>
        </div>
    </div>

    <script>
        // Funciones Modal Soporte
        function abrirModalSoporte(event) {
            if (event) event.preventDefault();
            actualizarEnlaceMailto();
            document.getElementById('modalSoporte').classList.add('active');
        }

        function cerrarModalSoporte() {
            document.getElementById('modalSoporte').classList.remove('active');
        }

        function actualizarEnlaceMailto() {
            const email = "sabrinaalanoca@gmail.com";
            const asunto = encodeURIComponent("[Soporte] Consulta / Incidencia en Arqué Projects");
            const textoCuerpo = document.getElementById('plantillaCorreo').value;
            const cuerpo = encodeURIComponent(textoCuerpo);
            
            const btnMailto = document.getElementById('btnLinkMailto');
            
            // Genera el enlace directo a la redacción en Gmail Web
            btnMailto.href = `https://mail.google.com/mail/?view=cm&fs=1&to=${email}&su=${asunto}&body=${cuerpo}`;
            btnMailto.target = "_blank";
        }

        function copiarPlantilla() {
            const texto = document.getElementById('plantillaCorreo');
            texto.select();
            navigator.clipboard.writeText(texto.value).then(() => {
                alert('¡Plantilla copiada al portapapeles!');
            }).catch(() => {
                document.execCommand('copy');
                alert('¡Plantilla copiada al portapapeles!');
            });
        }

        // Actualizar URL del enlace si se modifica el texto en la caja
        document.getElementById('plantillaCorreo').addEventListener('input', actualizarEnlaceMailto);

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            const modalSoporte = document.getElementById('modalSoporte');
            if (event.target === modalSoporte) cerrarModalSoporte();
        }
    </script>
</body>
</html>
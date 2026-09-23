<?php
require_once '../conexion.php';

$project_ref = "gaerzvsvjbkzfpznkvye";
$apiKey      = "sb_publishable_SEYe6-WdhwUF7iXkkGF2Fg_V00qUFrS";
$baseUrl     = "https://{$project_ref}.supabase.co/rest/v1/";

$mensaje = "";

// PROCESAR FORMULARIOS (CREAR, EDITAR, ELIMINAR)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion =$_POST['accion'] ?? 'crear';
    $id_org =$_POST['id_organizacion'] ?? '';

    // ACCIÓN: ELIMINAR ORGANIZACIÓN
    if ($accion === 'eliminar' && !empty($id_org)) {$ch = curl_init("{$baseUrl}organizacion?id_organizacion=eq.{$id_org}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apikey: {$apiKey}",
            "Authorization: Bearer {$apiKey}",
            "Content-Type: application/json"
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 &&$httpCode < 300) {
            header("Location: organizaciones.php");
            exit;
        } else {
            $mensaje = "Error al eliminar la organización (HTTP {$httpCode}): " . $response;
        }
    } 
    // ACCIONES: CREAR / EDITAR ORGANIZACIÓN
    else {
        $data = [
            'nombredeorganizacion' => $_POST['nombreorganizacion'] ?? '',
            'tipodeorganizacion'   => $_POST['tipodeorganizacion'] ?? ''
        ];

        if (!empty($data['nombredeorganizacion'])) {
            if ($accion === 'editar' && !empty($id_org)) {
                // EDITAR (PATCH)
                $ch = curl_init("{$baseUrl}organizacion?id_organizacion=eq.{$id_org}");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "apikey: {$apiKey}",
                    "Authorization: Bearer {$apiKey}",
                    "Content-Type: application/json",
                    "Prefer: return=representation"
                ]);
            } else {
                // CREAR (POST)
                $ch = curl_init("{$baseUrl}organizacion");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "apikey: {$apiKey}",
                    "Authorization: Bearer {$apiKey}",
                    "Content-Type: application/json",
                    "Prefer: return=representation"
                ]);
            }

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode >= 200 &&$httpCode < 300) {
                header("Location: organizaciones.php");
                exit;
            } else {
                $mensaje = "Error Supabase (HTTP {$httpCode}): " . $response;
            }
        }
    }
}

// Consultar organizaciones y traer sus proyectos con el líder asignado
$endpoint       = "organizacion?select=*,proyecto(*,lider(*))";
$organizaciones = supabase_get($endpoint, $baseUrl,$apiKey);

// Conteo total y contadores por tipo de organización
$totalOrgs = is_array($organizaciones) ? count($organizaciones) : 0;
$publicos  = 0;
$privados  = 0;

if (is_array($organizaciones)) {
    foreach ($organizaciones as$org) {
        $tipo = strtolower($org['tipodeorganizacion'] ?? '');
        if (str_contains($tipo, 'públ') || str_contains($tipo, 'publ')) {$publicos++;
        } elseif (str_contains($tipo, 'priva')) {$privados++;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arqué Projects — Organizaciones</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand"><span class="brand-mark">&gt;_</span><span>Arqué Projects</span></div>
            <nav class="main-nav" aria-label="Navegación principal">
                <a class="nav-item" href="inicio.php"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 13h6V4H4v9Zm0 7h6v-4H4v4Zm10 0h6v-9h-6v9Zm0-16v4h6V4h-6Z"/></svg><span>Dashboard</span></a>
                <a class="nav-item" href="proyectos.php"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3.5 6.5A2.5 2.5 0 0 1 6 4h4l2 2h6a2.5 2.5 0 0 1 2.5 2.5v8A2.5 2.5 0 0 1 18 19H6a2.5 2.5 0 0 1-2.5-2.5v-10Z"/><path d="M4 9h16"/></svg><span>Proyectos</span></a>
                <a class="nav-item" href="lideres.php"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3"/><path d="M5 19c.8-3.1 3.1-5 7-5s6.2 1.9 7 5M18 5.5a2.4 2.4 0 0 1 0 4.8M20 14c1.2.5 1.9 1.4 2.2 2.6"/></svg><span>Líderes Técnicos</span></a>
                <a class="nav-item" href="desarrolladores.php"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3 19c.6-3.1 2.6-5 6-5s5.4 1.9 6 5M16 6.2a2.8 2.8 0 0 1 0 5.6M17 14.3c2.2.7 3.5 2.3 4 4.7"/></svg><span>Desarrolladores</span></a>
                <a class="nav-item active" href="organizaciones.php"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="3" width="14" height="18" rx="1.5"/><path d="M8 7h2m4 0h2M8 11h2m4 0h2M8 15h2m4 0h2M10 21v-3h4v3"/></svg><span>Organizaciones</span></a>
                <a class="nav-item" href="tecnicas.php"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg><span>Técnicas</span></a>
            </nav>
            <div class="sidebar-footer">
                <div class="support-card">
                    <strong>¿Necesitas ayuda?</strong>
                    <span>Contacta a soporte</span>
                    <a href="#" onclick="abrirModalSoporte(event)">Abrir soporte <b>↗</b></a>
                </div>
                
        </aside>

        <main class="main-content">
            <header class="topbar">
                <button class="mobile-menu" aria-label="Abrir menú">☰</button>
                <h1>Organizaciones</h1>
                <label class="search-box"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.5 4.5"/></svg><input type="search" placeholder="Buscar organizaciones..."></label>
            </header>

            <div class="page-content">
                <div class="page-toolbar">
                    <div class="filter-chips">
                        <button class="filter-chip active">Todas <b><?= $totalOrgs ?></b></button>
                        <button class="filter-chip">Público <b><?= $publicos ?></b></button>
                        <button class="filter-chip">Privado <b><?= $privados ?></b></button>
                    </div>
                    <button class="btn-primary" onclick="abrirModalCrear()">+ Nueva Organización</button>
                </div>

                <?php if ($mensaje): ?>
                    <div style="background-color: #fee2e2; border: 1px solid #ef4444; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 16px; font-family: monospace;">
                        <?= htmlspecialchars($mensaje) ?>
                    </div>
                <?php endif; ?>

                <section class="panel">
                    <div class="panel-header"><h2>Organizaciones Clientes</h2></div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Organización</th>
                                    <th>Tipo de Organización</th>
                                    <th>Proyectos</th>
                                    <th>Líderes Asignados</th>
                                    <th style="text-align: right; padding-right: 16px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (is_array($organizaciones) && count($organizaciones) > 0): ?>
                                    <?php foreach ($organizaciones as$org): ?>
                                        <?php 
                                            $idOrg =$org['id_organizacion'] ?? '';
                                            $nombre =$org['nombredeorganizacion'] ?? $org['nombreorganizacion'] ?? $org['nombre_organizacion'] ?? $org['nombre'] ?? 'Sin Nombre';$tipoOrg = $org['tipodeorganizacion'] ?? 'No especificado';$partes = explode(' ', trim($nombre));$iniciales = strtoupper(substr($partes[0], 0, 1) . (isset($partes[1]) ? substr($partes[1], 0, 1) : ''));$proyectos = $org['proyecto'] ?? [];$cantProyectos = is_array($proyectos) ? count($proyectos) : 0;
                                            
                                            $nombresLideres = [];
                                            if (is_array($proyectos)) {
                                                foreach ($proyectos as$p) {
                                                    if (!empty($p['lider']['nombre'])) {
                                                        $nombresLideres[] =$p['lider']['nombre'];
                                                    }
                                                }
                                            }
                                            $lideresUnicos = array_unique($nombresLideres);$textoLideres = !empty($lideresUnicos) ? implode(', ', $lideresUnicos) : 'Sin líder asignado';
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($idOrg !== '' ?$idOrg : '-') ?></td>
                                            <td>
                                                <div class="leader-cell">
                                                    <span class="leader-avatar avatar-s"><?= htmlspecialchars($iniciales) ?></span>
                                                    <span class="leader-details">
                                                        <strong><?= htmlspecialchars($nombre) ?></strong>
                                                    </span>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($tipoOrg) ?></td>
                                            <td><?= $cantProyectos ?> proyecto(s)</td>
                                            <td><?= htmlspecialchars($textoLideres) ?></td>
                                            <td>
                                                <div class="actions-cell">
                                                    <button type="button" class="btn-primary btn-sm" 
                                                            onclick="abrirModalEditar('<?= $idOrg ?>', '<?= htmlspecialchars($nombre, ENT_QUOTES) ?>', '<?= htmlspecialchars($tipoOrg, ENT_QUOTES) ?>')">
                                                        Editar
                                                    </button>
                                                    <form action="organizaciones.php" method="POST" style="display:inline; margin:0;" onsubmit="return confirm('¿Estás seguro de eliminar esta organización?');">
                                                        <input type="hidden" name="accion" value="eliminar">
                                                        <input type="hidden" name="id_organizacion" value="<?= $idOrg ?>">
                                                        <button type="submit" class="btn-primary btn-sm btn-danger-sm">Eliminar</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 20px;">No se encontraron organizaciones registradas.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- MODAL POPUP DINÁMICO DE ORGANIZACIONES -->
    <div id="modalOrg" class="modal-overlay">
        <div class="modal-container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 id="modalTitle" style="margin: 0; font-size: 1.25rem;">Nueva Organización</h2>
                <button onclick="cerrarModal()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #888;">✕</button>
            </div>

            <form method="POST" action="organizaciones.php" style="display: flex; flex-direction: column; gap: 16px;">
                <input type="hidden" name="accion" id="formAccion" value="crear">
                <input type="hidden" name="id_organizacion" id="formIdOrg" value="">

                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label for="nombreorganizacion"><strong>Nombre de la Organización</strong></label>
                    <input type="text" id="nombreorganizacion" name="nombreorganizacion" placeholder="Ej: Tech Solutions S.A." required style="padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
                </div>

                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label for="tipodeorganizacion"><strong>Tipo de Organización</strong></label>
                    <select id="tipodeorganizacion" name="tipodeorganizacion" required style="padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
                        <option value="">Selecciona un tipo...</option>
                        <option value="Público">Público</option>
                        <option value="Privado">Privado</option>
                    </select>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="button" onclick="cerrarModal()" class="filter-chip" style="cursor: pointer;">Cancelar</button>
                    <button type="submit" id="btnSubmit" class="btn-primary" style="cursor: pointer;">Guardar Organización</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL POPUP DE SOPORTE -->
    <div id="modalSoporte" class="modal-overlay">
        <div class="modal-container" style="max-width: 500px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <h2 style="margin: 0; font-size: 1.25rem;">Enviar Correo a Soporte</h2>
                <button onclick="cerrarModalSoporte()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #888;">✕</button>
            </div>
            
            <p style="font-size: 0.85rem; color: #666; margin-bottom: 12px;">
                Envía tu mensaje a <strong>sabrinaalanoca@gmail.com</strong>. Puedes copiar la plantilla a tu portapapeles o intentar abrir tu correo.
            </p>

            <textarea id="plantillaCorreo" rows="9" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc; font-family: inherit; font-size: 0.85rem; resize: vertical;">
Asunto: [Soporte] Consulta / Incidencia en Arqué Projects

Hola, equipo de soporte:

Tengo una consulta / reporte sobre la plataforma Arqué Projects:

- Módulo / Sección: Organizaciones
- Descripción del problema: 

- Pasos para reproducirlo:
  1. 
  2. 

Atentamente,
Nombre Apellido
            </textarea>

            <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 14px;">
                <button type="button" onclick="copiarPlantilla()" class="filter-chip" style="cursor: pointer; background: #e0e0e0;">Copiar Texto</button>
                <a id="btnLinkMailto" href="#" target="_blank" class="btn-primary" style="text-decoration: none; display: inline-block; text-align: center;">Abrir Aplicación de Correo</a>
            </div>
        </div>
    </div>

    <script>
    // Funciones Modal Organización
    function abrirModalCrear() {
        document.getElementById('modalTitle').textContent = "Nueva Organización";
        document.getElementById('formAccion').value = "crear";
        document.getElementById('formIdOrg').value = "";
        document.getElementById('nombreorganizacion').value = "";
        document.getElementById('tipodeorganizacion').value = "";
        document.getElementById('btnSubmit').textContent = "Guardar Organización";
        document.getElementById('modalOrg').classList.add('active');
    }

    function abrirModalEditar(id, nombre, tipo) {
        document.getElementById('modalTitle').textContent = "Editar Organización";
        document.getElementById('formAccion').value = "editar";
        document.getElementById('formIdOrg').value = id;
        document.getElementById('nombreorganizacion').value = nombre;
        document.getElementById('tipodeorganizacion').value = tipo;
        document.getElementById('btnSubmit').textContent = "Actualizar Organización";
        document.getElementById('modalOrg').classList.add('active');
    }

    function cerrarModal() {
        document.getElementById('modalOrg').classList.remove('active');
    }

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
        btnMailto.href = `mailto:${email}?subject=${asunto}&body=${cuerpo}`;
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

    document.getElementById('plantillaCorreo').addEventListener('input', actualizarEnlaceMailto);

    // Cerrar modales al hacer clic fuera
    window.onclick = function(event) {
        const modalOrg = document.getElementById('modalOrg');
        const modalSoporte = document.getElementById('modalSoporte');
        if (event.target === modalOrg) cerrarModal();
        if (event.target === modalSoporte) cerrarModalSoporte();
    }
    </script>
</body>
</html>
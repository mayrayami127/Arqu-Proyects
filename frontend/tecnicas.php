<?php
require_once '../conexion.php';

$project_ref = "gaerzvsvjbkzfpznkvye";
$apiKey      = "sb_publishable_SEYe6-WdhwUF7iXkkGF2Fg_V00qUFrS";
$baseUrl     = "https://{$project_ref}.supabase.co/rest/v1/";

$mensaje = "";

// PROCESAR ACCIONES (CREAR, EDITAR, ELIMINAR)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion =$_POST['accion'] ?? 'crear';

    // 1. ELIMINAR TÉCNICA
    if ($accion === 'eliminar') {
        $id_tec =$_POST['id_tecnica'] ?? '';
        if (!empty($id_tec)) {$url = $baseUrl . "tecnica?id_tecnica=eq." . urlencode($id_tec);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "apikey: {$apiKey}",
                "Authorization: Bearer {$apiKey}"
            ]);
            $response = curl_exec($ch);
            curl_close($ch);

            header("Location: tecnicas.php");
            exit;
        }
    }

    // 2. CREAR O EDITAR TÉCNICA
    $data = [
        'descripcion' => $_POST['descripcion'] ?? '',
        'rol'         => $_POST['rol'] ?? ''
    ];

    if (!empty($data['descripcion'])) {
        if ($accion === 'editar') {
            // EDITAR (PATCH)
            $id_tec = $_POST['id_tecnica'] ?? '';$url = $baseUrl . "tecnica?id_tecnica=eq." . urlencode($id_tec);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "apikey: {$apiKey}",
                "Authorization: Bearer {$apiKey}",
                "Content-Type: application/json",
                "Prefer: return=minimal"
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode >= 200 &&$httpCode < 300) {
                header("Location: tecnicas.php");
                exit;
            } else {
                $mensaje = "Error al editar la tecnología.";
            }
        } else {
            // CREAR
            $resultado = supabase_post('tecnica',$data, $baseUrl,$apiKey);
            if ($resultado) {
                header("Location: tecnicas.php");
                exit;
            } else {
                $mensaje = "Error al guardar la tecnología.";
            }
        }
    }
}

// Traer las técnicas y sus relaciones mediante proyecto_desarrollador
$endpoint = "tecnica?select=*,proyecto_desarrollador(*,proyecto(*),desarrollador(*))";
$tecnicas = supabase_get($endpoint, $baseUrl,$apiKey);

// Contar el total de registros
$totalTecnicas = (is_array($tecnicas)) ? count($tecnicas) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arqué Projects — Técnicas</title>
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
                <a class="nav-item" href="organizaciones.php"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="3" width="14" height="18" rx="1.5"/><path d="M8 7h2m4 0h2M8 11h2m4 0h2M8 15h2m4 0h2M10 21v-3h4v3"/></svg><span>Organizaciones</span></a>
                <a class="nav-item active" href="tecnicas.php"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg><span>Técnicas</span></a>
            </nav>
            <div class="sidebar-footer">
                <div class="support-card">
                    <strong>¿Necesitas ayuda?</strong>
                    <span>Contacta a soporte</span>
                    <a href="#" onclick="abrirModalSoporte(event)">Abrir soporte <b>↗</b></a>
                </div>
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
                    <!-- BOTÓN QUE ABRE EL MODAL DINÁMICO -->
                    <button class="btn-primary" onclick="abrirModalCrear()">+ Nueva Tecnología</button>
                </div>

                <?php if ($mensaje): ?>
                    <p style="color: red; padding: 10px 0;"><?= htmlspecialchars($mensaje) ?></p>
                <?php endif; ?>

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
                                    <th style="text-align: right; padding-right: 16px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (is_array($tecnicas) && count($tecnicas) > 0): ?>
                                    <?php foreach ($tecnicas as$tec): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($tec['id_tecnica'] ?? '-') ?></td>
                                            <td class="project-name"><?= htmlspecialchars($tec['descripcion'] ?? 'Sin nombre') ?></td>
                                            <td><?= htmlspecialchars($tec['rol'] ?? 'Sin rol') ?></td>
                                            <td><?= isset($tec['proyecto_desarrollador']) ? count($tec['proyecto_desarrollador']) : 0 ?></td>
                                            <td>
                                                <div class="actions-cell">
                                                    <!-- BOTÓN EDITAR -->
                                                    <button type="button" class="btn-primary btn-sm" 
                                                            onclick="abrirModalEditar('<?= $tec['id_tecnica'] ?>', '<?= htmlspecialchars($tec['descripcion'] ?? '', ENT_QUOTES) ?>', '<?= htmlspecialchars($tec['rol'] ?? '', ENT_QUOTES) ?>')">
                                                        Editar
                                                    </button>

                                                    <!-- BOTÓN ELIMINAR -->
                                                    <form action="tecnicas.php" method="POST" style="display:inline; margin:0;" onsubmit="return confirm('¿Estás seguro de eliminar esta tecnología?');">
                                                        <input type="hidden" name="accion" value="eliminar">
                                                        <input type="hidden" name="id_tecnica" value="<?= $tec['id_tecnica'] ?>">
                                                        <button type="submit" class="btn-primary btn-sm btn-danger-sm">Eliminar</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 20px;">No se encontraron técnicas registradas.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- MODAL POPUP DE TÉCNICAS / TECNOLOGÍAS -->
    <div id="modalTecnica" class="modal-overlay">
        <div class="modal-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 id="modalTitle" style="margin: 0; font-size: 1.25rem;">Nueva Tecnología</h2>
                <button onclick="cerrarModal()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #888;">✕</button>
            </div>

            <form method="POST" action="tecnicas.php" style="display: flex; flex-direction: column; gap: 16px;">
                <input type="hidden" name="accion" id="formAccion" value="crear">
                <input type="hidden" name="id_tecnica" id="formIdTecnica" value="">

                <div class="form-group">
                    <label for="descripcion">Nombre / Descripción de la Tecnología</label>
                    <input type="text" id="descripcion" name="descripcion" placeholder="Ej: React, PHP, PostgreSQL..." required>
                </div>

                <div class="form-group">
                    <label for="rol">Categoría / Rol</label>
                    <input type="text" id="rol" name="rol" placeholder="Ej: Frontend, Backend, Base de Datos, DevOps..." required>
                </div>

                <div class="modal-actions">
                    <button type="button" onclick="cerrarModal()" class="btn-secondary">Cancelar</button>
                    <button type="submit" id="btnSubmit" class="btn-primary">Guardar Tecnología</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL POPUP DE SOPORTE -->
    <div id="modalSoporte" class="modal-overlay">
        <div class="modal-card" style="max-width: 500px;">
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

- Módulo / Sección: Técnicas
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

    <!-- JAVASCRIPT PARA CONTROLAR MODALES Y EDICIÓN -->
    <script>
        // Funciones para el Modal de Soporte
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

        document.getElementById('plantillaCorreo')?.addEventListener('input', actualizarEnlaceMailto);

        // Funciones para el Modal de Técnica
        function abrirModalCrear() {
            document.getElementById('modalTitle').textContent = "Nueva Tecnología";
            document.getElementById('formAccion').value = "crear";
            document.getElementById('formIdTecnica').value = "";
            document.getElementById('descripcion').value = "";
            document.getElementById('rol').value = "";
            document.getElementById('btnSubmit').textContent = "Guardar Tecnología";
            document.getElementById('modalTecnica').classList.add('active');
        }

        function abrirModalEditar(id, descripcion, rol) {
            document.getElementById('modalTitle').textContent = "Editar Tecnología";
            document.getElementById('formAccion').value = "editar";
            document.getElementById('formIdTecnica').value = id;
            document.getElementById('descripcion').value = descripcion;
            document.getElementById('rol').value = rol;
            document.getElementById('btnSubmit').textContent = "Actualizar Tecnología";
            document.getElementById('modalTecnica').classList.add('active');
        }

        function cerrarModal() {
            document.getElementById('modalTecnica').classList.remove('active');
        }

        // Cierre general de modales al hacer clic fuera de ellos
        window.onclick = function(event) {
            const modalTecnica = document.getElementById('modalTecnica');
            const modalSoporte = document.getElementById('modalSoporte');
            if (event.target === modalTecnica) {
                cerrarModal();
            }
            if (event.target === modalSoporte) {
                cerrarModalSoporte();
            }
        }
    </script>
</body>
</html>
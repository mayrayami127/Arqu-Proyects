<?php
require_once '../conexion.php';

$project_ref = "gaerzvsvjbkzfpznkvye";
$apiKey      = "sb_publishable_SEYe6-WdhwUF7iXkkGF2Fg_V00qUFrS";
$baseUrl     = "https://{$project_ref}.supabase.co/rest/v1/";

$mensaje = "";

// PROCESAR ACCIONES (CREAR, EDITAR, ELIMINAR)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion =$_POST['accion'] ?? '';

    // 1. ELIMINAR LÍDER
    if ($accion === 'eliminar') {
        $id_lider =$_POST['id_lider'] ?? '';
        if (!empty($id_lider)) {$url = $baseUrl . "lider?id_lider=eq." . urlencode($id_lider);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "apikey: {$apiKey}",
                "Authorization: Bearer {$apiKey}"
            ]);
            $response = curl_exec($ch);
            curl_close($ch);

            header("Location: lideres.php");
            exit;
        }
    }

    // 2. CREAR O EDITAR LÍDER
    $data = [
        'nombre'                => $_POST['nombre'] ?? '',
        'disponibilidadhoraria' => $_POST['disponibilidadhoraria'] ?? ''
    ];

    if (!empty($data['nombre'])) {
        if ($accion === 'editar') {
            // EDITAR (PATCH)
            $id_lider = $_POST['id_lider'] ?? '';$url = $baseUrl . "lider?id_lider=eq." . urlencode($id_lider);
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
                header("Location: lideres.php");
                exit;
            } else {
                $mensaje = "Error al editar el líder.";
            }
        } else {
            // CREAR
            $resultado = supabase_post('lider',$data, $baseUrl,$apiKey);
            if ($resultado) {
                header("Location: lideres.php");
                exit;
            } else {
                $mensaje = "Error al guardar el líder técnico.";
            }
        }
    }
}

// Consultar líderes
$endpoint = "lider?select=*,proyecto(*)";
$lideres  = supabase_get($endpoint,$baseUrl, $apiKey);$totalLideres = is_array($lideres) ? count($lideres) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arqué Projects — Líderes Técnicos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand"><span class="brand-mark">&gt;_</span><span>Arqué Projects</span></div>
            <nav class="main-nav" aria-label="Navegación principal">
                <a class="nav-item" href="inicio.php"><svg viewBox="0 0 24 24"><path d="M4 13h6V4H4v9Zm0 7h6v-4H4v4Zm10 0h6v-9h-6v9Zm0-16v4h6V4h-6Z"/></svg><span>Dashboard</span></a>
                <a class="nav-item" href="proyectos.php"><svg viewBox="0 0 24 24"><path d="M3.5 6.5A2.5 2.5 0 0 1 6 4h4l2 2h6a2.5 2.5 0 0 1 2.5 2.5v8A2.5 2.5 0 0 1 18 19H6a2.5 2.5 0 0 1-2.5-2.5v-10Z"/><path d="M4 9h16"/></svg><span>Proyectos</span></a>
                <a class="nav-item active" href="lideres.php"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="3"/><path d="M5 19c.8-3.1 3.1-5 7-5s6.2 1.9 7 5M18 5.5a2.4 2.4 0 0 1 0 4.8M20 14c1.2.5 1.9 1.4 2.2 2.6"/></svg><span>Líderes Técnicos</span></a>
                <a class="nav-item" href="desarrolladores.php"><svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"/><path d="M3 19c.6-3.1 2.6-5 6-5s5.4 1.9 6 5M16 6.2a2.8 2.8 0 0 1 0 5.6M17 14.3c2.2.7 3.5 2.3 4 4.7"/></svg><span>Desarrolladores</span></a>
                <a class="nav-item" href="organizaciones.php"><svg viewBox="0 0 24 24"><rect x="5" y="3" width="14" height="18" rx="1.5"/><path d="M8 7h2m4 0h2M8 11h2m4 0h2M8 15h2m4 0h2M10 21v-3h4v3"/></svg><span>Organizaciones</span></a>
                <a class="nav-item" href="tecnicas.php"><svg viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg><span>Técnicas</span></a>
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
                <button class="mobile-menu">☰</button>
                <h1>Líderes Técnicos</h1>
            </header>

            <div class="page-content">
                <div class="page-toolbar">
                    <div class="filter-chips">
                        <button class="filter-chip active">Todos <b><?= $totalLideres ?></b></button>
                    </div>
                    <button type="button" class="btn-primary" id="btnOpenModal">+ Nuevo Líder</button>
                </div>

                <?php if ($mensaje): ?>
                    <p style="color: var(--red); padding: 10px 0;"><?= htmlspecialchars($mensaje) ?></p>
                <?php endif; ?>

                <section class="panel">
                    <div class="panel-header"><h2>Líderes Técnicos</h2></div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Líder</th>
                                    <th>Disponibilidad Horaria</th>
                                    <th>Proyectos a Cargo</th>
                                    <th style="text-align: right; padding-right: 16px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (is_array($lideres) && count($lideres) > 0): ?>
                                    <?php foreach ($lideres as$lider): ?>
                                        <?php 
                                            $nombre =$lider['nombre'] ?? 'Sin Nombre';
                                            $partes = explode(' ', trim($nombre));
                                            $iniciales = strtoupper(substr($partes[0], 0, 1) . (isset($partes[1]) ? substr($partes[1], 0, 1) : ''));
                                            $cantProyectos = isset($lider['proyecto']) && is_array($lider['proyecto']) ? count($lider['proyecto']) : 0;
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($lider['id_lider'] ?? '-') ?></td>
                                            <td>
                                                <div class="leader-cell">
                                                    <span class="leader-avatar avatar-s"><?= htmlspecialchars($iniciales) ?></span>
                                                    <span class="leader-details">
                                                        <strong><?= htmlspecialchars($nombre) ?></strong>
                                                    </span>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($lider['disponibilidadhoraria'] ?? 'Sin especificar') ?></td>
                                            <td>
                                                <span class="tech-tag"><?= $cantProyectos ?> proyecto(s)</span>
                                            </td>
                                            <td>
                                                <div class="actions-cell">
                                                    <!-- BOTÓN EDITAR -->
                                                    <button type="button" class="btn-primary btn-sm btn-edit" 
                                                            data-id="<?= $lider['id_lider'] ?>" 
                                                            data-nombre="<?= htmlspecialchars($nombre) ?>" 
                                                            data-dispo="<?= htmlspecialchars($lider['disponibilidadhoraria'] ?? '') ?>">
                                                        Editar
                                                    </button>

                                                    <!-- BOTÓN ELIMINAR -->
                                                    <form action="lideres.php" method="POST" style="display:inline; margin:0;" onsubmit="return confirm('¿Estás seguro de eliminar este líder?');">
                                                        <input type="hidden" name="accion" value="eliminar">
                                                        <input type="hidden" name="id_lider" value="<?= $lider['id_lider'] ?>">
                                                        <button type="submit" class="btn-primary btn-sm btn-danger-sm">Eliminar</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 20px;">No se encontraron líderes registrados.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- Modal Reutilizable Líder -->
    <div class="modal-overlay" id="modalLider">
        <div class="modal-card">
            <div class="modal-header">
                <h2 id="modalTitle">Nuevo Líder Técnico</h2>
                <button type="button" class="btn-close" id="btnCloseX" aria-label="Cerrar">&times;</button>
            </div>
            <form action="lideres.php" method="POST" id="formLider">
                <input type="hidden" name="accion" id="formAccion" value="crear">
                <input type="hidden" name="id_lider" id="formIdLider" value="">

                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre" id="inputNombre" placeholder="Ej: Lucas Martínez" required>
                </div>
                <div class="form-group">
                    <label>Disponibilidad Horaria</label>
                    <input type="text" name="disponibilidadhoraria" id="inputDispo" placeholder="Ej: Tiempo Completo / Mañana" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="btnCancel">Cancelar</button>
                    <button type="submit" class="btn-primary" id="btnSubmit">Guardar Líder</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Popup de Soporte -->
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

- Módulo / Sección: Líderes Técnicos
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

        // Actualizar URL de Gmail en tiempo real al editar el texto del correo
        document.getElementById('plantillaCorreo')?.addEventListener('input', actualizarEnlaceMailto);

        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('modalLider');
            const btnOpen = document.getElementById('btnOpenModal');
            const btnCloseX = document.getElementById('btnCloseX');
            const btnCancel = document.getElementById('btnCancel');
            
            const modalTitle = document.getElementById('modalTitle');
            const formAccion = document.getElementById('formAccion');
            const formIdLider = document.getElementById('formIdLider');
            const inputNombre = document.getElementById('inputNombre');
            const inputDispo = document.getElementById('inputDispo');
            const btnSubmit = document.getElementById('btnSubmit');

            const closeModal = () => {
                modal.classList.remove('active');
            };

            if (btnOpen) {
                btnOpen.addEventListener('click', () => {
                    modalTitle.textContent = "Nuevo Líder Técnico";
                    formAccion.value = "crear";
                    formIdLider.value = "";
                    inputNombre.value = "";
                    inputDispo.value = "";
                    btnSubmit.textContent = "Guardar Líder";
                    modal.classList.add('active');
                });
            }

            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', (e) => {
                    const btn = e.currentTarget;
                    modalTitle.textContent = "Editar Líder Técnico";
                    formAccion.value = "editar";
                    formIdLider.value = btn.dataset.id;
                    inputNombre.value = btn.dataset.nombre;
                    inputDispo.value = btn.dataset.dispo;
                    btnSubmit.textContent = "Actualizar Líder";
                    modal.classList.add('active');
                });
            });

            if (btnCloseX) btnCloseX.addEventListener('click', closeModal);
            if (btnCancel) btnCancel.addEventListener('click', closeModal);
            
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });
        });

        // Cerrar modales al hacer clic fuera
        window.onclick = function(event) {
            const modalSoporte = document.getElementById('modalSoporte');
            if (event.target === modalSoporte) cerrarModalSoporte();
        }
    </script>
</body>
</html>
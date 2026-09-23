<?php

require_once '../conexion.php';
$project_ref = "gaerzvsvjbkzfpznkvye";
$apiKey      = "sb_publishable_SEYe6-WdhwUF7iXkkGF2Fg_V00qUFrS";
$baseUrl     = "https://{$project_ref}.supabase.co/rest/v1/";

// Se cambia 'lider_tecnico' por 'lider'
$endpoint = "proyecto_desarrollador?select=*,proyecto(*,organizacion(*),lider(*)),desarrollador(*),tecnica(*)";
$asignaciones = supabase_get($endpoint, $baseUrl, $apiKey);

// Total de asignaciones registradas
$totalProyectos = (is_array($asignaciones)) ? count($asignaciones) : 0;

// Consultar listas auxiliares
$desarrolladores = supabase_get("desarrollador?select=*", $baseUrl, $apiKey);
$tecnicas        = supabase_get("tecnica?select=*", $baseUrl, $apiKey);
$organizaciones  = supabase_get("organizacion?select=*", $baseUrl, $apiKey);
$lideres         = supabase_get("lider?select=*", $baseUrl, $apiKey); // Cambiado a 'lider'

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arqué Proyects — Asignaciones de Proyectos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand"><span class="brand-mark">&gt;_</span><span>Arqué Proyects</span></div>
            <nav class="main-nav" aria-label="Navegación principal">
                <a class="nav-item" href="inicio.php"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 13h6V4H4v9Zm0 7h6v-4H4v4Zm10 0h6v-9h-6v9Zm0-16v4h6V4h-6Z"/></svg><span>Dashboard</span></a>
                <a class="nav-item active" href="proyectos.php"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3.5 6.5A2.5 2.5 0 0 1 6 4h4l2 2h6a2.5 2.5 0 0 1 2.5 2.5v8A2.5 2.5 0 0 1 18 19H6a2.5 2.5 0 0 1-2.5-2.5v-10Z"/><path d="M4 9h16"/></svg><span>Proyectos</span></a>
                <a class="nav-item" href="lideres.php"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3"/><path d="M5 19c.8-3.1 3.1-5 7-5s6.2 1.9 7 5M18 5.5a2.4 2.4 0 0 1 0 4.8M20 14c1.2.5 1.9 1.4 2.2 2.6"/></svg><span>Líderes Técnicos</span></a>
                <a class="nav-item" href="desarrolladores.php"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3 19c.6-3.1 2.6-5 6-5s5.4 1.9 6 5M16 6.2a2.8 2.8 0 0 1 0 5.6M17 14.3c2.2.7 3.5 2.3 4 4.7"/></svg><span>Desarrolladores</span></a>
                <a class="nav-item" href="organizaciones.php"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="3" width="14" height="18" rx="1.5"/><path d="M8 7h2m4 0h2M8 11h2m4 0h2M8 15h2m4 0h2M10 21v-3h4v3"/></svg><span>Organizaciones</span></a>
                <a class="nav-item" href="tecnicas.php"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg><span>Técnicas</span></a>
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
                <h1>Asignaciones de Proyectos</h1>
                <label class="search-box">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.5 4.5"/></svg>
                    <input type="search" placeholder="Buscar asignaciones...">
                </label>
            </header>

            <div class="page-content">
                <div class="page-toolbar">
                    <div class="filter-chips">
                        <button class="filter-chip active">Total Proyectos <b><?= $totalProyectos ?></b></button>
                    </div>
                    <button type="button" class="btn-primary" id="btnOpenModal">+ Nueva Asignación</button>
                </div>

                <section class="panel">
                    <div class="panel-header"><h2>Gestión de Proyectos</h2></div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>PROYECTO</th>
                                    <th>ORGANIZACIÓN</th>
                                    <th>LÍDER TÉCNICO</th>
                                    <th>DESARROLLADOR</th>
                                    <th>TÉCNICA / ROL</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th>FECHA INICIO</th>
                                    <th>FECHA FIN</th>
                                    <th style="text-align: right;">ACCIONES</th>                               
                                </tr>
                            </thead>
                            <tbody id="tablaAsignaciones">
                                <?php if (is_array($asignaciones) && count($asignaciones) > 0): ?>
                                    <?php foreach ($asignaciones as $item): ?>
                                        <?php 
                                            $idAsig       = $item['id_proyecto_desarrollador'] ?? '';
                                            $projData     = $item['proyecto'] ?? [];
                                            $nomProyecto  = $projData['nombreproyecto'] ?? $projData['nombre'] ?? '';
                                            $descProyecto = $projData['descripcion'] ?? '';
                                            $idOrg        = $projData['id_organizacion'] ?? ($projData['organizacion']['id_organizacion'] ?? '');
                                            
                                            // Corrección: buscar 'nombredeorganizacion' en la tabla guardada
                                            $nomOrg       = $projData['organizacion']['nombredeorganizacion'] ?? $projData['organizacion']['nombre'] ?? 'Sin organización';
                                            
                                            $idLider      = $projData['id_lider'] ?? ($projData['lider']['id_lider'] ?? '');
                                            $nomLider     = $projData['lider']['nombre'] ?? 'Sin líder';
                                            
                                            $idDev        = $item['id_desarrollador'] ?? ($item['desarrollador']['id_desarrollador'] ?? '');
                                            $idTec        = $item['id_tecnica'] ?? ($item['tecnica']['id_tecnica'] ?? '');
                                            $fInicio      = $item['fecha_inicio'] ?? '';
                                            $fFin         = $item['fecha_fin'] ?? '';
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($idAsig ?: '-') ?></td>
                                            <td class="project-name"><strong><?= htmlspecialchars($nomProyecto ?: 'Sin asignar') ?></strong></td>
                                            <td><?= htmlspecialchars($nomOrg) ?></td>
                                            <td><?= htmlspecialchars($nomLider) ?></td>
                                            <td><?= htmlspecialchars($item['desarrollador']['nombre'] ?? 'Sin asignar') ?></td>
                                            <td>
                                                <span class="tech-tag">
                                                    <?= htmlspecialchars($item['tecnica']['descripcion'] ?? $item['tecnica']['nombre'] ?? 'Sin técnica') ?>
                                                    <?= !empty($item['tecnica']['rol']) ? " (" . htmlspecialchars($item['tecnica']['rol']) . ")" : '' ?>
                                                </span>
                                            </td>
                                            <td><small><?= htmlspecialchars($descProyecto ?: '-') ?></small></td>
                                            <td><?= htmlspecialchars($fInicio ?: '-') ?></td>
                                            <td><?= htmlspecialchars($fFin ?: '-') ?></td>
                                            <td>
                                                <div class="actions-cell">
                                                    <button type="button" class="btn-action-edit" 
                                                            data-id="<?= htmlspecialchars($idAsig) ?>"
                                                            data-proyecto="<?= htmlspecialchars($nomProyecto) ?>"
                                                            data-desc="<?= htmlspecialchars($descProyecto) ?>"
                                                            data-org="<?= htmlspecialchars($idOrg) ?>"
                                                            data-lider="<?= htmlspecialchars($idLider) ?>"
                                                            data-dev="<?= htmlspecialchars($idDev) ?>"
                                                            data-tec="<?= htmlspecialchars($idTec) ?>"
                                                            data-finicio="<?= htmlspecialchars($fInicio) ?>"
                                                            data-ffin="<?= htmlspecialchars($fFin) ?>">
                                                        Editar
                                                    </button>
                                                    <button type="button" class="btn-action-delete" 
                                                            data-id="<?= htmlspecialchars($idAsig) ?>">
                                                        Eliminar
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" style="text-align: center; padding: 24px; color: #777;">No se encontraron registros o la base de datos está vacía.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- Modal Crear Asignación -->
    <div class="modal-overlay" id="modalAsignacion">
        <div class="modal-card">
            <div class="modal-header">
                <h2>Nueva Asignación</h2>
                <button type="button" class="btn-close" id="btnCloseX" aria-label="Cerrar">&times;</button>
            </div>
            
            <form id="formNuevaAsignacion">
                <div class="form-group">
                    <label>Nombre del Proyecto</label>
                    <input type="text" id="inputProyecto" name="proyecto" placeholder="Ej: Proyecto Alpha" required>
                </div>

                <div class="form-group">
                    <label>Descripción del Proyecto</label>
                    <input type="text" id="inputDescripcion" placeholder="Escriba una descripción breve">
                </div>

                <div class="form-row" style="display: flex; gap: 12px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Organización</label>
                        <select id="selectOrganizacion" style="padding: 12px 16px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 0.95rem; outline: none; width: 100%;">
                            <option value="">Seleccione Organización</option>
                            <?php if (is_array($organizaciones)): ?>
                                <?php foreach ($organizaciones as$o): ?>
                                    <option value="<?= $o['id_organizacion'] ?>">
                                        <?= htmlspecialchars($o['nombredeorganizacion'] ?? $o['nombre'] ?? 'Sin nombre') ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label>Líder Técnico</label>
                        <select id="selectLider" style="padding: 12px 16px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 0.95rem; outline: none; width: 100%;">
                            <option value="">Seleccione Líder Técnico</option>
                            <?php if (is_array($lideres)): ?>
                                <?php foreach ($lideres as$l): ?>
                                    <option value="<?= $l['id_lider'] ?>"><?= htmlspecialchars($l['nombre']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Desarrollador</label>
                    <select id="selectDesarrollador" name="id_desarrollador" required style="padding: 12px 16px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 0.95rem; outline: none; width: 100%;">
                        <option value="">Seleccione desarrollador</option>
                        <?php if (is_array($desarrolladores)): ?>
                            <?php foreach ($desarrolladores as$d): ?>
                                <option value="<?= $d['id_desarrollador'] ?>"><?= htmlspecialchars($d['nombre']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Técnica / Rol</label>
                    <select id="selectTecnica" name="id_tecnica" style="padding: 12px 16px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 0.95rem; outline: none; width: 100%;">
                        <option value="">Seleccione técnica (opcional)</option>
                        <?php if (is_array($tecnicas)): ?>
                            <?php foreach ($tecnicas as$t): ?>
                                <option value="<?= $t['id_tecnica'] ?>"><?= htmlspecialchars($t['descripcion'] ?? $t['nombre']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-row" style="display: flex; gap: 12px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Fecha Inicio</label>
                        <input type="date" id="inputFechaInicio" name="fecha_inicio" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Fecha Fin</label>
                        <input type="date" id="inputFechaFin" name="fecha_fin">
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="btnCancel">Cancelar</button>
                    <button type="submit" class="btn-primary" id="btnSubmitForm">Guardar Asignación</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Asignación -->
    <div class="modal-overlay" id="modalEditarAsignacion">
        <div class="modal-card">
            <div class="modal-header">
                <h2>Editar Asignación</h2>
                <button type="button" class="btn-close" id="btnCloseEditX" aria-label="Cerrar">&times;</button>
            </div>
            
            <form id="formEditarAsignacion">
                <input type="hidden" id="editIdAsig">
                <div class="form-group">
                    <label>Nombre del Proyecto</label>
                    <input type="text" id="editProyecto" required placeholder="Nombre del proyecto">
                </div>

                <div class="form-group">
                    <label>Descripción del Proyecto</label>
                    <input type="text" id="editDescripcion" placeholder="Descripción breve del proyecto">
                </div>

                <div class="form-row" style="display: flex; gap: 12px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Organización</label>
                        <select id="editOrganizacion" style="padding: 12px 16px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 0.95rem; outline: none; width: 100%;">
                            <option value="">Seleccione Organización</option>
                            <?php if (is_array($organizaciones)): ?>
                                <?php foreach ($organizaciones as$o): ?>
                                    <option value="<?= $o['id_organizacion'] ?>">
                                        <?= htmlspecialchars($o['nombredeorganizacion'] ?? $o['nombre'] ?? 'Sin nombre') ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label>Líder Técnico</label>
                        <select id="editLider" style="padding: 12px 16px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 0.95rem; outline: none; width: 100%;">
                            <option value="">Seleccione Líder Técnico</option>
                            <?php if (is_array($lideres)): ?>
                                <?php foreach ($lideres as$l): ?>
                                    <option value="<?= $l['id_lider'] ?>"><?= htmlspecialchars($l['nombre']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Desarrollador</label>
                    <select id="editDesarrollador" required style="padding: 12px 16px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 0.95rem; outline: none; width: 100%;">
                        <option value="">Seleccione desarrollador</option>
                        <?php if (is_array($desarrolladores)): ?>
                            <?php foreach ($desarrolladores as$d): ?>
                                <option value="<?= $d['id_desarrollador'] ?>"><?= htmlspecialchars($d['nombre']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Técnica / Rol</label>
                    <select id="editTecnica" style="padding: 12px 16px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 0.95rem; outline: none; width: 100%;">
                        <option value="">Seleccione técnica (opcional)</option>
                        <?php if (is_array($tecnicas)): ?>
                            <?php foreach ($tecnicas as$t): ?>
                                <option value="<?= $t['id_tecnica'] ?>"><?= htmlspecialchars($t['descripcion'] ?? $t['nombre']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-row" style="display: flex; gap: 12px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Fecha Inicio</label>
                        <input type="date" id="editFechaInicio" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Fecha Fin</label>
                        <input type="date" id="editFechaFin">
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="btnCancelEdit">Cancelar</button>
                    <button type="submit" class="btn-primary" id="btnSubmitEdit">Guardar Cambios</button>
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

Tengo una consulta / reporte sobre la plataforma Arqué Proyects:

- Módulo / Sección: Proyectos
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
        const BASE_URL = "<?= $baseUrl ?>";
        const API_KEY = "<?= $apiKey ?>";
        const HEADERS = {
            'apikey': API_KEY,
            'Authorization': `Bearer ${API_KEY}`,
            'Content-Type': 'application/json',
            'Prefer': 'return=representation'
        };

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
            const asunto = encodeURIComponent("[Soporte] Consulta / Incidencia en Arqué Proyects");
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

        // Evento para actualizar URL de Gmail en tiempo real si edita la plantilla
        document.getElementById('plantillaCorreo')?.addEventListener('input', actualizarEnlaceMailto);

        document.addEventListener('DOMContentLoaded', () => {
            // Modal Crear
            const modal = document.getElementById('modalAsignacion');
            const btnOpen = document.getElementById('btnOpenModal');
            const btnCloseX = document.getElementById('btnCloseX');
            const btnCancel = document.getElementById('btnCancel');
            const form = document.getElementById('formNuevaAsignacion');

            if (btnOpen && modal) {
                btnOpen.addEventListener('click', (e) => {
                    e.preventDefault();
                    modal.classList.add('active');
                });

                const closeModal = () => {
                    modal.classList.remove('active');
                    form.reset();
                };

                btnCloseX?.addEventListener('click', closeModal);
                btnCancel?.addEventListener('click', closeModal);
                modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

                // Crear Asignación
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const submitBtn = document.getElementById('btnSubmitForm');
                    submitBtn.disabled = true;
                    submitBtn.innerText = 'Guardando...';

                    const nombreProyecto = document.getElementById('inputProyecto').value.trim();
                    const descProyecto = document.getElementById('inputDescripcion').value.trim();
                    const idOrg = document.getElementById('selectOrganizacion').value || null;
                    const idLider = document.getElementById('selectLider').value || null;

                    const idDesarrollador = document.getElementById('selectDesarrollador').value;
                    const idTecnica = document.getElementById('selectTecnica').value || null;
                    const fechaInicio = document.getElementById('inputFechaInicio').value;
                    const fechaFin = document.getElementById('inputFechaFin').value || null;

                    try {
                        let idProyecto = await obtenerOCrearProyecto(nombreProyecto, descProyecto, idOrg, idLider);
                        if (idProyecto) {
                            await fetch(`${BASE_URL}proyecto_desarrollador`, {
                                method: 'POST',
                                headers: HEADERS,
                                body: JSON.stringify({
                                    id_proyecto: idProyecto,
                                    id_desarrollador: parseInt(idDesarrollador),
                                    id_tecnica: idTecnica ? parseInt(idTecnica) : null,
                                    fecha_inicio: fechaInicio,
                                    fecha_fin: fechaFin
                                })
                            });
                        }
                        closeModal();
                        window.location.reload();
                    } catch (err) {
                        console.error('Error al guardar:', err);
                        alert('Error al guardar la asignación.');
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.innerText = 'Guardar Asignación';
                    }
                });
            }

            // Modal Editar
            const modalEdit = document.getElementById('modalEditarAsignacion');
            const btnCloseEditX = document.getElementById('btnCloseEditX');
            const btnCancelEdit = document.getElementById('btnCancelEdit');
            const formEdit = document.getElementById('formEditarAsignacion');

            const closeEditModal = () => {
                modalEdit.classList.remove('active');
                formEdit.reset();
            };

            btnCloseEditX?.addEventListener('click', closeEditModal);
            btnCancelEdit?.addEventListener('click', closeEditModal);
            modalEdit?.addEventListener('click', (e) => { if (e.target === modalEdit) closeEditModal(); });

            document.querySelectorAll('.btn-action-edit').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.getElementById('editIdAsig').value = btn.dataset.id;
                    document.getElementById('editProyecto').value = btn.dataset.proyecto;
                    document.getElementById('editDescripcion').value = btn.dataset.desc;
                    document.getElementById('editOrganizacion').value = btn.dataset.org;
                    document.getElementById('editLider').value = btn.dataset.lider;
                    document.getElementById('editDesarrollador').value = btn.dataset.dev;
                    document.getElementById('editTecnica').value = btn.dataset.tec;
                    document.getElementById('editFechaInicio').value = btn.dataset.finicio;
                    document.getElementById('editFechaFin').value = btn.dataset.ffin;
                    modalEdit.classList.add('active');
                });
            });

            // Guardar Cambios de Edición
            formEdit?.addEventListener('submit', async (e) => {
                e.preventDefault();
                const submitBtn = document.getElementById('btnSubmitEdit');
                submitBtn.disabled = true;
                submitBtn.innerText = 'Guardando...';

                const idAsig = document.getElementById('editIdAsig').value;
                const nombreProyecto = document.getElementById('editProyecto').value.trim();
                const descProyecto = document.getElementById('editDescripcion').value.trim();
                const idOrg = document.getElementById('editOrganizacion').value || null;
                const idLider = document.getElementById('editLider').value || null;

                const idDesarrollador = document.getElementById('editDesarrollador').value;
                const idTecnica = document.getElementById('editTecnica').value || null;
                const fechaInicio = document.getElementById('editFechaInicio').value;
                const fechaFin = document.getElementById('editFechaFin').value || null;

                try {
                    let idProyecto = await obtenerOCrearProyecto(nombreProyecto, descProyecto, idOrg, idLider);
                    if (idProyecto && idAsig) {
                        await fetch(`${BASE_URL}proyecto_desarrollador?id_proyecto_desarrollador=eq.${idAsig}`, {
                            method: 'PATCH',
                            headers: HEADERS,
                            body: JSON.stringify({
                                id_proyecto: idProyecto,
                                id_desarrollador: parseInt(idDesarrollador),
                                id_tecnica: idTecnica ? parseInt(idTecnica) : null,
                                fecha_inicio: fechaInicio,
                                fecha_fin: fechaFin
                            })
                        });
                    }
                    closeEditModal();
                    window.location.reload();
                } catch (err) {
                    console.error('Error al editar:', err);
                    alert('Error al actualizar la asignación.');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Guardar Cambios';
                }
            });

            // Eliminar Asignación
            document.querySelectorAll('.btn-action-delete').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const id = btn.dataset.id;
                    if (confirm('¿Estás seguro de que deseas eliminar esta asignación?')) {
                        try {
                            await fetch(`${BASE_URL}proyecto_desarrollador?id_proyecto_desarrollador=eq.${id}`, {
                                method: 'DELETE',
                                headers: HEADERS
                            });
                            window.location.reload();
                        } catch (err) {
                            console.error('Error al eliminar:', err);
                        }
                    }
                });
            });

            // Función para buscar, crear o actualizar el proyecto
            async function obtenerOCrearProyecto(nombre, descripcion, idOrg, idLider) {
                let res = await fetch(`${BASE_URL}proyecto?nombreproyecto=eq.${encodeURIComponent(nombre)}`, { headers: HEADERS });
                let data = await res.json();
                
                let bodyPayload = {
                    nombreproyecto: nombre,
                    descripcion: descripcion || null,
                    id_organizacion: idOrg ? parseInt(idOrg) : null,
                    id_lider: idLider ? parseInt(idLider) : null
                };

                if (Array.isArray(data) && data.length > 0) {
                    let idProj = data[0].id_proyecto;
                    await fetch(`${BASE_URL}proyecto?id_proyecto=eq.${idProj}`, {
                        method: 'PATCH',
                        headers: HEADERS,
                        body: JSON.stringify(bodyPayload)
                    });
                    return idProj;
                } else {
                    let createRes = await fetch(`${BASE_URL}proyecto`, {
                        method: 'POST',
                        headers: HEADERS,
                        body: JSON.stringify(bodyPayload)
                    });
                    let newProj = await createRes.json();
                    return (Array.isArray(newProj) && newProj.length > 0) ? newProj[0].id_proyecto : null;
                }
            }
        });

        // Cerrar modales al hacer clic fuera
        window.onclick = function(event) {
            const modalSoporte = document.getElementById('modalSoporte');
            if (event.target === modalSoporte) cerrarModalSoporte();
        }
    </script>
</body>
</html>
<?php
require_once '../conexion.php';

$project_ref = "gaerzvsvjbkzfpznkvye";
$apiKey      = "sb_publishable_SEYe6-WdhwUF7iXkkGF2Fg_V00qUFrS";
$baseUrl     = "https://{$project_ref}.supabase.co/rest/v1/";

$mensaje = "";

// Procesar envío del formulario cuando se envía el modal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {$data = [
        'nombre'       => $_POST['nombre'] ?? '',
        'dni'          => $_POST['dni'] ?? '',
        'especialidad' => $_POST['especialidad'] ?? ''
    ];

    if (!empty($data['nombre'])) {
        $resultado = supabase_post('desarrollador',$data, $baseUrl,$apiKey);
        if ($resultado) {
            header("Location: desarrolladores.php");
            exit;
        } else {
            $mensaje = "Error al guardar el desarrollador.";
        }
    }
}

// Consultar la lista de desarrolladores en Supabase
$desarrolladores = supabase_get("desarrollador?select=*", $baseUrl, $apiKey);$totalDevs = $desarrolladores ? count($desarrolladores) : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arqué Proyects — Desarrolladores</title>
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
                <a class="nav-item active" href="desarrolladores.php"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3 19c.6-3.1 2.6-5 6-5s5.4 1.9 6 5M16 6.2a2.8 2.8 0 0 1 0 5.6M17 14.3c2.2.7 3.5 2.3 4 4.7"/></svg><span>Desarrolladores</span></a>
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
                <h1>Desarrolladores</h1>
                <label class="search-box"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.5 4.5"/></svg><input type="search" placeholder="Buscar desarrolladores..."></label>
            </header>

            <div class="page-content">
                <div class="page-toolbar">
                    <div class="filter-chips">
                        <button class="filter-chip active">Todos <b><?= $totalDevs ?></b></button>
                    </div>
                    <button class="btn-primary" onclick="abrirModalCrear()">+ Nuevo Desarrollador</button>
                </div>

                <?php if ($mensaje): ?>
                    <p style="color: red; padding: 10px 0;"><?= htmlspecialchars($mensaje) ?></p>
                <?php endif; ?>

                <section class="panel">
                    <div class="panel-header"><h2>Equipo de Desarrollo</h2></div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Desarrollador</th>
                                    <th>DNI / Identificación</th>
                                    <th>Especialidad</th>
                                    <th style="text-align: right; padding-right: 16px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($desarrolladores && count($desarrolladores) > 0): ?>
                                    <?php foreach ($desarrolladores as$dev): ?>
                                        <?php 
                                            $nombre =$dev['nombre'] ?? 'Sin Nombre';
                                            $partes = explode(' ', trim($nombre));
                                            $iniciales = strtoupper(substr($partes[0], 0, 1) . (isset($partes[1]) ? substr($partes[1], 0, 1) : ''));
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($dev['id_desarrollador'] ?? '') ?></td>
                                            <td>
                                                <div class="leader-cell">
                                                    <span class="leader-avatar avatar-s"><?= htmlspecialchars($iniciales) ?></span>
                                                    <span class="leader-details">
                                                        <strong><?= htmlspecialchars($nombre) ?></strong>
                                                    </span>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($dev['dni'] ?? '-') ?></td>
                                            <td>
                                                <?php if (!empty($dev['especialidad'])): ?>
                                                    <span class="tech-tag"><?= htmlspecialchars($dev['especialidad']) ?></span>
                                                <?php else: ?>
                                                    <small>Sin definir</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="actions-cell">
                                                    <button type="button" class="btn-primary btn-sm" 
                                                            onclick="abrirModalEditar('<?= $dev['id_desarrollador'] ?>', '<?= htmlspecialchars($dev['nombre'] ?? '', ENT_QUOTES) ?>', '<?= htmlspecialchars($dev['dni'] ?? '', ENT_QUOTES) ?>', '<?= htmlspecialchars($dev['especialidad'] ?? '', ENT_QUOTES) ?>')">
                                                        Editar
                                                    </button>
                                                    <form action="desarrolladores.php" method="POST" style="display:inline; margin:0;" onsubmit="return confirm('¿Estás seguro de eliminar a este desarrollador?');">
                                                        <input type="hidden" name="accion" value="eliminar">
                                                        <input type="hidden" name="id_desarrollador" value="<?= $dev['id_desarrollador'] ?>">
                                                        <button type="submit" class="btn-primary btn-sm btn-danger-sm">Eliminar</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 20px;">No se encontraron desarrolladores registrados.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- MODAL POPUP DINÁMICO DE DESARROLLADOR -->
    <div id="modalDev" class="modal-overlay">
        <div class="modal-container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 id="modalTitle" style="margin: 0; font-size: 1.25rem;">Nuevo Desarrollador</h2>
                <button onclick="cerrarModal()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #888;">✕</button>
            </div>

            <form method="POST" action="desarrolladores.php" style="display: flex; flex-direction: column; gap: 16px;">
                <input type="hidden" name="accion" id="formAccion" value="crear">
                <input type="hidden" name="id_desarrollador" id="formIdDev" value="">

                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label for="nombre"><strong>Nombre Completo</strong></label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej: Lucas Martínez" required style="padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
                </div>

                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label for="dni"><strong>DNI / Documento</strong></label>
                    <input type="text" id="dni" name="dni" placeholder="Ej: 45892011" required style="padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
                </div>

                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label for="especialidad"><strong>Especialidad</strong></label>
                    <input type="text" id="especialidad" name="especialidad" placeholder="Ej: Frontend / Backend / Fullstack" required style="padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="button" onclick="cerrarModal()" class="filter-chip" style="cursor: pointer;">Cancelar</button>
                    <button type="submit" id="btnSubmit" class="btn-primary" style="cursor: pointer;">Guardar Desarrollador</button>
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
Asunto: [Soporte] Consulta / Incidencia en Arqué Proyects

Hola, equipo de soporte:

Tengo una consulta / reporte sobre la plataforma Arqué Proyects:

- Módulo / Sección: Desarrolladores
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
    // Funciones Modal Desarrollador
    function abrirModalCrear() {
        document.getElementById('modalTitle').textContent = "Nuevo Desarrollador";
        document.getElementById('formAccion').value = "crear";
        document.getElementById('formIdDev').value = "";
        document.getElementById('nombre').value = "";
        document.getElementById('dni').value = "";
        document.getElementById('especialidad').value = "";
        document.getElementById('btnSubmit').textContent = "Guardar Desarrollador";
        document.getElementById('modalDev').classList.add('active');
    }

    function abrirModalEditar(id, nombre, dni, especialidad) {
        document.getElementById('modalTitle').textContent = "Editar Desarrollador";
        document.getElementById('formAccion').value = "editar";
        document.getElementById('formIdDev').value = id;
        document.getElementById('nombre').value = nombre;
        document.getElementById('dni').value = dni;
        document.getElementById('especialidad').value = especialidad;
        document.getElementById('btnSubmit').textContent = "Actualizar Desarrollador";
        document.getElementById('modalDev').classList.add('active');
    }

    function cerrarModal() {
        document.getElementById('modalDev').classList.remove('active');
    }

    // Funciones Modal Soporte (Corregido)
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
    
    // Abre directamente la ventana de redactar en Gmail Web
    btnMailto.href = `https://mail.google.com/mail/?view=cm&fs=1&to=${email}&su=${asunto}&body=${cuerpo}`;
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

    // Escuchar cambios en el textarea para actualizar la URL del mailto en tiempo real
    document.getElementById('plantillaCorreo').addEventListener('input', actualizarEnlaceMailto);

    // Cerrar modales al hacer clic fuera
    window.onclick = function(event) {
        const modalDev = document.getElementById('modalDev');
        const modalSoporte = document.getElementById('modalSoporte');
        if (event.target === modalDev) cerrarModal();
        if (event.target === modalSoporte) cerrarModalSoporte();
    }
</script>
</body>
</html>
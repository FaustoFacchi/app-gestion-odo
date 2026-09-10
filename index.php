<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OdontoApp Pro - Sistema Clínico</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <style>
        /* ==========================================================
           1. ESTILOS GENERALES Y LAYOUT
           ========================================================== */
        body { 
            background-color: #f1f5f9; 
            font-family: system-ui, sans-serif; 
        }
        
        .sidebar { 
            min-height: 100vh; 
            background-color: #0f172a; 
            color: white; 
            display: flex; 
            flex-direction: column; 
            justify-content: space-between; 
        }
        
        .sidebar .nav-link { 
            color: #94a3b8; 
            padding: 12px 20px; 
            font-weight: 500; 
        }
        
        .sidebar .nav-link.active, 
        .sidebar .nav-link:hover { 
            color: white; 
            background-color: #1e293b; 
            border-radius: 8px; 
        }

        /* ==========================================================
           2. REFUERZO DE BOTONES DE FULLCALENDAR
           ========================================================== */
        .fc .fc-button-primary {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
            color: #ffffff !important;
            opacity: 1 !important;
        }

        .fc .fc-button-primary:hover {
            background-color: #0b5ed7 !important;
            border-color: #0a58ca !important;
        }

        .fc .fc-button-primary:disabled {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            opacity: 0.65 !important;
        }

        /* ==========================================================
           3. TARJETAS DE EVENTOS EN EL CALENDARIO
           ========================================================== */
        .fc-timegrid-event {
            border-radius: 6px !important;
            border: none !important;
            border-left: 5px solid rgba(0, 0, 0, 0.25) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08) !important;
            padding: 2px 6px !important;
        }

        .fc-timegrid-event .fc-event-main {
            padding: 2px !important;
        }

        .fc-timegrid-event .fc-event-title {
            font-weight: 600;
            font-size: 0.85rem;
        }

        .fc-timegrid-event .fc-event-time {
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* ==========================================================
           4. CABECERA PERSONALIZADA
           ========================================================== */
        .fc-col-header-cell-cushion {
            text-decoration: none !important;
            padding: 10px 0 12px 0 !important;
            width: 100%;
        }

        .gc-header-cell {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2px;
            width: 100%;
        }

        .gc-day-name {
            font-size: 0.725rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .gc-day-number {
            font-size: 1.6rem;
            line-height: 1;
            font-family: 'Google Sans', Roboto, Arial, sans-serif;
        }

        .gc-past .gc-day-name,
        .gc-past .gc-day-number {
            color: #b6b6b6 !important;
            font-weight: 400 !important;
        }

        .gc-future .gc-day-name {
            color: #020202 !important;
            font-weight: 600 !important;
        }
        
        .gc-future .gc-day-number {
            color: #1a1b1b !important;
            font-weight: 700 !important;
        }

        .gc-today-cell .gc-day-name,
        .gc-today-cell .gc-day-number {
            color: #328bff !important;
            font-weight: 700 !important;
        }

        /* ==========================================================
           5. LIMPIEZA DE RENGLONES
           ========================================================== */
        .fc-theme-standard .fc-timegrid-slots td {
            border-top: none !important;
        }

        .fc-timegrid-slot-lane:nth-child(4n+1) {
            border-top: 1px solid #e2e8f0 !important;
        }

        .fc-theme-standard td, 
        .fc-theme-standard th {
            border-color: #cbd5e1 !important;
        }

        /* ==========================================================
           6. ODONTOGRAMA ANATÓMICO (5 caras)
           ========================================================== */
        .tooth-box { 
            display: inline-block; 
            text-align: center; 
            margin: 4px; 
        }
        
        .tooth-number { 
            font-size: 12px; 
            font-weight: bold; 
            margin-bottom: 2px; 
        }
        
        .tooth-svg { 
            width: 42px; 
            height: 42px; 
            cursor: pointer; 
        }
        
        .tooth-face { 
            fill: #ffffff; 
            stroke: #475569; 
            stroke-width: 1.5; 
            transition: fill 0.2s; 
        }
        
        .tooth-face:hover { 
            fill: #cbd5e1; 
        }
        
        .tooth-face.tratado { 
            fill: #0d6efd; 
        }
        
        .tooth-face.pendiente { 
            fill: #dc3545; 
        }
        
        .tooth-face.activa-seleccion { 
            fill: #ffc107 !important; 
        }

        /* ==========================================================
           7. MODALES Y CAPAS
           ========================================================== */
        #modalNuevoTurno .modal-dialog,
        #modalNuevoTurno .modal-content,
        #modalNuevoTurno .modal-body {
            overflow: visible !important;
        }

        #listaResultadosPacientes {
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 1060 !important;
            max-height: 220px !important;
            overflow-y: auto !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- BARRA LATERAL (Navegación) -->
        <div class="col-md-2 sidebar p-3">
            <div>
                <h4 class="fw-bold text-white mt-3 mb-4">
                    <img src="img/muela.png" alt="Logo" style="width: 30px; height: 30px; object-fit: contain; position: relative; top: -3px; left: 5px;" class="me-2">OdontoApp
                </h4>
                <ul class="nav nav-pills flex-column gap-2" id="mainTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active w-100 text-start" data-bs-toggle="tab" data-bs-target="#tab-agenda">
                            <i class="bi bi-calendar3 me-2"></i>Agenda y Turnos
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link w-100 text-start" data-bs-toggle="tab" data-bs-target="#tab-pacientes">
                            <i class="bi bi-people-fill me-2"></i>Pacientes
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link w-100 text-start d-flex align-items-center justify-content-between" data-bs-toggle="tab" data-bs-target="#tab-clinica">
                            <span>
                                <i class="bi bi-journal-medical me-2"></i>Clínica
                            </span>
                        </button>
                    </li>
                </ul>
            </div>

            <!-- PERFIL DE USUARIO Y LOGOUT -->
            <div class="pt-3 border-top border-secondary mt-4">
                <div class="d-flex align-items-center mb-2 text-white">
                    <i class="bi bi-person-circle fs-4 me-2"></i>
                    <div class="text-truncate">
                        <small class="d-block" style="font-size: 11px; color: #9b9b9b;">PROFESIONAL</small>
                        <span class="fw-bold small"><?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario'); ?></span>
                    </div>
                </div>
                <a href="logout.php" class="btn btn-outline-danger btn-sm w-100 fw-bold mt-2">
                    <i class="bi bi-box-arrow-left me-1"></i> Cerrar Sesión
                </a>
            </div>
        </div>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="col-md-10 p-4">
            <div class="tab-content">
                
                <!-- 1. VISTA DE CALENDARIO -->
                <div class="tab-pane fade show active" id="tab-agenda">
                    <div class="card shadow-sm p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="fw-bold m-0"><i class="bi bi-calendar-week me-2"></i>Agenda del Consultorio</h4>
                            
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input" type="checkbox" id="switchModoEdicion" style="cursor: pointer;">
                                    <label class="form-check-label small fw-semibold text-muted" for="switchModoEdicion" style="cursor: pointer;">Modo Edición</label>
                                </div>

                                <button class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalNuevoTurno">+ Agendar Turno</button>
                            </div>
                        </div>
                        <div id="calendar"></div>
                    </div>
                </div>

                <!-- 2. VISTA DE REGISTRO COMPLETO DE PACIENTES -->
                <div class="tab-pane fade" id="tab-pacientes">
                    <div class="card shadow-sm p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="fw-bold m-0"><i class="bi bi-people me-2"></i>Base de Datos de Pacientes</h4>
                            <button class="btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#modalNuevoPaciente">+ Nuevo Paciente</button>
                        </div>
                        <div class="table-responsive">
                            <table id="tablaPacientes" class="table table-striped table-hover align-middle w-100">
                                <thead class="table-dark">
                                    <tr>
                                        <th>DNI</th>
                                        <th>Nombre y Apellido</th>
                                        <th>Teléfono</th>
                                        <th>Obra Social</th>
                                        <th>Alertas Médicas</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Carga automatizada vía AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 3. VISTA DE MÓDULO CLÍNICO (FICHA Y ANAMNESIS) -->
                <div class="tab-pane fade" id="tab-clinica">
                    
                    <!-- BLOQUE DE SELECCIÓN DE PACIENTE (PASOS PREVIOS) -->
                    <div class="card shadow-sm p-4 mb-4" id="bloqueSeleccionPacienteClinica">
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                            <h5 class="fw-bold m-0"><i class="bi bi-person-bounding-box me-2"></i>Selección de Paciente para Ficha Clínica</h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="switchPacienteNuevoClinica">
                                <label class="form-check-label fw-bold text-primary" for="switchPacienteNuevoClinica">Cargar Nuevo Paciente</label>
                            </div>
                        </div>

                        <!-- OPCIÓN A: PACIENTE EXISTENTE -->
                        <div id="contenedorPacienteExistenteClinica">
                            <label class="form-label fw-semibold">Buscar Paciente Existente</label>
                            <div class="position-relative mb-3">
                                <input type="text" id="inputBuscarPacienteClinica" class="form-control" placeholder="Escribí Nombre, Apellido o DNI...">
                                <button type="button" class="btn btn-sm btn-secondary position-absolute end-0 top-0 mt-1 me-1 d-none" id="btnLimpiarPacienteClinica">Limpiar</button>
                                <div id="resultadosBusquedaPacienteClinica" class="list-group position-absolute w-100 shadow d-none" style="z-index: 1050; max-height: 200px; overflow-y: auto;"></div>
                            </div>

                            <div id="fichaPacienteSeleccionadoClinica" class="p-3 bg-light rounded border mb-3 d-none">
                                <div class="fw-bold text-dark fs-5" id="labelNombrePacienteSeleccionado">-</div>
                                <div class="small text-muted mb-2" id="labelDetallesPacienteSeleccionado">-</div>
                                <button type="button" class="btn btn-primary fw-bold" id="btnContinuarClinica">
                                    <i class="bi bi-arrow-right-circle me-1"></i> Abrir Ficha Clínica
                                </button>
                            </div>
                        </div>

                        <!-- OPCIÓN B: PACIENTE NUEVO -->
                        <div id="contenedorPacienteNuevoClinica" class="d-none">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nombre *</label>
                                    <input type="text" id="nuevoNombreClinica" class="form-control" placeholder="Ej: Juan">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Apellido *</label>
                                    <input type="text" id="nuevoApellidoClinica" class="form-control" placeholder="Ej: Pérez">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">DNI *</label>
                                    <input type="text" id="nuevoDniClinica" class="form-control" placeholder="Sin puntos">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Teléfono</label>
                                    <input type="text" id="nuevoTelefonoClinica" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Obra Social</label>
                                    <input type="text" id="nuevoObraSocialClinica" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-danger">Alertas Médicas / Alergias</label>
                                    <textarea id="nuevoAlertasClinica" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="mt-3 text-end" id="contenedorBtnNuevoPaciente">
                                <button type="button" class="btn btn-success fw-bold" id="btnContinuarNuevoClinica">
                                    <i class="bi bi-floppy me-1"></i> Guardar Paciente
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- BLOQUE DE CONFIRMACIÓN DEL PACIENTE ACTIVO -->
                    <div id="bloquePacienteConfirmado" class="card shadow-sm p-3 mb-4 bg-primary text-white d-none">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-light text-primary mb-1 fw-bold">PACIENTE SELECCIONADO</span>
                                <h4 class="fw-bold m-0" id="labelNombreConfirmado">-</h4>
                                <small id="labelDetallesConfirmados" class="opacity-75">-</small>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm fw-bold" id="btnVolverSeleccionPaciente">
                                <i class="bi bi-arrow-left-circle me-1"></i> Cambiar Paciente
                            </button>
                        </div>
                    </div>

                    <!-- CONTENEDOR DE FICHA CLÍNICA ACTIVA -->
                    <div id="seccionesClinicaContenido" class="d-none">

                        <!-- 1. CUESTIONARIO DE SALUD / ANAMNESIS -->
                        <div class="card shadow-sm border-0 mb-4" id="cardAnamnesis">
                            
                            <!-- ENCABEZADO CON BOTÓN EDITAR -->
                            <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-file-earmark-medical me-2"></i>Anamnesis Odontológica (Cuestionario de Salud)</span>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-warning btn-sm fw-bold d-none" id="btnEditarAnamnesis">
                                        <i class="bi bi-pencil-square me-1"></i> Editar
                                    </button>
                                    <span class="badge bg-secondary font-monospace">Paso 1 de 2</span>
                                </div>
                            </div>

                            <!-- CUERPO DE LA TARJETA (ENVOLVIENDO CON UN FORM) -->
                            <div class="card-body" id="bodyAnamnesis">
                                
                                <form id="formAnamnesis">
                                    <!-- CAMPO OCULTO QUE GUARDA EL ID DEL PACIENTE -->
                                    <input type="hidden" name="paciente_id" id="paciente_id" value="">
                                    <input type="hidden" name="idPacienteSeleccionadoClinica" id="idPacienteSeleccionadoClinica" value="">
                                    <input type="hidden" name="id_paciente" id="id_paciente" value="">

                                    <!-- SECCIÓN: SALUD GENERAL -->
                                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                                        <i class="bi bi-activity me-1"></i> 1. Salud General
                                    </h6>

                                    <div class="row g-3 mb-4">
                                        
                                        <!-- TRATAMIENTO MÉDICO -->
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded border h-100">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input me-2" type="checkbox" id="sg_tratamiento" onchange="toggleCampoTexto('sg_tratamiento', 'box_sg_tratamiento_txt')">
                                                    <label class="form-check-label fw-semibold text-dark" for="sg_tratamiento">¿Está en tratamiento médico?</label>
                                                </div>
                                                <div id="box_sg_tratamiento_txt" class="mt-2 d-none">
                                                    <input type="text" class="form-control form-control-sm" id="sg_tratamiento_txt" placeholder="¿Cuál o por qué motivo?">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- MEDICACIÓN -->
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded border h-100">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input me-2" type="checkbox" id="sg_medicacion" onchange="toggleCampoTexto('sg_medicacion', 'box_sg_medicacion_txt')">
                                                    <label class="form-check-label fw-semibold text-dark" for="sg_medicacion">¿Toma alguna medicación?</label>
                                                </div>
                                                <div id="box_sg_medicacion_txt" class="mt-2 d-none">
                                                    <input type="text" class="form-control form-control-sm" id="sg_medicacion_txt" placeholder="Nombre de los medicamentos...">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- ALERGIAS -->
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded border border-danger-subtle h-100">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input me-2" type="checkbox" id="sg_alergia" onchange="toggleCampoTexto('sg_alergia', 'box_sg_alergia_txt')">
                                                    <label class="form-check-label fw-semibold text-danger" for="sg_alergia">¿Tiene alguna alergia?</label>
                                                </div>
                                                <div id="box_sg_alergia_txt" class="mt-2 d-none">
                                                    <input type="text" class="form-control form-control-sm border-danger" id="sg_alergia_txt" placeholder="Alergia a: medicamentos, anestesia, látex, etc.">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- CIRUGÍAS PREVIAS -->
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded border h-100">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input me-2" type="checkbox" id="sg_cirugia" onchange="toggleCampoTexto('sg_cirugia', 'box_sg_cirugia_txt')">
                                                    <label class="form-check-label fw-semibold text-dark" for="sg_cirugia">¿Tuvo cirugías previas?</label>
                                                </div>
                                                <div id="box_sg_cirugia_txt" class="mt-2 d-none">
                                                    <input type="text" class="form-control form-control-sm" id="sg_cirugia_txt" placeholder="Detallar intervenciones...">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- PRESIÓN ARTERIAL -->
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded border h-100">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input me-2" type="checkbox" id="sg_presion" onchange="toggleCampoTexto('sg_presion', 'box_sg_presion_opt')">
                                                    <label class="form-check-label fw-semibold text-dark" for="sg_presion">¿Alteración de presión arterial?</label>
                                                </div>
                                                <div id="box_sg_presion_opt" class="mt-2 pt-2 border-top d-none">
                                                    <div class="d-flex gap-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="sg_presion_alta">
                                                            <label class="form-check-label small fw-bold text-danger" for="sg_presion_alta">Presión Alta (Hipertensión)</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="sg_presion_baja">
                                                            <label class="form-check-label small fw-bold text-primary" for="sg_presion_baja">Presión Baja (Hipotensión)</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- SWITCHES DE SALUD GENERAL -->
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded border h-100">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input me-2" type="checkbox" id="sg_diabetico">
                                                    <label class="form-check-label fw-semibold text-dark" for="sg_diabetico">¿Es diabético/a?</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded border h-100">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input me-2" type="checkbox" id="sg_cardiaco">
                                                    <label class="form-check-label fw-semibold text-dark" for="sg_cardiaco">¿Problemas cardíacos?</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded border h-100">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input me-2" type="checkbox" id="sg_epilepsia">
                                                    <label class="form-check-label fw-semibold text-dark" for="sg_epilepsia">¿Epilepsia / Convulsiones?</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded border h-100">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input me-2" type="checkbox" id="sg_asma">
                                                    <label class="form-check-label fw-semibold text-dark" for="sg_asma">¿Asma / Prob. respiratorios?</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded border h-100">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input me-2" type="checkbox" id="sg_hemorragia">
                                                    <label class="form-check-label fw-semibold text-dark" for="sg_hemorragia">¿Hemorragias / Mala cicatrización?</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded border h-100">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input me-2" type="checkbox" id="sg_fuma">
                                                    <label class="form-check-label fw-semibold text-dark" for="sg_fuma">¿Fuma / Consume tabaco?</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded border h-100">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input me-2" type="checkbox" id="sg_embarazo">
                                                    <label class="form-check-label fw-semibold text-dark" for="sg_embarazo">¿Está embarazada?</label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- SECCIÓN: SALUD BUCAL -->
                                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                                        <i class="bi bi-emoji-smile me-1"></i> 2. Salud Bucal e Higiene
                                    </h6>

                                    <div class="row g-3">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="p-3 bg-light rounded border h-100">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input me-2" type="checkbox" id="sb_dolor">
                                                    <label class="form-check-label fw-semibold" for="sb_dolor">¿Dolor en dientes/mandíbula?</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-lg-4">
                                            <div class="p-3 bg-light rounded border h-100">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input me-2" type="checkbox" id="sb_sangrado">
                                                    <label class="form-check-label fw-semibold" for="sb_sangrado">¿Sangrado de encías?</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-lg-4">
                                            <div class="p-3 bg-light rounded border h-100">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input me-2" type="checkbox" id="sb_bruxismo">
                                                    <label class="form-check-label fw-semibold" for="sb_bruxismo">¿Bruxismo (rechina dientes)?</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-lg-4">
                                            <div class="p-3 bg-light rounded border h-100">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input me-2" type="checkbox" id="sb_movilidad">
                                                    <label class="form-check-label fw-semibold" for="sb_movilidad">¿Dientes con movilidad?</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-lg-4">
                                            <div class="p-3 bg-light rounded border h-100">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input me-2" type="checkbox" id="sb_anestesia">
                                                    <label class="form-check-label fw-semibold" for="sb_anestesia">¿Mala reacción a anestesia?</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- FRECUENCIA DE CEPILLADO -->
                                        <div class="col-md-6 col-lg-4">
                                            <div class="p-3 bg-light rounded border h-100 d-flex align-items-center">
                                                <label class="form-label fw-bold small text-nowrap mb-0 me-2" for="sb_frecuencia_cepillado">Frecuencia Cepillado:</label>
                                                <select class="form-select form-select-sm" id="sb_frecuencia_cepillado">
                                                    <option value="1">1 vez al día</option> 
                                                    <option value="2" selected>2 veces al día</option>
                                                    <option value="3">3+ veces al día</option>
                                                    <option value="4">Ocasionalmente</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- MOTIVO DE CONSULTA -->
                                        <div class="col-12">
                                            <div class="p-3 bg-light rounded border">
                                                <label class="form-label fw-bold small mb-1" for="sb_motivo_consulta">Motivo principal de la consulta:</label>
                                                <input type="text" class="form-control form-control-sm" id="sb_motivo_consulta" placeholder="Ej: Dolor en muela, Limpieza, Control, Estética...">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- BOTÓN GUARDAR -->
                                    <div class="text-end border-top pt-3 mt-3">
                                        <button type="button" class="btn btn-primary fw-bold" id="btnGuardarAnamnesis">
                                            <i class="bi bi-floppy me-1"></i> Guardar Anamnesis
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>

<!-- MODAL EDITAR PACIENTE -->
<div class="modal fade" id="modalEditarPaciente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Paciente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarPaciente">
                    <input type="hidden" name="id" id="editPacienteId">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre</label>
                            <input type="text" name="nombre" id="editPacienteNombre" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Apellido</label>
                            <input type="text" name="apellido" id="editPacienteApellido" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">DNI</label>
                            <input type="text" name="dni" id="editPacienteDni" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Teléfono / WhatsApp</label>
                            <input type="text" name="telefono" id="editPacienteTelefono" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Obra Social / Prepaga</label>
                            <input type="text" name="obra_social" id="editPacienteObraSocial" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold text-danger"><i class="bi bi-exclamation-triangle me-1"></i>Alertas Médicas / Alergias</label>
                            <textarea name="alertas_medicas" id="editPacienteAlertas" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btnActualizarPaciente" class="btn btn-warning fw-bold">
                    <i class="bi bi-arrow-repeat me-1"></i>Actualizar Paciente
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL 2: NUEVO / EDITAR TURNO -->
<div class="modal fade" id="modalNuevoTurno" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="tituloModalTurno"><i class="bi bi-calendar-plus me-2"></i>Agendar Nuevo Turno</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTurno" autocomplete="off">
                    <input type="hidden" id="turnoId" name="id">

                    <div class="card mb-3 bg-light" style="overflow: visible !important;">
                        <div class="card-body" style="overflow: visible !important;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold m-0" id="labelTituloPaciente">Paciente</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="switchPacienteNuevo" onchange="togglePacienteNuevo()">
                                    <label class="form-check-label fw-semibold text-primary" for="switchPacienteNuevo">Nuevo paciente</label>
                                </div>
                            </div>

                            <div id="bloquePacienteExistente" class="position-relative" style="overflow: visible !important;">
                                <input type="text" 
                                    id="buscarPacienteInput" 
                                    class="form-control" 
                                    placeholder="Escribí Nombre, Apellido o DNI para buscar..." 
                                    autocomplete="one-time-code" 
                                    autocorrect="off" 
                                    spellcheck="false">
                                <input type="hidden" id="pacienteId" name="paciente_id">
                                
                                <div id="listaResultadosPacientes" class="list-group position-absolute w-100 shadow" style="z-index: 9999; top: 100%; left: 0; max-height: 200px; overflow-y: auto; display: none;"></div>
                            </div>

                            <div id="bloquePacienteNuevo" class="row g-2 d-none">
                                <div class="col-md-6">
                                    <input type="text" id="nuevoPacienteNombre" name="nuevo_nombre" class="form-control" placeholder="Nombre y Apellido *">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="nuevoPacienteTelefono" name="nuevo_telefono" class="form-control" placeholder="Teléfono / WhatsApp">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Fecha del Turno</label>
                            <input type="date" id="turnoFecha" name="fecha" class="form-control fw-bold text-center" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Hora Inicio</label>
                            <div class="d-flex align-items-center gap-1">
                                <div class="input-group input-group-sm">
                                    <button type="button" class="btn btn-outline-secondary" onclick="cambiarHora('Inicio', -1)">-</button>
                                    <input type="text" id="horaInicio" class="form-control text-center fw-bold" value="08" readonly>
                                    <button type="button" class="btn btn-outline-secondary" onclick="cambiarHora('Inicio', 1)">+</button>
                                </div>
                                <span class="fw-bold">:</span>
                                <div class="input-group input-group-sm">
                                    <button type="button" class="btn btn-outline-secondary" onclick="cambiarMinutos('Inicio', -15)">-</button>
                                    <input type="text" id="minInicio" class="form-control text-center fw-bold" value="00" readonly>
                                    <button type="button" class="btn btn-outline-secondary" onclick="cambiarMinutos('Inicio', 15)">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Hora Fin</label>
                            <div class="d-flex align-items-center gap-1">
                                <div class="input-group input-group-sm">
                                    <button type="button" class="btn btn-outline-secondary" onclick="cambiarHora('Fin', -1)">-</button>
                                    <input type="text" id="horaFin" class="form-control text-center fw-bold" value="08" readonly>
                                    <button type="button" class="btn btn-outline-secondary" onclick="cambiarHora('Fin', 1)">+</button>
                                </div>
                                <span class="fw-bold">:</span>
                                <div class="input-group input-group-sm">
                                    <button type="button" class="btn btn-outline-secondary" onclick="cambiarMinutos('Fin', -15)">-</button>
                                    <input type="text" id="minFin" class="form-control text-center fw-bold" value="30" readonly>
                                    <button type="button" class="btn btn-outline-secondary" onclick="cambiarMinutos('Fin', 15)">+</button>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="hora_inicio" id="inputHiddenHoraInicio" value="08:00">
                        <input type="hidden" name="hora_fin" id="inputHiddenHoraFin" value="08:30">

                        <div class="col-12">
                            <label class="form-label fw-bold d-block">Motivo / Tratamiento</label>
                            <div id="textoMotivosSeleccionados" class="small text-muted fw-bold mb-2">Seleccionar motivos...</div>

                            <div class="border rounded p-3 bg-light">
                                <div class="d-flex flex-column gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input check-motivo" type="checkbox" name="motivo[]" value="Consulta General" id="motivoConsulta" onchange="actualizarMotivos()">
                                        <label class="form-check-label" for="motivoConsulta">Consulta General</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input check-motivo" type="checkbox" name="motivo[]" value="Limpieza" id="motivoLimpieza" onchange="actualizarMotivos()">
                                        <label class="form-check-label" for="motivoLimpieza">Limpieza / Detección</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input check-motivo" type="checkbox" name="motivo[]" value="Ortodoncia" id="motivoOrtodoncia" onchange="actualizarMotivos()">
                                        <label class="form-check-label" for="motivoOrtodoncia">Ortodoncia</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input check-motivo" type="checkbox" name="motivo[]" value="Conducto" id="motivoConducto" onchange="actualizarMotivos()">
                                        <label class="form-check-label" for="motivoConducto">Tratamiento de Conducto</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input check-motivo" type="checkbox" name="motivo[]" value="Extracción" id="motivoExtraccion" onchange="actualizarMotivos()">
                                        <label class="form-check-label" for="motivoExtraccion">Extracción</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="motivo[]" value="Otro" id="motivoOtro" onchange="toggleMotivoOtro()">
                                        <label class="form-check-label" for="motivoOtro">Otro / Especificar</label>
                                    </div>

                                    <input type="text" 
                                        id="inputMotivoOtro" 
                                        name="motivo_otro_texto" 
                                        class="form-control form-control-sm mt-1 d-none" 
                                        placeholder="Escribí el motivo personalizado..." 
                                        oninput="actualizarMotivos()">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Observaciones / Notas</label>
                            <textarea name="observaciones" id="turnoObservaciones" class="form-control" rows="2" placeholder="Notas opcionales sobre la consulta..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btnGuardarTurno" class="btn btn-primary fw-bold">
                    <i class="bi bi-calendar-check me-1"></i>Guardar Turno
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar / Detalle Turno -->
<div class="modal fade" id="modalDetalleTurno" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Gestionar Turno</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarTurno">
                    <input type="hidden" id="editTurnoId" name="id">

                    <div class="mb-3 p-2 bg-light rounded border">
                        <div class="fw-bold text-dark" id="editPacienteNombre">Paciente: -</div>
                        <div class="small text-muted" id="editMotivoTexto">Motivo: -</div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-12">
                            <label class="form-label fw-bold small">Fecha</label>
                            <input type="date" id="editTurnoFecha" name="fecha" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small">Hora Inicio</label>
                            <select id="editTurnoHoraInicio" name="hora_inicio" class="form-select" required></select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small">Hora Fin</label>
                            <select id="editTurnoHoraFin" name="hora_fin" class="form-select" required></select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-outline-danger" onclick="confirmarEliminarTurno()">
                    <i class="bi bi-trash me-1"></i>Eliminar Turno
                </button>
                
                <div>
                    <button type="button" class="btn btn-secondary me-1" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary fw-bold" onclick="guardarEdicionTurno()">
                        <i class="bi bi-check-lg me-1"></i>Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL 3: FICHA CLÍNICA CON ODONTOGRAMA E HISTORIAL -->
<div class="modal fade" id="modalFichaClinica" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="fichaPacienteTitulo">Expediente Clínico</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="fichaPacienteId">
                
                <div class="row">
                    <div class="col-md-7 border-end">
                        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-diagram-3 me-2"></i>Odontograma Anatómico</h6>
                        
                        <div id="odontogramaContainer" class="d-flex flex-wrap gap-2 justify-content-center p-3 bg-light rounded border mb-3">
                            <div class="tooth-box text-center" data-diente="16">
                                <div class="tooth-number">16</div>
                                <svg class="tooth-svg" viewBox="0 0 100 100">
                                    <polygon class="tooth-face" data-cara="Superior" points="0,0 100,0 80,20 20,20" onclick="marcarCara(this)"/>
                                    <polygon class="tooth-face" data-cara="Derecha" points="100,0 100,100 80,80 80,20" onclick="marcarCara(this)"/>
                                    <polygon class="tooth-face" data-cara="Inferior" points="0,100 100,100 80,80 20,80" onclick="marcarCara(this)"/>
                                    <polygon class="tooth-face" data-cara="Izquierda" points="0,0 0,100 20,80 20,20" onclick="marcarCara(this)"/>
                                    <polygon class="tooth-face" data-cara="Centro" points="20,20 80,20 80,80 20,80" onclick="marcarCara(this)"/>
                                </svg>
                            </div>
                            <div class="tooth-box text-center" data-diente="11">
                                <div class="tooth-number">11</div>
                                <svg class="tooth-svg" viewBox="0 0 100 100">
                                    <polygon class="tooth-face" data-cara="Superior" points="0,0 100,0 80,20 20,20" onclick="marcarCara(this)"/>
                                    <polygon class="tooth-face" data-cara="Derecha" points="100,0 100,100 80,80 80,20" onclick="marcarCara(this)"/>
                                    <polygon class="tooth-face" data-cara="Inferior" points="0,100 100,100 80,80 20,80" onclick="marcarCara(this)"/>
                                    <polygon class="tooth-face" data-cara="Izquierda" points="0,0 0,100 20,80 20,20" onclick="marcarCara(this)"/>
                                    <polygon class="tooth-face" data-cara="Centro" points="20,20 80,20 80,80 20,80" onclick="marcarCara(this)"/>
                                </svg>
                            </div>
                        </div>

                        <div class="card p-3 border-0 bg-light">
                            <h6 class="fw-bold mb-2"><i class="bi bi-pencil-square me-1"></i>Registrar Evolución / Tratamiento</h6>
                            <form id="formEvolucion">
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <input type="text" id="evoDiente" name="diente_nro" class="form-control form-control-sm" placeholder="N° Diente (ej: 16)" readonly>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" id="evoCara" name="cara" class="form-control form-control-sm" placeholder="Cara seleccionada" readonly>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <input type="text" name="diagnostico" class="form-control form-control-sm" placeholder="Diagnóstico (ej: Caries oclusal)">
                                </div>
                                <div class="mb-2">
                                    <textarea name="tratamiento" class="form-control form-control-sm" rows="2" placeholder="Tratamiento realizado..." required></textarea>
                                </div>
                                <button type="button" id="btnGuardarEvolucion" class="btn btn-sm btn-success w-100 fw-bold">
                                    <i class="bi bi-check-lg me-1"></i>Guardar Evolución y Odontograma
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-clock-history me-2"></i>Historial del Paciente</h6>
                        <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
                            <table class="table table-sm table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Pieza</th>
                                        <th>Tratamiento</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaHistorialEvoluciones">
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Cargando expediente...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- LIBRERÍAS JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<script>
// -------------------------------------------------------------
// FUNCIÓN GLOBAL PARA MANEJO DE SESIÓN Y AJAX
// -------------------------------------------------------------
function fetchConSesion(url, opciones = {}) {
    return fetch(url, opciones)
        .then(response => {
            if (response.status === 401) {
                alert('🕒 Tu sesión ha expirado por inactividad. Serás redirigido al login.');
                window.location.href = 'login.php';
                throw new Error('SESSION_EXPIRED');
            }
            return response.json();
        })
        .then(data => {
            if (data && data.status === 'session_expired') {
                alert('🕒 Tu sesión ha expirado. Por favor, volvé a iniciar sesión.');
                window.location.href = 'login.php';
                throw new Error('SESSION_EXPIRED');
            }
            return data;
        });
}

let calendar;
let caraSeleccionadaActual = null;

// =============================================================
// UNIFICACIÓN DE EVENTO DOMCONTENTLOADED
// =============================================================
document.addEventListener('DOMContentLoaded', function() {

    // 1. DATATABLES DE PACIENTES
    const tablaPacientes = $('#tablaPacientes').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
        ajax: {
            url: 'get_pacientes.php',
            dataSrc: function(json) {
                if (json && json.status === 'session_expired') {
                    alert('🕒 Tu sesión ha expirado. Redirigiendo...');
                    window.location.href = 'login.php';
                    return [];
                }
                return json.data || [];
            }
        },
        columns: [
            { data: 'dni' },
            { 
                data: null,
                render: function(data) {
                    return `<b>${data.nombre} ${data.apellido}</b>`;
                }
            },
            { data: 'telefono' },
            { data: 'obra_social' },
            { 
                data: 'alertas_medicas',
                render: function(data) {
                    return data ? `<span class="badge bg-danger">${data}</span>` : '<span class="text-muted">Sin alertas</span>';
                }
            },
            {
                data: null,
                render: function(data) {
                    const jsonPaciente = JSON.stringify(data).replace(/'/g, "&apos;");
                    return `
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-primary" onclick="abrirFichaClinica(${data.id}, '${data.nombre} ${data.apellido}')" title="Ficha Clínica">
                                <i class="bi bi-card-checklist"></i> Ficha
                            </button>
                            <button class="btn btn-warning" onclick='abrirModalEditarPaciente(${jsonPaciente})' title="Editar Paciente">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ]
    });

    // 2. GUARDAR NUEVO PACIENTE
    const btnGuardarPaciente = document.getElementById('btnGuardarPaciente');
    const formPaciente = document.getElementById('formNuevoPaciente');

    if (btnGuardarPaciente) {
        btnGuardarPaciente.addEventListener('click', function() {
            if (!formPaciente.checkValidity()) {
                formPaciente.reportValidity();
                return;
            }

            const formData = new FormData(formPaciente);

            fetchConSesion('guardar_paciente.php', {
                method: 'POST',
                body: formData
            })
            .then(data => {
                if (data.status === 'success') {
                    alert('✅ ' + data.message);
                    formPaciente.reset();
                    
                    const modalElement = document.getElementById('modalNuevoPaciente');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) modalInstance.hide();
                    
                    tablaPacientes.ajax.reload();
                } else {
                    alert('⚠️ Error: ' + data.message);
                }
            })
            .catch(err => {
                if (err.message !== 'SESSION_EXPIRED') console.error(err);
            });
        });
    }

    // 3. EDITAR PACIENTE
    const btnActualizarPaciente = document.getElementById('btnActualizarPaciente');
    if (btnActualizarPaciente) {
        btnActualizarPaciente.addEventListener('click', function() {
            const form = document.getElementById('formEditarPaciente');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);

            fetchConSesion('editar_paciente.php', {
                method: 'POST',
                body: formData
            })
            .then(data => {
                if (data.status === 'success') {
                    alert('✅ ' + data.message);
                    const modalEl = document.getElementById('modalEditarPaciente');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) modalInstance.hide();
                    tablaPacientes.ajax.reload();
                } else {
                    alert('⚠️ Error: ' + data.message);
                }
            })
            .catch(err => {
                if (err.message !== 'SESSION_EXPIRED') console.error(err);
            });
        });
    }

    // 4. INICIALIZACIÓN DE FULLCALENDAR
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            locale: 'es',
            buttonText: {
                today: 'hoy',
                month: 'mes',
                week: 'semana',
                day: 'día'
            },
            editable: false,           
            eventStartEditable: false, 
            selectable: true,
            height: 650, 
            allDaySlot: false, 

            dayHeaderContent: function(arg) {
                const date = arg.date;
                const today = new Date();
                
                const dDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
                const dToday = new Date(today.getFullYear(), today.getMonth(), today.getDate());

                let estadoClase = 'gc-future';
                if (dDate < dToday) {
                    estadoClase = 'gc-past';      
                } else if (dDate.getTime() === dToday.getTime()) {
                    estadoClase = 'gc-today-cell'; 
                }

                const dayName = date.toLocaleDateString('es-ES', { weekday: 'short' }).replace('.', '');
                const dayNumber = date.getDate();

                return {
                    html: `
                        <div class="gc-header-cell ${estadoClase}">
                            <span class="gc-day-name">${dayName}</span>
                            <span class="gc-day-number">${dayNumber}</span>
                        </div>
                    `
                };
            },

            titleFormat: { year: 'numeric', month: 'long' },
            slotMinTime: '06:00:00',
            slotMaxTime: '21:00:00',
            slotDuration: '00:15:00',
            snapDuration: '00:15:00',
            slotLabelInterval: '01:00:00',
            slotLabelContent: function(arg) {
                return `${arg.date.getHours()} hs`;
            },
            
            eventSources: [
                function(fetchInfo, successCallback, failureCallback) {
                    fetchConSesion(`get_turnos.php?start=${fetchInfo.startStr}&end=${fetchInfo.endStr}`)
                        .then(data => successCallback(data))
                        .catch(err => failureCallback(err));
                }
            ],

            eventClick: function(info) {
                const inicio = info.event.start;
                const fin = info.event.end || info.event.start;

                const pad = num => String(num).padStart(2, '0');
                const fechaStr = `${inicio.getFullYear()}-${pad(inicio.getMonth() + 1)}-${pad(inicio.getDate())}`;
                const horaInicioStr = `${pad(inicio.getHours())}:${pad(inicio.getMinutes())}`;
                const horaFinStr = `${pad(fin.getHours())}:${pad(fin.getMinutes())}`;

                abrirModalEditarTurno({
                    id: info.event.id,
                    paciente_nombre: info.event.title,
                    motivo: info.event.extendedProps.motivo || 'Consulta',
                    fecha: fechaStr,
                    hora_inicio: horaInicioStr,
                    hora_fin: horaFinStr
                });
            },

            eventDrop: function(info) {
                actualizarTurnoPorDragDrop(info.event);
            }
        });
        calendar.render();
    }

    // 5. GUARDAR TURNO
    const modalTurnoEl = document.getElementById('modalNuevoTurno');
    const btnGuardarTurno = document.getElementById('btnGuardarTurno');
    const formTurno = document.getElementById('formTurno');

    if (btnGuardarTurno && formTurno) {
        btnGuardarTurno.addEventListener('click', function(e) {
            e.preventDefault();

            if (!formTurno.checkValidity()) {
                formTurno.reportValidity();
                return;
            }

            actualizarHorasOcultas();

            const formData = new FormData(formTurno);

            fetchConSesion('guardar_turno.php', {
                method: 'POST',
                body: formData
            })
            .then(data => {
                if (data.status === 'success' || data.success === true) {
                    alert('✅ ' + (data.message || 'Turno guardado correctamente'));
                    formTurno.reset();

                    const turnoIdInput = document.getElementById('turnoId');
                    if (turnoIdInput) turnoIdInput.value = '';

                    const modalInstance = bootstrap.Modal.getInstance(modalTurnoEl);
                    if (modalInstance) modalInstance.hide();

                    if (calendar) calendar.refetchEvents();
                } else {
                    alert('⚠️ ' + (data.message || 'No se pudo guardar el turno.'));
                }
            })
            .catch(err => {
                if (err.message !== 'SESSION_EXPIRED') console.error(err);
            });
        });
    }

    // 6. GUARDAR EVOLUCIÓN
    const btnGuardarEvo = document.getElementById('btnGuardarEvolucion');
    if (btnGuardarEvo) {
        btnGuardarEvo.addEventListener('click', function() {
            const form = document.getElementById('formEvolucion');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);
            const idPaciente = document.getElementById('fichaPacienteId').value;
            formData.append('paciente_id', idPaciente);

            fetchConSesion('guardar_evolucion.php', {
                method: 'POST',
                body: formData
            })
            .then(data => {
                if (data.status === 'success') {
                    if (caraSeleccionadaActual) {
                        caraSeleccionadaActual.classList.remove('activa-seleccion');
                        caraSeleccionadaActual.classList.add('tratado');
                    }
                    
                    alert('✅ Evolución guardada correctamente.');
                    form.reset();
                    
                    const pacienteNombre = document.getElementById('fichaPacienteTitulo').innerText.replace('Expediente Clínico: ', '');
                    abrirFichaClinica(idPaciente, pacienteNombre);
                } else {
                    alert('⚠️ Error: ' + data.message);
                }
            })
            .catch(err => {
                if (err.message !== 'SESSION_EXPIRED') console.error(err);
            });
        });
    }

    // 7. CARGAR HORAS EN SELECTORES DE EDICIÓN DE TURNO
    const editInicio = document.getElementById('editTurnoHoraInicio');
    const editFin = document.getElementById('editTurnoHoraFin');
    if (editInicio && editFin) {
        editInicio.innerHTML = '';
        editFin.innerHTML = '';
        for (let h = 7; h <= 21; h++) {
            for (let m = 0; m < 60; m += 15) {
                const horaStr = h.toString().padStart(2, '0');
                const minStr = m.toString().padStart(2, '0');
                const valorTime = `${horaStr}:${minStr}`;
                
                editInicio.innerHTML += `<option value="${valorTime}">${valorTime} hs</option>`;
                editFin.innerHTML += `<option value="${valorTime}">${valorTime} hs</option>`;
            }
        }
    }

    // 8. AUTOBÚSQUEDA PREDICTIVA DE PACIENTES
    const inputBuscar = document.getElementById('buscarPacienteInput');
    const inputPacienteId = document.getElementById('pacienteId');
    const listaResultados = document.getElementById('listaResultadosPacientes');

    if (inputBuscar && listaResultados) {
        let timeoutBusqueda = null;

        inputBuscar.addEventListener('input', function() {
            const query = this.value.trim();
            if (inputPacienteId) inputPacienteId.value = '';

            if (query.length < 2) {
                listaResultados.style.display = 'none';
                listaResultados.innerHTML = '';
                return;
            }

            clearTimeout(timeoutBusqueda);
            timeoutBusqueda = setTimeout(() => {
                fetch(`buscar_pacientes.php?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(pacientes => {
                        listaResultados.innerHTML = '';
                        if (!Array.isArray(pacientes) || pacientes.length === 0) {
                            listaResultados.innerHTML = `<div class="list-group-item text-muted small p-2 bg-white">No se encontraron pacientes.</div>`;
                        } else {
                            pacientes.forEach(p => {
                                const btn = document.createElement('button');
                                btn.type = 'button';
                                btn.className = 'list-group-item list-group-item-action text-start p-2 bg-white text-dark';
                                btn.innerHTML = `
                                    <div class="fw-bold">${p.apellido || ''}, ${p.nombre || ''}</div>
                                    <small class="text-muted">DNI: ${p.dni || 'Sin DNI'} | Tel: ${p.telefono || 'Sin Tel'}</small>
                                `;
                                btn.addEventListener('mousedown', function(e) {
                                    e.preventDefault();
                                    inputBuscar.value = `${p.apellido || ''}, ${p.nombre || ''} (DNI: ${p.dni || 'N/A'})`;
                                    if (inputPacienteId) inputPacienteId.value = p.id;
                                    listaResultados.style.display = 'none';
                                });
                                listaResultados.appendChild(btn);
                            });
                        }
                        listaResultados.style.display = 'block';
                    })
                    .catch(err => console.error(err));
            }, 300);
        });

        document.addEventListener('click', function(e) {
            if (inputBuscar && !inputBuscar.contains(e.target) && !listaResultados.contains(e.target)) {
                listaResultados.style.display = 'none';
            }
        });
    }

    // 9. SWITCH MODO EDICIÓN
    const switchModoEdicion = document.getElementById('switchModoEdicion');
    if (switchModoEdicion) {
        switchModoEdicion.addEventListener('change', function() {
            const estaActivo = this.checked;
            if (calendar) {
                calendar.setOption('editable', estaActivo);
                calendar.setOption('eventStartEditable', estaActivo);
                calendar.refetchEvents();
            }
        });
    }

// 10. MÓDULO DE CLÍNICA
const switchNuevo = document.getElementById("switchPacienteNuevoClinica");
const contenedorExistente = document.getElementById("contenedorPacienteExistenteClinica");
const contenedorNuevo = document.getElementById("contenedorPacienteNuevoClinica");
const contenedorBtnNuevo = document.getElementById("contenedorBtnNuevoPaciente");

const inputBuscarClinica = document.getElementById("inputBuscarPacienteClinica");
const listaResultadosClinica = document.getElementById("resultadosBusquedaPacienteClinica");
const btnLimpiarClinica = document.getElementById("btnLimpiarPacienteClinica");

const fichaSeleccionado = document.getElementById("fichaPacienteSeleccionadoClinica");
const labelNombre = document.getElementById("labelNombrePacienteSeleccionado");
const labelDetalles = document.getElementById("labelDetallesPacienteSeleccionado");
const inputIdHidden = document.getElementById("idPacienteSeleccionadoClinica");

const btnContinuar = document.getElementById("btnContinuarClinica");
const btnContinuarNuevo = document.getElementById("btnContinuarNuevoClinica");
const seccionesClinicaContenido = document.getElementById("seccionesClinicaContenido");

// Elementos para ocultar/mostrar e interfaz con botón rojo "Volver"
const bloqueSeleccion = document.getElementById("bloqueSeleccionPacienteClinica");
const bloqueConfirmado = document.getElementById("bloquePacienteConfirmado");
const labelNombreConfirmado = document.getElementById("labelNombreConfirmado");
const labelDetallesConfirmados = document.getElementById("labelDetallesConfirmados");
const btnVolver = document.getElementById("btnVolverSeleccionPaciente");

// Alternar entre Paciente Existente y Paciente Nuevo
if (switchNuevo) {
    switchNuevo.addEventListener("change", function() {
        resetSeleccionPaciente();
        if (this.checked) {
            contenedorExistente.classList.add("d-none");
            contenedorNuevo.classList.remove("d-none");
            if (contenedorBtnNuevo) contenedorBtnNuevo.classList.remove("d-none");
        } else {
            contenedorExistente.classList.remove("d-none");
            contenedorNuevo.classList.add("d-none");
            if (contenedorBtnNuevo) contenedorBtnNuevo.classList.add("d-none");
        }
    });
}

// Búsqueda en tiempo real de pacientes existentes
if (inputBuscarClinica) {
    inputBuscarClinica.addEventListener("input", function() {
        const query = this.value.trim();

        if (query.length < 2) {
            listaResultadosClinica.classList.add("d-none");
            listaResultadosClinica.innerHTML = "";
            return;
        }

        fetch(`buscar_pacientes.php?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                listaResultadosClinica.innerHTML = "";
                if (data.length === 0) {
                    listaResultadosClinica.innerHTML = `<div class="list-group-item disabled small">No se encontraron pacientes</div>`;
                } else {
                    data.forEach(paciente => {
                        const item = document.createElement("a");
                        item.href = "#";
                        item.classList.add("list-group-item", "list-group-item-action", "small");
                        
                        const dniTxt = paciente.dni ? ` (DNI: ${paciente.dni})` : '';
                        item.textContent = `${paciente.apellido}, ${paciente.nombre}${dniTxt}`;
                        
                        item.addEventListener("click", function(e) {
                            e.preventDefault();
                            seleccionarPaciente(paciente);
                        });
                        listaResultadosClinica.appendChild(item);
                    });
                }
                listaResultadosClinica.classList.remove("d-none");
            })
            .catch(err => console.error("Error al buscar paciente:", err));
    });
}

function seleccionarPaciente(p) {
    inputIdHidden.value = p.id;
    labelNombre.textContent = `${p.apellido}, ${p.nombre}`;
    labelDetalles.textContent = `DNI: ${p.dni || '--'} | Tel: ${p.telefono || '--'}`;
    
    fichaSeleccionado.classList.remove("d-none");
    listaResultadosClinica.classList.add("d-none");
    inputBuscarClinica.value = `${p.apellido}, ${p.nombre}`;
    btnLimpiarClinica.classList.remove("d-none");
}

function resetSeleccionPaciente() {
    inputIdHidden.value = "";
    inputBuscarClinica.value = "";
    fichaSeleccionado.classList.add("d-none");
    btnLimpiarClinica.classList.add("d-none");
    listaResultadosClinica.classList.add("d-none");
    if (seccionesClinicaContenido) seccionesClinicaContenido.classList.add("d-none");
}

if (btnLimpiarClinica) btnLimpiarClinica.addEventListener("click", resetSeleccionPaciente);

// ACCIÓN: Paciente Existente -> Continuar a la Ficha
if (btnContinuar) {
    btnContinuar.addEventListener("click", function() {
        const pacienteId = inputIdHidden.value;
        if (!pacienteId) {
            alert("Por favor, seleccioná un paciente válido primero.");
            return;
        }

        activarVistaPacienteConfirmado(labelNombre.textContent, labelDetalles.textContent);
    });
}

// ACCIÓN: Paciente Nuevo -> GUARDAR EN BDD Y CONTINUAR (ACTUALIZADO)
if (btnContinuarNuevo) {
    btnContinuarNuevo.addEventListener("click", function() {
        const nombre = document.getElementById("nuevoNombreClinica").value.trim();
        const apellido = document.getElementById("nuevoApellidoClinica").value.trim();
        const dni = document.getElementById("nuevoDniClinica").value.trim();
        const telefono = document.getElementById("nuevoTelefonoClinica").value.trim();
        const obraSocial = document.getElementById("nuevoObraSocialClinica").value.trim();
        const alertas = document.getElementById("nuevoAlertasClinica").value.trim();

        if (!nombre || !apellido || !dni) {
            alert("⚠️ Por favor completá los campos obligatorios: Nombre, Apellido y DNI.");
            return;
        }

        // Armamos el FormData enviándolo a guardar_paciente.php (Backend existente)
        const formData = new FormData();
        formData.append("nombre", nombre);
        formData.append("apellido", apellido);
        formData.append("dni", dni);
        formData.append("telefono", telefono);
        formData.append("obra_social", obraSocial);
        formData.append("alertas_medicas", alertas);

        btnContinuarNuevo.disabled = true;
        btnContinuarNuevo.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Guardando...`;

        fetchConSesion('guardar_paciente.php', {
            method: 'POST',
            body: formData
        })
        .then(data => {
            btnContinuarNuevo.disabled = false;
            btnContinuarNuevo.innerHTML = `<i class="bi bi-floppy me-1"></i> Guardar Paciente`;

            if (data.status === 'success') {
                alert('✅ Paciente registrado con éxito en la base de datos.');
                
                // Si la tabla de pacientes de la pestaña 2 está cargada, la actualizamos
                if ($.fn.DataTable.isDataTable('#tablaPacientes')) {
                    $('#tablaPacientes').DataTable().ajax.reload();
                }

                // Limpiar el formulario de nuevo paciente
                document.getElementById("nuevoNombreClinica").value = "";
                document.getElementById("nuevoApellidoClinica").value = "";
                document.getElementById("nuevoDniClinica").value = "";
                document.getElementById("nuevoTelefonoClinica").value = "";
                document.getElementById("nuevoObraSocialClinica").value = "";
                document.getElementById("nuevoAlertasClinica").value = "";

                // Volver a la sección "Paciente Existente" automáticamente
                if(switchNuevo) {
                    switchNuevo.checked = false;
                    switchNuevo.dispatchEvent(new Event('change'));
                }
            } else {
                alert('⚠️ Error al registrar paciente: ' + data.message);
            }
        })
        .catch(err => {
            btnContinuarNuevo.disabled = false;
            btnContinuarNuevo.innerHTML = `<i class="bi bi-floppy me-1"></i> Guardar Paciente`;
            if (err.message !== 'SESSION_EXPIRED') console.error(err);
        });
    });
}

// Función auxiliar para ocultar buscador y mostrar confirmación (MODIFICADO SIN AUTOSCROLL)
function activarVistaPacienteConfirmado(nombre, detalles) {
    if (labelNombreConfirmado) labelNombreConfirmado.textContent = nombre;
    if (labelDetallesConfirmados) labelDetallesConfirmados.textContent = detalles;

    if (bloqueSeleccion) bloqueSeleccion.classList.add("d-none");
    if (bloqueConfirmado) bloqueConfirmado.classList.remove("d-none");

    if (seccionesClinicaContenido) {
        seccionesClinicaContenido.classList.remove("d-none");
        // scrollIntoView eliminado a pedido para evitar el salto
    }
    const pacienteId = document.getElementById('idPacienteSeleccionadoClinica')?.value;
    if (pacienteId) {
        cargarAnamnesisPaciente(pacienteId);
    }
} 

// BOTÓN ROJO VOLVER / CAMBIAR PACIENTE
if (btnVolver) {
    btnVolver.addEventListener("click", function() {
        if (bloqueConfirmado) bloqueConfirmado.classList.add("d-none");
        if (bloqueSeleccion) bloqueSeleccion.classList.remove("d-none");
        
        if (seccionesClinicaContenido) {
            seccionesClinicaContenido.classList.add("d-none");
        }
    });
}
});

function toggleCampoTexto(switchId, containerId) {
    const sw = document.getElementById(switchId);
    const box = document.getElementById(containerId);
    if (!sw || !box) return;

    if (sw.checked) {
        box.classList.remove("d-none");
        const input = box.querySelector("input[type='text']");
        if (input) input.focus();
    } else {
        box.classList.add("d-none");
        const input = box.querySelector("input[type='text']");
        if (input) input.value = "";
    }
}

// Cargar la ficha del paciente desde la BDD
function cargarAnamnesisPaciente(pacienteId) {
    fetchConSesion(`obtener_anamnesis.php?paciente_id=${pacienteId}`)
        .then(res => {
            if (res.status === 'success' && res.exists) {
                poblarFormularioAnamnesis(res.data);
                bloquearAnamnesis(true);
            } else {
                limpiarFormularioAnamnesis();
                bloquearAnamnesis(false);
            }
        })
        .catch(err => {
            if (err.message !== 'SESSION_EXPIRED') console.error("Error al obtener anamnesis:", err);
        });
}

// Llenar campos con los datos recuperados
function poblarFormularioAnamnesis(d) {
    const idsText = ['sg_tratamiento_txt', 'sg_medicacion_txt', 'sg_alergia_txt', 'sg_cirugia_txt', 'sb_motivo_consulta'];
    idsText.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = d[id] || '';
    });

    const selects = ['sb_frecuencia_cepillado'];
    selects.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = d[id] || '2';
    });

    const switches = [
        'sg_tratamiento', 'sg_medicacion', 'sg_alergia', 'sg_cirugia', 'sg_presion',
        'sg_presion_alta', 'sg_presion_baja', 'sg_diabetico', 'sg_cardiaco', 'sg_epilepsia',
        'sg_asma', 'sg_hemorragia', 'sg_fuma', 'sg_embarazo', 'sb_dolor', 'sb_sangrado',
        'sb_bruxismo', 'sb_movilidad', 'sb_anestesia'
    ];

    switches.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.checked = (parseInt(d[id]) === 1);
        }
    });

    // Disparar eventos para desplegar cajas de texto si corresponde
    toggleCampoTexto('sg_tratamiento', 'box_sg_tratamiento_txt');
    toggleCampoTexto('sg_medicacion', 'box_sg_medicacion_txt');
    toggleCampoTexto('sg_alergia', 'box_sg_alergia_txt');
    toggleCampoTexto('sg_cirugia', 'box_sg_cirugia_txt');
    toggleCampoTexto('sg_presion', 'box_sg_presion_opt');
}

// Bloquear o desbloquear controles (Disable/Enable)
function bloquearAnamnesis(bloquear) {
    const body = document.getElementById('bodyAnamnesis');
    const btnEditar = document.getElementById('btnEditarAnamnesis');
    const btnGuardar = document.getElementById('btnGuardarAnamnesis');

    if (!body) return;

    const inputs = body.querySelectorAll('input, select, button:not(#btnEditarAnamnesis)');
    inputs.forEach(el => {
        el.disabled = bloquear;
    });

    if (bloquear) {
        btnEditar?.classList.remove('d-none');
        btnGuardar?.classList.add('d-none');
    } else {
        btnEditar?.classList.add('d-none');
        btnGuardar?.classList.remove('d-none');
    }
}

// Limpiar formulario cuando el paciente no tiene ficha
function limpiarFormularioAnamnesis() {
    const body = document.getElementById('bodyAnamnesis');
    if (!body) return;

    body.querySelectorAll('input[type="checkbox"]').forEach(i => i.checked = false);
    body.querySelectorAll('input[type="text"]').forEach(i => i.value = '');
    const sel = document.getElementById('sb_frecuencia_cepillado');
    if (sel) sel.value = '2';

    toggleCampoTexto('sg_tratamiento', 'box_sg_tratamiento_txt');
    toggleCampoTexto('sg_medicacion', 'box_sg_medicacion_txt');
    toggleCampoTexto('sg_alergia', 'box_sg_alergia_txt');
    toggleCampoTexto('sg_cirugia', 'box_sg_cirugia_txt');
    toggleCampoTexto('sg_presion', 'box_sg_presion_opt');
}

// Evento: Clic en "Guardar Anamnesis" (CORREGIDO)
document.getElementById('btnGuardarAnamnesis')?.addEventListener('click', function() {
    // 1. Obtener el ID del paciente desde cualquiera de los hidden disponibles
    let pacienteId = document.getElementById('idPacienteSeleccionadoClinica')?.value 
                  || document.getElementById('paciente_id')?.value 
                  || document.getElementById('id_paciente')?.value;

    if (!pacienteId || parseInt(pacienteId) <= 0) {
        alert("⚠️ Por favor seleccioná un paciente válido antes de guardar.");
        return;
    }

    const formData = new FormData();
    // Forzamos el envío de paciente_id exacto al FormData
    formData.append('paciente_id', pacienteId);

    const body = document.getElementById('bodyAnamnesis');
    body.querySelectorAll('input, select').forEach(el => {
        // Evitamos volver a procesar los inputs ocultos de id dentro del loop
        if (el.id === 'paciente_id' || el.id === 'idPacienteSeleccionadoClinica' || el.id === 'id_paciente') {
            return;
        }

        if (el.type === 'checkbox') {
            if (el.checked) formData.append(el.id, '1');
        } else {
            formData.append(el.id, el.value);
        }
    });

    fetchConSesion('guardar_anamnesis.php', {
        method: 'POST',
        body: formData
    })
    .then(data => {
        if (data.status === 'success') {
            alert('✅ Anamnesis guardada correctamente.');
            bloquearAnamnesis(true);
        } else {
            alert('⚠️ Error al guardar: ' + data.message);
        }
    })
    .catch(err => console.error("Error en la solicitud:", err));
});

// Evento: Clic en "Editar"
document.getElementById('btnEditarAnamnesis')?.addEventListener('click', function() {
    bloquearAnamnesis(false);
});

// =============================================================
// FUNCIONES DE APOYO GLOBALES
// =============================================================
function actualizarHorasOcultas() {
    const hInicio = document.getElementById('horaInicio').value;
    const mInicio = document.getElementById('minInicio').value;
    document.getElementById('inputHiddenHoraInicio').value = `${hInicio}:${mInicio}`;

    const hFin = document.getElementById('horaFin').value;
    const mFin = document.getElementById('minFin').value;
    document.getElementById('inputHiddenHoraFin').value = `${hFin}:${mFin}`;
}

function cambiarHora(tipo, delta) {
    const input = document.getElementById('hora' + tipo);
    let hora = parseInt(input.value, 10) + delta;
    
    if (hora > 23) hora = 0;
    if (hora < 0) hora = 23;
    
    input.value = String(hora).padStart(2, '0');
    actualizarHorasOcultas();
}

function cambiarMinutos(tipo, delta) {
    const input = document.getElementById('min' + tipo);
    let min = parseInt(input.value, 10) + delta;
    
    if (min > 45) min = 0;
    if (min < 0) min = 45;
    
    input.value = String(min).padStart(2, '0');
    actualizarHorasOcultas();
}

function togglePacienteNuevo() {
    const esNuevo = document.getElementById('switchPacienteNuevo').checked;
    document.getElementById('labelTituloPaciente').innerText = esNuevo ? 'Nuevo Paciente' : 'Paciente';
    document.getElementById('bloquePacienteExistente').classList.toggle('d-none', esNuevo);
    document.getElementById('bloquePacienteNuevo').classList.toggle('d-none', !esNuevo);
    
    if (esNuevo) {
        document.getElementById('pacienteId').value = '';
        document.getElementById('buscarPacienteInput').value = '';
    }
}

function toggleMotivoOtro() {
    const checkOtro = document.getElementById('motivoOtro');
    const inputOtro = document.getElementById('inputMotivoOtro');
    
    if (checkOtro && inputOtro) {
        inputOtro.classList.toggle('d-none', !checkOtro.checked);
        if (!checkOtro.checked) inputOtro.value = '';
        actualizarMotivos();
    }
}

function actualizarMotivos() {
    let seleccionados = [];
    document.querySelectorAll('.check-motivo:checked').forEach(c => seleccionados.push(c.value));
    
    const checkOtro = document.getElementById('motivoOtro');
    if (checkOtro && checkOtro.checked) {
        const textoOtro = document.getElementById('inputMotivoOtro').value.trim();
        seleccionados.push(textoOtro !== '' ? textoOtro : "Otro");
    }
    
    const label = document.getElementById('textoMotivosSeleccionados');
    if (label) {
        label.innerText = seleccionados.length > 0 ? seleccionados.join(', ') : 'Seleccionar motivos...';
    }
}

function abrirModalEditarPaciente(paciente) {
    document.getElementById('editPacienteId').value = paciente.id;
    document.getElementById('editPacienteNombre').value = paciente.nombre;
    document.getElementById('editPacienteApellido').value = paciente.apellido;
    document.getElementById('editPacienteDni').value = paciente.dni;
    document.getElementById('editPacienteTelefono').value = paciente.telefono || '';
    document.getElementById('editPacienteObraSocial').value = paciente.obra_social || '';
    document.getElementById('editPacienteAlertas').value = paciente.alertas_medicas || '';

    const modal = new bootstrap.Modal(document.getElementById('modalEditarPaciente'));
    modal.show();
}

function abrirFichaClinica(idPaciente, nombreCompleto) {
    document.getElementById('fichaPacienteId').value = idPaciente;
    document.getElementById('fichaPacienteTitulo').innerText = 'Expediente Clínico: ' + nombreCompleto;
    
    limpiarOdontograma();
    document.getElementById('formEvolucion').reset();

    fetchConSesion(`get_historial_paciente.php?paciente_id=${idPaciente}`)
        .then(data => {
            if (data.status === 'success') {
                data.odontograma.forEach(item => {
                    const toothBox = document.querySelector(`.tooth-box[data-diente="${item.diente_nro}"]`);
                    if (toothBox) {
                        const polygon = toothBox.querySelector(`polygon[data-cara="${item.cara}"]`);
                        if (polygon) polygon.classList.add('tratado');
                    }
                });

                const tbody = document.getElementById('tablaHistorialEvoluciones');
                if (data.evoluciones.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Sin atenciones registradas</td></tr>';
                } else {
                    tbody.innerHTML = data.evoluciones.map(evo => `
                        <tr>
                            <td><small>${new Date(evo.fecha).toLocaleDateString()}</small></td>
                            <td><span class="badge bg-secondary">${evo.diente_nro ? 'D' + evo.diente_nro : 'General'}</span></td>
                            <td>
                                <div><b>${evo.tratamiento}</b></div>
                                ${evo.diagnostico ? `<small class="text-muted">${evo.diagnostico}</small>` : ''}
                            </td>
                        </tr>
                    `).join('');
                }
            }
        })
        .catch(err => {
            if (err.message !== 'SESSION_EXPIRED') console.error(err);
        });

    const modal = new bootstrap.Modal(document.getElementById('modalFichaClinica'));
    modal.show();
}

function marcarCara(elemento) {
    document.querySelectorAll('.tooth-face.activa-seleccion').forEach(el => el.classList.remove('activa-seleccion'));

    const dienteBox = elemento.closest('.tooth-box');
    const nroDiente = dienteBox.getAttribute('data-diente');
    const nombreCara = elemento.getAttribute('data-cara');

    document.getElementById('evoDiente').value = nroDiente;
    document.getElementById('evoCara').value = nombreCara;
    
    elemento.classList.add('activa-seleccion');
    caraSeleccionadaActual = elemento;
}

function limpiarOdontograma() {
    document.querySelectorAll('.tooth-face').forEach(el => {
        el.classList.remove('tratado', 'pendiente', 'activa-seleccion');
    });
    caraSeleccionadaActual = null;
}

function abrirModalEditarTurno(turno) {
    document.getElementById('editTurnoId').value = turno.id;
    document.getElementById('editPacienteNombre').innerText = `Paciente: ${turno.paciente_nombre}`;
    document.getElementById('editMotivoTexto').innerText = `Motivo: ${turno.motivo}`;
    
    document.getElementById('editTurnoFecha').value = turno.fecha;
    document.getElementById('editTurnoHoraInicio').value = turno.hora_inicio;
    document.getElementById('editTurnoHoraFin').value = turno.hora_fin;

    const modalEditar = new bootstrap.Modal(document.getElementById('modalDetalleTurno'));
    modalEditar.show();
}

function guardarEdicionTurno() {
    const form = document.getElementById('formEditarTurno');
    const formData = new FormData(form);

    fetch('editar_horario_turno.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            const modalEl = document.getElementById('modalDetalleTurno');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

            if (calendar) calendar.refetchEvents();
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(err => console.error('Error al actualizar turno:', err));
}

function confirmarEliminarTurno() {
    const id = document.getElementById('editTurnoId').value;

    if (confirm('¿Estás seguro de que querés eliminar este turno? Esta acción no se puede deshacer.')) {
        fetch('eliminar_turno.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${encodeURIComponent(id)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const modalEl = document.getElementById('modalDetalleTurno');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();

                if (calendar) calendar.refetchEvents();
                alert('🗑️ Turno eliminado correctamente');
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(err => console.error('Error al eliminar turno:', err));
    }
}

function actualizarTurnoPorDragDrop(event) {
    const inicio = event.start;
    const fin = event.end || event.start;

    const pad = num => String(num).padStart(2, '0');
    const fechaStr = `${inicio.getFullYear()}-${pad(inicio.getMonth() + 1)}-${pad(inicio.getDate())}`;
    const horaInicioStr = `${pad(inicio.getHours())}:${pad(inicio.getMinutes())}`;
    const horaFinStr = `${pad(fin.getHours())}:${pad(fin.getMinutes())}`;

    const formData = new URLSearchParams();
    formData.append('id', event.id);
    formData.append('fecha', fechaStr);
    formData.append('hora_inicio', horaInicioStr);
    formData.append('hora_fin', horaFinStr);

    fetch('editar_horario_turno.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData.toString()
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            console.log('Turno reubicado con éxito');
        } else {
            alert('❌ No se pudo mover el turno: ' + data.message);
            event.revert();
        }
    })
    .catch(err => {
        console.error('Error al reubicar turno:', err);
        event.revert();
    });
}

    // Forzar la asignación del ID cuando elegís un paciente en el buscador
    $(document).on('click', '.list-group-item, #btnContinuarClinica, #btnContinuarNuevoClinica', function() {
        // Busca cualquier valor de ID presente en la pantalla
        let idEncontrado = $('#idPacienteSeleccionadoClinica').val() 
                        || $('#paciente_id').val() 
                        || $('#id_paciente').val();
                        
        if (idEncontrado) {
            $('#paciente_id').val(idEncontrado);
            $('#idPacienteSeleccionadoClinica').val(idEncontrado);
            $('#id_paciente').val(idEncontrado);
        }
    });

    // Interceptar el guardado para verificar que no vaya vacío
    $('#btnGuardarAnamnesis').on('click', function() {
        let idFinal = $('#paciente_id').val() || $('#idPacienteSeleccionadoClinica').val() || $('#id_paciente').val();
        
        if (!idFinal || idFinal === "") {
            console.warn("Atención: El ID del paciente está vacío en los inputs ocultos.");
        }
    });


    // 1. Restauramos el guardado estándar apuntando siempre al ID activo
document.getElementById('btnGuardarAnamnesis')?.addEventListener('click', function() {
    let pacienteId = document.getElementById('idPacienteSeleccionadoClinica')?.value 
                  || document.getElementById('paciente_id')?.value 
                  || document.getElementById('id_paciente')?.value;

    if (!pacienteId || parseInt(pacienteId) <= 0) {
        alert("⚠️ Seleccioná un paciente en la sección de Pacientes antes de cargar la ficha.");
        
        // Redirigir a la pestaña/sección de Pacientes si la ficha se abrió sin paciente
        irASeccionPacientes(); 
        return;
    }

    const formData = new FormData();
    formData.append('paciente_id', pacienteId);

    const body = document.getElementById('bodyAnamnesis');
    body.querySelectorAll('input, select').forEach(el => {
        if (el.id === 'paciente_id' || el.id === 'idPacienteSeleccionadoClinica' || el.id === 'id_paciente') {
            return;
        }

        if (el.type === 'checkbox') {
            if (el.checked) formData.append(el.id, '1');
        } else {
            formData.append(el.id, el.value);
        }
    });

    fetchConSesion('guardar_anamnesis.php', {
        method: 'POST',
        body: formData
    })
    .then(data => {
        if (data.status === 'success') {
            alert('✅ Anamnesis guardada correctamente.');
            bloquearAnamnesis(true);
        } else {
            alert('⚠️ Error al guardar: ' + data.message);
        }
    })
    .catch(err => console.error("Error en la solicitud:", err));
});

// 2. Función para redirigir/activar la solapa o sección de Pacientes
function irASeccionPacientes() {
    // Si usás pestañas/tabs (por ejemplo Bootstrap o lógica JS propia)
    const btnTabPacientes = document.getElementById('tab-pacientes') || document.querySelector('[href="#pacientes"]');
    if (btnTabPacientes) {
        btnTabPacientes.click();
    }
}

// 3. Carga normal: Solo se ejecuta al seleccionar el paciente en la lista
function cargarAnamnesis(pacienteId) {
    if (!pacienteId || parseInt(pacienteId) <= 0) {
        alert("Primero debés seleccionar un paciente cargado.");
        irASeccionPacientes();
        return;
    }

    fetchConSesion(`obtener_anamnesis.php?paciente_id=${pacienteId}`)
        .then(data => {
            if (data.status === 'success' && data.anamnesis) {
                completarAnamnesis(data.anamnesis);
                bloquearAnamnesis(true);
            } else {
                // Si es un paciente nuevo que se acaba de seleccionar, 
                // abre la ficha limpia y lista para su primer llenado.
                desmarcarTodosLosCampos(); 
                bloquearAnamnesis(false);
            }
        })
        .catch(err => console.error("Error al cargar anamnesis:", err));
}

// Función auxiliar simple para limpiar checkboxes en nuevos registros
function desmarcarTodosLosCampos() {
    const body = document.getElementById('bodyAnamnesis');
    if (!body) return;
    body.querySelectorAll('input, select').forEach(el => {
        if (el.type === 'checkbox') el.checked = false;
        else if (el.type !== 'hidden') el.value = '';
    });
}

</script>
</body>
</html>
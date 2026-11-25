<?php
// =====================================================================
// guardar.php  -  Recibe el formulario y lo guarda en la tabla "materias"
// Ubicación: C:\xampp\htdocs\materias\guardar.php
// =====================================================================
header('Content-Type: application/json; charset=utf-8');

// ---- Configuración de conexión (cambia DB_PASS por la que pusiste en 02_usuario_app.sql)
const DB_HOST = '127.0.0.1';
const DB_PORT = 3308;   // tu MySQL de XAMPP usa el puerto 3308
const DEBUG   = true;   // true = muestra el error real en la alerta. Ponlo en false cuando todo funcione.
const DB_NAME = 'uaq_registro_materias';
const DB_USER = 'root';
const DB_PASS = '';

function responder(bool $ok, string $mensaje, array $extra = [], int $codigo = 200): void {
    http_response_code($codigo);
    echo json_encode(array_merge(['ok' => $ok, 'mensaje' => $mensaje], $extra), JSON_UNESCAPED_UNICODE);
    exit;
}

function texto(string $campo): string {
    return isset($_POST[$campo]) && !is_array($_POST[$campo]) ? trim((string)$_POST[$campo]) : '';
}

function lista(string $campo): array {
    $valores = $_POST[$campo] ?? [];
    if (!is_array($valores)) return [];
    $salida = [];
    foreach ($valores as $v) {
        $v = trim((string)$v);
        if ($v !== '') $salida[] = $v;
    }
    return $salida;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(false, 'Método no permitido.', [], 405);
}

// ---------------------------------------------------------------------
// Lectura y validación (el servidor no debe confiar solo en el navegador)
// ---------------------------------------------------------------------
$errores = [];

$nombreFacultad = texto('nombreFacultad');
$campus         = texto('campus');
$carrera        = (int)texto('carrera');
$plan           = texto('planEstudios');
$periodo        = texto('periodoEstudios');
$nivel          = texto('nivelEducativo');

$clave          = texto('claveMateria');
$nombreMateria  = texto('nombreMateria');
$etapa          = (int)texto('etapa');
$preReq         = texto('preRequisitos');
$creditos       = (int)texto('creditos');

$horas = [
    'horas_teoria'        => texto('horasTeoria'),
    'horas_presenciales'  => texto('horasPresenciales'),
    'horas_independiente' => texto('horasIndependiente'),
    'horas_linea'         => texto('horasLinea'),
    'horas_virtuales'     => texto('horasVirtuales'),
    'horas_campo'         => texto('horasCampo'),
    'horas_laboratorio'   => texto('horasLaboratorio'),
];

$modalidad = texto('modalidadPlan');
$opcion    = texto('opcionEducativa');

if ($nombreFacultad === '' || $campus === '')                     $errores[] = 'Facultad y campus son obligatorios.';
if ($carrera < 1 || $carrera > 7)                                  $errores[] = 'Carrera no válida.';
if (!preg_match('/^[A-Z]{3}[0-9]{2}$/', $plan))                    $errores[] = 'Plan de estudios no válido.';
if (!preg_match('/^20[0-3][0-9]-[1-2]$/', $periodo))               $errores[] = 'Período no válido.';
if (!in_array($nivel, ['Licenciatura', 'Maestría', 'Doctorado'], true)) $errores[] = 'Nivel educativo no válido.';
if ($clave === '' || $nombreMateria === '')                        $errores[] = 'Clave y nombre de la materia son obligatorios.';
if ($etapa < 1 || $etapa > 12)                                     $errores[] = 'Eje/área/etapa no válida.';
if ($creditos < 1 || $creditos > 20)                               $errores[] = 'Créditos fuera de rango (1 a 20).';
if (!in_array($modalidad, ['Escolarizada', 'No Escolarizada', 'Mixta', 'Dual'], true)) $errores[] = 'Modalidad no válida.';
if (!in_array($opcion, ['Presencial', 'En Línea', 'Dual', 'Mixta'], true))             $errores[] = 'Opción educativa no válida.';

foreach ($horas as $nombre => $valor) {
    if ($valor === '' || !ctype_digit($valor) || (int)$valor > 65535) {
        $errores[] = "Valor de horas no válido ($nombre).";
    } else {
        $horas[$nombre] = (int)$valor;
    }
}

$objetivoGeneral = texto('objetivoGeneral');
$introduccion    = texto('introduccion');
$justificacion   = texto('justificacion');
$perfilDocente   = texto('perfilDocente');
$evalDiag        = texto('evalDiagnostica');
$evalForm        = texto('evalFormativa');
$evalSum         = texto('evalSumativa');
$evalInd         = texto('evalIndependientes');

foreach ([
    'Objetivo general' => $objetivoGeneral, 'Justificación' => $justificacion,
    'Perfil docente' => $perfilDocente, 'Evaluación diagnóstica' => $evalDiag,
    'Evaluación formativa' => $evalForm, 'Evaluación sumativa' => $evalSum,
    'Evaluación independientes' => $evalInd,
] as $etiqueta => $valor) {
    if ($valor === '') $errores[] = "$etiqueta es obligatorio.";
}

$objetivos    = lista('objetivoEspecifico');
$competencias = lista('competencias');
$estrategias  = lista('estrategias');
$instrumentos = lista('instrumentosEval');
$bibliografia = lista('bibliografia');

foreach ([
    'objetivo específico' => $objetivos, 'competencia' => $competencias,
    'estrategia' => $estrategias, 'instrumento de evaluación' => $instrumentos,
    'referencia bibliográfica' => $bibliografia,
] as $etiqueta => $arreglo) {
    if (count($arreglo) === 0) $errores[] = "Debe haber al menos un(a) $etiqueta.";
}

// Unidades con sus subtemas (campos: tituloUnidad[] y subtema_1[], subtema_2[], ...)
$titulos = lista('tituloUnidad');
$unidades = [];
if (count($titulos) === 0 || count($titulos) > 20) {
    $errores[] = 'Debe haber entre 1 y 20 unidades.';
}
foreach ($titulos as $i => $titulo) {
    $subtemas = lista('subtema_' . ($i + 1));
    if (count($subtemas) === 0) $errores[] = 'La unidad ' . ($i + 1) . ' necesita al menos un subtema.';
    $unidades[] = ['unidad' => $i + 1, 'titulo' => $titulo, 'subtemas' => $subtemas];
}

// Ponderación: rubros + porcentajes (deben sumar 100)
$rubros      = isset($_POST['rubroEval']) && is_array($_POST['rubroEval']) ? $_POST['rubroEval'] : [];
$porcentajes = isset($_POST['porcentajeEval']) && is_array($_POST['porcentajeEval']) ? $_POST['porcentajeEval'] : [];
$ponderacion = [];
$suma = 0.0;
foreach ($rubros as $i => $rubro) {
    $rubro = trim((string)$rubro);
    $pct   = $porcentajes[$i] ?? '';
    if ($rubro === '' || !is_numeric($pct)) {
        $errores[] = 'Hay un rubro de ponderación incompleto.';
        continue;
    }
    $ponderacion[] = ['rubro' => $rubro, 'porcentaje' => (float)$pct + 0];
    $suma += (float)$pct;
}
if (count($ponderacion) === 0 || abs($suma - 100) > 0.001) {
    $errores[] = 'La ponderación debe sumar exactamente 100%.';
}

if ($errores) {
    responder(false, implode(' ', array_unique($errores)), [], 422);
}

// ---------------------------------------------------------------------
// Inserción con consulta preparada (evita inyección SQL)
// ---------------------------------------------------------------------
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false]
    );

    $sql = 'INSERT INTO materias (
                nombre_facultad, campus, carrera, plan_estudios, periodo, nivel_educativo,
                clave_materia, nombre_materia, etapa, pre_requisitos, creditos,
                horas_teoria, horas_presenciales, horas_independiente, horas_linea,
                horas_virtuales, horas_campo, horas_laboratorio, modalidad_plan, opcion_educativa,
                objetivo_general, objetivos_especificos, introduccion, justificacion, competencias,
                unidades, estrategias, eval_diagnostica, eval_formativa, eval_sumativa,
                eval_independientes, instrumentos_eval, ponderacion, perfil_docente, bibliografia
            ) VALUES (
                :nombre_facultad, :campus, :carrera, :plan_estudios, :periodo, :nivel_educativo,
                :clave_materia, :nombre_materia, :etapa, :pre_requisitos, :creditos,
                :horas_teoria, :horas_presenciales, :horas_independiente, :horas_linea,
                :horas_virtuales, :horas_campo, :horas_laboratorio, :modalidad_plan, :opcion_educativa,
                :objetivo_general, :objetivos_especificos, :introduccion, :justificacion, :competencias,
                :unidades, :estrategias, :eval_diagnostica, :eval_formativa, :eval_sumativa,
                :eval_independientes, :instrumentos_eval, :ponderacion, :perfil_docente, :bibliografia
            )';

    $json = fn($v) => json_encode($v, JSON_UNESCAPED_UNICODE);

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre_facultad'       => $nombreFacultad,
        ':campus'                => $campus,
        ':carrera'               => $carrera,
        ':plan_estudios'         => $plan,
        ':periodo'               => $periodo,
        ':nivel_educativo'       => $nivel,
        ':clave_materia'         => $clave,
        ':nombre_materia'        => $nombreMateria,
        ':etapa'                 => $etapa,
        ':pre_requisitos'        => $preReq !== '' ? $preReq : null,
        ':creditos'              => $creditos,
        ':horas_teoria'          => $horas['horas_teoria'],
        ':horas_presenciales'    => $horas['horas_presenciales'],
        ':horas_independiente'   => $horas['horas_independiente'],
        ':horas_linea'           => $horas['horas_linea'],
        ':horas_virtuales'       => $horas['horas_virtuales'],
        ':horas_campo'           => $horas['horas_campo'],
        ':horas_laboratorio'     => $horas['horas_laboratorio'],
        ':modalidad_plan'        => $modalidad,
        ':opcion_educativa'      => $opcion,
        ':objetivo_general'      => $objetivoGeneral,
        ':objetivos_especificos' => $json($objetivos),
        ':introduccion'          => $introduccion !== '' ? $introduccion : null,
        ':justificacion'         => $justificacion,
        ':competencias'          => $json($competencias),
        ':unidades'              => $json($unidades),
        ':estrategias'           => $json($estrategias),
        ':eval_diagnostica'      => $evalDiag,
        ':eval_formativa'        => $evalForm,
        ':eval_sumativa'         => $evalSum,
        ':eval_independientes'   => $evalInd,
        ':instrumentos_eval'     => $json($instrumentos),
        ':ponderacion'           => $json($ponderacion),
        ':perfil_docente'        => $perfilDocente,
        ':bibliografia'          => $json($bibliografia),
    ]);

    responder(true, 'Materia guardada correctamente.', ['id' => (int)$pdo->lastInsertId()]);

} catch (PDOException $e) {
    // El detalle técnico queda en el log de Apache; al navegador solo un mensaje genérico
    error_log('[guardar.php] ' . $e->getMessage());
    $detalle = DEBUG ? ' [Detalle técnico: ' . $e->getMessage() . ']' : ' Revisa C:\\xampp\\apache\\logs\\error.log';
    responder(false, 'No se pudo guardar en la base de datos.' . $detalle, [], 500);
}
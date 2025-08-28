<?php
// api/alta_emisor.php

// --- 1. Configuración y Headers ---
header('Content-Type: application/json');

// --- 2. Constantes de Configuración ---
// ¡CAMBIA ESTE TOKEN POR UNO MUY SEGURO Y LARGO! Es la llave de tu API.
define('SECRET_ADMIN_TOKEN', 'TuTokenSuperSecreto12345!@#$');

// Definimos las rutas seguras usando __DIR__ para que siempre sean relativas al script
define('CERT_DIR', __DIR__ . '/../certificados/');
define('CONFIG_DIR', __DIR__ . '/../config/');
define('PASSWORD_FILE', CONFIG_DIR . 'passwords.ini');

require_once __DIR__ . '/security.php';

// --- 3. Verificación del Método HTTP ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Método no permitido. Use POST.']);
    exit();
}


$token = getBearerToken();

if ($token !== SECRET_ADMIN_TOKEN) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Acceso no autorizado. Token inválido o ausente.']);
    exit();
}

// --- 5. Lógica Principal ---
try {
    // --- 5.1. Validación de Entradas (parámetros y archivos) ---
    if (!isset($_POST['rfc']) || empty(trim($_POST['rfc']))) {
        throw new \InvalidArgumentException('El parámetro "rfc" es requerido.');
    }
    if (!isset($_POST['password']) || empty($_POST['password'])) {
        throw new \InvalidArgumentException('El parámetro "password" es requerido.');
    }
    if (!isset($_FILES['cer_file']) || $_FILES['cer_file']['error'] !== UPLOAD_ERR_OK) {
        throw new \RuntimeException('Error al subir el archivo .cer o no fue enviado.');
    }
    if (!isset($_FILES['key_file']) || $_FILES['key_file']['error'] !== UPLOAD_ERR_OK) {
        throw new \RuntimeException('Error al subir el archivo .key o no fue enviado.');
    }

    $rfc = trim(strtoupper($_POST['rfc']));
    $password = $_POST['password'];
    $cerFile = $_FILES['cer_file'];
    $keyFile = $_FILES['key_file'];

    // Validar formato de RFC
    if (!preg_match('/^[A-Z&Ñ]{3,4}\d{6}[A-Z\d]{3}$/', $rfc)) {
        throw new \InvalidArgumentException('El formato del RFC no es válido.');
    }

    // Validar extensiones de archivo
    if (strtolower(pathinfo($cerFile['name'], PATHINFO_EXTENSION)) !== 'cer') {
        throw new \InvalidArgumentException('El primer archivo debe tener extensión .cer');
    }
    if (strtolower(pathinfo($keyFile['name'], PATHINFO_EXTENSION)) !== 'key') {
        throw new \InvalidArgumentException('El segundo archivo debe tener extensión .key');
    }

    // --- 5.2. Preparación de Directorios Seguros ---
    if (!is_dir(CERT_DIR)) mkdir(CERT_DIR, 0755, true);
    if (!is_dir(CONFIG_DIR)) mkdir(CONFIG_DIR, 0755, true);
    if (!is_writable(CERT_DIR) || !is_writable(CONFIG_DIR)) {
        throw new \RuntimeException('Los directorios de almacenamiento no tienen permisos de escritura.');
    }

    // --- 5.3. Guardado de Archivos de Certificado ---
    $newCerPath = CERT_DIR . $rfc . '.cer';
    $newKeyPath = CERT_DIR . $rfc . '.key';

    if (!move_uploaded_file($cerFile['tmp_name'], $newCerPath)) {
        throw new \RuntimeException('No se pudo guardar el archivo .cer.');
    }
    if (!move_uploaded_file($keyFile['tmp_name'], $newKeyPath)) {
        // Si el .key falla, borramos el .cer para no dejar archivos huérfanos
        unlink($newCerPath);
        throw new \RuntimeException('No se pudo guardar el archivo .key.');
    }

    // --- 5.4. Guardado/Actualización de Contraseña ---
    $passwords = file_exists(PASSWORD_FILE) ? parse_ini_file(PASSWORD_FILE, true) : ['passwords' => []];
    $passwords['passwords'][$rfc] = $password;

    // Re-escribimos el archivo .ini para asegurar su formato correcto
    $iniContent = "[passwords]\n";
    foreach ($passwords['passwords'] as $key => $value) {
        // Escapamos comillas dobles en la contraseña por si acaso
        $escapedValue = str_replace('"', '\"', $value);
        $iniContent .= "$key = \"$escapedValue\"\n";
    }

    if (file_put_contents(PASSWORD_FILE, $iniContent, LOCK_EX) === false) {
        // Si falla el guardado de la contraseña, borramos los certificados
        unlink($newCerPath);
        unlink($newKeyPath);
        throw new \RuntimeException('No se pudo guardar la contraseña del CSD.');
    }
    
    // --- 5.5. Respuesta de Éxito ---
    http_response_code(200); // OK
    echo json_encode([
        'success' => true,
        'message' => 'Emisor "' . $rfc . '" configurado correctamente.'
    ]);

} catch (\Exception $e) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
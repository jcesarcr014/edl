<?php

// ---- INICIO: CÓDIGO DE DEPURACIÓN ----
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// api/timbrar_cfdi40.php

header('Content-Type: application/json');
date_default_timezone_set('America/Mexico_City');

// --- 1. CONFIGURACIÓN Y SEGURIDAD ---
// Usaremos el mismo token que para el alta. En un futuro podrías usar uno diferente.
define('SECRET_API_TOKEN', 'TuTokenSuperSecreto12345!@#$'); // ¡Debe ser el mismo que en alta_emisor.php!

define('CERT_DIR', __DIR__ . '/../certificados/');
define('PASSWORD_FILE', __DIR__ . '/../config/passwords.ini');

require_once __DIR__ . '/security.php';

// Requerimos el autoload de composer para tener acceso a las clases de la librería
require_once __DIR__ . '/../vendor/autoload.php';
// Requerimos las clases de Utils que provee el ejemplo de la librería
require_once __DIR__ . '/../ejemplos/Utils/Utils.php'; 
require_once __DIR__ . '/../ejemplos/Data/Constants.php'; 

// --- Namespaces de la librería EDL ---
use Facturando\Ecodex\Proveedor;
use Facturando\Ecodex\Timbrado\Parameters;
use Facturando\EDL\Example\Data\Constants;
use Facturando\EDL\Example\Utils\Utils;
use Facturando\ElectronicDocumentLibrary\Base\Types\ProcessProviderResult;
use Facturando\ElectronicDocumentLibrary\Certificate\ElectronicCertificate;
use Facturando\ElectronicDocumentLibrary\Document\ElectronicDocument;

// --- Funciones de Seguridad (Bearer Token) ---


$token = getBearerToken();

if ($token !== SECRET_API_TOKEN) {
    http_response_code(401);
    echo json_encode(['error' => 'Acceso no autorizado.']);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido.']);
    exit();
}

// --- 2. LÓGICA DE TIMBRADO ---
try {
    $jsonInput = file_get_contents('php://input');
    $data = json_decode($jsonInput, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('JSON inválido en el cuerpo de la petición.');
    }

    $emisorRfc = $data['emisor']['rfc'];
    if (empty($emisorRfc)) {
        throw new Exception('El RFC del emisor es requerido.');
    }

    // --- 2.1. Cargar Certificado y Contraseña Dinámicamente ---
    $pathCer = CERT_DIR . $emisorRfc . '.cer';
    $pathKey = CERT_DIR . $emisorRfc . '.key';
    if (!file_exists($pathCer) || !file_exists($pathKey)) {
        throw new Exception("No se encontraron los CSD para el RFC: $emisorRfc");
    }
    $passwords = parse_ini_file(PASSWORD_FILE);
    if (!isset($passwords[$emisorRfc])) {
        throw new Exception("No se encontró la contraseña para el RFC: $emisorRfc");
    }
    $password = $passwords[$emisorRfc];

    // --- 2.2. Preparar Documento Electrónico ---
    $electronicDocument = new ElectronicDocument();
    $certificate = Utils::loadCertificateFromFile($pathCer, $pathKey, $password);
    $electronicDocument->Manage->Save->Certificate = $certificate;

    // --- 2.3. Mapear JSON al Objeto ElectronicDocument (El Corazón de la API) ---
    $electronicDocument->Data->clear();

    //<editor-fold desc="Datos del comprobante (FIJOS)">
    $electronicDocument->Data->Version->Value = '4.0';
    $electronicDocument->Data->Exportacion->Value = '01';
    $electronicDocument->Data->Folio->Value = 'CFDI40146';
    $electronicDocument->Data->FormaPago->Value = '01';
    $electronicDocument->Data->LugarExpedicion->Value = '89400';
    $electronicDocument->Data->MetodoPago->Value = 'PUE';
    $electronicDocument->Data->Moneda->Value = 'MXN';
    $electronicDocument->Data->Serie->Value = 'CFDI40';
    $electronicDocument->Data->Fecha->Value = new \DateTime('NOW -5 hours'); // Corregido como en el ejemplo
    $electronicDocument->Data->SubTotal->Value = 10;
    $electronicDocument->Data->TipoComprobante->Value = 'I';
    $electronicDocument->Data->Total->Value = 10.32;
    //</editor-fold>

    //<editor-fold desc="CFDI Relacionados (FIJO)">
    $relacionados = $electronicDocument->Data->CfdiRelacionadosExt->add();
    $relacionados->CfdiRelacionados->TipoRelacion->Value = '01';
    $relacionados->CfdiRelacionados->add()->Uuid->Value = 'A39DA66B-52CA-49E3-879B-5C05185B0EF7';
    //</editor-fold>

    //<editor-fold desc="Datos del Emisor (DINÁMICOS del JSON)">
    $emisorData = $data['emisor'];
    $electronicDocument->Data->Emisor->Rfc->Value = $emisorData['rfc'];
    $electronicDocument->Data->Emisor->Nombre->Value = $emisorData['nombre'];
    $electronicDocument->Data->Emisor->RegimenFiscal->Value = $emisorData['regimenFiscal'];
    //</editor-fold>

    //<editor-fold desc="Datos del Receptor (DINÁMICOS del JSON)">
    $receptorData = $data['receptor'];
    $electronicDocument->Data->Receptor->Rfc->Value = $receptorData['rfc'];
    $electronicDocument->Data->Receptor->Nombre->Value = $receptorData['nombre'];
    $electronicDocument->Data->Receptor->DomicilioFiscalReceptor->Value = $receptorData['domicilioFiscal'];
    $electronicDocument->Data->Receptor->RegimenFiscalReceptor->Value = $receptorData['regimenFiscal'];
    $electronicDocument->Data->Receptor->UsoCfdi->Value = $receptorData['usoCfdi'];
    //</editor-fold>

    //<editor-fold desc="Concepto (FIJO)">
    $concepto = $electronicDocument->Data->Conceptos->add();
    $concepto->Cantidad->Value = 2;
    $concepto->ClaveProductoServicio->Value = '78101500';
    $concepto->ClaveUnidad->Value = 'CMT';
    $concepto->Descripcion->Value = 'ACERO';
    $concepto->Importe->Value = 10;
    $concepto->NumeroIdentificacion->Value = '00001';
    $concepto->ObjetoImpuesto->Value = '02';
    $concepto->Unidad->Value = 'TONELADA';
    $concepto->ValorUnitario->Value = 5;

    //<editor-fold desc="Impuestos trasladados del concepto (FIJOS)">
    $trasladoConcepto = $concepto->Impuestos->Traslados->add();
    $trasladoConcepto->Base->Value = 10; // CORREGIDO: La base es el importe del concepto
    $trasladoConcepto->Importe->Value = 1.60; // CORREGIDO: El importe es 10 * 0.16
    $trasladoConcepto->Impuesto->Value = '002';
    $trasladoConcepto->TipoFactor->Value = 'Tasa';
    $trasladoConcepto->TasaCuota->Value = 0.160000;
    //</editor-fold>

    //<editor-fold desc="Impuestos retenidos del concepto (FIJOS)">
    $retencionConcepto = $concepto->Impuestos->Retenciones->add();
    $retencionConcepto->Base->Value = 10; // CORREGIDO
    $retencionConcepto->Importe->Value = 0;
    $retencionConcepto->Impuesto->Value = '002'; // El ejemplo usa '002' para IVA
    $retencionConcepto->TipoFactor->Value = 'Tasa';
    $retencionConcepto->TasaCuota->Value = 0;
    //</editor-fold>

    //<editor-fold desc="Cuenta predial del concepto (FIJO)">
    $concepto->CuentasPrediales->add()->Numero->Value = "51888";
    //</editor-fold>
    //</editor-fold>

    //<editor-fold desc="Impuestos Globales (FIJOS)">
    //<editor-fold desc="Impuestos trasladados">
    $traslado = $electronicDocument->Data->Impuestos->Traslados->add();
    $traslado->Base->Value = 10; // CORREGIDO
    $traslado->Importe->Value = 1.60; // CORREGIDO
    $traslado->Tipo->Value = '002';
    $traslado->TipoFactor->Value = 'Tasa';
    $traslado->TasaCuota->Value = 0.160000;
    $electronicDocument->Data->Impuestos->TotalTraslados->Value = 1.60; // CORREGIDO
    //</editor-fold>

    //<editor-fold desc="Impuestos retenidos">
    $retencion = $electronicDocument->Data->Impuestos->Retenciones->add();
    $retencion->Tipo->Value = '002';
    $retencion->Importe->Value = 0;
    $electronicDocument->Data->Impuestos->TotalRetenciones->Value = 0;
    
    

    

    // --- 2.4. Llamada al Servicio de Timbrado ---
    $parameters = new Parameters();
    $parameters->Rfc = Constants::RFC_INTEGRADOR;
    $parameters->Usuario = Constants::ID_INTEGRADOR;
    $parameters->IdTransaccion = PHP_INT_MAX; // ID de transacción único
    $parameters->ElectronicDocument = $electronicDocument;

    $ecodex = new Proveedor();

    $result = $ecodex->TimbrarCfdi($parameters);

    // --- 2.5. Procesar Respuesta ---
    if ($result == ProcessProviderResult::OK) {
        $electronicDocument->Manage->Save->Options->Validations = false;
        $electronicDocument->saveToString($xml);

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'uuid' => $parameters->Information->Timbre->Uuid,
            'xml' => base64_encode($xml) // Devolvemos el XML en Base64
        ]);
    } else {
        $errorDetails = is_string($parameters->Information->Error) ? $parameters->Information->Error : json_encode($parameters->Information->Error, JSON_PRETTY_PRINT);
        throw new Exception('Error del PAC: ' . $errorDetails);
    }

} catch (\Exception $e) {
    http_response_code(400); // Bad Request o Internal Error
    echo json_encode([
        'success' => false,
        'error' => 'Error al procesar la factura.',
        'details' => $e->getMessage()
    ]);
}
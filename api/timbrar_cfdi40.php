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
    $comprobanteData = $data['comprobante'];
    $electronicDocument->Data->clear();
    $electronicDocument->Data->Version->Value = '4.0';
    $electronicDocument->Data->Exportacion->Value = '01';
    //$electronicDocument->Data->Serie->Value = $comprobanteData['serie'];
    $electronicDocument->Data->Folio->Value = 'CFDI40146';
    $electronicDocument->Data->FormaPago->Value = '01';
    $electronicDocument->Data->LugarExpedicion->Value = '89400';
    $electronicDocument->Data->MetodoPago->Value = 'PUE';
    $electronicDocument->Data->Moneda->Value = 'MXN';
    $electronicDocument->Data->Serie->Value = 'CFDI40';
    $electronicDocument->Data->Fecha->Value = new \DateTime();
    $electronicDocument->Data->TipoComprobante->Value = 'I';
    $electronicDocument->Data->Exportacion->Value = '01'; // Ajusta si es necesario


    // Emisor
    $emisorData = $data['emisor'];
    $electronicDocument->Data->Emisor->Rfc->Value = $emisorData['rfc'];
    $electronicDocument->Data->Emisor->Nombre->Value = $emisorData['nombre'];
    $electronicDocument->Data->Emisor->RegimenFiscal->Value = $emisorData['regimenFiscal'];

    // Receptor
    $receptorData = $data['receptor'];
    $electronicDocument->Data->Receptor->Rfc->Value = $receptorData['rfc'];
    $electronicDocument->Data->Receptor->Nombre->Value = $receptorData['nombre'];
    $electronicDocument->Data->Receptor->DomicilioFiscalReceptor->Value = $receptorData['domicilioFiscal'];
    $electronicDocument->Data->Receptor->RegimenFiscalReceptor->Value = $receptorData['regimenFiscal'];
    $electronicDocument->Data->Receptor->UsoCfdi->Value = $receptorData['usoCfdi'];

    // Conceptos
    foreach ($data['conceptos'] as $conceptoData) {
        $concepto = $electronicDocument->Data->Conceptos->add();
        $concepto->Cantidad->Value = $conceptoData['cantidad'];
        $concepto->ClaveProductoServicio->Value = $conceptoData['claveProdServ'];
        $concepto->ClaveUnidad->Value = $conceptoData['claveUnidad'];
        $concepto->Descripcion->Value = $conceptoData['descripcion'];
        $concepto->Importe->Value = round($conceptoData['cantidad'] * $conceptoData['valorUnitario'], 2);
        $concepto->NumeroIdentificacion->Value = '00001';
        $concepto->ObjetoImpuesto->Value = '02';
        $concepto->Unidad->Value = $conceptoData['unidad'];
        $concepto->ValorUnitario->Value = $conceptoData['valorUnitario'];

        $trasladoConcepto = $concepto->Impuestos->Traslados->add();
        $trasladoConcepto->Base->Value = 2;
        $trasladoConcepto->Importe->Value = 0.32;
        $trasladoConcepto->Impuesto->Value = '002';
        $trasladoConcepto->TipoFactor->Value = 'Tasa';
        $trasladoConcepto->TasaCuota->Value = 0.160000;
        
        $retencionConcepto = $concepto->Impuestos->Retenciones->add();
        $retencionConcepto->Base->Value = 2;
        $retencionConcepto->Importe->Value = 0;
        $retencionConcepto->Impuesto->Value = '002';
        $retencionConcepto->TipoFactor->Value = 'Tasa';
        $retencionConcepto->TasaCuota->Value = 0;

        $concepto->CuentasPrediales->add()->Numero->Value = "51888";
        // Impuestos del Concepto
        
        
       
    }

    $traslado = $electronicDocument->Data->Impuestos->Traslados->add();
    $traslado->Base->Value = 2;
    $traslado->Importe->Value = 0.32;
    $traslado->Tipo->Value = '002';
    $traslado->TipoFactor->Value = 'Tasa';
    $traslado->TasaCuota->Value = 0.160000;
    
    $electronicDocument->Data->Impuestos->TotalTraslados->Value = 0.32;
    
    $retencion = $electronicDocument->Data->Impuestos->Retenciones->add();
    $retencion->Tipo->Value = '002';
    $retencion->Importe->Value = 0;

    $electronicDocument->Data->Impuestos->TotalRetenciones->Value = 0;



    
    $totalesData = $data['totales'];
    $electronicDocument->Data->SubTotal->Value = $totalesData['subtotal'];
    $electronicDocument->Data->Total->Value = $totalesData['total'];
    
    

    

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
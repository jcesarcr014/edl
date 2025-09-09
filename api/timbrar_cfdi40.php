<?php

// ---- INICIO: CÓDIGO DE DEPURACIÓN (Déjalo por ahora, quítalo al pasar a producción) ----
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
date_default_timezone_set('America/Mexico_City');

// --- 1. CONFIGURACIÓN Y DEPENDENCIAS ---
define('SECRET_API_TOKEN', 'TuTokenSuperSecreto12345!@');
define('CERT_DIR', __DIR__ . '/../certificados/');
define('PASSWORD_FILE', __DIR__ . '/../config/passwords.ini');

require_once __DIR__ . '/security.php';
require_once __DIR__ . '/../vendor/autoload.php';
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

// --- 2. VALIDACIÓN DE LA PETICIÓN ---
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

// --- 3. LÓGICA DE TIMBRADO DINÁMICO ---
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

    // Carga de Certificado Dinámico
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

    // Preparar Documento Electrónico
    $electronicDocument = new ElectronicDocument();
    $certificate = Utils::loadCertificateFromFile($pathCer, $pathKey, $password);
    $electronicDocument->Manage->Save->Certificate = $certificate;

    // --- Mapeo del JSON al Objeto ---
    $electronicDocument->Data->clear();

    // Comprobante
    $comprobanteData = $data['comprobante'];
    $electronicDocument->Data->Version->Value = '4.0';
    $electronicDocument->Data->Exportacion->Value = '01';
    $electronicDocument->Data->Folio->Value = 'CFDI40146';
    $electronicDocument->Data->FormaPago->Value = '01';
    $electronicDocument->Data->LugarExpedicion->Value = '89400';
    $electronicDocument->Data->MetodoPago->Value = 'PUE';
    $electronicDocument->Data->Moneda->Value = 'MXN';
    $electronicDocument->Data->Serie->Value = 'CFDI40';
    $electronicDocument->Data->Fecha->Value = new \DateTime('NOW -5 hours');
    $electronicDocument->Data->SubTotal->Value = 10;
    $electronicDocument->Data->TipoComprobante->Value = 'I';
    $electronicDocument->Data->Total->Value = 10.32;
    $relacionados = $electronicDocument->Data->CfdiRelacionadosExt->add();
    $relacionados->CfdiRelacionados->TipoRelacion->Value = '01';
    $relacionados->CfdiRelacionados->add()->Uuid->Value = 'A39DA66B-52CA-49E3-879B-5C05185B0EF7';

    // Emisor
    $electronicDocument->Data->Emisor->Rfc->Value = 'EKU9003173C9';
    $electronicDocument->Data->Emisor->Nombre->Value = 'ESCUELA KEMPER URGATE SA DE CV';
    $electronicDocument->Data->Emisor->RegimenFiscal->Value = '601';

    // Receptor
    $electronicDocument->Data->Receptor->Rfc->Value = 'AAAD770905441';
    $electronicDocument->Data->Receptor->Nombre->Value = 'DARIO ALVAREZ ARANDA';
    $electronicDocument->Data->Receptor->RegimenFiscalReceptor->Value = '612';
    $electronicDocument->Data->Receptor->UsoCfdi->Value = 'G03';
    $electronicDocument->Data->Receptor->DomicilioFiscalReceptor->Value = '07300';

    // Conceptos
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
    // Totales e Impuestos Globales
    

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
    

    // Llamada al Servicio de Timbrado
    $parameters = new Parameters();
    $parameters->Rfc = Constants::RFC_INTEGRADOR;
    $parameters->Usuario = Constants::ID_INTEGRADOR;
    $parameters->IdTransaccion = PHP_INT_MAX;
    $parameters->ElectronicDocument = $electronicDocument;

    $ecodex = new Proveedor();
    $result = $ecodex->TimbrarCfdi($parameters);

    // Procesar Respuesta
    if ($result == ProcessProviderResult::OK) {
        $electronicDocument->Manage->Save->Options->Validations = false;
        $electronicDocument->saveToString($xml);
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'uuid' => $parameters->Information->Timbre->Uuid,
            'xml' => $xml
        ]);
    } else {
        $errorDetails = is_string($parameters->Information->Error) ? $parameters->Information->Error : json_encode($parameters->Information->Error, JSON_PRETTY_PRINT);
        throw new Exception('Error del PAC: ' . $errorDetails);
    }

} catch (\Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Error al procesar la factura.',
        'details' => $e->getMessage()
    ]);
}
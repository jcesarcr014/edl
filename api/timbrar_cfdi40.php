<?php

// ---- INICIO: CÓDIGO DE DEPURACIÓN ----
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
date_default_timezone_set('America/Mexico_City');

// --- 1. CONFIGURACIÓN Y DEPENDENCIAS ---
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

// --- 2. VALIDACIÓN BÁSICA DE LA PETICIÓN ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido. Use POST.']);
    exit();
}

// --- 3. PRUEBA TOTALMENTE FIJA (IGNORANDO TODO LO DINÁMICO) ---
try {
    
    // --- 3.1. Usamos el CSD y la contraseña FIJOS del ejemplo que SÍ funciona ---
    $pathCer = __DIR__ . '/../certificados/EKU9003173C9.cer';
    $pathKey = __DIR__ . '/../certificados/EKU9003173C9.key';
    $password = '12345678a'; // Contraseña del CSD de prueba

    // Asegúrate de que este CSD de prueba exista en tu carpeta de certificados
    if (!file_exists($pathCer) || !file_exists($pathKey)) {
        throw new Exception("El CSD de prueba EKU9003173C9 no se encontró en la carpeta /certificados.");
    }

    // --- 3.2. Preparar Documento Electrónico ---
    $electronicDocument = new ElectronicDocument();
    $certificate = Utils::loadCertificateFromFile($pathCer, $pathKey, $password);
    $electronicDocument->Manage->Save->Certificate = $certificate;

    // --- 3.3. Llenado de datos (COPIA 1:1 DEL EJEMPLO FUNCIONAL) ---
    $electronicDocument->Data->clear();

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

    $electronicDocument->Data->Emisor->Rfc->Value = 'EKU9003173C9';
    $electronicDocument->Data->Emisor->Nombre->Value = 'ESCUELA KEMPER URGATE SA DE CV';
    $electronicDocument->Data->Emisor->RegimenFiscal->Value = '601';

    $electronicDocument->Data->Receptor->Rfc->Value = 'AAAD770905441';
    $electronicDocument->Data->Receptor->Nombre->Value = 'DARIO ALVAREZ ARANDA';
    $electronicDocument->Data->Receptor->RegimenFiscalReceptor->Value = '612';
    $electronicDocument->Data->Receptor->UsoCfdi->Value = 'G03';
    $electronicDocument->Data->Receptor->DomicilioFiscalReceptor->Value = '07300';
    
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
    $trasladoConcepto->Base->Value = 10.00;
    $trasladoConcepto->Importe->Value = 1.60;
    $trasladoConcepto->Impuesto->Value = '002';
    $trasladoConcepto->TipoFactor->Value = 'Tasa';
    $trasladoConcepto->TasaCuota->Value = 0.160000;

    $electronicDocument->Data->Total->Value = 11.60; // Ajuste matemático

    $traslado = $electronicDocument->Data->Impuestos->Traslados->add();
    $traslado->Base->Value = 10.00;
    $traslado->Importe->Value = 1.60;
    $traslado->Tipo->Value = '002';
    $traslado->TipoFactor->Value = 'Tasa';
    $traslado->TasaCuota->Value = 0.160000;
    $electronicDocument->Data->Impuestos->TotalTraslados->Value = 1.60;

    $electronicDocument->Data->Impuestos->TotalRetenciones->Value = 0;
    
    // --- 3.4. Llamada al Servicio de Timbrado ---
    $parameters = new Parameters();
    $parameters->Rfc = Constants::RFC_INTEGRADOR;
    $parameters->Usuario = Constants::ID_INTEGRADOR;
    $parameters->IdTransaccion = PHP_INT_MAX;
    $parameters->ElectronicDocument = $electronicDocument;

    $ecodex = new Proveedor();
    $result = $ecodex->TimbrarCfdi($parameters);

    // --- 3.5. Procesar Respuesta ---
    if ($result == ProcessProviderResult::OK) {
        $electronicDocument->Manage->Save->Options->Validations = false;
        $electronicDocument->saveToString($xml);
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => '¡PRUEBA DEFINITIVA EXITOSA!',
            'uuid' => $parameters->Information->Timbre->Uuid,
            'xml' => base64_encode($xml)
        ]);
    } else {
        $errorDetails = is_string($parameters->Information->Error) ? $parameters->Information->Error : json_encode($parameters->Information->Error, JSON_PRETTY_PRINT);
        throw new Exception('Prueba definitiva falló. Error del PAC: ' . $errorDetails);
    }

} catch (\Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Error al procesar la factura.',
        'details' => $e->getMessage()
    ]);
}
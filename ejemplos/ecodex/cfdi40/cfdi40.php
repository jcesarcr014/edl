<?php

// ---- INICIO: CÓDIGO DE DEPURACIÓN ----
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
date_default_timezone_set('America/Mexico_City');

// --- 1. CONFIGURACIÓN Y DEPENDENCIAS ---
define('CERT_DIR', __DIR__ . '/../certificados/');
define('PASSWORD_FILE', __DIR__ . '/../config/passwords.ini');

// Requerimos dependencias
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
// NOTA: Se omite la validación del Token para esta prueba ultra-simple.

// --- 3. LÓGICA DE TIMBRADO TOTALMENTE HARCODEADA ---
try {
    // --- 3.1. Definimos el RFC emisor a probar (ÚNICO DATO DINÁMICO) ---
    $emisorRfcParaPrueba = 'XOJI740919U48';

    // --- 3.2. Cargar Certificado y Contraseña para el RFC de prueba ---
    $pathCer = CERT_DIR . $emisorRfcParaPrueba . '.cer';
    $pathKey = CERT_DIR . $emisorRfcParaPrueba . '.key';
    if (!file_exists($pathCer) || !file_exists($pathKey)) {
        throw new Exception("No se encontraron los CSD para el RFC de prueba: $emisorRfcParaPrueba");
    }
    $passwords = parse_ini_file(PASSWORD_FILE);
    if (!isset($passwords[$emisorRfcParaPrueba])) {
        throw new Exception("No se encontró la contraseña para el RFC de prueba: $emisorRfcParaPrueba");
    }
    $password = $passwords[$emisorRfcParaPrueba];

    // --- 3.3. Preparar Documento Electrónico ---
    $electronicDocument = new ElectronicDocument();
    $certificate = Utils::loadCertificateFromFile($pathCer, $pathKey, $password);
    $electronicDocument->Manage->Save->Certificate = $certificate;

    // --- 3.4. Llenado de datos (COPIA EXACTA DEL EJEMPLO FUNCIONAL) ---
    $electronicDocument->Data->clear();

    //<editor-fold desc="Datos del comprobante">
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
    //</editor-fold>

    //<editor-fold desc="Información de los comprobantes fiscales relacionados">
    $relacionados = $electronicDocument->Data->CfdiRelacionadosExt->add();
    $relacionados->CfdiRelacionados->TipoRelacion->Value = '01';
    $relacionados->CfdiRelacionados->add()->Uuid->Value = 'A39DA66B-52CA-49E3-879B-5C05185B0EF7';
    //</editor-fold>

    //<editor-fold desc="Datos del emisor">
    // USAMOS EL RFC DE PRUEBA Y DATOS FIJOS
    $electronicDocument->Data->Emisor->Rfc->Value = $emisorRfcParaPrueba;
    $electronicDocument->Data->Emisor->Nombre->Value = 'JIMENEZ ORTEGA XOCHITL'; // Asumo este nombre, ajústalo si es otro
    $electronicDocument->Data->Emisor->RegimenFiscal->Value = '612'; // Asumo este régimen, ajústalo
    //</editor-fold>

    //<editor-fold desc="Datos del Receptor">
    $electronicDocument->Data->Receptor->Rfc->Value = 'XEXX010101000';
    $electronicDocument->Data->Receptor->Nombre->Value = 'PUBLICO EN GENERAL';
    $electronicDocument->Data->Receptor->RegimenFiscalReceptor->Value = '616';
    $electronicDocument->Data->Receptor->UsoCfdi->Value = 'S01';
    $electronicDocument->Data->Receptor->DomicilioFiscalReceptor->Value = '07300'; // CP del receptor
    //</editor-fold>

    //<editor-fold desc="Concepto">
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

    //<editor-fold desc="Impuestos trasladados del concepto">
    $trasladoConcepto = $concepto->Impuestos->Traslados->add();
    $trasladoConcepto->Base->Value = 10.00; // Corregido para consistencia matemática
    $trasladoConcepto->Importe->Value = 1.60;
    $trasladoConcepto->Impuesto->Value = '002';
    $trasladoConcepto->TipoFactor->Value = 'Tasa';
    $trasladoConcepto->TasaCuota->Value = 0.160000;
    //</editor-fold>

    //<editor-fold desc="Impuestos retenidos del concepto">
    $retencionConcepto = $concepto->Impuestos->Retenciones->add();
    $retencionConcepto->Base->Value = 10.00;
    $retencionConcepto->Importe->Value = 0;
    $retencionConcepto->Impuesto->Value = '001'; // ISR, por ejemplo
    $retencionConcepto->TipoFactor->Value = 'Tasa';
    $retencionConcepto->TasaCuota->Value = 0;
    //</editor-fold>

    //<editor-fold desc="Cuenta predial del concepto">
    $concepto->CuentasPrediales->add()->Numero->Value = "51888";
    //</editor-fold>
    //</editor-fold>
    
    // **CORRECCIÓN MATEMÁTICA IMPORTANTE EN TOTALES**
    $electronicDocument->Data->SubTotal->Value = 10.00;
    $electronicDocument->Data->Total->Value = 11.60; // 10.00 (subtotal) + 1.60 (IVA) - 0.00 (retenciones)
    

    //<editor-fold desc="Impuestos trasladados">
    $traslado = $electronicDocument->Data->Impuestos->Traslados->add();
    $traslado->Base->Value = 10.00;
    $traslado->Importe->Value = 1.60;
    $traslado->Tipo->Value = '002';
    $traslado->TipoFactor->Value = 'Tasa';
    $traslado->TasaCuota->Value = 0.160000;
    $electronicDocument->Data->Impuestos->TotalTraslados->Value = 1.60;
    //</editor-fold>

    //<editor-fold desc="Impuestos retenidos">
    $retencion = $electronicDocument->Data->Impuestos->Retenciones->add();
    $retencion->Tipo->Value = '001';
    $retencion->Importe->Value = 0;
    $electronicDocument->Data->Impuestos->TotalRetenciones->Value = 0;
    //</editor-fold>
    
    // --- 3.5. Llamada al Servicio de Timbrado ---
    $parameters = new Parameters();
    $parameters->Rfc = Constants::RFC_INTEGRADOR;
    $parameters->Usuario = Constants::ID_INTEGRADOR;
    $parameters->IdTransaccion = PHP_INT_MAX;
    $parameters->ElectronicDocument = $electronicDocument;

    $ecodex = new Proveedor();
    $result = $ecodex->TimbrarCfdi($parameters);

    // --- 3.6. Procesar Respuesta ---
    if ($result == ProcessProviderResult::OK) {
        $electronicDocument->Manage->Save->Options->Validations = false;
        $electronicDocument->saveToString($xml);
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => '¡TIMBRADO EXITOSO CON DATOS FIJOS!',
            'uuid' => $parameters->Information->Timbre->Uuid,
            'xml' => base64_encode($xml)
        ]);
    } else {
        $errorDetails = is_string($parameters->Information->Error) ? $parameters->Information->Error : json_encode($parameters->Information->Error, JSON_PRETTY_PRINT);
        throw new Exception('Error del PAC (datos fijos): ' . $errorDetails);
    }

} catch (\Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Error al procesar la factura.',
        'details' => $e->getMessage()
    ]);
}
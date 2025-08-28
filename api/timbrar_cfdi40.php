<?php
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
require_once __DIR__ . '/../../Utils/Utils.php'; // Ajusta la ruta si es necesario
require_once __DIR__ . '/../../Data/Constants.php'; // Para las credenciales del integrador

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
    $electronicDocument->Data->Version->Value = '4.0';
    $electronicDocument->Data->Serie->Value = $comprobanteData['serie'];
    $electronicDocument->Data->Folio->Value = $comprobanteData['folio'];
    $electronicDocument->Data->Fecha->Value = new \DateTime();
    $electronicDocument->Data->FormaPago->Value = $comprobanteData['formaPago'];
    $electronicDocument->Data->MetodoPago->Value = $comprobanteData['metodoPago'];
    if (isset($comprobanteData['condicionesPago'])) {
        $electronicDocument->Data->CondicionesPago->Value = $comprobanteData['condicionesPago'];
    }
    $electronicDocument->Data->Moneda->Value = $comprobanteData['moneda'];
    $electronicDocument->Data->TipoComprobante->Value = 'I';
    $electronicDocument->Data->Exportacion->Value = '01'; // Ajusta si es necesario
    $electronicDocument->Data->LugarExpedicion->Value = $comprobanteData['lugarExpedicion'];

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
        $concepto->ClaveProductoServicio->Value = $conceptoData['claveProdServ'];
        $concepto->Cantidad->Value = $conceptoData['cantidad'];
        $concepto->ClaveUnidad->Value = $conceptoData['claveUnidad'];
        $concepto->Descripcion->Value = $conceptoData['descripcion'];
        $concepto->ValorUnitario->Value = $conceptoData['valorUnitario'];
        $concepto->Importe->Value = round($conceptoData['cantidad'] * $conceptoData['valorUnitario'], 2);
        if (isset($conceptoData['descuento']) && $conceptoData['descuento'] > 0) {
            $concepto->Descuento->Value = $conceptoData['descuento'];
        }
        $concepto->ObjetoImpuesto->Value = $conceptoData['objetoImp'];

        // Impuestos del Concepto
        if (isset($conceptoData['impuestos']['traslados'])) {
            foreach ($conceptoData['impuestos']['traslados'] as $imp) {
                $traslado = $concepto->Impuestos->Traslados->add();
                $traslado->Base->Value = $imp['base'];
                $traslado->Impuesto->Value = $imp['impuesto'];
                $traslado->TipoFactor->Value = $imp['tipoFactor'];
                $traslado->TasaCuota->Value = $imp['tasaCuota'];
                $traslado->Importe->Value = $imp['importe'];
            }
        }
        // ... (Añadir lógica para retenciones del concepto si es necesario)
    }

    // Totales e Impuestos Globales
    $totalesData = $data['totales'];
    $electronicDocument->Data->SubTotal->Value = $totalesData['subtotal'];
    if (isset($totalesData['descuento']) && $totalesData['descuento'] > 0) {
        $electronicDocument->Data->Descuento->Value = $totalesData['descuento'];
    }
    $electronicDocument->Data->Total->Value = $totalesData['total'];
    
    if (isset($totalesData['totalImpuestosTrasladados']) && $totalesData['totalImpuestosTrasladados'] > 0) {
        $electronicDocument->Data->Impuestos->TotalTraslados->Value = $totalesData['totalImpuestosTrasladados'];
    }
    // ... (Añadir lógica para retenciones globales si es necesario)

    if (isset($data['impuestosGlobales']['traslados'])) {
        foreach($data['impuestosGlobales']['traslados'] as $imp) {
            $trasladoGlobal = $electronicDocument->Data->Impuestos->Traslados->add();
            $trasladoGlobal->Base->Value = $imp['base'];
            $trasladoGlobal->Tipo->Value = $imp['impuesto']; // OJO: Es 'Tipo' en el nodo global, no 'Impuesto'
            $trasladoGlobal->TipoFactor->Value = $imp['tipoFactor'];
            $trasladoGlobal->TasaCuota->Value = $imp['tasaCuota'];
            $trasladoGlobal->Importe->Value = $imp['importe'];
        }
    }

    // --- 2.4. Llamada al Servicio de Timbrado ---
    $parameters = new Parameters();
    $parameters->Rfc = Constants::RFC_INTEGRADOR;
    $parameters->Usuario = Constants::ID_INTEGRADOR;
    $parameters->IdTransaccion = time(); // ID de transacción único
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
        throw new Exception('Error del PAC: ' . $parameters->Information->Error);
    }

} catch (\Exception $e) {
    http_response_code(400); // Bad Request o Internal Error
    echo json_encode([
        'success' => false,
        'error' => 'Error al procesar la factura.',
        'details' => $e->getMessage()
    ]);
}
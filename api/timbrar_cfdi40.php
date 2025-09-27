<?php

// ---- DEPURACIÓN ----
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
date_default_timezone_set('America/Mexico_City');

// --- CONFIGURACIÓN ---
define('SECRET_API_TOKEN', 'TuTokenSuperSecreto12345!@');
define('CERT_DIR', __DIR__ . '/../certificados/');
define('PASSWORD_FILE', __DIR__ . '/../config/passwords.ini');

require_once __DIR__ . '/security.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../ejemplos/Utils/Utils.php'; 
require_once __DIR__ . '/../ejemplos/Data/Constants.php'; 

use Facturando\Ecodex\Proveedor;
use Facturando\Ecodex\Timbrado\Parameters;
use Facturando\EDL\Example\Data\Constants;
use Facturando\EDL\Example\Utils\Utils;
use Facturando\ElectronicDocumentLibrary\Base\Types\ProcessProviderResult;
use Facturando\ElectronicDocumentLibrary\Certificate\ElectronicCertificate;
use Facturando\ElectronicDocumentLibrary\Document\ElectronicDocument;

// --- VALIDACIÓN ---
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

// --- LÓGICA TIMBRADO ---
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

    // Certificados
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

    // Documento
    $electronicDocument = new ElectronicDocument();
    $certificate = Utils::loadCertificateFromFile($pathCer, $pathKey, $password);
    $electronicDocument->Manage->Save->Certificate = $certificate;
    $electronicDocument->Data->clear();

    // --- Comprobante ---
    $comprobante = $data['comprobante'];
    $electronicDocument->Data->Version->Value = '4.0';
    $electronicDocument->Data->Exportacion->Value = $comprobante['exportacion'] ?? '01';
    $electronicDocument->Data->Folio->Value = $comprobante['folio'];
    $electronicDocument->Data->FormaPago->Value = $comprobante['formaPago'];
    $electronicDocument->Data->LugarExpedicion->Value = $comprobante['lugarExpedicion'];
    $electronicDocument->Data->MetodoPago->Value = $comprobante['metodoPago'];
    $electronicDocument->Data->Moneda->Value = $comprobante['moneda'];
    $electronicDocument->Data->Serie->Value = $comprobante['serie'];
    $electronicDocument->Data->Fecha->Value = new \DateTime('NOW -5 hours');
    $electronicDocument->Data->SubTotal->Value = $comprobante['subTotal'];
    $electronicDocument->Data->TipoComprobante->Value = $comprobante['tipoComprobante'];
    $electronicDocument->Data->Total->Value = $comprobante['total'];

    if (isset($comprobante['cfdiRelacionados'])) {
        foreach ($comprobante['cfdiRelacionados'] as $rel) {
            $relacionados = $electronicDocument->Data->CfdiRelacionadosExt->add();
            $relacionados->CfdiRelacionados->TipoRelacion->Value = $rel['tipoRelacion'];
            foreach ($rel['uuids'] as $uuid) {
                $relacionados->CfdiRelacionados->add()->Uuid->Value = $uuid;
            }
        }
    }

    // --- Emisor ---
    $electronicDocument->Data->Emisor->Rfc->Value = $data['emisor']['rfc'];
    $electronicDocument->Data->Emisor->Nombre->Value = $data['emisor']['nombre'];
    $electronicDocument->Data->Emisor->RegimenFiscal->Value = $data['emisor']['regimenFiscal'];

    // --- Receptor ---
    $electronicDocument->Data->Receptor->Rfc->Value = $data['receptor']['rfc'];
    $electronicDocument->Data->Receptor->Nombre->Value = $data['receptor']['nombre'];
    $electronicDocument->Data->Receptor->RegimenFiscalReceptor->Value = $data['receptor']['regimenFiscalReceptor'];
    $electronicDocument->Data->Receptor->UsoCfdi->Value = $data['receptor']['usoCfdi'];
    $electronicDocument->Data->Receptor->DomicilioFiscalReceptor->Value = $data['receptor']['domicilioFiscalReceptor'];

    // --- Conceptos ---
    foreach ($data['conceptos'] as $c) {
        $concepto = $electronicDocument->Data->Conceptos->add();
        $concepto->Cantidad->Value = $c['cantidad'];
        $concepto->ClaveProductoServicio->Value = $c['claveProductoServicio'];
        $concepto->ClaveUnidad->Value = $c['claveUnidad'];
        $concepto->Descripcion->Value = $c['descripcion'];
        $concepto->Importe->Value = $c['importe'];
        $concepto->NumeroIdentificacion->Value = $c['numeroIdentificacion'];
        $concepto->ObjetoImpuesto->Value = $c['objetoImpuesto'];
        $concepto->Unidad->Value = $c['unidad'];
        $concepto->ValorUnitario->Value = $c['valorUnitario'];

        if (isset($c['impuestos']['traslados'])) {
            foreach ($c['impuestos']['traslados'] as $t) {
                $trasladoConcepto = $concepto->Impuestos->Traslados->add();
                $trasladoConcepto->Base->Value = $t['base'];
                $trasladoConcepto->Importe->Value = $t['importe'];
                $trasladoConcepto->Impuesto->Value = $t['impuesto'];
                $trasladoConcepto->TipoFactor->Value = $t['tipoFactor'];
                $trasladoConcepto->TasaCuota->Value = $t['tasaCuota'];
            }
        }
        if (isset($c['impuestos']['retenciones'])) {
            foreach ($c['impuestos']['retenciones'] as $r) {
                $retencionConcepto = $concepto->Impuestos->Retenciones->add();
                $retencionConcepto->Base->Value = $r['base'];
                $retencionConcepto->Importe->Value = $r['importe'];
                $retencionConcepto->Impuesto->Value = $r['impuesto'];
                $retencionConcepto->TipoFactor->Value = $r['tipoFactor'];
                $retencionConcepto->TasaCuota->Value = $r['tasaCuota'];
            }
        }

        if (isset($c['cuentasPrediales'])) {
            foreach ($c['cuentasPrediales'] as $cp) {
                $concepto->CuentasPrediales->add()->Numero->Value = $cp['numero'];
            }
        }
    }

    // --- Impuestos globales ---
    if (isset($data['impuestos']['traslados'])) {
        foreach ($data['impuestos']['traslados'] as $t) {
            $traslado = $electronicDocument->Data->Impuestos->Traslados->add();
            $traslado->Base->Value = $t['base'];
            $traslado->Importe->Value = $t['importe'];
            $traslado->Tipo->Value = $t['impuesto'];
            $traslado->TipoFactor->Value = $t['tipoFactor'];
            $traslado->TasaCuota->Value = $t['tasaCuota'];
        }
        $electronicDocument->Data->Impuestos->TotalTraslados->Value = $data['impuestos']['totalTraslados'];
    }
    if (isset($data['impuestos']['retenciones'])) {
        foreach ($data['impuestos']['retenciones'] as $r) {
            $retencion = $electronicDocument->Data->Impuestos->Retenciones->add();
            $retencion->Tipo->Value = $r['impuesto'];
            $retencion->Importe->Value = $r['importe'];
        }
        $electronicDocument->Data->Impuestos->TotalRetenciones->Value = $data['impuestos']['totalRetenciones'];
    }

    // --- Timbrado ---
    $parameters = new Parameters();
    $parameters->Rfc = Constants::RFC_INTEGRADOR;
    $parameters->Usuario = Constants::ID_INTEGRADOR;
    if (!isset($data['max_id']) || !is_numeric($data['max_id'])) {
        $parameters->IdTransaccion = PHP_INT_MAX;
    } else{
        $parameters->IdTransaccion = (int)$data['max_id'] + 1;
    }
    
    $parameters->ElectronicDocument = $electronicDocument;

    $ecodex = new Proveedor();
    $result = $ecodex->TimbrarCfdi($parameters);

    ob_clean(); 
    
    if ($result == ProcessProviderResult::OK) {
        $electronicDocument->Manage->Save->Options->Validations = false;
        $electronicDocument->saveToString($xml);

        $uuidValue = is_string($parameters->Information->Timbre->Uuid) ? $parameters->Information->Timbre->Uuid : null;

        if (empty($uuidValue) && is_string($xml)) {
            try {
                $xmlObj = new SimpleXMLElement($xml);
                $namespaces = $xmlObj->getNamespaces(true);
    
                if (isset($namespaces['tfd'])) {
                    $tfd = $xmlObj->children($namespaces['cfdi'])->Complemento->children($namespaces['tfd']);
                    if (isset($tfd->TimbreFiscalDigital)) {
                        $uuidValue = (string) $tfd->TimbreFiscalDigital['UUID'];
                    }
                }
            } catch (Exception $e) {
                $uuidValue = null; // si falla la lectura, lo dejamos en null
            }
        }
        
        $xmlValue = is_string($xml) ? $xml : null;
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'uuid' => $uuidValue,
            'id_transaccion' => $parameters->IdTransaccion,
            'xml' => base64_encode($xmlValue)
        ]);
        exit();

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

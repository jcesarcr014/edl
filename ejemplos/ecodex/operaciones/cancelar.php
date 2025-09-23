<?php

date_default_timezone_set('America/Mexico_City');

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Utils/Descripcion.php';
require_once __DIR__ . '/../../Utils/Utils.php';
require_once __DIR__ . '/../../Utils/Html.php';
require_once __DIR__ . '/../../Utils/Config.php';
require_once __DIR__ . '/../../Data/Constants.php';

use Facturando\Ecodex\Proveedor;
use Facturando\EDL\Example\Data\Constants;
use Facturando\EDL\Example\Utils\Descripcion;
use Facturando\EDL\Example\Utils\Html;
use Facturando\ElectronicDocumentLibrary\Document\ElectronicDocument;
use Facturando\XmlSignature\Parameters;

// Mostramos la versión de la librería
Html::showVersion(ElectronicDocument::Version());

// Código para medir el tiempo que toma el proceso
Facturando\Shared\Chronometer::start();

//<editor-fold desc="Se genera el XML Signature">
$parameters = new Parameters();

$parameters->Rfc = Constants::RFC_INTEGRADOR;
$parameters->Uuid = '13BEDEC4-2E26-40E1-8BF4-F87860FD42D5';
$parameters->Motivo = '01';
$parameters->FolioSustitucion = '13BEDEC4-2E26-40E1-8BF4-F87860FD42D8';
$parameters->CertFile = '../../certificados/EKU9003173C9.cer';
$parameters->KeyFile = '../../certificados/EKU9003173C9.key';
$parameters->Password = '12345678a';

$signature = new \Facturando\XmlSignature\Signature();
if ($signature->createXml($parameters, $xml) == false) {
    Html::showErrorInfo(json_decode($xml, true));
    exit;
}
//</editor-fold>

$parameters = new \Facturando\Ecodex\Cancelar\Parameters();
$parameters->Rfc = Constants::RFC_INTEGRADOR;
$parameters->Usuario = Constants::ID_INTEGRADOR;
$parameters->XmlSignature = $xml;

$ecodex = new Proveedor();

/** @var int $result */
$result = $ecodex->CancelarCfdi($parameters);

Html::showXml('CANCELACION', $result, Descripcion::get(Descripcion::ECODEX_CANCELAR_CFDI));

Html::showTimeElapesed();
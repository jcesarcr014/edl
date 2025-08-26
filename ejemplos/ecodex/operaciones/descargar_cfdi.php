<?php

date_default_timezone_set('America/Mexico_City');

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Utils/Descripcion.php';
require_once __DIR__ . '/../../Utils/Utils.php';
require_once __DIR__ . '/../../Utils/Html.php';
require_once __DIR__ . '/../../Utils/Config.php';
require_once __DIR__ . '/../../Data/Constants.php';

use Facturando\Ecodex\Descargar\Parameters;
use Facturando\Ecodex\Proveedor;
use Facturando\EDL\Example\Data\Constants;
use Facturando\EDL\Example\Utils\Descripcion;
use Facturando\EDL\Example\Utils\Html;
use Facturando\EDL\Example\Utils\Utils;
use Facturando\ElectronicDocumentLibrary\Base\Types\ProcessProviderResult;
use Facturando\ElectronicDocumentLibrary\Document\ElectronicDocument;

// Mostramos la versión de la librería
Html::showVersion(ElectronicDocument::Version());

// Código para medir el tiempo que toma el proceso
Facturando\Shared\Chronometer::start();

/** @var Parameters $parameters */
$parameters = new Parameters();

$parameters->Rfc = Constants::RFC_INTEGRADOR;
$parameters->Usuario = Constants::ID_INTEGRADOR;
$parameters->IdTransaccion = PHP_INT_MAX;

//Por UUID
$parameters->Uuid = '0FD8CA23-CDE8-4C78-B6F7-918F55DA6A0C';

// Por TRANSACCION ORIGINAL
//$parameters->TransaccionOriginal = 2192;

// Por HASH
//$parameters->Hash = '2fb7a290e6f921e9f10b66bd97df41fbe3b1d945';

$ecodex = new Proveedor();

/** @var int $result */
$result = $ecodex->DescargarCfdi($parameters);

// En este caso se pudo obtener respuesta por parte del PAC
if ($result == ProcessProviderResult::OK) {
    
    $xml = Utils::XmlPrettyPrint($parameters->Information->Xml->Value);

    Html::showXml('COMPROBANTE', $xml, Descripcion::get(Descripcion::ECODEX_DESCARGAR_UN_CFDI));

} else {
    Html::showErrorPac($parameters->Information->Error);
}

Html::showTimeElapesed();
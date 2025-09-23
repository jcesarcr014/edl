<?php

date_default_timezone_set('America/Mexico_City');

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Utils/Descripcion.php';
require_once __DIR__ . '/../../Utils/Utils.php';
require_once __DIR__ . '/../../Utils/Html.php';
require_once __DIR__ . '/../../Utils/Config.php';
require_once __DIR__ . '/../../Data/Constants.php';

use Facturando\Ecodex\AcuseCancelacion\Parameters;
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
$parameters->Uuid = '13BEDEC4-2E26-40E1-8BF4-F87860FD42D9';

$ecodex = new Proveedor();

/** @var int $result */
$result = $ecodex->AcuseCancelacion($parameters);

// En este caso se pudo obtener respuesta por parte del PAC
if ($result == ProcessProviderResult::OK) {
    $xml = Utils::XmlPrettyPrint($parameters->Information->Acuse->Value);
    Html::showXml('ACUSE DE CANCELACION', $xml, Descripcion::get(Descripcion::ECODEX_ACUSE_DE_CANCELACION));
} else {
    Html::showErrorPac($parameters->Information->Error);
}

Html::showTimeElapesed();
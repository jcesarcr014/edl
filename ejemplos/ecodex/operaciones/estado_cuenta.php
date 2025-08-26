<?php

date_default_timezone_set('America/Mexico_City');

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Utils/Descripcion.php';
require_once __DIR__ . '/../../Utils/Utils.php';
require_once __DIR__ . '/../../Utils/Html.php';
require_once __DIR__ . '/../../Utils/Config.php';
require_once __DIR__ . '/../../Data/Constants.php';

use Facturando\Ecodex\EstadoCuenta\Parameters;
use Facturando\Ecodex\Proveedor;
use Facturando\EDL\Example\Data\Constants;
use Facturando\EDL\Example\Utils\Descripcion;
use Facturando\EDL\Example\Utils\Html;
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

$ecodex = new Proveedor();

/** @var int $result */
$result = $ecodex->EstadoCuenta($parameters);

// En este caso se pudo obtener respuesta por parte del PAC
if ($result == ProcessProviderResult::OK) {
    Html::showTitle('ESTADO DE CUENTA', Descripcion::get(Descripcion::ECODEX_ESTADO_DE_CUENTA));
    Html::showData('RFC', $parameters->Information->Estado->Rfc->Value);
    Html::showData('Fecha de inicio', $parameters->Information->Estado->FechaInicio->Value->format('d/m/Y G:i a'));
    Html::showData('Fecha de fin', $parameters->Information->Estado->FechaFin->Value->format('d/m/Y G:i a'));
    Html::showData('Timbres asignados', number_format($parameters->Information->Estado->TimbresAsignados->Value));
    Html::showData('Timbres consumidos', number_format($parameters->Information->Estado->TimbresConsumidos->Value));
    Html::showData('Timbres disponibles', number_format($parameters->Information->Estado->TimbresDisponibles->Value));
} else {
    Html::showErrorPac($parameters->Information->Error);
}

Html::showTimeElapesed();
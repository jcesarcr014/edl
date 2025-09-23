<?php

date_default_timezone_set('America/Mexico_City');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Utils/Descripcion.php';
require_once __DIR__ . '/Utils/Html.php';
require_once __DIR__ . '/Utils/Utils.php';

use Facturando\EDL\Example\Utils\Descripcion;
use Facturando\EDL\Example\Utils\Html;
use Facturando\ElectronicDocumentLibrary\Document\ElectronicDocument;

/** @var stdClass $licencia */
$licencia = ElectronicDocument::getLicenceData();

Html::showVersion(ElectronicDocument::Version());

Html::showTitle('DATOS DE LA LICENCIA', Descripcion::get(Descripcion::LEER_LICENCIA));
Html::showData('Versión', ElectronicDocument::Version());
Html::showData('Empresa', $licencia->Empresa);
Html::showData('Fecha de vencimiento', $licencia->FechaVencimiento);
Html::showData('No. de serie', $licencia->Serial);
Html::showData('Addenda', $licencia->Addenda);
Html::showData('Modo de prueba', $licencia->Testing ? 'Si' : 'No');
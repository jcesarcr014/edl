<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Utils/Descripcion.php';
require_once __DIR__ . '/Utils/Html.php';
require_once __DIR__ . '/Utils/Utils.php';

use Facturando\EDL\Example\Utils\Descripcion;
use Facturando\EDL\Example\Utils\Html;
use Facturando\ElectronicDocumentLibrary\BarCode;
use Facturando\ElectronicDocumentLibrary\Document\ElectronicDocument;

// Mostramos la versión de la librería
Html::showVersion(ElectronicDocument::Version());

// Código para medir el tiempo que toma el proceso
Facturando\Shared\Chronometer::start();

$cbb = new BarCode\BarCode();

Html::showTitle('CODIGO DE BARRAS BIDIMENSIONAL', Descripcion::get(Descripcion::CODIGO_BARRAS));

Html::showTitle('MENSAJE DEL QR CODE');
$message = $cbb->calculateCfdi33("AAA010101AAA", "BBB010101BB1", 1, "D4B3A974-E2F2-4F16-A4F8-6CA2BAADCC8A", "XXXXXXXX");
Html::showData('Mensaje', $message);

//
Html::showTitle('IMAGEN');
$image = $cbb->generateCfdi33("AAA010101AAA", "BBB010101BB1", 1, "D4B3A974-E2F2-4F16-A4F8-6CA2BAADCC8A", "XXXXXXXX");
file_put_contents('cbb.png', $image);

echo '<img src="cbb.png">';

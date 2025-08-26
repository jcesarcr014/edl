<?php

date_default_timezone_set('America/Mexico_City');

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Utils/Descripcion.php';
require_once __DIR__ . '/../../Utils/Utils.php';
require_once __DIR__ . '/../../Utils/Html.php';
require_once __DIR__ . '/../../Data/ConstanciaRetenciones/Complemento/Dividendos.php';
require_once __DIR__ . '/../../Data/ConstanciaRetenciones/Constancia20.php';

use Facturando\EDL\Example\Utils\Descripcion;
use Facturando\EDL\Example\Utils\Html;
use Facturando\EDL\Example\Utils\Utils;
use Facturando\ElectronicDocumentLibrary\Certificate\ElectronicCertificate;
use Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\ConstanciaRetenciones;
use Facturando\ElectronicDocumentLibrary\Document\ElectronicDocument;

// Mostramos la versión de la librería
Html::showVersion(ElectronicDocument::Version());

// Código para medir el tiempo que toma el proceso
Facturando\Shared\Chronometer::start();


// Creamos el objeto CONSTANCIARETENCIONES que es el que contiene los datos
/** @var ConstanciaRetenciones $constanciaRetenciones */
$constanciaRetenciones = new ConstanciaRetenciones();

// Leemos el certificado y su llave privada con el que se va sellar el comprobante
/** @var ElectronicCertificate $certificate */
$certificate = Utils::loadCertificateFromFile('../../certificados/EKU9003173C9.cer', '../../certificados/EKU9003173C9.key', '12345678a');

// Asignamos el certificado al objeto CONSTANCIARETENCIONES
$constanciaRetenciones->Manage->Save->Certificate = $certificate;

// Cargamos los datos
\Facturando\EDL\Example\Data\ConstanciaRetenciones\Complemento\Dividendos::CargarDatosCompleto($constanciaRetenciones);

// Guardamos el documento a memoria para mostrarlo en pantalla
if ($constanciaRetenciones->saveToString($xml))
{
    // Guardamos el documento a disco
    $constanciaRetenciones->saveToFile('../xml/constancia_retenciones_dividendos.xml');

    Html::showXml('Constancia de retenciones 2.0 - Dividendos', $xml, Descripcion::get(Descripcion::CONSTANCIA_DIVIDENDOS));
}
else{
    Html::showError($constanciaRetenciones->ErrorText);
}

Html::showTimeElapesed();


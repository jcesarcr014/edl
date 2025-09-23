<?php
date_default_timezone_set('America/Mexico_City');

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Utils/Descripcion.php';
require_once __DIR__ . '/../Utils/Utils.php';
require_once __DIR__ . '/../Utils/Html.php';
require_once __DIR__ . '/../Data/Cfdi40.php';

use Facturando\EDL\Example\Data\Cfdi40;
use Facturando\EDL\Example\Utils\Descripcion;
use Facturando\EDL\Example\Utils\Html;
use Facturando\EDL\Example\Utils\Utils;
use Facturando\ElectronicDocumentLibrary\Certificate\ElectronicCertificate;
use Facturando\ElectronicDocumentLibrary\Document\ElectronicDocument;

// Código para medir el tiempo que toma el proceso
Facturando\Shared\Chronometer::start();

// Creamos el objeto ELECTRONICDOCUMENT que es el que contiene los datos
$electronicDocument = new ElectronicDocument();

// Leemos el certificado y su llave privada con el que se va sellar el comprobante
/** @var ElectronicCertificate $certificate */
$certificate = Utils::loadCertificateFromFile('../certificados/EKU9003173C9.cer', '../certificados/EKU9003173C9.key', '12345678a');

// Asignamos el certificado al objeto ELECTRONICDOCUMENT
$electronicDocument->Manage->Save->Certificate = $certificate;

// Cargamos los datos del comprobantes
Cfdi40::CargarDatosCompleto($electronicDocument);

// Mostramos la versión de la librería
Html::showVersion(ElectronicDocument::Version());


// Guardamos el PRE-CFDI a memoria para mostrarlo en pantalla
if ($electronicDocument->saveToString($xml)) {
    // Guardamos el PRE-CFDI a disco
    $electronicDocument->saveToFile('xml/cfdi40.xml');

    Html::showXml('CFDI 4.0', $xml, Descripcion::get(Descripcion::CFDI_40));
} else {
    Html::showError($electronicDocument->ErrorText);
}

Html::showTimeElapesed();
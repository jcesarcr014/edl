<?php

date_default_timezone_set('America/Mexico_City');

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Utils/Descripcion.php';
require_once __DIR__ . '/../../Utils/Utils.php';
require_once __DIR__ . '/../../Utils/Html.php';
require_once __DIR__ . '/../../Utils/Config.php';
require_once __DIR__ . '/../../Data/Complemento/Nomina12.php';
require_once __DIR__ . '/../../Data/Complemento/Base.php';
require_once __DIR__ . '/../../Data/Constants.php';

use Facturando\Ecodex\Proveedor;
use Facturando\Ecodex\Timbrado\Parameters;
use Facturando\EDL\Example\Data\Complemento\Nomina12;
use Facturando\EDL\Example\Data\Constants;
use Facturando\EDL\Example\Utils\Descripcion;
use Facturando\EDL\Example\Utils\Html;
use Facturando\EDL\Example\Utils\Utils;
use Facturando\ElectronicDocumentLibrary\Base\Types\ProcessProviderResult;
use Facturando\ElectronicDocumentLibrary\Certificate\ElectronicCertificate;
use Facturando\ElectronicDocumentLibrary\Document\ElectronicDocument;

// Mostramos la versión de la librería
Html::showVersion(ElectronicDocument::Version());

// Creamos el objeto ELECTRONICDOCUMENT que es el que contiene los datos
$electronicDocument = new ElectronicDocument();

// Leemos el certificado y su llave privada con el que se va sellar el comprobante
/** @var ElectronicCertificate $certificate */
$certificate = Utils::loadCertificateFromFile('../../certificados/EKU9003173C9.cer', '../../certificados/EKU9003173C9.key', '12345678a');

// Asignamos el certificado al objeto ELECTRONICDOCUMENT
$electronicDocument->Manage->Save->Certificate = $certificate;

// Cargamos los datos del comprobantes
Nomina12::CargarDatosCfdi40Timbrado($electronicDocument);

/** @var Parameters $parameters */
$parameters = new Parameters();

$parameters->Rfc = Constants::RFC_INTEGRADOR;
$parameters->Usuario = Constants::ID_INTEGRADOR;
$parameters->IdTransaccion = PHP_INT_MAX;
$parameters->ElectronicDocument = $electronicDocument;

/** @var Facturando\Ecodex\Proveedor $ecodex */
$ecodex = new Proveedor();

// Código para medir el tiempo que toma el proceso
Facturando\Shared\Chronometer::start();

/** @var int $result */
$result = $ecodex->TimbrarCfdi($parameters);

// En este caso se pudo obtener respuesta por parte del PAC
if ($result == ProcessProviderResult::OK) {

    $electronicDocument->Manage->Save->Options->Validations = false;

    //Esta línea de código se usa para mostrar el resultado en pantalla
    $electronicDocument->saveToString($xml);

    $electronicDocument->saveToFile('xml/nomina12.xml');
    $electronicDocument->Manage->Save->Options->Validations = true;

    Html::showXml('CFDI 4.0 - RECIBO DE NOMINA 1.2', $xml, Descripcion::get(Descripcion::ECODEX_RECIBO_NOMINA_CFDI_40));

    Html::showTimbre($parameters->Information->Timbre);

    Html::showCadenaOriginal($electronicDocument->FingerPrint, $electronicDocument->FingerPrintPac);
} else {

    Html::showErrorPac($parameters->Information->Error);
}

Html::showTimeElapesed();
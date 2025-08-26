<?php

date_default_timezone_set('America/Mexico_City');

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../Utils/Descripcion.php';
require_once __DIR__ . '/../../Utils/Utils.php';
require_once __DIR__ . '/../../Utils/Html.php';
require_once __DIR__ . '/../../Utils/Config.php';
require_once __DIR__ . '/../../Data/Constants.php';
require_once __DIR__ . '/../../Data/ConstanciaRetenciones/Constancia20.php';

use Facturando\Ecodex\Proveedor;
use Facturando\Ecodex\TimbradoConstanciaRetenciones\Parameters;
use Facturando\EDL\Example\Data\Constants;
use Facturando\EDL\Example\Data\Retenciones\Constancia20;
use Facturando\EDL\Example\Utils\Descripcion;
use Facturando\EDL\Example\Utils\Html;
use Facturando\EDL\Example\Utils\Utils;
use Facturando\ElectronicDocumentLibrary\Base\Types\ProcessProviderResult;
use Facturando\ElectronicDocumentLibrary\Certificate\ElectronicCertificate;
use Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\ConstanciaRetenciones;
use Facturando\ElectronicDocumentLibrary\Document\ElectronicDocument;

// Mostramos la versión de la librería
Html::showVersion(ElectronicDocument::Version());

// Creamos el objeto CONSTANCIARETENCIONES que es el que contiene los datos
/** @var ConstanciaRetenciones $constanciaRetenciones */
$constanciaRetenciones = new ConstanciaRetenciones();

// Leemos el certificado y su llave privada con el que se va sellar el comprobante
/** @var ElectronicCertificate $certificate */
$certificate = Utils::loadCertificateFromFile('../../certificados/EKU9003173C9.cer', '../../certificados/EKU9003173C9.key', '12345678a');

// Asignamos el certificado al objeto CONSTANCIARETENCIONES
$constanciaRetenciones->Manage->Save->Certificate = $certificate;

// Cargamos los datos de la constancia
Constancia20::CargarDatosTimbrado($constanciaRetenciones);

/** @var Parameters $parameters */
$parameters = new Parameters();

$parameters->Rfc = Constants::RFC_INTEGRADOR;
$parameters->Usuario = Constants::ID_INTEGRADOR;
$parameters->IdTransaccion = PHP_INT_MAX;
$parameters->ConstanciaRetenciones = $constanciaRetenciones;

/** @var Facturando\Ecodex\Proveedor $ecodex */
$ecodex = new Proveedor();

// Código para medir el tiempo que toma el proceso
Facturando\Shared\Chronometer::start();

/** @var int $result */
$result = $ecodex->TimbrarConstanciaRetenciones($parameters);

// En este caso se pudo obtener respuesta por parte del PAC
if ($result == ProcessProviderResult::OK) {

    $constanciaRetenciones->Manage->Save->Options->Validations = false;

    //Esta línea de código se usa para mostrar el resultado en pantalla
    $constanciaRetenciones->saveToString($xml);

    $constanciaRetenciones->saveToFile('xml/retencion20.xml');
    $constanciaRetenciones->Manage->Save->Options->Validations = true;

    Html::showXml('Constancia de retenciones 2.0', $xml, Descripcion::get(Descripcion::ECODEX_CONSTANCIA_DE_RETENCIONES));

    Html::showTimbre($parameters->Information->Timbre);

    Html::showCadenaOriginal($constanciaRetenciones->FingerPrint, $constanciaRetenciones->FingerPrintPac);
} else {
    Html::showErrorPac($parameters->Information->Error);
}

Html::showTimeElapesed();
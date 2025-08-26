<?php

date_default_timezone_set('America/Mexico_City');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Utils/Descripcion.php';
require_once __DIR__ . '/Utils/Utils.php';
require_once __DIR__ . '/Utils/Html.php';

use Facturando\EDL\Example\Utils\Descripcion;
use Facturando\EDL\Example\Utils\Html;
use Facturando\EDL\Example\Utils\Utils;
use Facturando\ElectronicDocumentLibrary\Certificate\ElectronicCertificate;
use Facturando\ElectronicDocumentLibrary\Document\ElectronicDocument;

// Mostramos la versión de la librería
Html::showVersion(ElectronicDocument::Version());

// Código para medir el tiempo que toma el proceso
Facturando\Shared\Chronometer::start();

// Leemos el certificado y su llave privada con el que se va sellar el comprobante
/** @var ElectronicCertificate $certificate */
$certificate = Utils::loadCertificateFromFile('certificados/EKU9003173C9.cer', 'certificados/EKU9003173C9.key', '12345678a');


Html::showTitle('CERTIFICADO', Descripcion::get(Descripcion::LEER_CERTIFICADO));

Html::showTitle('DATOS DEL CERTIFICADO');
Html::showData('No. de serie', $certificate->Information->SerialNumber);
Html::showData('Válido desde', $certificate->Information->ValidFrom->format('Y-m-d H:i:s'));
Html::showData('Válido hasta', $certificate->Information->ValidTo->format('Y-m-d H:i:s'));

Html::showTitle('DATOS DEL EMISOR');
Html::showData('Nombre', $certificate->Information->Issuer->CommonName);
Html::showData('Razón social', $certificate->Information->Issuer->Organization);
Html::showData('Organización', $certificate->Information->Issuer->OrganizationUnit);
Html::showData('Correo electrónico', $certificate->Information->Issuer->ElectronicMail);
Html::showData('Dirección', $certificate->Information->Issuer->Street);
Html::showData('Código postal', $certificate->Information->Issuer->PostalCode);
Html::showData('País', $certificate->Information->Issuer->Country);
Html::showData('Estado', $certificate->Information->Issuer->StateOrProvince);
Html::showData('Localidad', $certificate->Information->Issuer->Locality);
Html::showData('Propietario', $certificate->Information->Issuer->Own);

Html::showTitle('DATOS DEL CONTRIBUYENTE');
Html::showData('Identificador', $certificate->Information->Subject->Id);
Html::showData('Localidad', $certificate->Information->Subject->Locality);
Html::showData('Organización', $certificate->Information->Subject->Organization);
Html::showData('Unidad', $certificate->Information->Subject->OrganizationUnit);
Html::showData('Nombre', $certificate->Information->Subject->CommonName);
Html::showData('País', $certificate->Information->Subject->Country);

Html::showTimeElapesed();
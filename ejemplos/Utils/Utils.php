<?php

namespace Facturando\EDL\Example\Utils;

use DOMDocument;
use Exception;
use Facturando\ElectronicDocumentLibrary\Certificate\ElectronicCertificate;
use Facturando\Exceptions\Certificate\InvalidPasswordException;
use Facturando\Exceptions\Certificate\NotDetectedFileFormatException;
use Facturando\Exceptions\Certificate\PrivateKeyNotMatchException;

final class Utils
{
    /**
     * Método que muestra como leer un certificado y su llave privada desde el disco duro
     *
     * @param string $cer
     * @param string $key
     * @param string $password
     * @return ElectronicCertificate
     */
    public static function loadCertificateFromFile($cer, $key, $password)
    {
        //<editor-fold desc="Cargamos el certificado">
        // Creamos el objeto certificado
        $certificate = new ElectronicCertificate();

        try {
            // Leemos el certficado
            $certificate->loadFromFile($cer);

        } catch (NotDetectedFileFormatException $e) {
            Html::showException('Se generó un error: No es posible reconocer el formato del CERTIFICADO, posiblemente sea un certificado no válido.', $e);
        } catch (Exception $e) {
            Html::showException('Se generó un error desconocido.', $e);
        }
        //</editor-fold>

        //<editor-fold desc="Cargamos la llave privada">
        try {

            // Leemos la llave privada
            $certificate->loadPrivateKeyFromFile($key, $password, false);


        } catch (NotDetectedFileFormatException $e) {
            Html::showException('Se generó un error: No es posible reconocer el formato de la LLAVE PRIVADA, posiblemente sea un certificado no válido.', $e);
        } catch (InvalidPasswordException $e) {
            Html::showException('Se generó un error: El password de la llave privada no es válido.', $e);
        } catch (PrivateKeyNotMatchException $e) {
            Html::showException('Se generó un error: El archivo KEY y el CERTIFICADO no corresponden.', $e);
        } catch (Exception $e) {
            Html::showException('Se generó un error desconocido.', $e);
        }
        //</editor-fold>

        return $certificate;
    }

    /**
     * Método que muestra como leer un certificado y su llave privada desde memoria
     *
     * @param $cer
     * @param $key
     * @param $password
     * @return ElectronicCertificate
     */
    public static function loadCertificateFromMemory($cer, $key, $password)
    {
        //<editor-fold desc="Cargamos el certificado">
        // Creamos el objeto certificado
        $certificate = new ElectronicCertificate();

        try {
            // Cargamos el archivo a memoria
            $content = file_get_contents($cer);

            // Leemos el certficado
            $certificate->loadFromMemory($content);

        } catch (NotDetectedFileFormatException $e) {
            Html::showException('Se generó un error: No es posible reconocer el formato del CERTIFICADO, posiblemente sea un certificado no válido.', $e);
        } catch (Exception $e) {
            Html::showException('Se generó un error desconocido.', $e);
        }
        //</editor-fold>

        //<editor-fold desc="Cargamos la llave privada">
        try {

            // Cargamos el archivo a memoria
            $content = file_get_contents($key);

            // Leemos la llave privada
            $certificate->loadPrivateKeyFromMemory($content, $password, false);
        } catch (NotDetectedFileFormatException $e) {
            Html::showException('Se generó un error: No es posible reconocer el formato de la LLAVE PRIVADA, posiblemente sea un certificado no válido.', $e);
        } catch (InvalidPasswordException $e) {
            Html::showException('Se generó un error: El password de la llave privada no es válido.', $e);
        } catch (PrivateKeyNotMatchException $e) {
            Html::showException('Se generó un error: El archivo KEY y el CERTIFICADO no corresponden.', $e);
        } catch (Exception $e) {
            Html::showException('Se generó un error desconocido.', $e);
        }
        //</editor-fold>

        return $certificate;
    }


    /**
     * @param $xml
     * @return string
     */
    public static function XmlPrettyPrint($xml)
    {
        $domxml = new DOMDocument('1.0');
        $domxml->preserveWhiteSpace = false;
        $domxml->formatOutput = true;
        $domxml->loadXML($xml);
        return $domxml->saveXML();
    }
}
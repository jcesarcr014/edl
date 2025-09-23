<?php

namespace Facturando\EDL\Example\Utils;

use Exception;
use Facturando\Ecodex\Base\Error;
use Facturando\Ecodex\Timbrado\Timbre;
use Facturando\ElectronicDocumentLibrary\Base\Types\ProcessProviderResult;
use Facturando\Shared\Chronometer;

final class Html
{
    CONST HTML_RETURN = '<br/>';
    
    public static function initialization()
    {
        $directory = 'assets';
        while (file_exists($directory) == false) {
            $directory = '../' . $directory;
        };

        echo '<!DOCTYPE html>';
        echo '<html lang="en">';
        echo '<head>';
        echo '<link rel="stylesheet" type="text/css" href="' . $directory . '/css/main.css"/>';
        echo '<meta charset="UTF-8">';
        echo '<title>FACTURANDO</title>';
        echo '<link rel="shortcut icon" href="' . $directory . '/img/favicon.ico"/>';
        echo '<link rel="icon" href="' . $directory . '/img/favicon.png" type="image/png"/>';
        echo '</head>';
        echo '<body background="' . $directory . '/img/fondo.jpg">';
    }

    /**
     * @param string $message
     * @param Exception $e
     */
    public static function showException($message, $e)
    {
        $message =
            $message . self::HTML_RETURN . self::HTML_RETURN .
            "<strong>EXCEPCION</strong>" . self::HTML_RETURN .
            '=====================================================' . self::HTML_RETURN .
            '<strong>Código: </strong>' . $e->getCode() . self::HTML_RETURN .
            '<strong>Mensaje: </strong>' . $e->getMessage() . self::HTML_RETURN .
            '<strong>Archivo: </strong>' . $e->getFile() . self::HTML_RETURN;

        echo $message;
        exit;
    }

    /**
     * @param string $title
     * @param string $descripcion
     */
    public static function showTitle($title, $descripcion = '')
    {
        echo sprintf("<br/><br/><strong>%s</strong><br/>", $title);

        if (empty($descripcion) == false) {
            echo '<p class="descripcion">' . $descripcion . '</p>';
        }

        echo "<hr/>";
    }

    /**
     * @param string $title
     * @param string $xml
     * @param string $descripcion
     */
    public static function showXml($title, $xml, $descripcion)
    {
        self::showTitle($title, $descripcion);
        echo "<textarea style='width:100%;height: 650px;' wrap='off'>" . $xml . "</textarea>";
    }

    /**
     * @param string $title
     * @param string $value
     */
    public static function showData($title, $value)
    {
        echo sprintf("<p class='tag'>%s:</p> <p class='value'>%s</p><br/>", $title, $value);
    }

    public static function showTimeElapesed()
    {
        echo self::HTML_RETURN;
        echo "<hr/>";
        $time = number_format(Chronometer::elapsed(), 0);
        self::showData('Tiempo', $time . ' milisegundos');
        exit;
    }

    /**
     * @param string $version
     */
    public static function showVersion($version)
    {
        Html::initialization();
        self::showMessage(sprintf('<div class="version">ELECTRONIC DOCUMENTO LIBRARY (%s)</div></font>', $version));
    }

    /**
     * @param string $message
     * @param bool $bold
     */
    public static function showMessage($message, $bold = false)
    {
        if ($bold) {
            echo "<strong>$message</strong>";
        } else {
            echo $message;
        }
    }

    /**
     * @param string $message
     */
    public static function showError($message)
    {
        Html::showTitle('ERROR');
        Html::showData('Descripción', $message);
    }

    /**
     * @param Timbre $timbre
     */
    public static function showTimbre($timbre)
    {
        Html::showTitle('DATOS DEL TIMBRE');
        Html::showData('Version', $timbre->Version->Value);
        Html::showData('UUID', $timbre->Uuid->Value);
        Html::showData('Fecha de timbrado', $timbre->FechaTimbrado->Value->format('Y-m-d H:i:s'));
        Html::showData('Sello del CFD', $timbre->SelloCfd->Value);
        Html::showData('No. de certificado', $timbre->NumeroCertificadoSat->Value);
        Html::showData('Sello del SAT', $timbre->SelloSat->Value);

        if ($timbre->Version->Value == '1.1') {
            Html::showData('RFC del PAC', $timbre->RfcProveedorCertificacion->Value);
            Html::showData('Leyenda', $timbre->Leyenda->Value);
        }
    }

    /**
     * @param string $fingerPrint
     * @param string $fingerPrintPac
     */
    public static function showCadenaOriginal($fingerPrint, $fingerPrintPac)
    {
        Html::showTitle('CADENA ORIGINAL');
        Html::showData('CFD', $fingerPrint);
        echo self::HTML_RETURN;
        Html::showData('TIMBRE', $fingerPrintPac);
    }

    /**
     * @param Error $error
     */
    public static function showErrorPac($error)
    {
        Html::showTitle('ERROR');
        Html::showData('Número', $error->Numero->Value);
        Html::showData('Descripción', $error->Descripcion->Value);
        $tipo = sprintf('%s - %s', $error->Tipo->Value, ProcessProviderResult::toString($error->Tipo->Value));

        Html::showData('Tipo', $tipo);

        // Lo posibles tipos de errores son: ($parameters->Information->Error->Tipo->Value)
        // ProcessProviderResult::OK = 0;
        // ProcessProviderResult::ERROR_IN_CONNECTION_WITH_PAC = 1; | Se generó un error al intentar conectarse con el PAC.
        // ProcessProviderResult::ERROR_WITH_PAC = 2;               | El PAC retorno un error.
        // ProcessProviderResult::ERROR_WITH_DATA = 3;              | Existe un error en los datos proporcionados para llevar a cabo el proceso.
        // ProcessProviderResult::ERROR_GENERAL = 4;                | Se generó un error desconocido.
    }

    /**
     * @param array $error
     */
    public static function showErrorInfo($error)
    {
        Html::showTitle('ERROR');
        Html::showData('Estatus', $error['estatus']);
        echo self::HTML_RETURN;
        Html::showData('Codigo', $error['codigo']);
        echo self::HTML_RETURN;
        Html::showData('Mensaje', $error['mensaje']);
        echo self::HTML_RETURN;
        Html::showData('Descripción', $error['descripcion']);
    }

    /**
     * @param bool $downloadedByHash
     */
    public static function showPreviamenteTimbrado($downloadedByHash)
    {
        if ($downloadedByHash == false) {
            return;
        }

        Html::showTitle('DOCUMENTO PREVIAMENTE TIMBRADO');
        Html::showData('Previo', 'El documento habia sido timbrado previamente');
    }
}



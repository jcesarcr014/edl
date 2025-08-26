<?php

namespace Facturando\EDL\Example\Utils;

use Facturando\Shared\Utils;

final class Descripcion
{
    CONST LEER_CERTIFICADO = 1;
    CONST CODIGO_BARRAS = 2;
    CONST LEER_LICENCIA = 3;
    CONST CONSTANCIA_SECTOR_FINANCIERO = 4;
    CONST CONSTANCIA_PREMIOS = 5;
    CONST CONSTANCIA_PLANES_RETIRO = 6;
    CONST CONSTANCIA_PAGO_EXTRANJERO = 7;
    CONST CONSTANCIA_OPERACIONES_CON_DERIVADO = 8;
    CONST CONSTANCIA_INTERESES_HIPOTECARIOS = 9;
    CONST CONSTANCIA_INTERESES = 10;
    CONST CONSTANCIA_FIDEICOMISO_NO_EMPRESARIAL = 11;
    CONST CONSTANCIA_ENAJENACION_ACCIONES = 12;
    CONST CONSTANCIA_DIVIDENDOS = 13;
    CONST CONSTANCIA_ARRENDAMIENTO_EN_FIDEICOMISO = 14;
    CONST CONSTANCIA = 15;
    CONST CFDI_IMPUESTOS_LOCALES = 16;
    CONST CFDI_IEDU = 17;
    CONST CFDI_RECIBO_DE_NOMINA_12 = 18;
    CONST CFDI_RECIBO_DE_PAGO_20 = 19;
    CONST CFDI_40 = 20;
    CONST ECODEX_CONSTANCIA_DE_RETENCIONES = 21;
    CONST ECODEX_ESTADO_DE_CUENTA = 22;
    CONST ECODEX_DESCARGAR_UN_CFDI = 23;
    CONST ECODEX_CANCELAR_CFDI = 24;
    CONST ECODEX_ACUSE_DE_CANCELACION = 25;
    CONST ECODEX_IMPUESTOS_LOCALES = 26;
    CONST ECODEX_IEDU = 27;
    CONST ECODEX_RECIBO_NOMINA_CFDI_33 = 28;
    CONST ECODEX_RECIBO_NOMINA_CFDI_40 = 29;
    CONST ECODEX_RECIBO_PAGO_CFDI_40 = 30;
    CONST ECODEX_CFDI_40 = 31;
    CONST CONSTANCIA_PLATAFORMAS_TECNOLOGICAS = 32;

    CONST HTML_LISTA = '<ul class="coments"><li>';
    CONST TIMBRADO_ECODEX = '</li><li>Si deseas ver la generación y timbrado de ésta deberás de ejecutar el demo correspondiente en <strong>Ejemplos - ECODEX.</strong></li></ul>';


    private static $description = [
        self::LEER_CERTIFICADO => 'Este es el resultado del ejemplo que muestra como realizar la lectura y extracción de los datos de un certificado.',
        self::CODIGO_BARRAS => 'Este es el resultado del ejemplo que muestra como realizar la generación del Código de Barras Bidimensional.',
        self::LEER_LICENCIA => 'Este es el resultado del ejemplo que muestra como realizar la carga y desplegado de los datos de la licencia requerida para la activación y uso de la librería.',

        self::CONSTANCIA_SECTOR_FINANCIERO => self::HTML_LISTA . 'Este es el resultado del ejemplo que muestra como realizar la generación del XML sin timbrar de una constancia de retenciones que incluye el complemento para el sector financiero' . self::TIMBRADO_ECODEX,
        self::CONSTANCIA_PREMIOS => self::HTML_LISTA . 'Este es el resultado del ejemplo que muestra como realizar la generación del XML sin timbrar de una constancia de retenciones que incluye el complemento de premios.' . self::TIMBRADO_ECODEX,
        self::CONSTANCIA_PLANES_RETIRO => self::HTML_LISTA . 'Este es el resultado del ejemplo que muestra como realizar la generación del XML sin timbrar de una constancia de retenciones que incluye el complemento para planes de retiro.' . self::TIMBRADO_ECODEX,
        self::CONSTANCIA_PAGO_EXTRANJERO => self::HTML_LISTA . 'Este es el resultado del ejemplo que muestra como realizar la generación del XML sin timbrar de una constancia de retenciones que incluye el complemento para pagos a extranjeros.' . self::TIMBRADO_ECODEX,
        self::CONSTANCIA_OPERACIONES_CON_DERIVADO => self::HTML_LISTA . 'Este es el resultado del ejemplo que muestra como realizar la generación del XML sin timbrar de una constancia de retenciones que incluye el complemento para operaciones con derivados.' . self::TIMBRADO_ECODEX,
        self::CONSTANCIA_INTERESES_HIPOTECARIOS => self::HTML_LISTA . 'Este es el resultado del ejemplo que muestra como realizar la generación del XML sin timbrar de una constancia de retenciones que incluye el complemento para intereses hipotecarios.' . self::TIMBRADO_ECODEX,
        self::CONSTANCIA_INTERESES => self::HTML_LISTA . 'Este es el resultado del ejemplo que muestra como realizar la generación del XML sin timbrar de una constancia de retenciones que incluye el complemento para intereses.' . self::TIMBRADO_ECODEX,
        self::CONSTANCIA_FIDEICOMISO_NO_EMPRESARIAL => self::HTML_LISTA . 'Este es el resultado del ejemplo que muestra como realizar la generación del XML sin timbrar de una constancia de retenciones que incluye el complemento para fideicomiso no empresarial.' . self::TIMBRADO_ECODEX,
        self::CONSTANCIA_ENAJENACION_ACCIONES => self::HTML_LISTA . 'Este es el resultado del ejemplo que muestra como realizar la generación del XML sin timbrar de una constancia de retenciones que incluye el complemento para enajenación de acciones.' . self::TIMBRADO_ECODEX,
        self::CONSTANCIA_DIVIDENDOS => self::HTML_LISTA . 'Este es el resultado del ejemplo que muestra como realizar la generación del XML sin timbrar de una constancia de retenciones que incluye el complemento para dividendos.' . self::TIMBRADO_ECODEX,
        self::CONSTANCIA_ARRENDAMIENTO_EN_FIDEICOMISO => self::HTML_LISTA . 'Este es el resultado del ejemplo que muestra como realizar la generación del XML sin timbrar de una constancia de retenciones que incluye el complemento para arrendamiento en fideicomiso.' . self::TIMBRADO_ECODEX,
        self::CONSTANCIA => self::HTML_LISTA . 'Este es el resultado del ejemplo que muestra como realizar la generación del XML sin timbrar de una constancia de retenciones en su versión 2.0.' . self::TIMBRADO_ECODEX,
        self::CONSTANCIA_PLATAFORMAS_TECNOLOGICAS => self::HTML_LISTA . 'Este es el resultado del ejemplo que muestra como realizar la generación del XML sin timbrar de una constancia de retenciones que incluye el complemento para plataformas tecnológicas.' . self::TIMBRADO_ECODEX,

        self::CFDI_IMPUESTOS_LOCALES => self::HTML_LISTA . 'Este es el resultado del ejemplo que muestra como realizar la generación del XML del sellado de un pre-CFDI sin timbrar el cual incluye el complemento de impuestos locales.' . self::TIMBRADO_ECODEX,
        self::CFDI_IEDU => self::HTML_LISTA . 'Este es el resultado del ejemplo que muestra como realizar la generación del XML del sellado de un pre-CFDI sin timbrar el cual incluye el complemento de IEDU.' . self::TIMBRADO_ECODEX,
        self::CFDI_RECIBO_DE_NOMINA_12 => self::HTML_LISTA . 'Este es el resultado del ejemplo que muestra como realizar la generación del XML del sellado de un pre-CFDI sin timbrar el cual incluye el complemento para un recibo de nómina 1.2.' . self::TIMBRADO_ECODEX,
        self::CFDI_RECIBO_DE_PAGO_20 => self::HTML_LISTA . 'Este es el resultado del ejemplo que muestra como realizar la generación del XML del sellado de un pre-CFDI sin timbrar el cual incluye el complemento para un recibo de pago 2.0.' . self::TIMBRADO_ECODEX,
        self::CFDI_40 => self::HTML_LISTA . 'Este es el resultado del ejemplo que muestra como realizar la generación del XML del sellado de un pre-CFDI sin timbrar para un CFDI 4.0.' . self::TIMBRADO_ECODEX,

        self::ECODEX_CONSTANCIA_DE_RETENCIONES => 'Este es el resultado del ejemplo que muestra como realizar la generación del XML de una constancia de retenciones 2.0 timbrada y sin complemento.',
        self::ECODEX_ESTADO_DE_CUENTA => 'Este es el resultado del ejemplo que muestra como realizar la consulta del estado de cuenta de un RFC.',
        self::ECODEX_DESCARGAR_UN_CFDI => 'Este es el resultado del ejemplo que muestra como realizar la descarga del archivo del CFDI directo del PAC.',
        self::ECODEX_CANCELAR_CFDI => 'Este es el resultado del ejemplo que muestra como realizar la cancelación de un CFDI.',
        self::ECODEX_ACUSE_DE_CANCELACION => 'Este es el resultado del ejemplo que muestra como obtener el acuse de cancelación de un CFDI.',
        self::ECODEX_IMPUESTOS_LOCALES => 'Este es el resultado del ejemplo que muestra como realizar la generación y timbrado del XML de un CFDI el cual incluye el complemento de impuestos locales.',
        self::ECODEX_IEDU => 'Este es el resultado del ejemplo que muestra como realizar la generación y timbrado del XML de un CFDI el cual incluye el complemento IEDU.',
        self::ECODEX_RECIBO_NOMINA_CFDI_33 => 'Este es el resultado del ejemplo que muestra como realizar la generación y timbrado del XML de un recibo de nómina 1.2 para un CFDI 3.3.',
        self::ECODEX_RECIBO_NOMINA_CFDI_40 => 'Este es el resultado del ejemplo que muestra como realizar la generación y timbrado del XML de un recibo de nómina 1.2 para un CFDI 4.0.',
        self::ECODEX_RECIBO_PAGO_CFDI_40 => 'Este es el resultado del ejemplo que muestra como realizar la generación y timbrado del XML de un recibo de PAGO 2.0 para un CFDI 4.0.',
        self::ECODEX_CFDI_40 => self::HTML_LISTA . 'Este es el resultado del ejemplo que muestra como realizar la generación y timbrado del XML de un CFDI 4.0.</li><li>Si el documento ya habia sido previamente enviado, se desplegará la sección: <strong>DOCUMENTO PREVIAMENTE TIMBRADO.</strong></li></ul>',
    ];

    /**
     * @param $type
     * @return string
     */
    public static function get($type)
    {
        return self::$description[$type];
    }
}
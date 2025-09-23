<?php

namespace Facturando\EDL\Example\Data;

use DateTime;
use Facturando\ElectronicDocumentLibrary\Document\Data\CfdiRelacionado;
use Facturando\ElectronicDocumentLibrary\Document\Data\Concepto;
use Facturando\ElectronicDocumentLibrary\Document\Data\Importacion;
use Facturando\ElectronicDocumentLibrary\Document\Data\Impuesto;
use Facturando\ElectronicDocumentLibrary\Document\Data\Partida;
use Facturando\ElectronicDocumentLibrary\Document\Data\RetencionConcepto;
use Facturando\ElectronicDocumentLibrary\Document\Data\Traslado;
use Facturando\ElectronicDocumentLibrary\Document\Data\TrasladoConcepto;
use Facturando\ElectronicDocumentLibrary\Document\ElectronicDocument;

final class Cfdi33
{
    // Este ejemplo se llenando los datos requeridos para timbrar un CFDI 3.3
    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosTimbrado($electronicDocument)
    {
        $electronicDocument->Data->clear();

        //<editor-fold desc="Datos del comprobante">
        $electronicDocument->Data->Version->Value = '3.3';
        $electronicDocument->Data->Serie->Value = 'C';
        $electronicDocument->Data->Folio->Value = '3000';
        $electronicDocument->Data->Fecha->Value = new \DateTime('NOW -5 hours');
        $electronicDocument->Data->FormaPago->Value = '01';
        $electronicDocument->Data->CondicionesPago->Value = 'Contado';
        $electronicDocument->Data->SubTotal->Value = 7200.00;
        $electronicDocument->Data->Descuento->Value = 360.00;
        $electronicDocument->Data->Moneda->Value = 'MXN';
        $electronicDocument->Data->Total->Value = 7934.40;
        $electronicDocument->Data->TipoComprobante->Value = 'I';
        $electronicDocument->Data->MetodoPago->Value = 'PUE';
        $electronicDocument->Data->LugarExpedicion->Value = '01000';
        //</editor-fold>

        //<editor-fold desc="Datos del emisor">
        $electronicDocument->Data->Emisor->Rfc->Value = 'EKU9003173C9';
        $electronicDocument->Data->Emisor->Nombre->Value = 'ESCUELA KEMPER URGATE SA DE CV';
        $electronicDocument->Data->Emisor->RegimenFiscal->Value = '601';
        //</editor-fold>

        //<editor-fold desc="Datos del Receptor">
        $electronicDocument->Data->Receptor->Rfc->Value = 'AAAD770905441';
        $electronicDocument->Data->Receptor->Nombre->Value = 'DARIO ALVAREZ ARANDA';
        $electronicDocument->Data->Receptor->UsoCfdi->Value = 'G03';
        //</editor-fold>

        //<editor-fold desc="Concepto No 1">
        /** @var Concepto $concepto */
        $concepto = $electronicDocument->Data->Conceptos->add();
        $concepto->ClaveProductoServicio->Value = '01010101';
        $concepto->Cantidad->Value = 10;
        $concepto->ClaveUnidad->Value = 'H87';
        $concepto->Descripcion->Value = 'DVD';
        $concepto->ValorUnitario->Value = 120;
        $concepto->Importe->Value = 1200;
        $concepto->Descuento->Value = 360;

        //<editor-fold desc="Impuestos trasladados del concepto">
        /** @var TrasladoConcepto $trasladoConcepto */
        $trasladoConcepto = $concepto->Impuestos->Traslados->add();
        $trasladoConcepto->Base->Value = 840;
        $trasladoConcepto->Impuesto->Value = '002';
        $trasladoConcepto->TipoFactor->Value = 'Tasa';
        $trasladoConcepto->TasaCuota->Value = 0.160000;
        $trasladoConcepto->Importe->Value = 134.4;
        //</editor-fold>
        //</editor-fold>

        //<editor-fold desc="Concepto No 2">
        $concepto = $electronicDocument->Data->Conceptos->add();
        $concepto->ClaveProductoServicio->Value = '01010101';
        $concepto->Cantidad->Value = 1;
        $concepto->ClaveUnidad->Value = 'H87';
        $concepto->Descripcion->Value = 'Computadora armada';
        $concepto->ValorUnitario->Value = 3000;
        $concepto->Importe->Value = 3000;

        //<editor-fold desc="Impuestos trasladados del concepto">
        $trasladoConcepto = $concepto->Impuestos->Traslados->add();
        $trasladoConcepto->Base->Value = 3000;
        $trasladoConcepto->Impuesto->Value = '002';
        $trasladoConcepto->TipoFactor->Value = 'Tasa';
        $trasladoConcepto->TasaCuota->Value = 0.160000;
        $trasladoConcepto->Importe->Value = 480;
        //</editor-fold>
        //</editor-fold>

        //<editor-fold desc="Concepto No 3">
        $concepto = $electronicDocument->Data->Conceptos->add();
        $concepto->ClaveProductoServicio->Value = '01010101';
        $concepto->Cantidad->Value = 1;
        $concepto->ClaveUnidad->Value = 'H87';
        $concepto->Descripcion->Value = 'Monitor de 19 \' marca AOC';
        $concepto->ValorUnitario->Value = 3000;
        $concepto->Importe->Value = 3000;

        //<editor-fold desc="Impuestos trasladados del concepto">
        $trasladoConcepto = $concepto->Impuestos->Traslados->add();
        $trasladoConcepto->Base->Value = 3000;
        $trasladoConcepto->Impuesto->Value = '002';
        $trasladoConcepto->TipoFactor->Value = 'Tasa';
        $trasladoConcepto->TasaCuota->Value = 0.160000;
        $trasladoConcepto->Importe->Value = 480;
        //</editor-fold>
        //</editor-fold>

        //<editor-fold desc="Impuestos trasladados">
        /** @var Traslado $traslado */
        $traslado = $electronicDocument->Data->Impuestos->Traslados->add();
        $traslado->Tipo->Value = '002';
        $traslado->TipoFactor->Value = 'Tasa';
        $traslado->TasaCuota->Value = 0.160000;
        $traslado->Importe->Value = 1094.4;

        $electronicDocument->Data->Impuestos->TotalTraslados->Value = 1094.4;
        //</editor-fold>
    }

    // Este ejemplo muestra como usar todas las clases y propiedades para el CFDI 3.3
    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosCompleto($electronicDocument)
    {
        $electronicDocument->Data->clear();

        //<editor-fold desc="Datos del comprobante">
        $electronicDocument->Data->Version->Value = '3.3';
        $electronicDocument->Data->Serie->Value = 'C';
        $electronicDocument->Data->Folio->Value = '3000';
        $electronicDocument->Data->Fecha->Value = new \DateTime('NOW -5 hours');
        $electronicDocument->Data->FormaPago->Value = '01';
        $electronicDocument->Data->CondicionesPago->Value = 'Contado';
        $electronicDocument->Data->SubTotal->Value = 7200.00;
        $electronicDocument->Data->Descuento->Value = 360.00;
        $electronicDocument->Data->Moneda->Value = 'MXN';
        $electronicDocument->Data->TipoCambioMx->Value = 1.00;
        $electronicDocument->Data->TipoCambioMx->Decimals = 0;
        $electronicDocument->Data->TipoCambioMx->Dot = false;
        $electronicDocument->Data->Total->Value = 7934.40;
        $electronicDocument->Data->TipoComprobante->Value = 'I';
        $electronicDocument->Data->MetodoPago->Value = 'PUE';
        $electronicDocument->Data->LugarExpedicion->Value = '01000';
        $electronicDocument->Data->Confirmacion->Value = 'ECVH1';
        //</editor-fold>

        //<editor-fold desc="Información de los comprobantes fiscales relacionados">
        $electronicDocument->Data->CfdiRelacionados->TipoRelacion->Value = '01';
        /** @var CfdiRelacionado $cfdiRelacionado */
        $cfdiRelacionado = $electronicDocument->Data->CfdiRelacionados->add();
        $cfdiRelacionado->Uuid->Value = '3BBDC347-3925-4792-B592-5151C773258B';
        //</editor-fold>

        //<editor-fold desc="Datos del emisor">
        $electronicDocument->Data->Emisor->Rfc->Value = 'EKU9003173C9';
        $electronicDocument->Data->Emisor->Nombre->Value = 'ESCUELA KEMPER URGATE SA DE CV';
        $electronicDocument->Data->Emisor->RegimenFiscal->Value = '601';
        //</editor-fold>

        //<editor-fold desc="Datos del Receptor">
        $electronicDocument->Data->Receptor->Rfc->Value = 'AAA010101AAA';
        $electronicDocument->Data->Receptor->Nombre->Value = 'Receptor de prueba';
        $electronicDocument->Data->Receptor->ResinciaFiscal->Value = 'USA';
        $electronicDocument->Data->Receptor->NumeroRegistroIdTributario->Value = '121585958';
        $electronicDocument->Data->Receptor->UsoCfdi->Value = 'G01';
        //</editor-fold>

        //<editor-fold desc="Concepto">
        /** @var Concepto $concepto */
        $concepto = $electronicDocument->Data->Conceptos->add();
        $concepto->ClaveProductoServicio->Value = '01010101';
        $concepto->NumeroIdentificacion->Value = 'UT421510';
        $concepto->Cantidad->Value = 10;
        $concepto->ClaveUnidad->Value = 'H87';
        $concepto->Unidad->Value = 'Pieza';
        $concepto->Descripcion->Value = 'DVD';
        $concepto->ValorUnitario->Value = 120;
        $concepto->Importe->Value = 1200;
        $concepto->Descuento->Value = 360;

        //<editor-fold desc="Impuestos trasladados del concepto">
        /** @var TrasladoConcepto $trasladoConcepto */
        $trasladoConcepto = $concepto->Impuestos->Traslados->add();
        $trasladoConcepto->Base->Value = 840;
        $trasladoConcepto->Impuesto->Value = '002';
        $trasladoConcepto->TipoFactor->Value = 'Tasa';
        $trasladoConcepto->TasaCuota->Value = 0.160000;
        $trasladoConcepto->Importe->Value = 134.4;
        //</editor-fold>

        //<editor-fold desc="Impuestos retenidos del concepto">
        /** @var RetencionConcepto $retencionConcepto */
        $retencionConcepto = $concepto->Impuestos->Retenciones->add();
        $retencionConcepto->Base->Value = 1200;
        $retencionConcepto->Impuesto->Value = '001';
        $retencionConcepto->TipoFactor->Value = 'Tasa';
        $retencionConcepto->TasaCuota->Value = 0.160000;
        $retencionConcepto->Importe->Value = 192;
        //</editor-fold>

        //<editor-fold desc="Información aduanera del concepto">
        /** @var Importacion $importacion */
        $importacion = $concepto->InformacionAduanera->add();
        $importacion->Numero->Value = '10  47  3807  8003832';
        //</editor-fold>

        //<editor-fold desc="Cuenta predial del concepto">
        $concepto->CuentaPredial->Numero->Value = '15956011002';
        //</editor-fold>

        //<editor-fold desc="Partes del concepto">
        /** @var Partida $partida */
        $partida = $concepto->Partes->add();
        $partida->ClaveProductoServicio->Value = '01010101';
        $partida->NumeroIdentificacion->Value = '7501030283645';
        $partida->Cantidad->Value = 10;
        $partida->Unidad->Value = 'Piezas';
        $partida->Descripcion->Value = 'Descripción de la parte';
        $partida->ValorUnitario->Value = 100;
        $partida->Importe->Value = 1000;

        //<editor-fold desc="Información aduanera de la parte del concepto">
        /** @var Importacion $importacion */
        $importacion = $partida->InformacionAduanera->add();
        $importacion->Numero->Value = '10  47  3807  8003832';
        //</editor-fold>
        //</editor-fold>

        //</editor-fold>

        //<editor-fold desc="Impuestos trasladados">
        /** @var Traslado $traslado */
        $traslado = $electronicDocument->Data->Impuestos->Traslados->add();
        $traslado->Tipo->Value = '002';
        $traslado->TipoFactor->Value = 'Tasa';
        $traslado->TasaCuota->Value = 0.160000;
        $traslado->Importe->Value = 1094.4;

        $electronicDocument->Data->Impuestos->TotalTraslados->Value = 1094.4;
        //</editor-fold>

        //<editor-fold desc="Impuestos retenidos">
        /** @var Impuesto $retencion */
        $retencion = $electronicDocument->Data->Impuestos->Retenciones->add();
        $retencion->Tipo->Value = '001';
        $retencion->Importe->Value = 0;

        $electronicDocument->Data->Impuestos->TotalRetenciones->Value = 0;
        //</editor-fold>
    }

    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosListas($electronicDocument)
    {
        $electronicDocument->Data->clear();

        //<editor-fold desc="Datos del comprobante">
        $electronicDocument->Data->Version->Value = '3.3';
        $electronicDocument->Data->Serie->Value = 'C';
        $electronicDocument->Data->Folio->Value = '3000';
        $electronicDocument->Data->Fecha->Value = new \DateTime('NOW -5 hours');
        $electronicDocument->Data->FormaPago->Value = '01';
        $electronicDocument->Data->CondicionesPago->Value = 'Contado';
        $electronicDocument->Data->SubTotal->Value = 7200.00;
        $electronicDocument->Data->Descuento->Value = 360.00;
        $electronicDocument->Data->Moneda->Value = 'MXN';
        $electronicDocument->Data->TipoCambioMx->Value = 1.00;
        $electronicDocument->Data->TipoCambioMx->Decimals = 0;
        $electronicDocument->Data->TipoCambioMx->Dot = false;
        $electronicDocument->Data->Total->Value = 7934.40;
        $electronicDocument->Data->TipoComprobante->Value = 'I';
        $electronicDocument->Data->MetodoPago->Value = 'PUE';
        $electronicDocument->Data->LugarExpedicion->Value = '01000';
        $electronicDocument->Data->Confirmacion->Value = 'ECVH1';
        //</editor-fold>

        //<editor-fold desc="Información de los comprobantes fiscales relacionados">
        $electronicDocument->Data->CfdiRelacionados->TipoRelacion->Value = '01';
        for ($i = 1; $i <= 2; $i++) {
            /** @var CfdiRelacionado $cfdiRelacionado */
            $cfdiRelacionado = $electronicDocument->Data->CfdiRelacionados->add();
            $cfdiRelacionado->Uuid->Value = '3BBDC347-3925-4792-B592-5151C773258B';
        }
        //</editor-fold>

        //<editor-fold desc="Datos del emisor">
        $electronicDocument->Data->Emisor->Rfc->Value = 'EKU9003173C9';
        $electronicDocument->Data->Emisor->Nombre->Value = 'ESCUELA KEMPER URGATE SA DE CV';
        $electronicDocument->Data->Emisor->RegimenFiscal->Value = '601';
        //</editor-fold>

        //<editor-fold desc="Datos del Receptor">
        $electronicDocument->Data->Receptor->Rfc->Value = 'AAA010101AAA';
        $electronicDocument->Data->Receptor->Nombre->Value = 'Receptor de prueba';
        $electronicDocument->Data->Receptor->ResinciaFiscal->Value = 'USA';
        $electronicDocument->Data->Receptor->NumeroRegistroIdTributario->Value = '121585958';
        $electronicDocument->Data->Receptor->UsoCfdi->Value = 'G01';
        //</editor-fold>

        //<editor-fold desc="Conceptos">
        for ($i = 1; $i <= 3; $i++) {
            /** @var Concepto $concepto */
            $concepto = $electronicDocument->Data->Conceptos->add();
            $concepto->ClaveProductoServicio->Value = '01010101';
            $concepto->NumeroIdentificacion->Value = 'UT42151' . $i;
            $concepto->Cantidad->Value = 10;
            $concepto->ClaveUnidad->Value = 'H87';
            $concepto->Unidad->Value = 'Pieza';
            $concepto->Descripcion->Value = 'DVD';
            $concepto->ValorUnitario->Value = 120;
            $concepto->Importe->Value = 1200;
            $concepto->Descuento->Value = 360;

            //<editor-fold desc="Impuestos trasladados del concepto">
            for ($j = 1; $j <= 4; $j++) {
                /** @var TrasladoConcepto $trasladoConcepto */
                $trasladoConcepto = $concepto->Impuestos->Traslados->add();
                $trasladoConcepto->Base->Value = 840 + $j;
                $trasladoConcepto->Impuesto->Value = '002';
                $trasladoConcepto->TipoFactor->Value = 'Tasa';
                $trasladoConcepto->TasaCuota->Value = 0.160000;
                $trasladoConcepto->Importe->Value = 134.4;
            }
            //</editor-fold>

            //<editor-fold desc="Impuestos retenidos del concepto">
            for ($j = 1; $j <= 5; $j++) {
                /** @var RetencionConcepto $retencionConcepto */
                $retencionConcepto = $concepto->Impuestos->Retenciones->add();
                $retencionConcepto->Base->Value = 1200 + $j;
                $retencionConcepto->Impuesto->Value = '001';
                $retencionConcepto->TipoFactor->Value = 'Tasa';
                $retencionConcepto->TasaCuota->Value = 0.160000;
                $retencionConcepto->Importe->Value = 192;
            }
            //</editor-fold>

            //<editor-fold desc="Información aduanera del concepto">
            for ($j = 1; $j <= 6; $j++) {
                /** @var Importacion $importacion */
                $importacion = $concepto->InformacionAduanera->add();
                $importacion->Numero->Value = '10  47  3807  800383' . $j;
            }
            //</editor-fold>

            //<editor-fold desc="Cuenta predial del concepto">
            $concepto->CuentaPredial->Numero->Value = '15956011002';
            //</editor-fold>

            //<editor-fold desc="Partes del concepto">
            for ($j = 1; $j <= 7; $j++) {
                /** @var Partida $partida */
                $partida = $concepto->Partes->add();
                $partida->ClaveProductoServicio->Value = '01010101';
                $partida->NumeroIdentificacion->Value = '750103028364' . $j;
                $partida->Cantidad->Value = 10;
                $partida->Unidad->Value = 'Piezas';
                $partida->Descripcion->Value = 'Descripción de la parte';
                $partida->ValorUnitario->Value = 100;
                $partida->Importe->Value = 1000;

                //<editor-fold desc="Información aduanera de la parte del concepto">
                for ($k = 1; $k <= 8; $k++) {
                    /** @var Importacion $importacion */
                    $importacion = $partida->InformacionAduanera->add();
                    $importacion->Numero->Value = '10  47  3807  800383' . $k;
                }
                //</editor-fold>
            }
            //</editor-fold>

        }
        //</editor-fold>

        //<editor-fold desc="Impuestos trasladados">
        for ($i = 1; $i <= 9; $i++) {
            /** @var Traslado $traslado */
            $traslado = $electronicDocument->Data->Impuestos->Traslados->add();
            $traslado->Tipo->Value = '002';
            $traslado->TipoFactor->Value = 'Tasa';
            $traslado->TasaCuota->Value = 0.160000;
            $traslado->Importe->Value = 1094.4 + $i;
        }

        $electronicDocument->Data->Impuestos->TotalTraslados->Value = 1094.4;
        //</editor-fold>

        //<editor-fold desc="Impuestos retenidos">
        for ($i = 1; $i <= 10; $i++) {
            /** @var Impuesto $retencion */
            $retencion = $electronicDocument->Data->Impuestos->Retenciones->add();
            $retencion->Tipo->Value = '001';
            $retencion->Importe->Value = 0 + $i;
        }

        $electronicDocument->Data->Impuestos->TotalRetenciones->Value = 0;
        //</editor-fold>

    }

    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosMinimo($electronicDocument)
    {
        $electronicDocument->Data->clear();

        //<editor-fold desc="Datos del comprobante">
        $electronicDocument->Data->Version->Value = '3.3';
        $electronicDocument->Data->Fecha->Value = new \DateTime('NOW -5 hours');
        $electronicDocument->Data->SubTotal->Value = 7200.00;
        $electronicDocument->Data->Moneda->Value = 'MXN';
        $electronicDocument->Data->Total->Value = 7934.40;
        $electronicDocument->Data->TipoComprobante->Value = 'I';
        $electronicDocument->Data->LugarExpedicion->Value = '01000';

        $electronicDocument->Data->SubTotal->Decimals = 0;
        $electronicDocument->Data->SubTotal->Value = 0;
        $electronicDocument->Data->SubTotal->Dot = false;
        //</editor-fold>

        //<editor-fold desc="Datos del emisor">
        $electronicDocument->Data->Emisor->Rfc->Value = 'EKU9003173C9';
        $electronicDocument->Data->Emisor->RegimenFiscal->Value = '601';
        //</editor-fold>

        //<editor-fold desc="Datos del Receptor">
        $electronicDocument->Data->Receptor->Rfc->Value = 'AAA010101AAA';
        $electronicDocument->Data->Receptor->UsoCfdi->Value = 'G01';
        //</editor-fold>

        //<editor-fold desc="Concepto">
        /** @var Concepto $concepto */
        $concepto = $electronicDocument->Data->Conceptos->add();
        $concepto->ClaveProductoServicio->Value = '01010101';
        $concepto->Cantidad->Value = 10;
        $concepto->ClaveUnidad->Value = 'H87';
        $concepto->Descripcion->Value = 'DVD';
        $concepto->ValorUnitario->Value = 120;
        $concepto->Importe->Value = 1200;
        //</editor-fold>
    }
}
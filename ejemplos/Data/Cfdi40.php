<?php

namespace Facturando\EDL\Example\Data;

use DateTime;
use Facturando\ElectronicDocumentLibrary\Document\Data\CfdiRelacionado;
use Facturando\ElectronicDocumentLibrary\Document\Data\Concepto;
use Facturando\ElectronicDocumentLibrary\Document\Data\CuentaPredial;
use Facturando\ElectronicDocumentLibrary\Document\Data\Importacion;
use Facturando\ElectronicDocumentLibrary\Document\Data\Partida;
use Facturando\ElectronicDocumentLibrary\Document\Data\Relacionados;
use Facturando\ElectronicDocumentLibrary\Document\Data\RetencionConcepto;
use Facturando\ElectronicDocumentLibrary\Document\Data\Traslado;
use Facturando\ElectronicDocumentLibrary\Document\Data\TrasladoConcepto;
use Facturando\ElectronicDocumentLibrary\Document\ElectronicDocument;

final class Cfdi40
{
    // Este ejemplo se llenando los datos requeridos para timbrar un CFDI 4.0
    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosTimbrado($electronicDocument)
    {
        $electronicDocument->Data->clear();

        //<editor-fold desc="Datos del comprobante">
        $electronicDocument->Data->Version->Value = '4.0';
        $electronicDocument->Data->Exportacion->Value = '01';
        $electronicDocument->Data->Folio->Value = 'CFDI40146';
        $electronicDocument->Data->FormaPago->Value = '01';
        $electronicDocument->Data->LugarExpedicion->Value = '89400';
        $electronicDocument->Data->MetodoPago->Value = 'PUE';
        $electronicDocument->Data->Moneda->Value = 'MXN';
        $electronicDocument->Data->Serie->Value = 'CFDI40';
        $electronicDocument->Data->Fecha->Value = new \DateTime('NOW -5 hours');
        $electronicDocument->Data->SubTotal->Value = 10;
        $electronicDocument->Data->TipoComprobante->Value = 'I';
        $electronicDocument->Data->Total->Value = 10.32;
        //</editor-fold>

        //<editor-fold desc="Información de los comprobantes fiscales relacionados">
        /** @var Relacionados $relacionados */
        $relacionados = $electronicDocument->Data->CfdiRelacionadosExt->add();
        $relacionados->CfdiRelacionados->TipoRelacion->Value = '01';
        $relacionados->CfdiRelacionados->add()->Uuid->Value = 'A39DA66B-52CA-49E3-879B-5C05185B0EF7';
        //</editor-fold>

        //<editor-fold desc="Datos del emisor">
        $electronicDocument->Data->Emisor->Rfc->Value = 'EKU9003173C9';
        $electronicDocument->Data->Emisor->Nombre->Value = 'ESCUELA KEMPER URGATE SA DE CV';
        $electronicDocument->Data->Emisor->RegimenFiscal->Value = '601';
        //</editor-fold>

        //<editor-fold desc="Datos del Receptor">
        $electronicDocument->Data->Receptor->Rfc->Value = 'AAAD770905441';
        $electronicDocument->Data->Receptor->Nombre->Value = 'DARIO ALVAREZ ARANDA';
        $electronicDocument->Data->Receptor->RegimenFiscalReceptor->Value = '612';
        $electronicDocument->Data->Receptor->UsoCfdi->Value = 'G03';
        $electronicDocument->Data->Receptor->DomicilioFiscalReceptor->Value = '07300';
        //</editor-fold>

        //<editor-fold desc="Concepto">
        /** @var Concepto $concepto */
        $concepto = $electronicDocument->Data->Conceptos->add();
        $concepto->Cantidad->Value = 2;
        $concepto->ClaveProductoServicio->Value = '78101500';
        $concepto->ClaveUnidad->Value = 'CMT';
        $concepto->Descripcion->Value = 'ACERO';
        $concepto->Importe->Value = 10;
        $concepto->NumeroIdentificacion->Value = '00001';
        $concepto->ObjetoImpuesto->Value = '02';
        $concepto->Unidad->Value = 'TONELADA';
        $concepto->ValorUnitario->Value = 5;

        //<editor-fold desc="Impuestos trasladados del concepto">
        /** @var TrasladoConcepto $trasladoConcepto */
        $trasladoConcepto = $concepto->Impuestos->Traslados->add();
        $trasladoConcepto->Base->Value = 2;
        $trasladoConcepto->Importe->Value = 0.32;
        $trasladoConcepto->Impuesto->Value = '002';
        $trasladoConcepto->TipoFactor->Value = 'Tasa';
        $trasladoConcepto->TasaCuota->Value = 0.160000;
        //</editor-fold>

        //<editor-fold desc="Impuestos retenidos del concepto">
        /** @var RetencionConcepto $retencionConcepto */
        $retencionConcepto = $concepto->Impuestos->Retenciones->add();
        $retencionConcepto->Base->Value = 2;
        $retencionConcepto->Importe->Value = 0;
        $retencionConcepto->Impuesto->Value = '002';
        $retencionConcepto->TipoFactor->Value = 'Tasa';
        $retencionConcepto->TasaCuota->Value = 0;
        //</editor-fold>

        //<editor-fold desc="Cuenta predial del concepto">
        $concepto->CuentasPrediales->add()->Numero->Value = "51888";
        //</editor-fold>
        //</editor-fold>

        //<editor-fold desc="Impuestos trasladados">
        /** @var Traslado $traslado */
        $traslado = $electronicDocument->Data->Impuestos->Traslados->add();
        $traslado->Base->Value = 2;
        $traslado->Importe->Value = 0.32;
        $traslado->Tipo->Value = '002';
        $traslado->TipoFactor->Value = 'Tasa';
        $traslado->TasaCuota->Value = 0.160000;

        $electronicDocument->Data->Impuestos->TotalTraslados->Value = 0.32;
        //</editor-fold>

        //<editor-fold desc="Impuestos retenidos">
        /** @var \Facturando\ElectronicDocumentLibrary\Document\Data\Impuesto $retencion */
        $retencion = $electronicDocument->Data->Impuestos->Retenciones->add();
        $retencion->Tipo->Value = '002';
        $retencion->Importe->Value = 0;

        $electronicDocument->Data->Impuestos->TotalRetenciones->Value = 0;
        //</editor-fold>
    }

    // Este ejemplo muestra como usar todas las clases y propiedades para el CFDI 4.0
    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosCompleto($electronicDocument)
    {
        $electronicDocument->Data->clear();

        //<editor-fold desc="Datos del comprobante">
        $electronicDocument->Data->Version->Value = '4.0';
        $electronicDocument->Data->Serie->Value = 'CFDI';
        $electronicDocument->Data->Folio->Value = '40';
        $electronicDocument->Data->Fecha->Value = new \DateTime('NOW -5 hours');
        $electronicDocument->Data->FormaPago->Value = '01';
        $electronicDocument->Data->CondicionesPago->Value = 'Efectivo';
        $electronicDocument->Data->SubTotal->Value = 1100.00;
        $electronicDocument->Data->Descuento->Value = 100.00;
        $electronicDocument->Data->Moneda->Value = 'MXN';

        $electronicDocument->Data->TipoCambioMx->Value = 1.00;
        $electronicDocument->Data->TipoCambioMx->Dot = false;
        $electronicDocument->Data->TipoCambioMx->Decimals = 0;

        $electronicDocument->Data->Total->Value = 1000.00;
        $electronicDocument->Data->TipoComprobante->Value = 'I';
        $electronicDocument->Data->Exportacion->Value = '01';
        $electronicDocument->Data->MetodoPago->Value = 'PUE';
        $electronicDocument->Data->LugarExpedicion->Value = '89400';
        $electronicDocument->Data->Confirmacion->Value = 'ECVH1';
        //</editor-fold>

        //<editor-fold desc="Información Global">
        $electronicDocument->Data->InformacionGlobal->Periodicidad->Value = '01';
        $electronicDocument->Data->InformacionGlobal->Meses->Value = '01';
        $electronicDocument->Data->InformacionGlobal->Anio->Value = 2022;
        //</editor-fold>

        //<editor-fold desc="Información de los comprobantes fiscales relacionados">
        /** @var Relacionados $relacionados */
        $relacionados = $electronicDocument->Data->CfdiRelacionadosExt->add();
        $relacionados->CfdiRelacionados->TipoRelacion->Value = '04';
        /** @var CfdiRelacionado $cfdiRelacionado */
        $cfdiRelacionado = $relacionados->CfdiRelacionados->add();
        $cfdiRelacionado->Uuid->Value = '3BBDC347-3925-4792-B592-5151C773258B';
        //</editor-fold>

        //<editor-fold desc="Datos del emisor">
        $electronicDocument->Data->Emisor->Rfc->Value = 'EKU9003173C9';
        $electronicDocument->Data->Emisor->Nombre->Value = 'ESCUELA KEMPER URGATE SA DE CV';
        $electronicDocument->Data->Emisor->RegimenFiscal->Value = '601';
        $electronicDocument->Data->Emisor->FacAtrAdquirente->Value = '1234567890';
        //</editor-fold>

        //<editor-fold desc="Datos del Receptor">
        $electronicDocument->Data->Receptor->Rfc->Value = 'AAA010101AAA';
        $electronicDocument->Data->Receptor->Nombre->Value = 'Receptor de prueba';
        $electronicDocument->Data->Receptor->DomicilioFiscalReceptor->Value = '46400';
        $electronicDocument->Data->Receptor->ResinciaFiscal->Value = 'USA';
        $electronicDocument->Data->Receptor->NumeroRegistroIdTributario->Value = '121585958';
        $electronicDocument->Data->Receptor->RegimenFiscalReceptor->Value = '601';
        $electronicDocument->Data->Receptor->UsoCfdi->Value = 'G03';
        //</editor-fold>

        //<editor-fold desc="Concepto">
        /** @var Concepto $concepto */
        $concepto = $electronicDocument->Data->Conceptos->add();
        $concepto->ClaveProductoServicio->Value = '43211508';
        $concepto->NumeroIdentificacion->Value = 'UT421510';
        $concepto->Cantidad->Value = 1;
        $concepto->ClaveUnidad->Value = 'H87';
        $concepto->Unidad->Value = 'Pieza';
        $concepto->Descripcion->Value = 'Computadores personales';
        $concepto->ValorUnitario->Value = 1100;
        $concepto->Importe->Value = 1100;
        $concepto->Descuento->Value = 100.00;
        $concepto->ObjetoImpuesto->Value = '02';

        //<editor-fold desc="Impuestos trasladados del concepto">
        /** @var TrasladoConcepto $trasladoConcepto */
        $trasladoConcepto = $concepto->Impuestos->Traslados->add();
        $trasladoConcepto->Base->Value = 1000;
        $trasladoConcepto->Impuesto->Value = '002';
        $trasladoConcepto->TipoFactor->Value = 'Tasa';
        $trasladoConcepto->TasaCuota->Value = 0.160000;
        $trasladoConcepto->Importe->Value = 160.00;
        //</editor-fold>

        //<editor-fold desc="Impuestos retenidos del concepto">
        /** @var RetencionConcepto $retencionConcepto */
        $retencionConcepto = $concepto->Impuestos->Retenciones->add();
        $retencionConcepto->Base->Value = 1000;
        $retencionConcepto->Impuesto->Value = '001';
        $retencionConcepto->TipoFactor->Value = 'Tasa';
        $retencionConcepto->TasaCuota->Value = 0.160000;
        $retencionConcepto->Importe->Value = 160;
        //</editor-fold>

        //<editor-fold desc="A cuenta de terceros">
        $concepto->ACuentaTerceros->Rfc->Value = 'AAA010101AAA';
        $concepto->ACuentaTerceros->Nombre->Value = 'Contribuyente tercero';
        $concepto->ACuentaTerceros->RegimenFiscal->Value = '601';
        $concepto->ACuentaTerceros->DomicilioFiscal->Value = '46400';
        //</editor-fold>

        //<editor-fold desc="Información aduanera del concepto">
        /** @var Importacion $importacion */
        $importacion = $concepto->InformacionAduanera->add();
        $importacion->Numero->Value = '21  24  1772  3000244';
        //</editor-fold>

        //<editor-fold desc="Cuenta predial del concepto">
        /** @var CuentaPredial $cuentaPredial */
        $cuentaPredial = $concepto->CuentasPrediales->add();
        $cuentaPredial->Numero->Value = '15956011002';
        //</editor-fold>

        //<editor-fold desc="Partes del concepto">
        /** @var Partida $partida */
        $partida = $concepto->Partes->add();
        $partida->ClaveProductoServicio->Value = '43202222';
        $partida->NumeroIdentificacion->Value = '7501030283645';
        $partida->Cantidad->Value = 10;
        $partida->Unidad->Value = 'Piezas';
        $partida->Descripcion->Value = 'Cables de computador';
        $partida->ValorUnitario->Value = 100;
        $partida->Importe->Value = 1000;

        //<editor-fold desc="Información aduanera de la parte del concepto">
        $importacion = $partida->InformacionAduanera->add();
        $importacion->Numero->Value = '21  24  1772  3000244';
        //</editor-fold>
        //</editor-fold>

        //</editor-fold>

        //<editor-fold desc="Impuestos trasladados">
        /** @var Traslado $traslado */
        $traslado = $electronicDocument->Data->Impuestos->Traslados->add();
        $traslado->Base->Value = 1000;
        $traslado->Tipo->Value = '002';
        $traslado->TipoFactor->Value = 'Tasa';
        $traslado->TasaCuota->Value = 0.160000;
        $traslado->Importe->Value = 160.00;

        $electronicDocument->Data->Impuestos->TotalTraslados->Value = 160.00;
        //</editor-fold>

        //<editor-fold desc="Impuestos retenidos">
        /** @var \Facturando\ElectronicDocumentLibrary\Document\Data\Impuesto $retencion */
        $retencion = $electronicDocument->Data->Impuestos->Retenciones->add();
        $retencion->Tipo->Value = '001';
        $retencion->Importe->Value = 160;

        $electronicDocument->Data->Impuestos->TotalRetenciones->Value = 160;
        //</editor-fold>

    }

    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosListas($electronicDocument)
    {
        $electronicDocument->Data->clear();

        //<editor-fold desc="Datos del comprobante">
        $electronicDocument->Data->Version->Value = '4.0';
        $electronicDocument->Data->Serie->Value = 'CFDI';
        $electronicDocument->Data->Folio->Value = '40';
        $electronicDocument->Data->Fecha->Value = new \DateTime('NOW -5 hours');
        $electronicDocument->Data->FormaPago->Value = '01';
        $electronicDocument->Data->CondicionesPago->Value = 'Efectivo';
        $electronicDocument->Data->SubTotal->Value = 1100.00;
        $electronicDocument->Data->Descuento->Value = 100.00;
        $electronicDocument->Data->Moneda->Value = 'MXN';

        $electronicDocument->Data->TipoCambioMx->Value = 1.00;
        $electronicDocument->Data->TipoCambioMx->Dot = false;
        $electronicDocument->Data->TipoCambioMx->Decimals = 0;

        $electronicDocument->Data->Total->Value = 1160.00;
        $electronicDocument->Data->TipoComprobante->Value = 'I';
        $electronicDocument->Data->Exportacion->Value = '01';
        $electronicDocument->Data->MetodoPago->Value = 'PUE';
        $electronicDocument->Data->LugarExpedicion->Value = '89400';
        $electronicDocument->Data->Confirmacion->Value = 'ECVH1';
        //</editor-fold>

        //<editor-fold desc="Información Global">
        $electronicDocument->Data->InformacionGlobal->Periodicidad->Value = '01';
        $electronicDocument->Data->InformacionGlobal->Meses->Value = '01';
        $electronicDocument->Data->InformacionGlobal->Anio->Value = 2022;
        //</editor-fold>

        //<editor-fold desc="Información de los comprobantes fiscales relacionados">
        for ($i = 1; $i <= 2; $i++) {
            /** @var Relacionados $relacionados */
            $relacionados = $electronicDocument->Data->CfdiRelacionadosExt->add();
            $relacionados->CfdiRelacionados->TipoRelacion->Value = '04';
            /** @var CfdiRelacionado $cfdiRelacionado */
            $cfdiRelacionado = $relacionados->CfdiRelacionados->add();
            $cfdiRelacionado->Uuid->Value = '3BBDC347-3925-4792-B592-5151C773258B';
        }

        //</editor-fold>

        //<editor-fold desc="Datos del emisor">
        $electronicDocument->Data->Emisor->Rfc->Value = 'EKU9003173C9';
        $electronicDocument->Data->Emisor->Nombre->Value = 'ESCUELA KEMPER URGATE SA DE CV';
        $electronicDocument->Data->Emisor->RegimenFiscal->Value = '601';
        $electronicDocument->Data->Emisor->FacAtrAdquirente->Value = '1234567890';
        //</editor-fold>

        //<editor-fold desc="Datos del Receptor">
        $electronicDocument->Data->Receptor->Rfc->Value = 'AAA010101AAA';
        $electronicDocument->Data->Receptor->Nombre->Value = 'Receptor de prueba';
        $electronicDocument->Data->Receptor->DomicilioFiscalReceptor->Value = '46400';
        $electronicDocument->Data->Receptor->ResinciaFiscal->Value = 'USA';
        $electronicDocument->Data->Receptor->NumeroRegistroIdTributario->Value = '121585958';
        $electronicDocument->Data->Receptor->RegimenFiscalReceptor->Value = '601';
        $electronicDocument->Data->Receptor->UsoCfdi->Value = 'G03';
        //</editor-fold>

        //<editor-fold desc="Concepto">
        for ($i = 1; $i <= 3; $i++) {
            /** @var Concepto $concepto */
            $concepto = $electronicDocument->Data->Conceptos->add();
            $concepto->ClaveProductoServicio->Value = '43211508';
            $concepto->NumeroIdentificacion->Value = 'UT421510';
            $concepto->Cantidad->Value = 1;
            $concepto->ClaveUnidad->Value = 'H87';
            $concepto->Unidad->Value = 'Pieza';
            $concepto->Descripcion->Value = 'Computadores personales';
            $concepto->ValorUnitario->Value = 1100;
            $concepto->Importe->Value = 1100;
            $concepto->Descuento->Value = 100.00;
            $concepto->ObjetoImpuesto->Value = '02';

            //<editor-fold desc="Impuestos trasladados del concepto">
            for ($j = 1; $j <= 4; $j++) {
                /** @var TrasladoConcepto $trasladoConcepto */
                $trasladoConcepto = $concepto->Impuestos->Traslados->add();
                $trasladoConcepto->Base->Value = 1000;
                $trasladoConcepto->Impuesto->Value = '002';
                $trasladoConcepto->TipoFactor->Value = 'Tasa';
                $trasladoConcepto->TasaCuota->Value = 0.160000;
                $trasladoConcepto->Importe->Value = 160.00;
            }
            //</editor-fold>

            //<editor-fold desc="Impuestos retenidos del concepto">
            for ($j = 1; $j <= 5; $j++) {
                /** @var RetencionConcepto $retencionConcepto */
                $retencionConcepto = $concepto->Impuestos->Retenciones->add();
                $retencionConcepto->Base->Value = 1000;
                $retencionConcepto->Impuesto->Value = '001';
                $retencionConcepto->TipoFactor->Value = 'Tasa';
                $retencionConcepto->TasaCuota->Value = 0.160000;
                $retencionConcepto->Importe->Value = 160;
            }
            //</editor-fold>

            //<editor-fold desc="A cuenta de terceros">
            $concepto->ACuentaTerceros->Rfc->Value = 'AAA010101AAA';
            $concepto->ACuentaTerceros->Nombre->Value = 'Contribuyente tercero';
            $concepto->ACuentaTerceros->RegimenFiscal->Value = '601';
            $concepto->ACuentaTerceros->DomicilioFiscal->Value = '46400';
            //</editor-fold>

            //<editor-fold desc="Información aduanera del concepto">
            for ($j = 1; $j <= 6; $j++) {
                /** @var Importacion $importacion */
                $importacion = $concepto->InformacionAduanera->add();
                $importacion->Numero->Value = '21  24  1772  3000244';
            }
            //</editor-fold>

            //<editor-fold desc="Cuenta predial del concepto">
            for ($j = 1; $j <= 7; $j++) {
                /** @var CuentaPredial $cuentaPredial */
                $cuentaPredial = $concepto->CuentasPrediales->add();
                $cuentaPredial->Numero->Value = '15956011002';
            }
            //</editor-fold>

            //<editor-fold desc="Partes del concepto">
            for ($j = 1; $j <= 8; $j++) {
                /** @var Partida $partida */
                $partida = $concepto->Partes->add();
                $partida->ClaveProductoServicio->Value = '43202222';
                $partida->NumeroIdentificacion->Value = '7501030283645';
                $partida->Cantidad->Value = 10;
                $partida->Unidad->Value = 'Piezas';
                $partida->Descripcion->Value = 'Cables de computador';
                $partida->ValorUnitario->Value = 100;
                $partida->Importe->Value = 1000;

                //<editor-fold desc="Información aduanera de la parte del concepto">
                for ($k = 1; $k <= 9; $k++) {
                    $importacion = $partida->InformacionAduanera->add();
                    $importacion->Numero->Value = '21  24  1772  3000244';
                }
                //</editor-fold>
            }
            //</editor-fold>
        }
        //</editor-fold>

        //<editor-fold desc="Impuestos trasladados">
        for ($i = 1; $i <= 10; $i++) {
            /** @var Traslado $traslado */
            $traslado = $electronicDocument->Data->Impuestos->Traslados->add();
            $traslado->Base->Value = 1000;
            $traslado->Tipo->Value = '002';
            $traslado->TipoFactor->Value = 'Tasa';
            $traslado->TasaCuota->Value = 0.160000;
            $traslado->Importe->Value = 160.00;
        }

        $electronicDocument->Data->Impuestos->TotalTraslados->Value = 160.00;
        //</editor-fold>

        //<editor-fold desc="Impuestos retenidos">
        for ($i = 1; $i <= 11; $i++) {
            /** @var \Facturando\ElectronicDocumentLibrary\Document\Data\Impuesto $retencion */
            $retencion = $electronicDocument->Data->Impuestos->Retenciones->add();
            $retencion->Tipo->Value = '001';
            $retencion->Importe->Value = 160;
        }

        $electronicDocument->Data->Impuestos->TotalRetenciones->Value = 160;
        //</editor-fold>
    }

    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosMinimo($electronicDocument)
    {
        $electronicDocument->Data->clear();

        //<editor-fold desc="Datos del comprobante">
        $electronicDocument->Data->Version->Value = '4.0';
        $electronicDocument->Data->Fecha->Value = new \DateTime('NOW -5 hours');
        $electronicDocument->Data->SubTotal->Value = 1100.00;
        $electronicDocument->Data->Moneda->Value = 'MXN';
        $electronicDocument->Data->Total->Value = 1160.00;
        $electronicDocument->Data->TipoComprobante->Value = 'I';
        $electronicDocument->Data->Exportacion->Value = '01';
        $electronicDocument->Data->LugarExpedicion->Value = '89400';
        //</editor-fold>

        //<editor-fold desc="Datos del emisor">
        $electronicDocument->Data->Emisor->Rfc->Value = 'EKU9003173C9';
        $electronicDocument->Data->Emisor->Nombre->Value = 'ESCUELA KEMPER URGATE SA DE CV';
        $electronicDocument->Data->Emisor->RegimenFiscal->Value = '601';
        //</editor-fold>

        //<editor-fold desc="Datos del Receptor">
        $electronicDocument->Data->Receptor->Rfc->Value = 'AAA010101AAA';
        $electronicDocument->Data->Receptor->Nombre->Value = 'Receptor de prueba';
        $electronicDocument->Data->Receptor->DomicilioFiscalReceptor->Value = '46400';
        $electronicDocument->Data->Receptor->RegimenFiscalReceptor->Value = '601';
        $electronicDocument->Data->Receptor->UsoCfdi->Value = 'G03';
        //</editor-fold>

        //<editor-fold desc="Concepto">
        /** @var Concepto $concepto */
        $concepto = $electronicDocument->Data->Conceptos->add();
        $concepto->ClaveProductoServicio->Value = '43211508';
        $concepto->Cantidad->Value = 1;
        $concepto->ClaveUnidad->Value = 'H87';
        $concepto->Descripcion->Value = 'Computadores personales';
        $concepto->ValorUnitario->Value = 1100;
        $concepto->Importe->Value = 1100;
        $concepto->ObjetoImpuesto->Value = '01';
        //</editor-fold>
    }
}
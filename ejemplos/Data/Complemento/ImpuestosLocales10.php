<?php

namespace Facturando\EDL\Example\Data\Complemento;

use Facturando\EDL\Example\Data\Cfdi40;
use Facturando\ElectronicDocumentLibrary\Base\Types\Complemento;
use Facturando\ElectronicDocumentLibrary\Complemento\ImpuestosLocales\Retencion;
use Facturando\ElectronicDocumentLibrary\Complemento\ImpuestosLocales\Traslado;
use Facturando\ElectronicDocumentLibrary\Document\Data\Concepto;
use Facturando\ElectronicDocumentLibrary\Document\Data\RetencionConcepto;
use Facturando\ElectronicDocumentLibrary\Document\Data\TrasladoConcepto;
use Facturando\ElectronicDocumentLibrary\Document\ElectronicDocument;

final class ImpuestosLocales10
{
    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosCompleto($electronicDocument)
    {
        //En este método se cargan los datos de la factura-
        Cfdi40::CargarDatosCompleto($electronicDocument);

        $electronicDocument->Data->Complementos->add(Complemento::IMPUESTOS_LOCALES);

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\ImpuestosLocales\Data $data */
        $data = $electronicDocument->Data->Complementos->last();

        $data->Version->Value = '1.0';
        $data->TotalRetenciones->Value = 256.75;
        $data->TotalTraslados->Value = 256.75;

        /** @var Retencion $retencion */
        $retencion = $data->Retenciones->add();
        $retencion->Impuesto->Value = 'ISR';
        $retencion->Tasa->Value = 10.88;
        $retencion->Importe->Value = 247.23;

        $retencion = $data->Retenciones->add();
        $retencion->Impuesto->Value = 'ISR';
        $retencion->Tasa->Value = 6.40;
        $retencion->Importe->Value = 9.52;

        /** @var Traslado $traslado */
        $traslado = $data->Traslados->add();
        $traslado->Impuesto->Value = 'IVA';
        $traslado->Tasa->Value = 16;
        $traslado->Importe->Value = 102;

        $traslado = $data->Traslados->add();
        $traslado->Impuesto->Value = 'IVA';
        $traslado->Tasa->Value = 16;
        $traslado->Importe->Value = 154.75;
    }

    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosTimbrado($electronicDocument)
    {
        Base::Comprobante($electronicDocument->Data);
        Base::Emisor($electronicDocument->Data->Emisor);
        Base::Receptor($electronicDocument->Data->Receptor);

        //<editor-fold desc="Concepto">
        /** @var Concepto $concepto */
        $concepto = $electronicDocument->Data->Conceptos->add();
        $concepto->ClaveProductoServicio->Value = '01010101';
        $concepto->Cantidad->Value = 1;
        $concepto->ClaveUnidad->Value = 'H87';
        $concepto->Descripcion->Value = 'Concepto';
        $concepto->ValorUnitario->Value = 1000;
        $concepto->Importe->Value = 1000;
        $concepto->ObjetoImpuesto->Value = '02';
        //</editor-fold>

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

        //<editor-fold desc="Impuestos trasladados">
        /** @var \Facturando\ElectronicDocumentLibrary\Document\Data\Traslado $retencionConcepto */
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

        $electronicDocument->Data->Complementos->add(Complemento::IMPUESTOS_LOCALES);

        //<editor-fold desc="Complemento Impuesto Locales">
        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\ImpuestosLocales\Data $data */
        $data = $electronicDocument->Data->Complementos->last();

        $data->Version->Value = '1.0';
        $data->TotalRetenciones->Value = 256.75;
        $data->TotalTraslados->Value = 256.75;

        //<editor-fold desc="Se agregan los Movimientos o Conceptos de los impuestos locales retenidos">
        /** @var Retencion $retencion */
        $retencion = $data->Retenciones->add();
        $retencion->Impuesto->Value = 'ISR';
        $retencion->Tasa->Value = 10.88;
        $retencion->Importe->Value = 247.23;

        $retencion = $data->Retenciones->add();
        $retencion->Impuesto->Value = 'ISR';
        $retencion->Tasa->Value = 6.40;
        $retencion->Importe->Value = 9.52;
        //</editor-fold>

        //<editor-fold desc="Se agregan los Movimientos o Conceptos de los impuestos locales de traslado">
        /** @var Traslado $traslado */
        $traslado = $data->Traslados->add();
        $traslado->Impuesto->Value = 'IVA';
        $traslado->Tasa->Value = 16;
        $traslado->Importe->Value = 102;

        $traslado = $data->Traslados->add();
        $traslado->Impuesto->Value = 'IVA';
        $traslado->Tasa->Value = 16;
        $traslado->Importe->Value = 154.75;
        //</editor-fold>

        //</editor-fold>
    }
}
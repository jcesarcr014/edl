<?php

namespace Facturando\EDL\Example\Data\Complemento;

use Facturando\EDL\Example\Data\Cfdi40;
use Facturando\ElectronicDocumentLibrary\Base\Types\ComplementoConcepto;
use Facturando\ElectronicDocumentLibrary\Document\Data\Concepto;
use Facturando\ElectronicDocumentLibrary\Document\ElectronicDocument;


final class InstitucionesEducativas10
{
    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosCompleto($electronicDocument)
    {
        //En este método se cargan los datos de la factura-
        Cfdi40::CargarDatosCompleto($electronicDocument);

        $electronicDocument->Data->Conceptos->clear();
        /** @var Concepto $concepto */
        $concepto = $electronicDocument->Data->Conceptos->add();
        $concepto->ClaveProductoServicio->Value = '01010101';
        $concepto->Cantidad->Value = 10;
        $concepto->ClaveUnidad->Value = 'H87';
        $concepto->Descripcion->Value = 'DVD';
        $concepto->ValorUnitario->Value = 120;
        $concepto->Importe->Value = 1200;
        $concepto->Descuento->Value = 360;
        $concepto->ObjetoImpuesto->Value = '01';

        $concepto->Complementos->add(ComplementoConcepto::INSTITUCIONES_EDUCATIVAS);

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\Conceptos\InstitucionesEducativasPrivadas\Data $data */
        $data = $concepto->Complementos->last();

        $data->Version->Value = '1.0';
        $data->NombreAlumno->Value = 'A';
        $data->Curp->Value = 'BEML920313HCMLNS01';
        $data->NivelEducativo->Value = 'Profesional técnico';
        $data->AutorizacionRvoe->Value = 'B';
        $data->RfcPago->Value = 'XXXX010101XX1';
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
        $concepto->ClaveUnidad->Value = 'E48';
        $concepto->Descripcion->Value = 'Colegiatura';
        $concepto->ValorUnitario->Value = 1000;
        $concepto->Importe->Value = 1000;
        $concepto->ObjetoImpuesto->Value = '01';
        //</editor-fold>

        //<editor-fold desc="Complemento IEDU">
        $concepto->Complementos->add(ComplementoConcepto::INSTITUCIONES_EDUCATIVAS);

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\Conceptos\InstitucionesEducativasPrivadas\Data $data */
        $data = $concepto->Complementos->last();

        $data->Version->Value = '1.0';
        $data->NombreAlumno->Value = 'A';
        $data->Curp->Value = 'BEML920313HCMLNS01';
        $data->NivelEducativo->Value = 'Profesional técnico';
        $data->AutorizacionRvoe->Value = 'B';
        $data->RfcPago->Value = 'XXXX010101XX1';
        //</editor-fold>
    }
}
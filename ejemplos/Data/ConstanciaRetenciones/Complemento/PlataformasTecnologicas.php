<?php

namespace Facturando\EDL\Example\Data\ConstanciaRetenciones\Complemento;

use Facturando\EDL\Example\Data\Retenciones\Constancia20;
use Facturando\ElectronicDocumentLibrary\Base\Types\ComplementoConstanciaRetenciones;
use Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\Complemento\PlataformasTecnologicas\DetalleServicio;
use Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\ConstanciaRetenciones;

final class PlataformasTecnologicas
{
    /**
     * @param ConstanciaRetenciones $constanciaRetenciones
     */
    public static function CargarDatosCompleto($constanciaRetenciones)
    {
        Constancia20::CargarDatosTimbrado($constanciaRetenciones);

        $constanciaRetenciones->Data->Complementos->add(ComplementoConstanciaRetenciones::PLATAFORMAS_TECNOLOGICAS);

        /** @var \Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\Complemento\PlataformasTecnologicas\Data $data */
        $data = $constanciaRetenciones->Data->Complementos->last();

        $data->Version->Value = '1.0';
        $data->Periodicidad->Value = '01';
        $data->NumeroServicio->Value = '1';
        $data->MontoTotalServicioSinIva->Value = 0;
        $data->TotalIvaTrasladado->Value = 0;
        $data->TotalIvaRetenido->Value = 0;
        $data->TotalIsrRetenido->Value = 0;
        $data->DiferenciaIvaEntregadoPrestadorServicio->Value = 0;
        $data->MontoTotalPorUsoPlataforma->Value = 0;
        $data->MontoTotalContribucionGubernamental->Value = 0;

        /** @var DetalleServicio $detalle */
        $detalle = $data->DetallesServicio->add();
        $detalle->FormaPagoServicio->Value = '01';
        $detalle->TipoServicio->Value = '01';
        $detalle->SubTipoServicio->Value = '01';
        $detalle->RfcTerceroAutorizado->Value = 'XAXX010101000';
        $detalle->FechaServicio->Value = new \DateTime('NOW');
        $detalle->PrecioServicioSinIva->Value = 0;

        $detalle->ImpuestosTrasladadosServicio->Base->Value = 0.000001;
        $detalle->ImpuestosTrasladadosServicio->Base->Decimals = 6;
        $detalle->ImpuestosTrasladadosServicio->Impuesto->Value = '01';
        $detalle->ImpuestosTrasladadosServicio->TipoFactor->Value = 'Tasa';
        $detalle->ImpuestosTrasladadosServicio->TasaCuota->Value = 0.160000;
        $detalle->ImpuestosTrasladadosServicio->Importe->Value = 0;

        $detalle->ContribucionGubernamental->EntidadDondePagaContribucion->Value = '01';
        $detalle->ContribucionGubernamental->ImporteContribucion->Value = 0;

        $detalle->ComisionServicio->Base->Value = 0.000001;
        $detalle->ComisionServicio->Base->Decimals = 6;
        $detalle->ComisionServicio->Porcentaje->Value = 0.001;
        $detalle->ComisionServicio->Porcentaje->Decimals = 3;
        $detalle->ComisionServicio->Importe->Value = 0;
    }
}
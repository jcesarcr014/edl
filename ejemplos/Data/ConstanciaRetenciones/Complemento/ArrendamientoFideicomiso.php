<?php

namespace Facturando\EDL\Example\Data\ConstanciaRetenciones\Complemento;

use Facturando\EDL\Example\Data\Retenciones\Constancia20;
use Facturando\ElectronicDocumentLibrary\Base\Types\ComplementoConstanciaRetenciones;
use Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\ConstanciaRetenciones;

final class ArrendamientoFideicomiso
{
    /**
     * @param ConstanciaRetenciones $constanciaRetenciones
     */
    public static function CargarDatosCompleto($constanciaRetenciones)
    {
        Constancia20::CargarDatosTimbrado($constanciaRetenciones);

        $constanciaRetenciones->Data->Complementos->add(ComplementoConstanciaRetenciones::ARRENDAMIENTO_FIDEICOMISO);

        /** @var \Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\Complemento\ArrendamientoFideicomiso\Data $data */
        $data = $constanciaRetenciones->Data->Complementos->last();

        $data->Version->Value = '1.0';
        $data->ImportePago->Value = 1000.00;
        $data->ImporteRendimiento->Value = 1200.00;
        $data->ImporteDeducciones->Value = 100.00;
        $data->MontoTotalRetencion->Value = 100.00;
        $data->MontoFibras->Value = 0.00;
        $data->MontoOtrosConceptos->Value = 100.00;
        $data->DescripcionMontoOtrosConceptos->Value = 'Descripción';
    }
}


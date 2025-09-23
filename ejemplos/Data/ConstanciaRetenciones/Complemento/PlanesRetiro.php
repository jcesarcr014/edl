<?php

namespace Facturando\EDL\Example\Data\ConstanciaRetenciones\Complemento;

use Facturando\EDL\Example\Data\Retenciones\Constancia20;
use Facturando\ElectronicDocumentLibrary\Base\Types\ComplementoConstanciaRetenciones;
use Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\ConstanciaRetenciones;

final class PlanesRetiro
{
    /**
     * @param ConstanciaRetenciones $constanciaRetenciones
     */
    public static function CargarDatosCompleto($constanciaRetenciones)
    {
        Constancia20::CargarDatosTimbrado($constanciaRetenciones);

        $constanciaRetenciones->Data->Complementos->add(ComplementoConstanciaRetenciones::PLANES_RETIRO);

        /** @var \Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\Complemento\PlanesRetiro\Data $data */
        $data = $constanciaRetenciones->Data->Complementos->last();

        $data->Version->Value = '1.0';
        $data->SistemaFinanciero->Value = 'SI';
        $data->MontoTotalAportaciones->Value = 15000;
        $data->MontoInteresesReales->Value = 2000;
        $data->HuboRetirosAnioAnteriorPermanencia->Value = 'SI';
        $data->MontoTotalRetiro->Value = 15000;
        $data->MontoTotalExentoRetiro->Value = 10000;
        $data->MontoTotalExedente->Value = 5000;
        $data->HuboRetirosAnioInmediatoAnterior->Value = 'SI';
        $data->MontTotalRetirado->Value = 2000;
    }
}
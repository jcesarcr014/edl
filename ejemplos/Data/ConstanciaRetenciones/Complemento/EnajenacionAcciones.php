<?php

namespace Facturando\EDL\Example\Data\ConstanciaRetenciones\Complemento;

use Facturando\EDL\Example\Data\Retenciones\Constancia20;
use Facturando\ElectronicDocumentLibrary\Base\Types\ComplementoConstanciaRetenciones;
use Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\ConstanciaRetenciones;

final class EnajenacionAcciones
{
    /**
     * @param ConstanciaRetenciones $constanciaRetenciones
     */
    public static function CargarDatosCompleto($constanciaRetenciones)
    {
        Constancia20::CargarDatosTimbrado($constanciaRetenciones);

        $constanciaRetenciones->Data->Complementos->add(ComplementoConstanciaRetenciones::ENAJENACION_ACCIONES);

        /** @var \Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\Complemento\EnajenacionAcciones\Data $data */
        $data = $constanciaRetenciones->Data->Complementos->last();

        $data->Version->Value = '1.0';
        $data->ContratoIntermediacion->Value = 'ContratoIntermediacion';
        $data->Ganancia->Value = 1000;
        $data->Perdida->Value = 2000;
    }
}
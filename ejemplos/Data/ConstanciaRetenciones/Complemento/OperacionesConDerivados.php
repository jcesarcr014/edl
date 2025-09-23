<?php

namespace Facturando\EDL\Example\Data\ConstanciaRetenciones\Complemento;

use Facturando\EDL\Example\Data\Retenciones\Constancia20;
use Facturando\ElectronicDocumentLibrary\Base\Types\ComplementoConstanciaRetenciones;
use Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\ConstanciaRetenciones;

final class OperacionesConDerivados
{
    /**
     * @param ConstanciaRetenciones $constanciaRetenciones
     */
    public static function CargarDatosCompleto($constanciaRetenciones)
    {
        Constancia20::CargarDatosTimbrado($constanciaRetenciones);

        $constanciaRetenciones->Data->Complementos->add(ComplementoConstanciaRetenciones::OPERACIONES_CON_DERIVADOS);

        /** @var \Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\Complemento\OperacionesConDerivados\Data $data */
        $data = $constanciaRetenciones->Data->Complementos->last();

        $data->Version->Value = '1.0';
        $data->MontoGanancia->Value = 10000;
        $data->MontoPerdida->Value = 500;
    }
}
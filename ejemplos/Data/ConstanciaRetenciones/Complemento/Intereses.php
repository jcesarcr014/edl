<?php

namespace Facturando\EDL\Example\Data\ConstanciaRetenciones\Complemento;

use Facturando\EDL\Example\Data\Retenciones\Constancia20;
use Facturando\ElectronicDocumentLibrary\Base\Types\ComplementoConstanciaRetenciones;
use Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\ConstanciaRetenciones;

final class Intereses
{
    /**
     * @param ConstanciaRetenciones $constanciaRetenciones
     */
    public static function CargarDatosCompleto($constanciaRetenciones)
    {
        Constancia20::CargarDatosTimbrado($constanciaRetenciones);

        $constanciaRetenciones->Data->Complementos->add(ComplementoConstanciaRetenciones::INTERESES);

        /** @var \Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\Complemento\Intereses\Data $data */
        $data = $constanciaRetenciones->Data->Complementos->last();

        $data->Version->Value = '1.0';
        $data->SistemaFinanciero->Value = 'SI';
        $data->Retiro->Value = 'NO';
        $data->OperacionesDerivadas->Value = 'SI';
        $data->MontoNominal->Value = 1500;
        $data->MontoReal->Value = 1000.12;
        $data->Perdida->Value = 500.1234;
    }
}
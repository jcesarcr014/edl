<?php

namespace Facturando\EDL\Example\Data\ConstanciaRetenciones\Complemento;

use Facturando\EDL\Example\Data\Retenciones\Constancia20;
use Facturando\ElectronicDocumentLibrary\Base\Types\ComplementoConstanciaRetenciones;
use Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\ConstanciaRetenciones;

final class SectorFinanciero
{
    /**
     * @param ConstanciaRetenciones $constanciaRetenciones
     */
    public static function CargarDatosCompleto($constanciaRetenciones)
    {
        Constancia20::CargarDatosTimbrado($constanciaRetenciones);

        $constanciaRetenciones->Data->Complementos->add(ComplementoConstanciaRetenciones::SECTOR_FINANCIERO);

        /** @var \Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\Complemento\SectorFinanciero\Data $data */
        $data = $constanciaRetenciones->Data->Complementos->last();

        $data->Version->Value = '1.0';
        $data->IdFideicomiso->Value = 'IdFideicomiso';
        $data->NombreFideicomiso->Value = 'NombreFideicomiso';
        $data->DescripcionFideicomiso->Value = 'DescripcionFideicomiso';
    }
}
<?php

namespace Facturando\EDL\Example\Data\ConstanciaRetenciones\Complemento;

use Facturando\EDL\Example\Data\Retenciones\Constancia20;
use Facturando\ElectronicDocumentLibrary\Base\Types\ComplementoConstanciaRetenciones;
use Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\ConstanciaRetenciones;

final class Dividendos
{
    /**
     * @param ConstanciaRetenciones $constanciaRetenciones
     */
    public static function CargarDatosCompleto($constanciaRetenciones)
    {
        Constancia20::CargarDatosTimbrado($constanciaRetenciones);

        $constanciaRetenciones->Data->Complementos->add(ComplementoConstanciaRetenciones::DIVIDENDOS);

        /** @var \Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\Complemento\Dividendos\Data $data */
        $data = $constanciaRetenciones->Data->Complementos->last();

        $data->Version->Value = '1.0';

        $data->DividendosUtilidades->ClaveTipoDividendo->Value = '01';
        $data->DividendosUtilidades->MontoIsrMexico->Value = 100;
        $data->DividendosUtilidades->MontoIsrExtranjero->Value = 200;
        $data->DividendosUtilidades->MontoRetencionExtranjero->Value = 200;
        $data->DividendosUtilidades->TipoSociedad->Value = 'Sociedad Extranjera';
        $data->DividendosUtilidades->MontoIsrNacional->Value = 100;
        $data->DividendosUtilidades->MontoDividendoNacional->Value = 200;
        $data->DividendosUtilidades->MontoDividendoExtranjero->Value = 400;

        $data->Remanente->Proporcion->Value = 7;
    }
}


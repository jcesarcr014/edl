<?php

namespace Facturando\EDL\Example\Data\ConstanciaRetenciones\Complemento;

use Facturando\EDL\Example\Data\Retenciones\Constancia20;
use Facturando\ElectronicDocumentLibrary\Base\Types\ComplementoConstanciaRetenciones;
use Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\ConstanciaRetenciones;

final class InteresesHipotecarios
{
    /**
     * @param ConstanciaRetenciones $constanciaRetenciones
     */
    public static function CargarDatosCompleto($constanciaRetenciones)
    {
        Constancia20::CargarDatosTimbrado($constanciaRetenciones);

        $constanciaRetenciones->Data->Complementos->add(ComplementoConstanciaRetenciones::INTERESES_HIPOTECARIOS);

        /** @var \Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\Complemento\InteresesHipotecarios\Data $data */
        $data = $constanciaRetenciones->Data->Complementos->last();

        $data->Version->Value = '1.0';
        $data->CreditoInstitucionFinanciera->Value = 'SI';
        $data->SaldoInsoluto->Value = 10000;
        $data->ProporcionDeducibleCredito->Value = 200;
        $data->MontoTotalInteresesNominalesDevengatos->Value = 450;
        $data->MontoTotalInteresesNominalesDevengatosPagados->Value = 400;
        $data->MontoTotalInteresesRealPagadoDeducible->Value = 200;
        $data->NumeroContrato->Value = 'NumeroContrato';
    }
}
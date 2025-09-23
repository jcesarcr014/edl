<?php

namespace Facturando\EDL\Example\Data\ConstanciaRetenciones\Complemento;

use Facturando\EDL\Example\Data\Retenciones\Constancia20;
use Facturando\ElectronicDocumentLibrary\Base\Types\ComplementoConstanciaRetenciones;
use Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\ConstanciaRetenciones;

final class FideicomisoNoEmpresarial
{
    /**
     * @param ConstanciaRetenciones $constanciaRetenciones
     */
    public static function CargarDatosCompleto($constanciaRetenciones)
    {
        Constancia20::CargarDatosTimbrado($constanciaRetenciones);

        $constanciaRetenciones->Data->Complementos->add(ComplementoConstanciaRetenciones::FIDEICOMISO_NO_EMPRESARIAL);

        /** @var \Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\Complemento\FideicomisoNoEmpresarial\Data $data */
        $data = $constanciaRetenciones->Data->Complementos->last();

        $data->Version->Value = '1.0';

        $data->IngresosEntradas->MontoTotal->Value = 500;
        $data->IngresosEntradas->ParteProporcional->Value = 200;
        $data->IngresosEntradas->Proporcion->Value = 300;
        $data->IngresosEntradas->IntegracionIngresos->Concepto->Value = 'Entradas';

        $data->DeduccionesSalidas->MontoTotal->Value = 400;
        $data->DeduccionesSalidas->ParteProporcional->Value = 250;
        $data->DeduccionesSalidas->Proporcion->Value = 150;
        $data->DeduccionesSalidas->IntegracionEngresos->Conceptos->Value = 'Salidas';

        $data->Retenciones->Monto->Value = 450;
        $data->Retenciones->Descripcion->Value = 'Retenciones';
    }
}
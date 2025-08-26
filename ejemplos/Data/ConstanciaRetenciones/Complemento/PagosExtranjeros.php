<?php

namespace Facturando\EDL\Example\Data\ConstanciaRetenciones\Complemento;

use Facturando\EDL\Example\Data\Retenciones\Constancia20;
use Facturando\ElectronicDocumentLibrary\Base\Types\ComplementoConstanciaRetenciones;
use Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\ConstanciaRetenciones;

final class PagosExtranjeros
{
    /**
     * @param ConstanciaRetenciones $constanciaRetenciones
     */
    public static function CargarDatosCompleto($constanciaRetenciones)
    {
        Constancia20::CargarDatosTimbrado($constanciaRetenciones);

        $constanciaRetenciones->Data->Complementos->add(ComplementoConstanciaRetenciones::PAGOS_EXTRANJEROS);

        /** @var \Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\Complemento\PagosExtranjeros\Data $data */
        $data = $constanciaRetenciones->Data->Complementos->last();

        $data->Version->Value = '1.0';
        $data->EsBeneficiario->Value = 'SI';

        $data->NoBeneficiario->PaisResidencia->Value = 'MX';
        $data->NoBeneficiario->ConceptoPago->Value = 1;
        $data->NoBeneficiario->DescripcionConcepto->Value = 'DescripcionConcepto';

        $data->Beneficiario->Rfc->Value = 'AAAA010101AAA';
        $data->Beneficiario->Curp->Value = 'AAAA010101HCMLNS09';
        $data->Beneficiario->RazonSocial->Value = 'RazonSocial';
        $data->Beneficiario->ConceptoPago->Value = 1;
        $data->Beneficiario->DescripcionConcepto->Value = 'DescripcionConcepto';
    }
}
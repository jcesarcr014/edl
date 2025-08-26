<?php

namespace Facturando\EDL\Example\Data\Complemento;

use Facturando\ElectronicDocumentLibrary\Document\Data\Data;
use Facturando\ElectronicDocumentLibrary\Document\Data\Emisor;
use Facturando\ElectronicDocumentLibrary\Document\Data\Receptor;

final class Base
{
    /**
     * @param Data $data
     */
    public static function Comprobante($data)
    {
        $data->clear();

        $data->Version->Value = '4.0';
        $data->Serie->Value = 'CFDI40';
        $data->Folio->Value = '1';
        $data->Fecha->Value = new \DateTime('NOW -5 hours');
        $data->FormaPago->Value = '01';
        $data->CondicionesPago->Value = 'Efectivo';
        $data->SubTotal->Value = 1000.00;
        $data->Moneda->Value = 'MXN';
        $data->Total->Value = 1000.00;
        $data->TipoComprobante->Value = 'I';
        $data->Exportacion->Value = '01';
        $data->MetodoPago->Value = 'PUE';
        $data->LugarExpedicion->Value = '89400';
    }

    /**
     * @param Emisor $data
     */
    public static function Emisor($data)
    {
        $data->Rfc->Value = 'EKU9003173C9';
        $data->Nombre->Value = 'ESCUELA KEMPER URGATE SA DE CV';
        $data->RegimenFiscal->Value = '601';
    }

    /**
     * @param Receptor $data
     */
    public static function Receptor($data)
    {
        $data->Rfc->Value = 'AAAD770905441';
        $data->Nombre->Value = 'DARIO ALVAREZ ARANDA';
        $data->RegimenFiscalReceptor->Value = '612';
        $data->DomicilioFiscalReceptor->Value = '07300';
        $data->UsoCfdi->Value = 'G03';
    }

}
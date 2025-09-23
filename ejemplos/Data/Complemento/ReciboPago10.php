<?php

namespace Facturando\EDL\Example\Data\Complemento;

use Facturando\ElectronicDocumentLibrary\Base\Types\Complemento;
use Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\DocumentoRelacionado;
use Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Pago;
use Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Retencion;
use Facturando\ElectronicDocumentLibrary\Document\Data\Concepto;
use Facturando\ElectronicDocumentLibrary\Document\ElectronicDocument;

final class ReciboPago10
{
    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosTimbrado($electronicDocument)
    {
        ReciboPago10::CargarDatosCfdi33($electronicDocument->Data);

        $electronicDocument->Data->Complementos->add(Complemento::RECEPCIONPAGO);

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Data $data */
        $data = $electronicDocument->Data->Complementos->last();

        $data->Version->Value = '1.0';

        /** @var Pago $pago */
        $pago = $data->Pagos->add();

        $pago->FechaPago->Value = new \DateTime('NOW -5 hours');
        $pago->FormaPago->Value = '01';
        $pago->Moneda->Value = 'MXN';
        $pago->Monto->Value = 100;
        $pago->NumeroOperacion->Value = '01';
        $pago->NombreBancoOrdenanteExtrajero->Value = 'Banco';

        /** @var DocumentoRelacionado $documentoRelacionado */
        $documentoRelacionado = $pago->DocumentosRelacionados->add();
        $documentoRelacionado->IdDocumento->Value = '708d3743-1a32-4ddc-9f70-4cd1c301623e';
        $documentoRelacionado->Serie->Value = 'SERIE';
        $documentoRelacionado->Folio->Value = '1';
        $documentoRelacionado->Moneda->Value = 'MXN';
        $documentoRelacionado->MetodoPago->Value = 'PPD';
        $documentoRelacionado->NumeroParcialidad->Value = 1;
        $documentoRelacionado->ImporteSaldoAnterior->Value = 100;
        $documentoRelacionado->ImportePagado->Value = 50;
        $documentoRelacionado->ImporteSaldoInsoluto->Value = 50;

        $documentoRelacionado = $pago->DocumentosRelacionados->add();
        $documentoRelacionado->IdDocumento->Value = '708d3743-1a32-4ddc-9f70-4cd1c301623d';
        $documentoRelacionado->Serie->Value = 'SERIE';
        $documentoRelacionado->Folio->Value = '2';
        $documentoRelacionado->Moneda->Value = 'MXN';
        $documentoRelacionado->MetodoPago->Value = 'PPD';
        $documentoRelacionado->NumeroParcialidad->Value = 1;
        $documentoRelacionado->ImporteSaldoAnterior->Value = 100;
        $documentoRelacionado->ImportePagado->Value = 50;
        $documentoRelacionado->ImporteSaldoInsoluto->Value = 50;
    }

    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosCompleto($electronicDocument)
    {
        ReciboPago10::CargarDatosCfdi33($electronicDocument->Data);

        $electronicDocument->Data->Complementos->add(Complemento::RECEPCIONPAGO);

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Data $data */
        $data = $electronicDocument->Data->Complementos->last();

        $data->Version->Value = '1.0';

        /** @var Pago $pago */
        $pago = $data->Pagos->add();
        $pago->FechaPago->Value = new \DateTime('NOW -5 hours');
        $pago->FormaPago->Value = '01';
        $pago->Moneda->Value = 'MXN';
        $pago->TipoCambio->Value = 20.00;
        $pago->Monto->Value = 100;
        $pago->NumeroOperacion->Value = '01';
        $pago->RfcEmisorCuentaOrdenante->Value = 'AAA010101AAA';
        $pago->NombreBancoOrdenanteExtrajero->Value = 'Banco ordenante';
        $pago->CuentaOrdenante->Value = '1234567890';
        $pago->RfcEmisorCuentaBeneficiario->Value = 'AAA010101AAA';
        $pago->CuentaBeneficiario->Value = '1234567890';
        $pago->TipoCadenaPago->Value = '01';
        $pago->CertificadoPago->Value = 'UjBsR09EbGhjZ0dTQUxNQUFBUUNBRU1tQ1p0dU1GUXhEUzhi';
        $pago->CadenaOriginalPago->Value = 'X';
        $pago->SelloPago->Value = 'UjBsR09EbGhjZ0dTQUxNQUFBUUNBRU1tQ1p0dU1GUXhEUzhi';

        /** @var DocumentoRelacionado $documentoRelacionado */
        $documentoRelacionado = $pago->DocumentosRelacionados->add();
        $documentoRelacionado->IdDocumento->Value = '708d3743-1a32-4ddc-9f70-4cd1c301623e';
        $documentoRelacionado->Serie->Value = 'SERIE';
        $documentoRelacionado->Folio->Value = '1';
        $documentoRelacionado->Moneda->Value = 'MXN';
        $documentoRelacionado->TipoCambio->Value = 0.000001;
        $documentoRelacionado->MetodoPago->Value = 'PPD';
        $documentoRelacionado->NumeroParcialidad->Value = 1;
        $documentoRelacionado->ImporteSaldoAnterior->Value = 100;
        $documentoRelacionado->ImportePagado->Value = 50;
        $documentoRelacionado->ImporteSaldoInsoluto->Value = 50;

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Impuesto $impuesto */
        $impuesto = $pago->Impuestos->add();
        $impuesto->TotalImpuestosTrasladados->Value = 16;
        $impuesto->TotalImpuestosRetenidos->Value = 0;

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Traslado $trasladoComplemento */
        $trasladoComplemento = $impuesto->Traslados->add();
        $trasladoComplemento->Impuesto->Value = '002';
        $trasladoComplemento->TipoFactor->Value = 'Tasa';
        $trasladoComplemento->TasaCuota->Value = 0.16;
        $trasladoComplemento->Importe->Value = 16;

        /** @var Retencion $retenidoComplemento */
        $retenidoComplemento = $impuesto->Retenciones->add();
        $retenidoComplemento->Impuesto->Value = '002';
        $retenidoComplemento->Importe->Value = 0;
    }

    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosListas($electronicDocument)
    {
        ReciboPago10::CargarDatosCfdi33($electronicDocument->Data);

        $electronicDocument->Data->Complementos->add(Complemento::RECEPCIONPAGO);

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Data $data */
        $data = $electronicDocument->Data->Complementos->last();

        $data->Version->Value = '1.0';

        for ($i = 1; $i <= 2; $i++) {
            /** @var Pago $pago */
            $pago = $data->Pagos->add();
            $pago->FechaPago->Value = new \DateTime('NOW -5 hours');
            $pago->FormaPago->Value = '01';
            $pago->Moneda->Value = 'MXN';
            $pago->TipoCambio->Value = 20.00;
            $pago->Monto->Value = 100;
            $pago->NumeroOperacion->Value = '01';
            $pago->RfcEmisorCuentaOrdenante->Value = 'AAA010101AAA';
            $pago->NombreBancoOrdenanteExtrajero->Value = 'Banco ordenante';
            $pago->CuentaOrdenante->Value = '1234567890';
            $pago->RfcEmisorCuentaBeneficiario->Value = 'AAA010101AAA';
            $pago->CuentaBeneficiario->Value = '1234567890';
            $pago->TipoCadenaPago->Value = '01';
            $pago->CertificadoPago->Value = 'UjBsR09EbGhjZ0dTQUxNQUFBUUNBRU1tQ1p0dU1GUXhEUzhi';
            $pago->CadenaOriginalPago->Value = 'X';
            $pago->SelloPago->Value = 'UjBsR09EbGhjZ0dTQUxNQUFBUUNBRU1tQ1p0dU1GUXhEUzhi';

            for ($j = 1; $j <= 3; $j++) {
                /** @var DocumentoRelacionado $documentoRelacionado */
                $documentoRelacionado = $pago->DocumentosRelacionados->add();
                $documentoRelacionado->IdDocumento->Value = '708d3743-1a32-4ddc-9f70-4cd1c301623e';
                $documentoRelacionado->Serie->Value = 'SERIE';
                $documentoRelacionado->Folio->Value = '1';
                $documentoRelacionado->Moneda->Value = 'MXN';
                $documentoRelacionado->TipoCambio->Value = 0.000001;
                $documentoRelacionado->MetodoPago->Value = 'PPD';
                $documentoRelacionado->NumeroParcialidad->Value = 1;
                $documentoRelacionado->ImporteSaldoAnterior->Value = 100;
                $documentoRelacionado->ImportePagado->Value = 50;
                $documentoRelacionado->ImporteSaldoInsoluto->Value = 50;
            }

            for ($j = 1; $j <= 4; $j++) {
                /** @var \Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Impuesto $impuesto */
                $impuesto = $pago->Impuestos->add();
                $impuesto->TotalImpuestosTrasladados->Value = 16;
                $impuesto->TotalImpuestosRetenidos->Value = 0;

                for ($k = 1; $k <= 5; $k++) {
                    /** @var \Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Traslado $trasladoComplemento */
                    $trasladoComplemento = $impuesto->Traslados->add();
                    $trasladoComplemento->Impuesto->Value = '002';
                    $trasladoComplemento->TipoFactor->Value = 'Tasa';
                    $trasladoComplemento->TasaCuota->Value = 0.16;
                    $trasladoComplemento->Importe->Value = 16;
                }

                for ($k = 1; $k <= 3; $k++) {
                    /** @var Retencion $retenidoComplemento */
                    $retenidoComplemento = $impuesto->Retenciones->add();
                    $retenidoComplemento->Impuesto->Value = '002';
                    $retenidoComplemento->Importe->Value = 0;
                }
            }
        }
    }

    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosMinimo($electronicDocument)
    {
        ReciboPago10::CargarDatosCfdi33($electronicDocument->Data);

        $electronicDocument->Data->Complementos->add(Complemento::RECEPCIONPAGO);

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Data $data */
        $data = $electronicDocument->Data->Complementos->last();
        $data->Version->Value = '1.0';

        /** @var Pago $pago */
        $pago = $data->Pagos->add();
        $pago->FechaPago->Value = new \DateTime('NOW -5 hours');
        $pago->FormaPago->Value = '01';
        $pago->Moneda->Value = 'MXN';
        $pago->Monto->Value = 100;

        /** @var DocumentoRelacionado $documentoRelacionado */
        $documentoRelacionado = $pago->DocumentosRelacionados->add();
        $documentoRelacionado->IdDocumento->Value = '708d3743-1a32-4ddc-9f70-4cd1c301623e';
        $documentoRelacionado->Moneda->Value = 'MXN';
        $documentoRelacionado->MetodoPago->Value = 'PPD';
        $documentoRelacionado->NumeroParcialidad->Value = 1;
        $documentoRelacionado->ImporteSaldoAnterior->Value = 100;
        $documentoRelacionado->ImportePagado->Value = 50;
        $documentoRelacionado->ImporteSaldoInsoluto->Value = 50;
    }

    /**
     * @param \Facturando\ElectronicDocumentLibrary\Document\Data\Data $data
     */
    private static function CargarDatosCfdi33($data)
    {
        $data->clear();

        //<editor-fold desc="Datos del comprobante">
        $data->Version->Value = '3.3';
        $data->Serie->Value = 'RP';
        $data->Folio->Value = '1';
        $data->Fecha->Value = new \DateTime('NOW -5 hours');
        $data->SubTotal->Value = 0;
        $data->SubTotal->Decimals = 0;
        $data->SubTotal->Dot = false;
        $data->Moneda->Value = 'XXX';
        $data->Total->Value = 0;
        $data->Total->Decimals = 0;
        $data->Total->Dot = false;
        $data->TipoComprobante->Value = 'P';
        $data->LugarExpedicion->Value = '01000';
        //</editor-fold>

        Base::Emisor($data->Emisor);

        //<editor-fold desc="Datos del Receptor">
        $data->Receptor->Rfc->Value = 'AAAD770905441';
        $data->Receptor->Nombre->Value = 'DARIO ALVAREZ ARANDA';
        $data->Receptor->UsoCfdi->Value = 'P01';
        //</editor-fold>

        //<editor-fold desc="Concepto">
        /** @var Concepto $concepto */
        $concepto = $data->Conceptos->add();
        $concepto->ClaveProductoServicio->Value = '84111506';
        $concepto->Cantidad->Value = 1;
        $concepto->Cantidad->Decimals = 0;
        $concepto->Cantidad->Dot = false;
        $concepto->ClaveUnidad->Value = 'ACT';
        $concepto->Descripcion->Value = 'Pago';
        $concepto->ValorUnitario->Value = 0;
        $concepto->ValorUnitario->Decimals = 0;
        $concepto->ValorUnitario->Dot = false;
        $concepto->Importe->Value = 0;
        $concepto->Importe->Decimals = 0;
        $concepto->Importe->Dot = false;
        //</editor-fold>
    }
}
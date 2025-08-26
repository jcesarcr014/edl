<?php

namespace Facturando\EDL\Example\Data\Complemento;

use Facturando\ElectronicDocumentLibrary\Base\Types\Complemento;
use Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\DocumentoRelacionado;
use Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Pago;
use Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Retencion;
use Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\RetencionDocumento;
use Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\TrasladoDocumento;
use Facturando\ElectronicDocumentLibrary\Document\Data\Concepto;
use Facturando\ElectronicDocumentLibrary\Document\ElectronicDocument;

final class ReciboPago20
{
    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosTimbrado($electronicDocument)
    {
        ReciboPago20::CargarDatosCfdi40($electronicDocument->Data);

        $electronicDocument->Data->Complementos->add(Complemento::RECEPCIONPAGO);

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Data $data */
        $data = $electronicDocument->Data->Complementos->last();

        $data->Version->Value = '2.0';

        $data->Totales->TotalTrasladosBaseIva16->Value = 1000;
        $data->Totales->TotalTrasladosImpuestoIva16->Value = 160;
        $data->Totales->MontoTotalPagos->Value = 1160;

        /** @var Pago $pago */
        $pago = $data->Pagos->add();
        $pago->FechaPago->Value = new \DateTime('NOW -5 hours');
        $pago->FormaPago->Value = '03';
        $pago->Monto->Value = 1160;
        $pago->Moneda->Value = 'MXN';
        $pago->TipoCambio->Value = 1;
        $pago->TipoCambio->Dot = false;
        $pago->TipoCambio->Decimals = 0;
        $pago->NumeroOperacion->Value = '2018081540014 TCT0000494330700';
        $pago->RfcEmisorCuentaOrdenante->Value = 'HMI950125KG8';
        $pago->NombreBancoOrdenanteExtrajero->Value = 'Santander';
        $pago->CuentaOrdenante->Value = '015680644065416681';
        $pago->RfcEmisorCuentaBeneficiario->Value = 'AAA010101AAA';
        $pago->CuentaBeneficiario->Value = '021190030551700271';
        $pago->TipoCadenaPago->Value = '01';
        $pago->CertificadoPago->Value = 'UjBsR09EbGhjZ0dTQUxNQUFBUUNBRU1tQ1p0dU1GUXhEUzhi';
        $pago->CadenaOriginalPago->Value = '||01|15082018|15082018|112654|40021|SANTANDER|ORDENANTE|40|015680644065416681|AAA010101AAA|HSBC|BENEFICIARIO|40|021190030551700271|AAA010101AAA|CONCEPTO|0.00|1000.00|00001000000307075918||';
        $pago->SelloPago->Value = 'UjBsR09EbGhjZ0dTQUxNQUFBUUNBRU1tQ1p0dU1GUXhEUzhi';

        /** @var DocumentoRelacionado $documento */
        $documento = $pago->DocumentosRelacionados->add();
        $documento->IdDocumento->Value = '222172B4-7330-4F3A-9E1A-B3FA29E20184';
        $documento->Serie->Value = 'F';
        $documento->Folio->Value = '1968';
        $documento->Moneda->Value = 'MXN';
        $documento->Equivalencia->Value = 1;
        $documento->Equivalencia->Dot = false;
        $documento->Equivalencia->Decimals = 0;
        $documento->NumeroParcialidad->Value = 1;
        $documento->ImporteSaldoAnterior->Value = 1160;
        $documento->ImportePagado->Value = 1160;
        $documento->ImporteSaldoInsoluto->Value = 0;
        $documento->ObjetoImpuesto->Value = '02';

        /** @var TrasladoDocumento $trasladoDocumento */
        $trasladoDocumento = $documento->Impuestos->Traslados->add();
        $trasladoDocumento->Base->Value = 1000;
        $trasladoDocumento->Impuesto->Value = '002';
        $trasladoDocumento->TipoFactor->Value = 'Tasa';
        $trasladoDocumento->TasaCuota->Value = 0.16;
        $trasladoDocumento->TasaCuota->Decimals = 6;
        $trasladoDocumento->Importe->Value = 160;

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Impuesto $impuesto */
        $impuesto = $pago->Impuestos->add();

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Traslado $traslado */
        $traslado = $impuesto->Traslados->add();
        $traslado->Base->Value = 1000;
        $traslado->Impuesto->Value = '002';
        $traslado->TipoFactor->Value = 'Tasa';
        $traslado->TasaCuota->Value = 0.16;
        $traslado->TasaCuota->Decimals = 6;;
        $traslado->Importe->Value = 160;
    }

    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosCompleto($electronicDocument)
    {
        ReciboPago20::CargarDatosCfdi40($electronicDocument->Data);

        $electronicDocument->Data->Complementos->add(Complemento::RECEPCIONPAGO);

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Data $data */
        $data = $electronicDocument->Data->Complementos->last();


        $data->Version->Value = '2.0';

        $data->Totales->TotalRetencionesIva->Value = 0;
        $data->Totales->TotalRetencionesIsr->Value = 0;
        $data->Totales->TotalRetencionesIeps->Value = 0;
        $data->Totales->TotalTrasladosBaseIva16->Value = 1000;
        $data->Totales->TotalTrasladosImpuestoIva16->Value = 160;
        $data->Totales->TotalTrasladosBaseIva8->Value = 0;
        $data->Totales->TotalTrasladosImpuestoIva8->Value = 0;
        $data->Totales->TotalTrasladosBaseIva0->Value = 0;
        $data->Totales->TotalTrasladosImpuestoIva0->Value = 0;
        $data->Totales->TotalTrasladosBaseIvaExento->Value = 0;
        $data->Totales->MontoTotalPagos->Value = 1160;

        /** @var Pago $pago */
        $pago = $data->Pagos->add();
        $pago->FechaPago->Value = new \DateTime('NOW -5 hours');
        $pago->FormaPago->Value = '01';
        $pago->Monto->Value = 1160;
        $pago->Moneda->Value = 'MXN';
        $pago->TipoCambio->Value = 1;
        $pago->NumeroOperacion->Value = '2018081540014 TCT0000494330700';
        $pago->RfcEmisorCuentaOrdenante->Value = 'AAA010101AAA';
        $pago->NombreBancoOrdenanteExtrajero->Value = 'Santander';
        $pago->CuentaOrdenante->Value = '015680644065416681';
        $pago->RfcEmisorCuentaBeneficiario->Value = 'AAA010101AAA';
        $pago->CuentaBeneficiario->Value = '021190030551700271';
        $pago->TipoCadenaPago->Value = '01';
        $pago->CertificadoPago->Value = 'UjBsR09EbGhjZ0dTQUxNQUFBUUNBRU1tQ1p0dU1GUXhEUzhi';
        $pago->CadenaOriginalPago->Value = '||01|15082018|15082018|112654|40021|SANTANDER|ORDENANTE|40|015680644065416681|AAA010101AAA|HSBC|BENEFICIARIO|40|021190030551700271|AAA010101AAA|CONCEPTO|0.00|1000.00|00001000000307075918||';
        $pago->SelloPago->Value = 'UjBsR09EbGhjZ0dTQUxNQUFBUUNBRU1tQ1p0dU1GUXhEUzhi';

        /** @var DocumentoRelacionado $documento */
        $documento = $pago->DocumentosRelacionados->add();
        $documento->IdDocumento->Value = '222172B4-7330-4F3A-9E1A-B3FA29E20184';
        $documento->Serie->Value = 'F';
        $documento->Folio->Value = '1968';
        $documento->Moneda->Value = 'MXN';
        $documento->Equivalencia->Value = 1;
        $documento->NumeroParcialidad->Value = 1;
        $documento->ImporteSaldoAnterior->Value = 1160;
        $documento->ImportePagado->Value = 1160;
        $documento->ImporteSaldoInsoluto->Value = 0;
        $documento->ObjetoImpuesto->Value = '01';

        /** @var RetencionDocumento $retencionDocumento */
        $retencionDocumento = $documento->Impuestos->Retenciones->add();
        $retencionDocumento->Base->Value = 1000;
        $retencionDocumento->Impuesto->Value = '001';
        $retencionDocumento->TipoFactor->Value = 'Tasa';
        $retencionDocumento->TasaCuota->Value = 0.160000;
        $retencionDocumento->Importe->Value = 160;

        /** @var TrasladoDocumento $trasladoDocumento */
        $trasladoDocumento = $documento->Impuestos->Traslados->add();
        $trasladoDocumento->Base->Value = 1000;
        $trasladoDocumento->Impuesto->Value = '001';
        $trasladoDocumento->TipoFactor->Value = 'Tasa';
        $trasladoDocumento->TasaCuota->Value = 0.160000;
        $trasladoDocumento->Importe->Value = 160;

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Impuesto $impuesto */
        $impuesto = $pago->Impuestos->add();

        /** @var Retencion $retencion */
        $retencion = $impuesto->Retenciones->add();
        $retencion->Importe->Value = 160;
        $retencion->Impuesto->Value = '001';

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Traslado $traslado */
        $traslado = $impuesto->Traslados->add();
        $traslado->Base->Value = 1000;
        $traslado->Impuesto->Value = '001';
        $traslado->TipoFactor->Value = 'Tasa';
        $traslado->TasaCuota->Value = 0.160000;
        $traslado->Importe->Value = 160;
    }

    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosListas($electronicDocument)
    {
        ReciboPago20::CargarDatosCfdi40($electronicDocument->Data);

        $electronicDocument->Data->Complementos->add(Complemento::RECEPCIONPAGO);

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Data $data */
        $data = $electronicDocument->Data->Complementos->last();

        $data->Version->Value = '2.0';

        $data->Totales->TotalRetencionesIva->Value = 0;
        $data->Totales->TotalRetencionesIsr->Value = 0;
        $data->Totales->TotalRetencionesIeps->Value = 0;
        $data->Totales->TotalTrasladosBaseIva16->Value = 1000;
        $data->Totales->TotalTrasladosImpuestoIva16->Value = 160;
        $data->Totales->TotalTrasladosBaseIva8->Value = 0;
        $data->Totales->TotalTrasladosImpuestoIva8->Value = 0;
        $data->Totales->TotalTrasladosBaseIva0->Value = 0;
        $data->Totales->TotalTrasladosImpuestoIva0->Value = 0;
        $data->Totales->TotalTrasladosBaseIvaExento->Value = 0;
        $data->Totales->MontoTotalPagos->Value = 1160;

        for ($i = 1; $i <= 2; $i++) {
            /** @var Pago $pago */
            $pago = $data->Pagos->add();
            $pago->FechaPago->Value = new \DateTime('NOW -5 hours');
            $pago->FormaPago->Value = '01';
            $pago->Monto->Value = 1160;
            $pago->Moneda->Value = 'MXN';
            $pago->TipoCambio->Value = 1;
            $pago->NumeroOperacion->Value = '2018081540014 TCT0000494330700';
            $pago->RfcEmisorCuentaOrdenante->Value = 'AAA010101AAA';
            $pago->NombreBancoOrdenanteExtrajero->Value = 'Santander';
            $pago->CuentaOrdenante->Value = '015680644065416681';
            $pago->RfcEmisorCuentaBeneficiario->Value = 'AAA010101AAA';
            $pago->CuentaBeneficiario->Value = '021190030551700271';
            $pago->TipoCadenaPago->Value = '01';
            $pago->CertificadoPago->Value = 'UjBsR09EbGhjZ0dTQUxNQUFBUUNBRU1tQ1p0dU1GUXhEUzhi';
            $pago->CadenaOriginalPago->Value = '||01|15082018|15082018|112654|40021|SANTANDER|ORDENANTE|40|015680644065416681|AAA010101AAA|HSBC|BENEFICIARIO|40|021190030551700271|AAA010101AAA|CONCEPTO|0.00|1000.00|00001000000307075918||';
            $pago->SelloPago->Value = 'UjBsR09EbGhjZ0dTQUxNQUFBUUNBRU1tQ1p0dU1GUXhEUzhi';

            for ($j = 1; $j <= 3; $j++) {
                /** @var DocumentoRelacionado $documento */
                $documento = $pago->DocumentosRelacionados->add();
                $documento->IdDocumento->Value = '222172B4-7330-4F3A-9E1A-B3FA29E20184';
                $documento->Serie->Value = 'F';
                $documento->Folio->Value = '1968';
                $documento->Moneda->Value = 'MXN';
                $documento->Equivalencia->Value = 1;
                $documento->NumeroParcialidad->Value = 1;
                $documento->ImporteSaldoAnterior->Value = 1160;
                $documento->ImportePagado->Value = 1160;
                $documento->ImporteSaldoInsoluto->Value = 0;
                $documento->ObjetoImpuesto->Value = '01';

                for ($k = 1; $k <= 4; $k++) {
                    /** @var RetencionDocumento $retencionDocumento */
                    $retencionDocumento = $documento->Impuestos->Retenciones->add();
                    $retencionDocumento->Base->Value = 1000;
                    $retencionDocumento->Impuesto->Value = '001';
                    $retencionDocumento->TipoFactor->Value = 'Tasa';
                    $retencionDocumento->TasaCuota->Value = 0.160000;
                    $retencionDocumento->Importe->Value = 160;
                }

                for ($k = 1; $k <= 5; $k++) {
                    /** @var TrasladoDocumento $trasladoDocumento */
                    $trasladoDocumento = $documento->Impuestos->Traslados->add();
                    $trasladoDocumento->Base->Value = 1000;
                    $trasladoDocumento->Impuesto->Value = '001';
                    $trasladoDocumento->TipoFactor->Value = 'Tasa';
                    $trasladoDocumento->TasaCuota->Value = 0.160000;
                    $trasladoDocumento->Importe->Value = 160;
                }
            }

            /** @var \Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Impuesto $impuesto */
            $impuesto = $pago->Impuestos->add();

            for ($k = 1; $k <= 6; $k++) {
                /** @var Retencion $retencion */
                $retencion = $impuesto->Retenciones->add();
                $retencion->Importe->Value = 160;
                $retencion->Impuesto->Value = '001';
            }

            for ($k = 1; $k <= 7; $k++) {
                /** @var \Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Traslado $traslado */
                $traslado = $impuesto->Traslados->add();
                $traslado->Base->Value = 1000;
                $traslado->Impuesto->Value = '001';
                $traslado->TipoFactor->Value = 'Tasa';
                $traslado->TasaCuota->Value = 0.160000;
                $traslado->Importe->Value = 160;
            }
        }
    }

    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosMinimo($electronicDocument)
    {
        ReciboPago20::CargarDatosCfdi40($electronicDocument->Data);

        $electronicDocument->Data->Complementos->add(Complemento::RECEPCIONPAGO);

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\RecepcionPago\Data $data */
        $data = $electronicDocument->Data->Complementos->last();

        $data->Version->Value = '2.0';

        $data->Totales->TotalTrasladosBaseIva16->Value = 1000;
        $data->Totales->TotalTrasladosImpuestoIva16->Value = 160;
        $data->Totales->MontoTotalPagos->Value = 1160;

        /** @var Pago $pago */
        $pago = $data->Pagos->add();
        $pago->FechaPago->Value = new \DateTime('NOW -5 hours');
        $pago->FormaPago->Value = '01';
        $pago->Moneda->Value = 'MXN';
        $pago->Monto->Value = 1160;

        /** @var DocumentoRelacionado $documento */
        $documento = $pago->DocumentosRelacionados->add();
        $documento->IdDocumento->Value = '222172B4-7330-4F3A-9E1A-B3FA29E20184';
        $documento->Moneda->Value = 'MXN';
        $documento->NumeroParcialidad->Value = 1;
        $documento->ImporteSaldoAnterior->Value = 1160;
        $documento->ImportePagado->Value = 1160;
        $documento->ImporteSaldoInsoluto->Value = 0;
        $documento->ObjetoImpuesto->Value = '01';
    }

    /**
     * @param \Facturando\ElectronicDocumentLibrary\Document\Data\Data $data
     */
    private static function CargarDatosCfdi40($data)
    {
        $data->clear();

        //<editor-fold desc="Datos del comprobante">
        $data->Version->Value = '4.0';
        $data->Serie->Value = 'CFDIPAGO';
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
        $data->Exportacion->Value = '01';
        $data->LugarExpedicion->Value = '06850';
        //</editor-fold>

        Base::Emisor($data->Emisor);

        //<editor-fold desc="Datos del Receptor">
        Base::Receptor($data->Receptor);

        $data->Receptor->UsoCfdi->Value = 'CP01';
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
        $concepto->ObjetoImpuesto->Value = '01';
        //</editor-fold>
    }
}
<?php

namespace Facturando\EDL\Example\Data\Retenciones;

use Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\ConstanciaRetenciones;
use Facturando\ElectronicDocumentLibrary\ConstanciaRetenciones\Data\ImpuestoRetenido;

final class Constancia10
{
    // Este ejemplo se muestra el llena de los datos requeridos para timbrar una Constancia de retenciones 1.0
    /**
     * @param ConstanciaRetenciones $constanciaRetenciones
     */
    public static function CargarDatosTimbrado($constanciaRetenciones)
    {
        $constanciaRetenciones->Data->clear();

        //<editor-fold desc="Datos del comprobante">
        $constanciaRetenciones->Data->Version->Value = '1.0';
        $constanciaRetenciones->Data->Folio->Value = '1';
        $constanciaRetenciones->Data->FechaExpedicion->Value = new \DateTime('NOW -5 hours');
        $constanciaRetenciones->Data->Clave->Value = '12';
        $constanciaRetenciones->Data->Descripcion->Value = 'Servicios profesionales';
        //</editor-fold>

        //<editor-fold desc="Datos del emisor">
        $constanciaRetenciones->Data->Emisor->Rfc->Value = 'EKU9003173C9';
        $constanciaRetenciones->Data->Emisor->RazonSocial->Value = 'ESCUELA KEMPER URGATE SA DE CV';
        //constanciaRetenciones->Data->Emisor->Curp->Value = 'UXBA000419HYNVRDA3';
        //</editor-fold>

        //<editor-fold desc="Datos del Receptor">
        $constanciaRetenciones->Data->Receptor->Nacionalidad->Value = 'Nacional';

        // Receptor - Nacional
        $constanciaRetenciones->Data->Receptor->Nacional->Rfc->Value = 'AAAA010101AA1';
        $constanciaRetenciones->Data->Receptor->Nacional->RazonSocial->Value = 'Receptor nacional de prueba';
        //constanciaRetenciones->Data->Receptor->Nacional->Curp->Value = 'UXBA000419HYNVRDA3';

        // Receptor - Extranjero
        //constanciaRetenciones->Data->Receptor->Extranjero->NumeroIdentificacionFiscal->Value = '1';
        //constanciaRetenciones->Data->Receptor->Extranjero->RazonSocial->Value = 'Receptor extranjero de prueba';
        //</editor-fold>

        //<editor-fold desc="Periodo">
        $constanciaRetenciones->Data->Periodo->MesInicial->Value = 1;
        $constanciaRetenciones->Data->Periodo->MesFinal->Value = 12;
        $constanciaRetenciones->Data->Periodo->Ejercicio->Value = 2014;
        //</editor-fold>

        //<editor-fold desc="Totales">
        $constanciaRetenciones->Data->Totales->MontoOperacion->Value = 1000.00;
        $constanciaRetenciones->Data->Totales->MontoGravado->Value = 1000.00;
        $constanciaRetenciones->Data->Totales->MontoExento->Value = 0.00;
        $constanciaRetenciones->Data->Totales->MontoRetenido->Value = 160.00;

        // Impuesto retenido 1
        /** @var ImpuestoRetenido $impuestoRetenido */
        $impuestoRetenido = $constanciaRetenciones->Data->Totales->ImpuestosRetenidos->add();
        $impuestoRetenido->Base->Value = 16;
        $impuestoRetenido->Impuesto->Value = '01';
        $impuestoRetenido->MontoRetenido->Value = 160.00;
        $impuestoRetenido->TipoPago->Value = 'Pago provisional';

        // Impuesto retenido 2
        $impuestoRetenido = $constanciaRetenciones->Data->Totales->ImpuestosRetenidos->add();
        $impuestoRetenido->Base->Value = 16;
        $impuestoRetenido->Impuesto->Value = '02';
        $impuestoRetenido->MontoRetenido->Value = 160.00;
        $impuestoRetenido->TipoPago->Value = 'Pago provisional';

        // Impuesto retenido 3
        $impuestoRetenido = $constanciaRetenciones->Data->Totales->ImpuestosRetenidos->add();
        $impuestoRetenido->Base->Value = 16;
        $impuestoRetenido->Impuesto->Value = '03';
        $impuestoRetenido->MontoRetenido->Value = 160.00;
        $impuestoRetenido->TipoPago->Value = 'Pago provisional';
        //</editor-fold>
    }

    // Este ejemplo muestra como usar todas las clases y propiedades para una Constancia de retenciones 1.0
    /**
     * @param ConstanciaRetenciones $constanciaRetenciones
     */
    public static function CargarDatosCompleto($constanciaRetenciones)
    {
        $constanciaRetenciones->Data->clear();

        //<editor-fold desc="Datos del comprobante">
        $constanciaRetenciones->Data->Version->Value = '1.0';
        $constanciaRetenciones->Data->Folio->Value = '1';
        $constanciaRetenciones->Data->FechaExpedicion->Value = new \DateTime('NOW -5 hours');
        $constanciaRetenciones->Data->Clave->Value = '12';
        $constanciaRetenciones->Data->Descripcion->Value = 'Servicios profesionales';
        //</editor-fold>

        //<editor-fold desc="Datos del emisor">
        $constanciaRetenciones->Data->Emisor->Rfc->Value = 'EKU9003173C9';
        $constanciaRetenciones->Data->Emisor->RazonSocial->Value = 'ESCUELA KEMPER URGATE SA DE CV';
        $constanciaRetenciones->Data->Emisor->Curp->Value = 'OERR880127HDFTZB00';
        //</editor-fold>

        //<editor-fold desc="Datos del Receptor">
        $constanciaRetenciones->Data->Receptor->Nacionalidad->Value = 'Nacional';

        // Receptor - Nacional
        $constanciaRetenciones->Data->Receptor->Nacional->Rfc->Value = 'AAAA010101AAA';
        $constanciaRetenciones->Data->Receptor->Nacional->RazonSocial->Value = 'Receptor nacional de prueba';
        $constanciaRetenciones->Data->Receptor->Nacional->Curp->Value = 'UXBA000419HYNVRDA3';

        // Receptor - Extranjero
        //constanciaRetenciones->Data->Receptor->Extranjero->NumeroIdentificacionFiscal->Value = '1';
        //constanciaRetenciones->Data->Receptor->Extranjero->RazonSocial->Value = 'Receptor extranjero de prueba';
        //</editor-fold>

        //<editor-fold desc="Periodo">
        $constanciaRetenciones->Data->Periodo->MesInicial->Value = 1;
        $constanciaRetenciones->Data->Periodo->MesFinal->Value = 12;
        $constanciaRetenciones->Data->Periodo->Ejercicio->Value = 2014;
        //</editor-fold>

        //<editor-fold desc="Totales">
        $constanciaRetenciones->Data->Totales->MontoOperacion->Value = 1000.00;
        $constanciaRetenciones->Data->Totales->MontoGravado->Value = 1000.00;
        $constanciaRetenciones->Data->Totales->MontoExento->Value = 0.00;
        $constanciaRetenciones->Data->Totales->MontoRetenido->Value = 160.00;

        // Impuesto retenido 1
        /** @var ImpuestoRetenido $impuestoRetenido */
        $impuestoRetenido = $constanciaRetenciones->Data->Totales->ImpuestosRetenidos->add();
        $impuestoRetenido->Base->Value = 16;
        $impuestoRetenido->Impuesto->Value = '01';
        $impuestoRetenido->MontoRetenido->Value = 160.00;
        $impuestoRetenido->TipoPago->Value = 'Pago provisional';
        //</editor-fold>
    }

    /**
     * @param ConstanciaRetenciones $constanciaRetenciones
     */
    public static function CargarDatosListas($constanciaRetenciones)
    {
        $constanciaRetenciones->Data->clear();

        //<editor-fold desc="Datos del comprobante">
        $constanciaRetenciones->Data->Version->Value = '1.0';
        $constanciaRetenciones->Data->Folio->Value = '1';
        $constanciaRetenciones->Data->FechaExpedicion->Value = new \DateTime('NOW -5 hours');
        $constanciaRetenciones->Data->Clave->Value = '12';
        $constanciaRetenciones->Data->Descripcion->Value = 'Servicios profesionales';
        //</editor-fold>

        //<editor-fold desc="Datos del emisor">
        $constanciaRetenciones->Data->Emisor->Rfc->Value = 'EKU9003173C9';
        $constanciaRetenciones->Data->Emisor->RazonSocial->Value = 'ESCUELA KEMPER URGATE SA DE CV';
        $constanciaRetenciones->Data->Emisor->Curp->Value = 'OERR880127HDFTZB00';
        //</editor-fold>

        //<editor-fold desc="Datos del Receptor">
        $constanciaRetenciones->Data->Receptor->Nacionalidad->Value = 'Nacional';

        // Receptor - Nacional
        $constanciaRetenciones->Data->Receptor->Nacional->Rfc->Value = 'AAAA010101AA1';
        $constanciaRetenciones->Data->Receptor->Nacional->RazonSocial->Value = 'Receptor nacional de prueba';
        $constanciaRetenciones->Data->Receptor->Nacional->Curp->Value = 'UXBA000419HYNVRDA3';

        // Receptor - Extranjero
        //constanciaRetenciones->Data->Receptor->Extranjero->NumeroIdentificacionFiscal->Value = '1';
        //constanciaRetenciones->Data->Receptor->Extranjero->RazonSocial->Value = 'Receptor extranjero de prueba';
        //</editor-fold>

        //<editor-fold desc="Periodo">
        $constanciaRetenciones->Data->Periodo->MesInicial->Value = 1;
        $constanciaRetenciones->Data->Periodo->MesFinal->Value = 12;
        $constanciaRetenciones->Data->Periodo->Ejercicio->Value = 2014;
        //</editor-fold>

        //<editor-fold desc="Totales">
        $constanciaRetenciones->Data->Totales->MontoOperacion->Value = 1000.00;
        $constanciaRetenciones->Data->Totales->MontoGravado->Value = 1000.00;
        $constanciaRetenciones->Data->Totales->MontoExento->Value = 0.00;
        $constanciaRetenciones->Data->Totales->MontoRetenido->Value = 160.00;

        for ($i = 1; $i <= 2; $i++) {
            // Impuesto retenido 1
            /** @var ImpuestoRetenido $impuestoRetenido */
            $impuestoRetenido = $constanciaRetenciones->Data->Totales->ImpuestosRetenidos->add();
            $impuestoRetenido->Base->Value = 16;
            $impuestoRetenido->Impuesto->Value = '01';
            $impuestoRetenido->MontoRetenido->Value = 160.00;
            $impuestoRetenido->TipoPago->Value = 'Pago provisional';
        }
        //</editor-fold>
    }

    /**
     * @param ConstanciaRetenciones $constanciaRetenciones
     */
    public static function CargarDatosMinimo($constanciaRetenciones)
    {
       $constanciaRetenciones->Data->clear();

        //<editor-fold desc="Datos del comprobante">
        $constanciaRetenciones->Data->Version->Value = '1.0';
        $constanciaRetenciones->Data->FechaExpedicion->Value = new \DateTime('NOW');
        $constanciaRetenciones->Data->Clave->Value = '12';
        //</editor-fold>

        //<editor-fold desc="Datos del emisor">
        $constanciaRetenciones->Data->Emisor->Rfc->Value = 'EKU9003173C9';
        //</editor-fold>

        //<editor-fold desc="Datos del Receptor">
        $constanciaRetenciones->Data->Receptor->Nacionalidad->Value = 'Nacional';

        // Receptor - Nacional
        $constanciaRetenciones->Data->Receptor->Nacional->Rfc->Value = 'AAAA010101AA1';
        //</editor-fold>

        //<editor-fold desc="Periodo">
        $constanciaRetenciones->Data->Periodo->MesInicial->Value = 1;
        $constanciaRetenciones->Data->Periodo->MesFinal->Value = 12;
        $constanciaRetenciones->Data->Periodo->Ejercicio->Value = 2014;
        //</editor-fold>

        //<editor-fold desc="Totales">
        $constanciaRetenciones->Data->Totales->MontoOperacion->Value = 1000.00;
        $constanciaRetenciones->Data->Totales->MontoGravado->Value = 1000.00;
        $constanciaRetenciones->Data->Totales->MontoExento->Value = 0.00;
        $constanciaRetenciones->Data->Totales->MontoRetenido->Value = 160.00;
        //</editor-fold>
    }
}

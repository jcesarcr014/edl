<?php

namespace Facturando\EDL\Example\Data\Complemento;

use DateInterval;
use Facturando\ElectronicDocumentLibrary\Base\Types\Complemento;
use Facturando\ElectronicDocumentLibrary\Complemento\Nomina\Deduccion;
use Facturando\ElectronicDocumentLibrary\Complemento\Nomina\HorasExtra;
use Facturando\ElectronicDocumentLibrary\Complemento\Nomina\Incapacidad;
use Facturando\ElectronicDocumentLibrary\Complemento\Nomina\OtroPago;
use Facturando\ElectronicDocumentLibrary\Complemento\Nomina\Percepcion;
use Facturando\ElectronicDocumentLibrary\Complemento\Nomina\SubContratacion;
use Facturando\ElectronicDocumentLibrary\Document\Data\Concepto;
use Facturando\ElectronicDocumentLibrary\Document\ElectronicDocument;

final class Nomina12
{
    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosCfdi40Timbrado($electronicDocument)
    {
        Nomina12::CargarDatosCfdi40($electronicDocument->Data);

        $electronicDocument->Data->Complementos->add(Complemento::NOMINA);

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\Nomina\Data $data */
        $data = $electronicDocument->Data->Complementos->last();

        $data->Version->Value = '1.2';
        $data->TipoNomina->Value = 'O';
        $data->FechaPago->Value = new \DateTime('NOW -5 hours');
        $data->FechaInicialPago->Value = new \DateTime('NOW -5 hours');
        $data->FechaFinalPago->Value = (new \DateTime('NOW'))->add(new DateInterval('P15D'));
        $data->NumeroDiasPagados->Value = 30;
        $data->NumeroDiasPagados->Decimals = 3;
        $data->TotalPercepciones->Value = 9100.00;
        $data->TotalDeducciones->Value = 250;
        $data->TotalOtrosPagos->Value = 200;

        //<editor-fold desc="EMISOR">
        $data->Emisor->RegistroPatronal->Value = '12345678901';
        //$data->Emisor->Curp->Value = 'OERR880127HDFTZB00';
        //</editor-fold>

        //<editor-fold desc="RECEPTOR">
        $data->Receptor->Curp->Value = 'OERR880127HDFTZB00';
        $data->Receptor->NumeroSeguridadSocial->Value = '12345678901';
        $data->Receptor->FechaInicioRelacionLaboral->Value = new \DateTime('NOW -5 hours');
        $data->Receptor->Antiguedad->Value = 'P2W';
        $data->Receptor->TipoContrato->Value = '01';
        $data->Receptor->Sindicalizado->Value = 'Sí';
        $data->Receptor->TipoJornada->Value = '03';
        $data->Receptor->TipoRegimen->Value = '02';
        $data->Receptor->NumeroEmpleado->Value = '1968';
        $data->Receptor->Departamento->Value = 'Contabilidad';
        $data->Receptor->Puesto->Value = 'Auxiliar Contable';
        $data->Receptor->RiesgoPuesto->Value = '1';
        $data->Receptor->PeriodicidadPago->Value = '05';
        $data->Receptor->Banco->Value = '021';
        $data->Receptor->CuentaBancaria->Value = '2147483640';
        $data->Receptor->SalarioBaseCotApor->Value = 300;
        $data->Receptor->SalarioDiarioIntegrado->Value = 335;
        $data->Receptor->ClaveEntidadFederativa->Value = 'AGU';
        //</editor-fold>

        //<editor-fold desc="PERCEPCIONES">
        $data->Percepciones->TotalSueldos->Value = 9100;
        $data->Percepciones->TotalGravado->Value = 9100;
        $data->Percepciones->TotalExento->Value = 0;

        /** @var Percepcion $percepcion */
        $percepcion = $data->Percepciones->add();
        $percepcion->Clave->Value = '100';
        $percepcion->Concepto->Value = 'Sueldo';
        $percepcion->Tipo->Value = '001';
        $percepcion->ImporteExento->Value = 0;
        $percepcion->ImporteGravado->Value = 9000;

        $percepcion = $data->Percepciones->add();
        $percepcion->Clave->Value = '110';
        $percepcion->Concepto->Value = 'Puntualidad';
        $percepcion->Tipo->Value = '010';
        $percepcion->ImporteExento->Value = 0;
        $percepcion->ImporteGravado->Value = 100;
        //</editor-fold>

        //<editor-fold desc="DEDUCCIONES">
        $data->Deducciones->TotalOtrasDeducciones->Value = 100.00;
        $data->Deducciones->TotalImpuestosRetenidos->Value = 150.00;

        /** @var Deduccion $deduccion */
        $deduccion = $data->Deducciones->add();
        $deduccion->Clave->Value = '201';
        $deduccion->Tipo->Value = '001';
        $deduccion->Concepto->Value = 'IMSS';
        $deduccion->Importe->Value = 100.00;

        $deduccion = $data->Deducciones->add();
        $deduccion->Clave->Value = '202';
        $deduccion->Tipo->Value = '002';
        $deduccion->Concepto->Value = 'ISR';
        $deduccion->Importe->Value = 150.00;
        //</editor-fold>

        //<editor-fold desc="OTROS PAGOS">
        /** @var OtroPago $otroPago */
        $otroPago = $data->OtrosPagos->add();
        $otroPago->Clave->Value = '300';
        $otroPago->Concepto->Value = 'Viáticos';
        $otroPago->Importe->Value = 100;
        $otroPago->Tipo->Value = '003';

        $otroPago = $data->OtrosPagos->add();
        $otroPago->Clave->Value = '300';
        $otroPago->Concepto->Value = 'Viáticos';
        $otroPago->Importe->Value = 100;
        $otroPago->Tipo->Value = '003';

        $otroPago = $data->OtrosPagos->add();
        $otroPago->Clave->Value = '302';
        $otroPago->Concepto->Value = 'Subsidio';
        $otroPago->Importe->Value = 0;
        $otroPago->Tipo->Value = '002';

        $otroPago->SubsidioAlEmpleo->SubsidioCausado->Value = 0;
        //</editor-fold>

        //<editor-fold desc="INCAPACIDADES">
        /** @var Incapacidad $incapacidad */
        $incapacidad = $data->Incapacidades->add();
        $incapacidad->TipoNomina->Value = '03';
        $incapacidad->DiasIncapacidad->Value = 1;
        $incapacidad->ImporteMonetario->Value = 300;

        $incapacidad = $data->Incapacidades->add();
        $incapacidad->TipoNomina->Value = '03';
        $incapacidad->DiasIncapacidad->Value = 1;
        $incapacidad->ImporteMonetario->Value = 300;
        //</editor-fold>
    }

    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosCfdi33Timbrado($electronicDocument)
    {
        Nomina12::CargarDatosCfdi33($electronicDocument->Data);

        $electronicDocument->Data->Complementos->add(Complemento::NOMINA);

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\Nomina\Data $data */
        $data = $electronicDocument->Data->Complementos->last();

        $data->Version->Value = '1.2';
        $data->TipoNomina->Value = 'O';
        $data->FechaPago->Value = new \DateTime('NOW -5 hours');
        $data->FechaInicialPago->Value = new \DateTime('NOW -5 hours');
        $data->FechaFinalPago->Value = (new \DateTime('NOW'))->add(new DateInterval('P15D'));
        $data->NumeroDiasPagados->Value = 30;
        $data->NumeroDiasPagados->Decimals = 3;
        $data->TotalPercepciones->Value = 9100.00;
        $data->TotalDeducciones->Value = 250;
        $data->TotalOtrosPagos->Value = 200;

        //<editor-fold desc="EMISOR">
        $data->Emisor->RegistroPatronal->Value = '12345678901';
        //$data->Emisor->Curp->Value = 'UXBA000419HYNVRDA3';
        //</editor-fold>

        //<editor-fold desc="RECEPTOR">
        $data->Receptor->Curp->Value = 'OERR880127HDFTZB00';
        $data->Receptor->NumeroSeguridadSocial->Value = '12345678901';
        $data->Receptor->FechaInicioRelacionLaboral->Value = new \DateTime('NOW -5 hours');
        $data->Receptor->Antiguedad->Value = 'P2W';
        $data->Receptor->TipoContrato->Value = '01';
        $data->Receptor->Sindicalizado->Value = 'Sí';
        $data->Receptor->TipoJornada->Value = '03';
        $data->Receptor->TipoRegimen->Value = '02';
        $data->Receptor->NumeroEmpleado->Value = '1968';
        $data->Receptor->Departamento->Value = 'Contabilidad';
        $data->Receptor->Puesto->Value = 'Auxiliar Contable';
        $data->Receptor->RiesgoPuesto->Value = '1';
        $data->Receptor->PeriodicidadPago->Value = '05';
        $data->Receptor->Banco->Value = '021';
        $data->Receptor->CuentaBancaria->Value = '2147483640';
        $data->Receptor->SalarioBaseCotApor->Value = 300;
        $data->Receptor->SalarioDiarioIntegrado->Value = 335;
        $data->Receptor->ClaveEntidadFederativa->Value = 'DIF';

        /** @var SubContratacion $subContratacion */
        $subContratacion = $data->Receptor->SubContrataciones->add();
        $subContratacion->RfcLabora->Value = 'AAAD770905441';
        $subContratacion->PorcentajeTiempo->Value = 100.000;
        //</editor-fold>

        //<editor-fold desc="PERCEPCIONES">
        $data->Percepciones->TotalSueldos->Value = 9100;
        $data->Percepciones->TotalGravado->Value = 9100;
        $data->Percepciones->TotalExento->Value = 0;

        /** @var Percepcion $percepcion */
        $percepcion = $data->Percepciones->add();
        $percepcion->Clave->Value = '100';
        $percepcion->Concepto->Value = 'Sueldo';
        $percepcion->Tipo->Value = '001';
        $percepcion->ImporteExento->Value = 0;
        $percepcion->ImporteGravado->Value = 9000;

        $percepcion = $data->Percepciones->add();
        $percepcion->Clave->Value = '110';
        $percepcion->Concepto->Value = 'Puntualidad';
        $percepcion->Tipo->Value = '010';
        $percepcion->ImporteExento->Value = 0;
        $percepcion->ImporteGravado->Value = 100;
        //</editor-fold>

        //<editor-fold desc="DEDUCCIONES">
        $data->Deducciones->TotalOtrasDeducciones->Value = 100.00;
        $data->Deducciones->TotalImpuestosRetenidos->Value = 150.00;

        /** @var Deduccion $deduccion */
        $deduccion = $data->Deducciones->add();
        $deduccion->Clave->Value = '201';
        $deduccion->Tipo->Value = '001';
        $deduccion->Concepto->Value = 'IMSS';
        $deduccion->Importe->Value = 100.00;

        $deduccion = $data->Deducciones->add();
        $deduccion->Clave->Value = '202';
        $deduccion->Tipo->Value = '002';
        $deduccion->Concepto->Value = 'ISR';
        $deduccion->Importe->Value = 150.00;
        //</editor-fold>

        //<editor-fold desc="OTROS PAGOS">
        /** @var OtroPago $otroPago */
        $otroPago = $data->OtrosPagos->add();
        $otroPago->Clave->Value = '300';
        $otroPago->Concepto->Value = 'Viáticos';
        $otroPago->Importe->Value = 100;
        $otroPago->Tipo->Value = '003';

        $otroPago = $data->OtrosPagos->add();
        $otroPago->Clave->Value = '300';
        $otroPago->Concepto->Value = 'Viáticos';
        $otroPago->Importe->Value = 100;
        $otroPago->Tipo->Value = '003';

        $otroPago = $data->OtrosPagos->add();
        $otroPago->Clave->Value = '302';
        $otroPago->Concepto->Value = 'Subsidio';
        $otroPago->Importe->Value = 0;
        $otroPago->Tipo->Value = '002';

        $otroPago->SubsidioAlEmpleo->SubsidioCausado->Value = 0;
        //</editor-fold>

        //<editor-fold desc="INCAPACIDADES">
        /** @var Incapacidad $incapacidad */
        $incapacidad = $data->Incapacidades->add();
        $incapacidad->TipoNomina->Value = '03';
        $incapacidad->DiasIncapacidad->Value = 1;
        $incapacidad->ImporteMonetario->Value = 300;

        $incapacidad = $data->Incapacidades->add();
        $incapacidad->TipoNomina->Value = '03';
        $incapacidad->DiasIncapacidad->Value = 1;
        $incapacidad->ImporteMonetario->Value = 300;
        //</editor-fold>
    }

    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosCompleto($electronicDocument)
    {
        Nomina12::CargarDatosCfdi40($electronicDocument->Data);

        $electronicDocument->Data->Complementos->add(Complemento::NOMINA);

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\Nomina\Data $data */
        $data = $electronicDocument->Data->Complementos->last();

        $data->Version->Value = '1.2';
        $data->TipoNomina->Value = 'O';
        $data->FechaPago->Value = new \DateTime('NOW -5 hours');
        $data->FechaInicialPago->Value = new \DateTime('NOW -5 hours');
        $data->FechaFinalPago->Value = new \DateTime('NOW -5 hours');
        $data->NumeroDiasPagados->Value = 30;
        $data->NumeroDiasPagados->Decimals = 3;
        $data->TotalPercepciones->Value = 9100.00;
        $data->TotalDeducciones->Value = 250;
        $data->TotalOtrosPagos->Value = 200;

        //<editor-fold desc="EMISOR">
        $data->Emisor->Curp->Value = 'OERR880127HDFTZB00';
        $data->Emisor->RegistroPatronal->Value = '12345678901';
        $data->Emisor->RfcPatronOrigen->Value = 'AAA010101AAA';

        $data->Emisor->EntidadSncf->OrigenRecurso->Value = 'IP';
        $data->Emisor->EntidadSncf->MontoRecursoPropio->Value = 1;
        //</editor-fold>

        //<editor-fold desc="RECEPTOR">
        $data->Receptor->Curp->Value = 'OERR880127HDFTZB00';
        $data->Receptor->NumeroSeguridadSocial->Value = '12345678901';
        $data->Receptor->FechaInicioRelacionLaboral->Value = new \DateTime('NOW -5 hours');
        $data->Receptor->Antiguedad->Value = 'P2W';
        $data->Receptor->TipoContrato->Value = '01';
        $data->Receptor->Sindicalizado->Value = 'Sí';
        $data->Receptor->TipoJornada->Value = '03';
        $data->Receptor->TipoRegimen->Value = '02';
        $data->Receptor->NumeroEmpleado->Value = '1968';
        $data->Receptor->Departamento->Value = 'Contabilidad';
        $data->Receptor->Puesto->Value = 'Auxiliar Contable';
        $data->Receptor->RiesgoPuesto->Value = '1';
        $data->Receptor->PeriodicidadPago->Value = '05';
        $data->Receptor->Banco->Value = '021';
        $data->Receptor->CuentaBancaria->Value = '2147483640';
        $data->Receptor->SalarioBaseCotApor->Value = 300;
        $data->Receptor->SalarioDiarioIntegrado->Value = 335;
        $data->Receptor->ClaveEntidadFederativa->Value = 'AGU';

        /** @var SubContratacion $subcontratacion */
        $subcontratacion = $data->Receptor->SubContrataciones->add();
        $subcontratacion->RfcLabora->Value = 'AAA010101AAA';
        $subcontratacion->PorcentajeTiempo->Value = 100;
        //</editor-fold>

        //<editor-fold desc="PERCEPCIONES">
        $data->Percepciones->TotalSueldos->Value = 9100;
        $data->Percepciones->TotalSeparacionIndemnizacion->Value = 100;
        $data->Percepciones->TotalJubilacionPensionRetiro->Value = 100;
        $data->Percepciones->TotalGravado->Value = 9100;
        $data->Percepciones->TotalExento->Value = 0;

        /** @var Percepcion $percepcion */
        $percepcion = $data->Percepciones->add();
        $percepcion->Tipo->Value = '001';
        $percepcion->Clave->Value = '100';
        $percepcion->Concepto->Value = 'Sueldo';
        $percepcion->ImporteGravado->Value = 9000;
        $percepcion->ImporteExento->Value = 0;

        $percepcion->AccionesTitulos->ValorMercado->Value = 0.000001;
        $percepcion->AccionesTitulos->PrecioAlOtorgarse->Value = 0.000001;

        /** @var HorasExtra $horasExtra */
        $horasExtra = $percepcion->HorasExtras->add();
        $horasExtra->Dias->Value = 1;
        $horasExtra->Tipo->Value = '03';
        $horasExtra->Horas->Value = 3;
        $horasExtra->ImportePagado->Value = 112.50;

        $data->Percepciones->JubilacionPensionRetiro->TotalUnaExhibicion->Value = 50;
        $data->Percepciones->JubilacionPensionRetiro->TotalParcialidad->Value = 50;
        $data->Percepciones->JubilacionPensionRetiro->MontoDiario->Value = 20;
        $data->Percepciones->JubilacionPensionRetiro->IngresoAcumulable->Value = 50;
        $data->Percepciones->JubilacionPensionRetiro->IngresoNoAcumulable->Value = 100;

        $data->Percepciones->SeparacionIndemnizacion->TotalPagado->Value = 300;
        $data->Percepciones->SeparacionIndemnizacion->NumeroAniosServicio->Value = 1;
        $data->Percepciones->SeparacionIndemnizacion->UltimoSueldoMensualOrdinario->Value = 300;
        $data->Percepciones->SeparacionIndemnizacion->IngresoAcumulable->Value = 50;
        $data->Percepciones->SeparacionIndemnizacion->IngresoNoAcumulable->Value = 100;
        //</editor-fold>

        //<editor-fold desc="DEDUCCIONES">
        $data->Deducciones->TotalOtrasDeducciones->Value = 100.00;
        $data->Deducciones->TotalImpuestosRetenidos->Value = 150.00;

        /** @var Deduccion $deduccion */
        $deduccion = $data->Deducciones->add();
        $deduccion->Tipo->Value = '001';
        $deduccion->Clave->Value = '201';
        $deduccion->Concepto->Value = 'IMSS';
        $deduccion->Importe->Value = 100.00;
        //</editor-fold>

        //<editor-fold desc="OTROS PAGOS">
        /** @var OtroPago $otroPago */
        $otroPago = $data->OtrosPagos->add();
        $otroPago->Tipo->Value = '003';
        $otroPago->Clave->Value = '300';
        $otroPago->Concepto->Value = 'Viáticos';
        $otroPago->Importe->Value = 100;

        $otroPago->SubsidioAlEmpleo->SubsidioCausado->Value = 0;

        $otroPago->CompensacionSaldosAFavor->SaldoAFavor->Value = 32;
        $otroPago->CompensacionSaldosAFavor->Anio->Value = 2016;
        $otroPago->CompensacionSaldosAFavor->RemanenteSaldoFavor->Value = 31;
        //</editor-fold>

        //<editor-fold desc="INCAPACIDADES">
        /** @var Incapacidad $incapacidad */
        $incapacidad = $data->Incapacidades->add();
        $incapacidad->DiasIncapacidad->Value = 1;
        $incapacidad->TipoNomina->Value = '03';
        $incapacidad->ImporteMonetario->Value = 300;
        //</editor-fold>
    }

    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosListas($electronicDocument)
    {
        Nomina12::CargarDatosCfdi40($electronicDocument->Data);

        $electronicDocument->Data->Complementos->add(Complemento::NOMINA);

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\Nomina\Data $data */
        $data = $electronicDocument->Data->Complementos->last();

        $data->Version->Value = '1.2';
        $data->TipoNomina->Value = 'O';
        $data->FechaPago->Value = new \DateTime('NOW -5 hours');
        $data->FechaInicialPago->Value = new \DateTime('NOW -5 hours');
        $data->FechaFinalPago->Value = new \DateTime('NOW -5 hours');
        $data->NumeroDiasPagados->Value = 30;
        $data->NumeroDiasPagados->Decimals = 3;
        $data->TotalPercepciones->Value = 9100.00;
        $data->TotalDeducciones->Value = 250;
        $data->TotalOtrosPagos->Value = 200;

        //<editor-fold desc="EMISOR">
        $data->Emisor->Curp->Value = 'OERR880127HDFTZB00';
        $data->Emisor->RegistroPatronal->Value = '12345678901';
        $data->Emisor->RfcPatronOrigen->Value = 'AAA010101AAA';
        $data->Emisor->EntidadSncf->OrigenRecurso->Value = 'IP';
        $data->Emisor->EntidadSncf->MontoRecursoPropio->Value = 1;
        //</editor-fold>

        //<editor-fold desc="RECEPTOR">
        $data->Receptor->Curp->Value = 'OERR880127HDFTZB00';
        $data->Receptor->NumeroSeguridadSocial->Value = '12345678901';
        $data->Receptor->FechaInicioRelacionLaboral->Value = new \DateTime('NOW -5 hours');
        $data->Receptor->Antiguedad->Value = 'P2W';
        $data->Receptor->TipoContrato->Value = '01';
        $data->Receptor->Sindicalizado->Value = 'Sí';
        $data->Receptor->TipoJornada->Value = '03';
        $data->Receptor->TipoRegimen->Value = '02';
        $data->Receptor->NumeroEmpleado->Value = '1968';
        $data->Receptor->Departamento->Value = 'Contabilidad';
        $data->Receptor->Puesto->Value = 'Auxiliar Contable';
        $data->Receptor->RiesgoPuesto->Value = '1';
        $data->Receptor->PeriodicidadPago->Value = '05';
        $data->Receptor->Banco->Value = '021';
        $data->Receptor->CuentaBancaria->Value = '2147483640';
        $data->Receptor->SalarioBaseCotApor->Value = 300;
        $data->Receptor->SalarioDiarioIntegrado->Value = 335;
        $data->Receptor->ClaveEntidadFederativa->Value = 'AGU';

        for ($i = 1; $i <= 2; $i++) {
            /** @var SubContratacion $subcontratacion */
            $subcontratacion = $data->Receptor->SubContrataciones->add();
            $subcontratacion->RfcLabora->Value = 'AAA010101AAA';
            $subcontratacion->PorcentajeTiempo->Value = 100;
        }
        //</editor-fold>

        //<editor-fold desc="PERCEPCIONES">
        $data->Percepciones->TotalSueldos->Value = 9100;
        $data->Percepciones->TotalSeparacionIndemnizacion->Value = 100;
        $data->Percepciones->TotalJubilacionPensionRetiro->Value = 100;
        $data->Percepciones->TotalGravado->Value = 9100;
        $data->Percepciones->TotalExento->Value = 0;

        for ($i = 1; $i <= 3; $i++) {
            /** @var Percepcion $percepcion */
            $percepcion = $data->Percepciones->add();
            $percepcion->Tipo->Value = '001';
            $percepcion->Clave->Value = '100';
            $percepcion->Concepto->Value = 'Sueldo';
            $percepcion->ImporteGravado->Value = 9000;
            $percepcion->ImporteExento->Value = 0;

            $percepcion->AccionesTitulos->ValorMercado->Value = 0.000001;
            $percepcion->AccionesTitulos->PrecioAlOtorgarse->Value = 0.000001;

            for ($j = 1; $j <= 4; $j++) {
                /** @var HorasExtra $horasExtra */
                $horasExtra = $percepcion->HorasExtras->add();
                $horasExtra->Dias->Value = 1;
                $horasExtra->Tipo->Value = '03';
                $horasExtra->Horas->Value = 3;
                $horasExtra->ImportePagado->Value = 112.50;
            }

            $data->Percepciones->JubilacionPensionRetiro->TotalUnaExhibicion->Value = 50;
            $data->Percepciones->JubilacionPensionRetiro->TotalParcialidad->Value = 50;
            $data->Percepciones->JubilacionPensionRetiro->MontoDiario->Value = 20;
            $data->Percepciones->JubilacionPensionRetiro->IngresoAcumulable->Value = 50;
            $data->Percepciones->JubilacionPensionRetiro->IngresoNoAcumulable->Value = 100;

            $data->Percepciones->SeparacionIndemnizacion->TotalPagado->Value = 300;
            $data->Percepciones->SeparacionIndemnizacion->NumeroAniosServicio->Value = 1;
            $data->Percepciones->SeparacionIndemnizacion->UltimoSueldoMensualOrdinario->Value = 300;
            $data->Percepciones->SeparacionIndemnizacion->IngresoAcumulable->Value = 50;
            $data->Percepciones->SeparacionIndemnizacion->IngresoNoAcumulable->Value = 100;
        }

        //</editor-fold>

        //<editor-fold desc="DEDUCCIONES">
        $data->Deducciones->TotalOtrasDeducciones->Value = 100.00;
        $data->Deducciones->TotalImpuestosRetenidos->Value = 150.00;

        for ($i = 1; $i <= 5; $i++) {
            /** @var Deduccion $deduccion */
            $deduccion = $data->Deducciones->add();
            $deduccion->Tipo->Value = '001';
            $deduccion->Clave->Value = '201';
            $deduccion->Concepto->Value = 'IMSS';
            $deduccion->Importe->Value = 100.00;
        }
        //</editor-fold>

        //<editor-fold desc="OTROS PAGOS">
        for ($i = 1; $i <= 6; $i++) {
            /** @var OtroPago $otroPago */
            $otroPago = $data->OtrosPagos->add();
            $otroPago->Tipo->Value = '003';
            $otroPago->Clave->Value = '300';
            $otroPago->Concepto->Value = 'Viáticos';
            $otroPago->Importe->Value = 100;

            $otroPago->SubsidioAlEmpleo->SubsidioCausado->Value = 0;

            $otroPago->CompensacionSaldosAFavor->SaldoAFavor->Value = 32;
            $otroPago->CompensacionSaldosAFavor->Anio->Value = 2016;
            $otroPago->CompensacionSaldosAFavor->RemanenteSaldoFavor->Value = 31;
        }
        //</editor-fold>

        //<editor-fold desc="INCAPACIDADES">
        for ($i = 1; $i <= 7; $i++) {
            /** @var Incapacidad $incapacidad */
            $incapacidad = $data->Incapacidades->add();
            $incapacidad->DiasIncapacidad->Value = 1;
            $incapacidad->TipoNomina->Value = '03';
            $incapacidad->ImporteMonetario->Value = 300;
        }
        //</editor-fold>
    }

    /**
     * @param ElectronicDocument $electronicDocument
     */
    public static function CargarDatosMinimo($electronicDocument)
    {
        Nomina12::CargarDatosCfdi40($electronicDocument->Data);

        $electronicDocument->Data->Complementos->add(Complemento::NOMINA);

        /** @var \Facturando\ElectronicDocumentLibrary\Complemento\Nomina\Data $data */
        $data = $electronicDocument->Data->Complementos->last();

        $data->Version->Value = '1.2';
        $data->TipoNomina->Value = 'O';
        $data->FechaPago->Value = new \DateTime('NOW -5 hours');
        $data->FechaInicialPago->Value = new \DateTime('NOW -5 hours');
        $data->FechaFinalPago->Value = new \DateTime('NOW -5 hours');
        $data->NumeroDiasPagados->Value = 30;
        $data->NumeroDiasPagados->Decimals = 3;

        $data->Receptor->Curp->Value = 'OERR880127HDFTZB00';
        $data->Receptor->TipoContrato->Value = '01';
        $data->Receptor->TipoRegimen->Value = '02';
        $data->Receptor->NumeroEmpleado->Value = '1968';
        $data->Receptor->PeriodicidadPago->Value = '05';
        $data->Receptor->ClaveEntidadFederativa->Value = 'AGU';
    }

    /**
     * @param \Facturando\ElectronicDocumentLibrary\Document\Data\Data $data
     */
    public static function CargarDatosCfdi40($data)
    {
        $data->clear();

        //<editor-fold desc="Datos del comprobante">
        $data->Version->Value = '4.0';
        $data->Serie->Value = 'CFDI';
        $data->Folio->Value = '40';
        $data->Fecha->Value = new \DateTime('NOW -5 hours');
        $data->SubTotal->Value = 9300.00;
        $data->Descuento->Value = 250.00;
        $data->Total->Value = 9050.00;
        $data->MetodoPago->Value = 'PUE';
        $data->TipoComprobante->Value = 'N';
        $data->Exportacion->Value = '01';
        $data->Moneda->Value = 'MXN';
        $data->LugarExpedicion->Value = '01000';
        //</editor-fold>

        Base::Emisor($data->Emisor);
        Base::Receptor($data->Receptor);

        $data->Receptor->RegimenFiscalReceptor->Value = '605';
        $data->Receptor->UsoCfdi->Value = 'CN01';

        //<editor-fold desc="Concepto">
        /** @var Concepto $concepto */
        $concepto = $data->Conceptos->add();

        $concepto->ClaveProductoServicio->Value = '84111505';
        $concepto->Cantidad->Value = 1;
        $concepto->Cantidad->Decimals = 0;
        $concepto->Cantidad->Dot = false;
        $concepto->ClaveUnidad->Value = 'ACT';
        $concepto->Descripcion->Value = 'Pago de nómina';
        $concepto->ValorUnitario->Value = 9300.00;
        $concepto->Importe->Value = 9300.00;
        $concepto->Descuento->Value = 250;
        $concepto->ObjetoImpuesto->Value = '01';
        //</editor-fold>
    }

    /**
     * @param \Facturando\ElectronicDocumentLibrary\Document\Data\Data $data
     */
    public static function CargarDatosCfdi33($data)
    {
        $data->clear();

        //<editor-fold desc="Datos del comprobante">
        $data->Version->Value = '3.3';
        $data->Serie->Value = 'C';
        $data->Folio->Value = '2000';
        $data->Fecha->Value = new \DateTime('NOW -5 hours');
        $data->FormaPago->Value = '99';
        $data->SubTotal->Value = 9300.00;
        $data->Descuento->Value = 250.00;
        $data->Total->Value = 9050.00;
        $data->MetodoPago->Value = 'PUE';
        $data->TipoComprobante->Value = 'N';
        $data->Moneda->Value = 'MXN';
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

        $concepto->ClaveProductoServicio->Value = '84111505';
        $concepto->Cantidad->Value = 1;
        $concepto->Cantidad->Decimals = 0;
        $concepto->Cantidad->Dot = false;
        $concepto->ClaveUnidad->Value = 'ACT';
        $concepto->Descripcion->Value = 'Pago de nómina';
        $concepto->ValorUnitario->Value = 9300.00;
        $concepto->Importe->Value = 9300.00;
        $concepto->Descuento->Value = 250;
        //</editor-fold>
    }
}
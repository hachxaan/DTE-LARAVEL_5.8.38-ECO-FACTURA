<?php

namespace App\Http\Controllers\FacturarlosWSDL;

use Illuminate\Http\Request;
use App\Http\Controllers\FacturarlosWSDL\FacturarlosWSDL;
use App\Http\Controllers\FacturarlosWSDL\NewVersion;
use App\Http\Controllers\FacturarlosWSDL\ResultWSDL;



class FacturarlosWSDLController extends \App\Http\Controllers\Controller
{
    public function index(){       
        return view('facturarlosFirma');
    }

     public function anulacion(){       
        return view('facturarlosAnulacion');
    }
   

    public function firmaFactura(Request $request){

        $Cliente = $_POST['FCliente'];   
        $Usuario = $_POST['FUsuario'];
        $Clave = $_POST['FClave'];
        $Nitemisor = $_POST['FNitemisor'];
        $Numautorizacionuuid = isset($_POST['FNumautorizacionuuid']) ? $_POST['FNumautorizacionuuid'] : 'NA';
        $Motivoanulacion = isset($_POST['FMotivoanulacion']) ? $_POST['FMotivoanulacion'] : '';
        $PathFacturaIn = $_POST['FPathFacturaIn'];
        
        $OutputEDT = $_POST['FOutputEDT'];


        $ClienteWSDL = new FacturarlosWSDL() ;
        
        /******************************************************/
        /*** true -> Firma en producción **********************/
        $ClienteWSDL->setEsProduccion(false);


        /******************************************************/
        /***********  Archivo XML que se envia ****************/
        $ClienteWSDL->setXmldocInFilePath($PathFacturaIn);
        
        /******************************************************/
        /***********  Path donde se guarda la factura *********/
        $ClienteWSDL->setOutputXmlPDFPath($OutputEDT); 

        
        /*******************************************************/
        /*********** Acceso a Web Service **********************/
        $ClienteWSDL->setCliente($Cliente);
        $ClienteWSDL->setUsuario($Usuario);
        $ClienteWSDL->setClave($Clave);
        $ClienteWSDL->setNitemisor($Nitemisor);


        /*******************************************************/
        /*********** Llamada a Web Service *********************/
        $ResultWSDL = $ClienteWSDL->Execute_Firma();
        /*******************************************************/
        /*******************************************************/
    
        /************************************************************************/
        /****************  R E S U L T A D O S  *********************************/
        $CodigoResp         = $ResultWSDL->getCodigoResp();
        $RespuestaMain      = $ResultWSDL->getCodigoResp().'-'.$ResultWSDL->getTextoResp();
        $FechaEmision       = $ResultWSDL->getFechaEmision();
        $FechaCertificacion = $ResultWSDL->getFechaCertificacion();
        $NumeroAutorizacion = $ResultWSDL->getNumeroAutorizacion();
        $Serie              = $ResultWSDL->getSerie();
        $Numero             = $ResultWSDL->getNumero();
        $PathXmlFile        = $ResultWSDL->getPathXmlFile();
        $PathPdfFile        = $ResultWSDL->getPathPdfFile();
        $XmlString64        = $ResultWSDL->getXmlString64();
        $PdfString64        = $ResultWSDL->getPdfString64();

        
         return view('RespuestaFirma', compact( 'CodigoResp',
                                                'RespuestaMain', 
                                                'FechaCertificacion', 
                                                'NumeroAutorizacion',
                                                'Serie',
                                                'Numero',
                                                'PathXmlFile',
                                                'PathPdfFile',
                                                'XmlString64',
                                                'PdfString64'));
    }

    public function anulacionFactura(Request $request){

        $Cliente = $_POST['FCliente'];   
        $Usuario = $_POST['FUsuario'];
        $Clave = $_POST['FClave'];
        $Nitemisor = $_POST['FNitemisor'];
        $Numautorizacionuuid = isset($_POST['FNumautorizacionuuid']) ? $_POST['FNumautorizacionuuid'] : 'NA';
        $Motivoanulacion = isset($_POST['FMotivoanulacion']) ? $_POST['FMotivoanulacion'] : '';
        
        
        $OutputEDT = $_POST['FOutputEDT'];


        $ClienteWSDL = new FacturarlosWSDL() ;
        
        /******************************************************/
        /*** true -> Firma en producción **********************/
        $ClienteWSDL->setEsProduccion(false);
        
        /******************************************************/
        /***********  Path donde se guarda la factura *********/
        $ClienteWSDL->setOutputXmlPDFPath($OutputEDT); 
        
        /*******************************************************/
        /*********** Acceso a Web Service **********************/
        $ClienteWSDL->setCliente($Cliente);
        $ClienteWSDL->setUsuario($Usuario);
        $ClienteWSDL->setClave($Clave);
        $ClienteWSDL->setNitemisor($Nitemisor);


        $ClienteWSDL->setNumautorizacionuuid($Numautorizacionuuid);
        $ClienteWSDL->setMotivoanulacion($Motivoanulacion);        

        /*******************************************************/
        /*********** Llamada a Web Service *********************/
        $ResultWSDL = $ClienteWSDL->Execute_Anulacion();
        /*******************************************************/
        /*******************************************************/
    
        /************************************************************************/
        /****************  R E S U L T A D O S  *********************************/
        $CodigoResp         = $ResultWSDL->getCodigoResp();
        $RespuestaMain      = $ResultWSDL->getCodigoResp().'-'.$ResultWSDL->getTextoResp();
        $FechaAnulacion       = $ResultWSDL->getFechaAnulacion();
        $FechaCertificacion = $ResultWSDL->getFechaCertificacion();
        $NumeroAutorizacion = $ResultWSDL->getNumeroAutorizacion();
        $PathXmlFile        = $ResultWSDL->getPathXmlFile();
        $PathPdfFile        = $ResultWSDL->getPathPdfFile();
        $XmlString64        = $ResultWSDL->getXmlString64();
        $PdfString64        = $ResultWSDL->getPdfString64();

        
         return view('RespuestaAnulacion', compact( 'CodigoResp',
                                                    'RespuestaMain', 
                                                    'FechaAnulacion', 
                                                    'FechaCertificacion',
                                                    'NumeroAutorizacion',
                                                    'PathXmlFile',
                                                    'PathPdfFile',
                                                    'XmlString64',
                                                    'PdfString64'));
    }


}


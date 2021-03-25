<?php

namespace App\Http\Controllers\FacturarlosWSDL ;

use SoapClient;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Http\Controllers\FacturarlosWSDL\ResultWSDL;

class FacturarlosWSDL
{
    private static $UrlWsdl_FirmaProd = 'https://www.facturaenlineagt.com/adocumento?wsdl';
    private static $UrlWsdl_FirmaDes = 'http://pruebas.ecofactura.com.gt:8080/fel/adocumento?wsdl';
    private static $UrlWsdl_AnulacionProd = 'https://www.facturaenlineagt.com/aanulacion?wsdl';
    private static $UrlWsdl_AnulacionDes = 'http://pruebas.ecofactura.com.gt:8080/fel/aanulacion?wsdl';

    private static $EsProduccion            = false;
    private static $Cliente                 = "80000000114K";
    private static $Usuario                 = "ADMIN";
    private static $Clave                   = "123";
    private static $NitEmisor               = "80000000114K";
    private static $XmldocInFilePath        = "";
    private static $Numautorizacionuuid     = "NA";
    private static $Motivoanulacion         = "";
    private static $OutputXmlPdfPath        = "./OutputXmlPdf";


    public function Execute_Firma(){

        try {   
            $parameters = ['Cliente' => self::$Cliente];
            $parameters = Arr::add($parameters, 'Usuario', self::$Usuario);
            $parameters = Arr::add($parameters, 'Clave', self::$Clave);
            $parameters = Arr::add($parameters, 'Nitemisor', self::$NitEmisor);
            
            $XmlInputString = '';
            $XmlInputString = self::getXmldocInStringFromFile( self::$XmldocInFilePath);
            
            if ($XmlInputString == ""){
                return new ResultWSDL(-1, "No se encontró el archivo de entrada: " . self::$XmldocInFilePath); 
            } else {
                    $parameters = Arr::add($parameters, 'Xmldoc', $XmlInputString);
                    $Response = self::_ExecuteFirma($parameters);
                    return self::procesaRespuesta($Response);  
            }
        }
        catch (Throwable $e) {
            return new ResultWSDL(-1, $e->getMessage());
        }   
    }

    public function Execute_Anulacion(){

        try {   
            $parameters = ['Cliente' => self::$Cliente];
            $parameters = Arr::add($parameters, 'Usuario', self::$Usuario);
            $parameters = Arr::add($parameters, 'Clave', self::$Clave);
            $parameters = Arr::add($parameters, 'Nitemisor', self::$NitEmisor);
            $parameters = Arr::add($parameters, 'Numautorizacionuuid', self::$Numautorizacionuuid);
            $parameters = Arr::add($parameters, 'Motivoanulacion', self::$Motivoanulacion);


            $Response = self::_ExecuteAnulacion($parameters);
            return self::procesaRespuesta($Response);  

        }
        catch (Throwable $e) {
            return new ResultWSDL(-1, $e->getMessage());
        }   
    }
    
    public static function _ExecuteFirma ( $aparameters) {
        try {
            if (self::$EsProduccion){
                $service = new SoapClient(self::$UrlWsdl_FirmaProd); 
            } else {
                $service = new SoapClient(self::$UrlWsdl_FirmaDes); 
            }

            $response  = $service->__soapCall("Execute", array($aparameters));
        } catch (Throwable $th) {
            throw $th;
        }
        return simplexml_load_string($response->Respuesta) ;
    }

    public static function _ExecuteAnulacion( $aparameters) {
        try {
            if (self::$EsProduccion){
                $service = new SoapClient(self::$UrlWsdl_AnulacionProd); 
            } else {
                $service = new SoapClient(self::$UrlWsdl_AnulacionDes); 
            }

            $response  = $service->__soapCall("Execute", array($aparameters));
        } catch (Throwable $th) {
            throw $th;
        }
        return simplexml_load_string($response->Respuesta) ;
    }    

    private static function procesaRespuesta ( $aResponse ) {
        $Result = new ResultWSDL(-5, "OK");            
        $Error = $aResponse->Error;   
        $ResultConcat = "";                              
        if ($Error) {
            $CodeResp = $Error->xpath("//Error")[0]->attributes()->Codigo;
            $ErroresRespuesta = $aResponse->xpath("//Errores");
            
            foreach ($aResponse->Error as $key=>$NodoError) 
            {
                if  ($key == 0 ) {
                     $CodeResp =  $NodoError[0]->attributes()->Codigo;
                     $ResultConcat = $NodoError[0];
                } else {
                    $ResultConcat .= "("."[".$NodoError[0]->attributes()->Codigo."] ".$NodoError[0] ."); ";
                }
            }
        } else
        {
            $CodeResp = 0;           
        }

        if ( ($CodeResp == 0) || ($CodeResp == 2001) ){ 
            /* Si la Factura ha sido FIRMADA O ANULADA con 
                exito o si ya ha sido firmada anteriormente,
                el PAC envia los archivo de la factura en XML y PDF */
            
            // Decodifica XML y PDF de Base64 a String
            $XmlText   = base64_decode($aResponse->Xml);
            $PdfText   = base64_decode($aResponse->Pdf);
            
            // Obtiene el nodo de los atributos del XML de la respuesta del PAC
            $NameFile = $aResponse->xpath("//DTE");

            $Result->setFechaEmision($NameFile[0]->attributes()->FechaEmision);
            $Result->setFechaCertificacion($NameFile[0]->attributes()->FechaCertificacion);
            
            
            $Result->setNumero($NameFile[0]->attributes()->Numero);
            $Result->setSerie($NameFile[0]->attributes()->Serie);
           
            $Result->setFechaAnulacion($NameFile[0]->attributes()->FechaAnulacion);

            // Genera el nombre de la factura.
            if (self::getNumautorizacionuuid() == 'NA'){
                $Result->setNumeroAutorizacion($NameFile[0]->attributes()->NumeroAutorizacion);
                $NameFile = (string) $NameFile[0]->attributes()->NumeroAutorizacion."_".
                            (string) $NameFile[0]->attributes()->Numero."_".
                            (string) $NameFile[0]->attributes()->Serie;
            } else
            {
                $Result->setNumeroAutorizacion(self::$Numeroautorizacion);
                $NameFile = "Anulado_".self::getNumautorizacionuuid();
            }
            // Guarda lor archivos en la ruta especificada en setOutput() 
            Self::SaveFiles($NameFile, $XmlText, $PdfText );
            
            $Result->setPathXmlFile(self::getOutputXmlPdfPath().$NameFile."xml");
            $Result->setXmlString64($aResponse->Xml);
            $Result->setPdfString64($aResponse->Pdf);
            $Result->setPathPdfFile(self::getOutputXmlPdfPath().$NameFile."pdf");

        }
        $Result->setCodigoResp($CodeResp);
        $Result->setTextoResp($ResultConcat);
       // $Result->setTextoResp("dsadasdsadas");
        return $Result;

    }

    /************************************************************************************************/
    /************************************************************************************************/
    static function setOutputXmlPDFPath( $value ){
        self::$OutputXmlPdfPath = $value;
    }
    /************************************************************************************************/
    /************************************************************************************************/
    static function getOutputXmlPDFPath(){
        return self::$OutputXmlPdfPath;
    }
    /************************************************************************************************/
    /************************************************************************************************/
    static function setCliente( $value ){
        self::$Cliente = $value;
    }
    /************************************************************************************************/
    /************************************************************************************************/
    static function getCliente(){
        return self::$Cliente;
    }
    /************************************************************************************************/
    /************************************************************************************************/
    static function setUsuario( $value ){
        self::$Usuario = $value;
    }
    /************************************************************************************************/
    /************************************************************************************************/
    static function getUsuario(){
        return self::$Usuario;
    }   
    /************************************************************************************************/
    /************************************************************************************************/
    static function setClave( $value ){
        self::$Clave = $value;
    }
    /************************************************************************************************/
    /************************************************************************************************/
    static function getClave(){
        return self::$Clave;
    }       
    /************************************************************************************************/
    /************************************************************************************************/
    static function setNitEmisor( $value ){
        self::$NitEmisor = $value;
    }
    /************************************************************************************************/
    /************************************************************************************************/
    static function getNitEmisor(){
        return self::$NitEmisor;
    }  
    /************************************************************************************************/
    /************************************************************************************************/
    static function setXmldocInFilePath( $value ){
        self::$XmldocInFilePath = $value;
    }
    /************************************************************************************************/
    /************************************************************************************************/
    static function getXmldocInFilePath(){
        return self::$XmldocInFilePath;
    }   
    /************************************************************************************************/
    /************************************************************************************************/
    static function setNumAutorizacionUuid( $value ){
        self::$Numautorizacionuuid = $value;
    }
    /************************************************************************************************/
    /************************************************************************************************/
    static function getNumAutorizacionUuid(){
        return self::$Numautorizacionuuid;
    }  
    /************************************************************************************************/
    /************************************************************************************************/
    static function setMotivoAnulacion( $value ){
        self::$Motivoanulacion = $value;
    }
    /************************************************************************************************/
    /************************************************************************************************/
    static function getMotivoAnulacion(){
        return self::$Motivoanulacion;
    }       
    /************************************************************************************************/
    /************************************************************************************************/
    static function setEsProduccion( $value = false){
        self::$EsProduccion = $value;
    }
    /************************************************************************************************/
    /************************************************************************************************/
    static function getEsProduccion(){
        return self::$EsProduccion;
    }   

    /************************************************************************************************/
    private static function SaveFiles($aNameFile, $aXmlText, $aPdfText){
    
        $Ruta = self::getOutputXmlPdfPath(); 
        if(!file_exists ($Ruta)){
            mkdir($Ruta, 0700);
        } 
        
        $XmlFile = fopen($Ruta.$aNameFile.".xml", "w");
        fwrite($XmlFile, $aXmlText);
        $PDFFile = fopen($Ruta.$aNameFile.".pdf", "w");
        fwrite($PDFFile, $aPdfText);

    }
    /************************************************************************************************/ 

    public static function getXmldocInStringFromFile ( string $aXmldocInFilePath) {
        
        if(file_exists ($aXmldocInFilePath)){
            $ResultSTR = file_get_contents(public_path($aXmldocInFilePath));
            return $ResultSTR;
        } else {
            return "";
        }
    }

}
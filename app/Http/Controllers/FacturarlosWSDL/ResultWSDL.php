<?php

namespace App\Http\Controllers\FacturarlosWSDL ;

class ResultWSDL {
    
    protected static $CodigoResp = '-10';
    private static   $TextoResp  = '';
    
    private static $FechaEmision        = '';
    private static $FechaCertificacion  = '';
    private static $NumeroAutorizacion  = '';
    private static $Serie               = '';
    private static $Numero              = '';
     
    private static $FechaAnulacion      = '';
    
    private static $XmlString64         = '';
    private static $PdfString64         = '';
    
    private static $XmlString           = '';
    private static $PdfString           = '';
    
    private static $PathXmlFile         = '';
    private static $PathPdfFile         = '';

    protected static $FullResponse      = '';
 
        /************************************************************************************************/
        public function __construct(int $aCodigoResp, string $aTextoResp) {
        
            self::$CodigoResp = $aCodigoResp;
            self::$TextoResp  = $aTextoResp;    

        }
/************************************************************************************************/
    
    private static function ResultWSDL( $aCodigoResp, $aTextoResp) {
        self::$CodigoResp = $aCodigoResp;
        self::$TextoResp = $aTextoResp;
    }
    public static function getCodigoResp() {
        return self::$CodigoResp;
    }
    public static function setCodigoResp( $value) {
        self::$CodigoResp = $value;
    }
          public static function getTextoResp() {
        return self::$TextoResp;
    }
    public static function setTextoResp( $value ) {
        self::$TextoResp = $value;
    }
    
    
    public static function getFechaEmision() {
        return self::$FechaEmision;
    }
    public static function setFechaEmision( $value ) {
        self::$FechaEmision = $value;
    }    
    
    
     public static function getFechaCertificacion() {
        return self::$FechaCertificacion;
    }
    public static function setFechaCertificacion( $value ) {
        self::$FechaCertificacion = $value;
    }    
       
    
    public static function getNumeroAutorizacion() {
        return self::$NumeroAutorizacion;
    }
    public static function setNumeroAutorizacion( $value ) {
        self::$NumeroAutorizacion = $value;
    }
    
    public static function getFechaAnulacion() {
        return self::$FechaAnulacion;
    }
    public static function setFechaAnulacion( $value ) {
        self::$FechaAnulacion = $value;
    }       
    
    
    public static function getSerie() {
        return self::$Serie;
    }
    public static function setSerie( $value ) {
        self::$Serie = $value;
    }  
    
    
    public static function getNumero() {
        return self::$Numero;
    }
    public static function setNumero( $value ) {
        self::$Numero = $value;
    } 
    
    
    public static function getXmlString64() {
        return self::$XmlString64;
    }
    static function setXmlString64( $value ) {
        self::$XmlString64 = $value;
    }    
    
     
    public static function getPdfString64() {
        return self::$PdfString64;
    }
    static function setPdfString64( $value ) {
        self::$PdfString64 = $value;
    }    
        
    
    public static function getXmlString() {
        return self::$XmlString;
    }
    static function setXmlString( $value ) {
        self::$XmlString = $value;
    }       
    public static function getPdf64() {
        return self::$PdfString64;
    }
    public static function setPdf64( $value ) {
        self::$PdfString64 = $value;
    }  
    
    
    public static function getPathXmlFile() {
        return self::$PathXmlFile;
    }
    public static function setPathXmlFile( $value ) {
        self::$PathXmlFile = $value;
    }    
    
    
    public static function getPathPdfFile() {
        return self::$PathPdfFile;
    }
    public static function setPathPdfFile( $value ) {
        self::$PathPdfFile = $value;
    }   

    


    
}

?>
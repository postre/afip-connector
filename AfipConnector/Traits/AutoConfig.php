<?php
namespace AfipConnector\Traits;

trait  AutoConfig{
    
    private function setConfigs(){
        $this->loadEnv();
        $this->cert_path=$_ENV['CERT_PATH'];
        $this->wsdl_path=$_ENV['WSDL_PATH'];
         
         if ($_ENV['PRODUCTION']==="TRUE") {
            $this->url  =$this->url_prod;
            $this->cert = ($this->cert!='') ?$this->cert_path.$this->cert :$this->cert_path.$this->cert_prod;
            $this->key = ($this->key!='') ?$this->cert_path.$this->key :$this->cert_path.$this->key_prod;
            $this->wsdl  =$this->wsdl_path.$this->wsdl_prod;
            $this->env   = 'prod'; 
         } else {
            $this->url =$this->url_homo;
            $this->cert = ($this->cert!='') ?$this->cert_path.$this->cert :$this->cert_path.$this->cert_homo;
            $this->key = ($this->key!='') ?$this->cert_path.$this->key :$this->cert_path.$this->key_homo;
            $this->wsdl  =$this->wsdl_path.$this->wsdl_homo;
            $this->env   = 'homo'; 
         }
 
         $cert=openssl_x509_parse(file_get_contents($this->cert));
 
         $_ENV['CERT_CN'] = $cert['subject']['CN'];
        $this->tra_path=$_ENV['XML_PATH'].DIRECTORY_SEPARATOR.$_ENV['CERT_CN'].DIRECTORY_SEPARATOR.'tra'.DIRECTORY_SEPARATOR;
        $this->wsaa_path=$_ENV['XML_PATH'].DIRECTORY_SEPARATOR.$_ENV['CERT_CN'].DIRECTORY_SEPARATOR.'wsaa'.DIRECTORY_SEPARATOR;
        $this->ta_file_path=$_ENV['XML_PATH'].DIRECTORY_SEPARATOR.$_ENV['CERT_CN'].DIRECTORY_SEPARATOR.'ta'.DIRECTORY_SEPARATOR;
        $this->cbte_path=$_ENV['XML_PATH'].DIRECTORY_SEPARATOR.$_ENV['CERT_CN'].DIRECTORY_SEPARATOR.
                                            'cbte'.DIRECTORY_SEPARATOR.$this->env.DIRECTORY_SEPARATOR;
        
        //  if(!is_dir($_ENV['XML_PATH'].DIRECTORY_SEPARATOR.$_ENV['CERT_CN'])){
        //      mkdir($this->ta_file_path,0777,true);
        //      mkdir($this->tra_path,0777,true);
        //      mkdir($this->cbte_path,0777,true);
        //  }
         if(!is_dir($this->ta_file_path)){
             mkdir($this->ta_file_path,0777,true);
         }
         if(!is_dir($this->tra_path)){
             mkdir($this->tra_path,0777,true);
         }
         if(!is_dir($this->cbte_path)){
             mkdir($this->cbte_path,0777,true);
         }
         if(!is_dir($this->wsaa_path)){
             mkdir($this->wsaa_path,0777,true);
         }

     }
 
     private function loadEnv(){
        //  $dir=str_replace('AfipConnector\\Afip','',__DIR__);
         $dir=str_replace('AfipConnector\\Traits','',__DIR__);
 
 
         if(!empty($_ENV['afip-connector'])){
             return;
         }

         $env = parse_ini_file($dir.'.env');
         foreach($env as $k=>$v){
             $_ENV[$k]=$v;
         }

         if(empty($_ENV['JSON'])){
            $_ENV['JSON']=false;
         }

         $_ENV['afip-connector']=true;
         
     }
}
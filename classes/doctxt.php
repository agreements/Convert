<?php
    class doctxt {
        public $connectname;
        public $connectpass;

        public function __construct($format_res, $flname) {
            require_once('config.php');
            // Turn up error reporting
            error_reporting (E_ALL|E_STRICT);
             
            // Turn off WSDL caching
            ini_set ('soap.wsdl_cache_enabled', 0);
             
            // Define credentials for LD
            define ('USERNAME', $this->connectname);
            define ('PASSWORD', $this->connectpass);
             
            // SOAP WSDL endpoint
            define ('ENDPOINT', 'https://api.livedocx.com/2.1/mailmerge.asmx?wsdl');
             
            // Define timezone
            
            date_default_timezone_set('Europe/Berlin');

            // Instantiate SOAP object and log into LiveDocx
             
            $this->soap = new SoapClient(ENDPOINT);
             
            $this->soap->LogIn(
                array(
                    'username' => USERNAME,
                    'password' => PASSWORD
                )
            );
             
            // Upload template
             
            $this->data = file_get_contents('Original/'.$format_res);

            $this->soap->SetLocalTemplate(
                array(
                    'template' => base64_encode($this->data),
                    'format'   => 'doc'
                )
            );

            $this->result = $this->soap->RetrieveDocument(
                array(
                    'format' => 'txt'
                )
            );
             
            $this->data = $this->result->RetrieveDocumentResult;
             
            file_put_contents('Recode/'.$flname.'.txt', base64_decode($this->data));
        }
    }
?>
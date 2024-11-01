<?php
declare(strict_types=1);

class AutoLoader{

    private string $chemin;
    private string $rep;

    public function __construct(string $c1, string $c2) {
        $this->chemin=$c1;
        $this->rep=$c2;
    }


    public function register(){
        spl_autoload_register( [$this, 'loadClass']);
    }

    public function loadClass(string $nomClass){

        $fileName = substr($nomClass, strlen($this->chemin));
        $fileName = $this->rep . "/" . $fileName . ".php";
        $fileName = str_replace("\\", "/", $fileName);
        if (is_file($fileName)) require_once($fileName);
        
    }
} 

    

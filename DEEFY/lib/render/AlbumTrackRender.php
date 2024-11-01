<?php
declare(strict_types=1);
namespace deefy\render;

class AlbumTrackRender extends AudioTrackRender{
    public function renderLong(){
        echo ('<pre>'); 
        var_dump($this->album);
        echo <<<FIN
                <audio controls>
                    <source src="$this->album->nomFichier" type = "audio/mpeg">
                </audio>
            FIN;
        echo ('</pre>');
    }
    public function renderCompact(){
        echo ('<pre>');
        print_r($this->album);
        echo ('</pre>');
    }
}
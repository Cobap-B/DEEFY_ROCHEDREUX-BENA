<?php
declare(strict_types=1);
namespace deefy\render;

class PodcastTrackRender extends AudioTrackRender{
    public function renderLong(){
        $html = "";
        $html.= ('<pre>');
        var_dump($this->album);
        $html.= <<<FIN
                <audio controls>
                    <source src="$this->album->nomFichier" type = "audio/mpeg">
                </audio>
            FIN;
        $html.= ('</pre>');
        return $html;
    } 
    public function renderCompact(){
        echo ('<pre>');
        print_r($this->album);
        echo ('</pre>');
    }
}
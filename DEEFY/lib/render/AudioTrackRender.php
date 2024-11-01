<?php
declare(strict_types=1);
namespace deefy\render;
use \deefy\audio\tracks\AudioTrack;

abstract class AudioTrackRender implements Render{

    protected AudioTrack $album;

    public function __construct(AudioTrack $album) {
        $this->album = $album;
    }
    abstract public function renderCompact();
    abstract public function renderLong(); 

    public function render(int $selector=0){  
        switch ($selector) {
            case self::COMPACT:   
                return $this->renderCompact();        
                break;
            
            case self::LONG:
                return $this->renderLong();
                break;
                
            default:
                print($this->album->titre);
                print('<br>');
                print($this->album->nomFichier);
                print('<br>');
                return "";
                break;
        }
    }
}
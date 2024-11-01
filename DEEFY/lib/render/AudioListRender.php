<?php
declare(strict_types=1);
namespace deefy\render;
use \deefy\audio\list\AudioList;
use \deefy\render\PodcastTrackRender;

class AudioListRender implements \deefy\render\Render{
   protected AudioList $liste;

    public function __construct(AudioList $l){
        $this->liste = $l;
    }

    public function renderLong(){
        $html = "";
        $html.= '<pre>';
        $l = $this->liste;
        $html.="Liste $l->nom : <br>";
        $html.=" . Taille $l->taille <br>";
        $html.=" . Duree $l->duree <br>";
        $html.="tracks : <br>";
        foreach($l->list as $audio){
            $r = new PodcastTrackRender($audio);
            $html.=$r->render(2);
        }
        $html.='</pre>';
        return $html;
    } 
    public function renderCompact(){
        $html = "";
        $html.= '<pre>';
        $html.=print_r($this->liste);
        $html.= '</pre>';
    }

    public function render(int $selector=0){  
        switch ($selector) {
            case self::COMPACT:   
                return $this->renderCompact();        
                break;
            
            case self::LONG:
                return $this->renderLong();
                break;
                
            default:
                return"";
                break;
        }
    }

    



}
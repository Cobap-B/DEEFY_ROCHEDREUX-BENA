<?php
declare(strict_types=1);
namespace deefy\audio\list;
use \deefy\audio\tracks\AudioTrack;

class Playlist extends AudioList{
    
    
    public function addPiste(AudioTrack $piste){
        $this->duree += $piste->duree;
        $this->taille += 1;
        array_push($this->list, $piste);
    }

    public function removePiste(int $i){
        $this->duree -= $this->list[$i]->duree;
        $this->taille -= 1;
        unset($this->list[$i]);
    }

    public function addPlaylist(array $l){
        $this->list += $l;
        $this->taille += sizeof($l);
        $d = 0;
        for ($i=0; $i < sizeof($l); $i++) { 
            $d+= $l[$i]->duree;
        }
        $this->duree += $d;
    }

}
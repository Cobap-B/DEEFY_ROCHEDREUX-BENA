<?php
namespace deefy\action;
use \deefy\render\AudioListRender; 
use \deefy\audio\list\AudioList;

class DisplayPlaylistAction extends Action{
    public function execute(){
        $html = "";
        if (isset($_SESSION["Playlist"])){
            $playlist = unserialize($_SESSION["Playlist"]);
            $render = new AudioListRender($playlist);
            
            $html = $render->render(2);
        }else{
            $html = <<<FIN
                    <div> Aucune playlist créer </div>
                FIN;
        }
        return $html;
    }
}
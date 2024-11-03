<?php
namespace deefy\action;
use \deefy\render\AudioListRender;
use \deefy\repository\DeefyRepository;

use \deefy\audio\list\AudioList;

class DisplayPlaylistAction extends Action{
    public function execute(){
        $html = '<!DOCTYPE html>
                <html lang="fr">
                    <head>
                        <meta charset="UTF-8">
                        <style>
                            body {
                                color: white;
                                text-align: center;                            }
                        </style>
                    </head>';
        $pdo = DeefyRepository::getInstance();
        $pl = $pdo->findAllPlaylists();
        if(count($pl)==0){
            $html .= "<p>Aucune playlist créer</p> <a href='?action=add-playlist'>Créer une playlist</a>";
        }else{
            $html .= '<body><form action="?action=playlist" method="post">
            Playlist : <select name="Playlist" size="1">';
            foreach($pl as $p){
                $id = $p->__get("ID");
                $name = $p->__get("nom");
                $html .= "<option value='$id'> $name </option>"; 
            }
            $html .= '</select>';
            $html .= '<input type="submit" value="Visualiser"></form>';
            if ($this->http_method === "GET") {          
                //rien
            }else{
                $playlist = $pdo->findPlaylistById($_POST["Playlist"]);
                $render = new AudioListRender($playlist);
                $html.=" <body>
                            {$render->render(2)}
                        </body>";
            }
        }
        
        $html .= "</html>";
        return $html;
    }
}

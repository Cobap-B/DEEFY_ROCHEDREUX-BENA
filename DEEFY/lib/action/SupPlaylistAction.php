<?php
namespace deefy\action;
use \deefy\repository\DeefyRepository;

class SupPlaylistAction extends Action{
    public function execute(){
        
        if ($this->http_method === "GET"){
            $html = "";
            if (isset($_SESSION["Playlist"])){
                $html = <<<FIN
                        <form action="?action=sup-playlist" method="post">
                            <input type="submit" value"Supprimer" value="Supprimer Playlist">
                        </form>
                    FIN;
            }else{
                $html = <<<FIN
                        <div> Aucune playlist créer </div>
                    FIN;
            }
            return $html;
        }else{

            $pdo = DeefyRepository::getInstance();

            $pdo->supPlaylist(unserialize($_SESSION["Playlist"])->__get("ID"));
            $_SESSION["Playlist"] = null;
            return "<div> Playlist supprimer </div>";
        }
    }
}
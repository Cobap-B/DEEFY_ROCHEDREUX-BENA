<?php
namespace deefy\action;
use \deefy\audio\list\Playlist; 
use \deefy\audio\track\PodcastTrack; 

class AddPodcastTrackAction extends Action{
    public function execute(){
        if ($this->http_method === "GET"){
            return <<<FIN
                <div>Ajouter un audio : </div>

                <form action="?action=add-track" method="post">
                    Nom : <input type="text" name="nom">
                    <br>
                    Chemin : <input type="text" name="chemin">


                    <input type="submit" value"Creer">
                </form>
            FIN;
        }else{
            $html = "";
            if (isset($_POST["nom"]) && isset($_POST["chemin"])){
                $name = filter_var($_POST["nom"], FILTER_SANITIZE_STRING);
                $chemin = filter_var($_POST["chemin"], FILTER_SANITIZE_STRING);
                if (! isset($_SESSION["Playlist"])){
                    $html ="<div> Aucune playlist créer  </div>".'<a href="?action=add-playlist">Add playlist</a>';
                }else{
                    $playlist = $_SESSION["Playlist"];
                    if (substr($chemin,-4) === '.mp3'){
                        $track = new PodcastTrack($name, "../src/audio/".$chemin);
                        $playlist->addPiste($track);
                        

                        $html = <<<FIN
                                    <div> Ajout de $name fait </div>
                                FIN .'<a href="?action=add-track">Add newtrack</a>';
                    }else{
                        $html = "<div> Pas un mp3 </div>".'<a href="?action=add-track">Add newtrack</a>'; 
                    }
                }
            }else{
                $html = "<div> Nom invalide ou chemin invalide </div>".'<a href="?action=add-track">Add newtrack</a>';
            }
            return $html;
        }
    }
}
<?php
namespace deefy\action;
use \deefy\audio\list\Playlist; 
use \deefy\repository\DeefyRepository;

class AddPlaylistAction extends Action{
    public function execute(){
        if ($this->http_method === "GET"){
            return <<<FIN
                <div>Ajouter une playlist : </div>

                <form action="?action=add-playlist" method="post">
                    Nom : <input type="text" name="nom">


                    <input type="submit" value"Creer">
                </form>
            FIN;
        }else{
            if (! isset($_SESSION["User"])){
                return "<div> Il faut un compte </div>".'<a href="?action=authentification">Authentification</a>';
            }
            if (isset($_POST["nom"])){
                $name = filter_var($_POST["nom"], FILTER_SANITIZE_STRING);
                if (! isset($_SESSION["Playlist"])){
                    
                    $pdo = DeefyRepository::getInstance();

                    $id = $pdo->findLastIdPlaylist()+1;

                    echo $id;

                    $p = new Playlist($id, $name, []);
                    $_SESSION["Playlist"]=  serialize($p);

                    $pdo->saveEmptyPlaylist($p);

                    return "<div> Playlist $name créer </div>";
                }else{
                    $name =unserialize($_SESSION["Playlist"])->nom;
                    return "<div> Playlist exist déjà : $name </div>";
                }
            }else{
                return "<div> Nom invalide  </div>";
            }
        }
    }
}
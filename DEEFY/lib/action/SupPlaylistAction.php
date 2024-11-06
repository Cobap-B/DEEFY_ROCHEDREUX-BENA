<?php
namespace deefy\action;
use \deefy\repository\DeefyRepository;

class SupPlaylistAction extends Action{
    public function execute(){
        $html = '
        <!DOCTYPE html>
                    <html lang="fr">
                        <head>
                            <meta charset="UTF-8">
                            <style>
                                body {
                                    color: white;
                                    text-align: center;
                                }
                            </style>
                        </head>';

        if (!isset($_SESSION["User"])) {
            $html .= "<body><div> Il faut un compte </div><a href='?action=authentification'>Se connecter</a></body>";
        } else{
            $pdo = DeefyRepository::getInstance();
            $pl = $pdo->findAllPlaylists();
            if(count($pl)==0){
                $html .= "<p>Aucune playlist créer</p> <a href='?action=add-playlist'>Créer une playlist</a>";
            }else{

                if ($this->http_method === "GET"){
                    $html .= '<body><form action="?action=sup-playlist" method="post">
                    Playlist : <select name="Playlist" size="1">';
                    foreach($pl as $p){
                        $id = $p->__get("ID");
                        $name = $p->__get("nom");
                        $html .= "<option value='$id'> $name </option>"; 
                    }
                    $html .= '</select><br><br>';
                    $html .= '<input type="submit" value="Supprimer Playlist"></form>
                            </body>';
                } else {
                    $playlist = $pdo->findPlaylistById($_POST["Playlist"]);
                    $pdo->supPlaylist($playlist->__get("ID"));
                    $_SESSION["Playlist"] = null;
        
                    $html .= '
                            <body>
                                <div>Playlist supprimée</div>
                                <a href="?action=add-playlist">Créer une playlist</a>
                            </body>';
                }
            }
        }
        $html .= "</html>";
        return $html;
    }
}
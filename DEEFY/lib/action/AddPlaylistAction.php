<?php
namespace deefy\action;
use \deefy\audio\list\Playlist;
use \deefy\repository\DeefyRepository;

class AddPlaylistAction extends Action {
    public function execute() {
        if (!isset($_SESSION["User"])) {
            return <<<FIN
                <!DOCTYPE html>
                <html lang="fr">
                    <head>
                        <meta charset="UTF-8">
                        <style>
                            body {
                                color: white;
                                text-align: center;
                            }
                        </style> <title> Ajouter Playlist</title>
                    </head>
                    <body>
                        <div>Il faut un compte</div>
                        <a href="?action=authentification">Se connecter</a>
                    </body>
                </html>
            FIN;
        }else{
            if ($this->http_method === "GET") {
                return <<<FIN
                    <!DOCTYPE html>
                    <html lang="fr">
                        <head>
                            <meta charset="UTF-8">
                            <style>
                                body {
                                    color: white;
                                    text-align: center;
                                }
                            </style><title> Ajouter Playlist</title>
                        </head>
                        <body>
                            <div>Ajouter une playlist :</div>
                            <form action="?action=add-playlist" method="post">
                                Nom : <input type="text" name="nom">
                                <input type="submit" value="Créer">
                            </form>
                        </body>
                    </html>
                FIN;
            } else {        
                if (isset($_POST["nom"])) {
                    $name = filter_var($_POST["nom"], FILTER_SANITIZE_STRING);
        
                    $pdo = DeefyRepository::getInstance();
                    $id = $pdo->findLastIdPlaylist() + 1;
    
                    $p = new Playlist($id, $name, []);
                    $_SESSION["Playlist"] = serialize($p);
    
                    $pdo->saveEmptyPlaylist($p);
    
                    return <<<FIN
                        <!DOCTYPE html>
                        <html lang="fr">
                            <head>
                                <meta charset="UTF-8">
                                <style>
                                    body {
                                        color: white;
                                        text-align: center;
                                    }
                                </style> <title> Ajouter Playlist</title>
                            </head>
                            <body>
                                <div>Playlist $name créée</div>
                            </body>
                        </html>
                    FIN;
                
                } else {
                    return <<<FIN
                        <!DOCTYPE html>
                        <html lang="fr">
                            <head>
                                <meta charset="UTF-8">
                                <style>
                                    body {
                                        color: white;
                                        text-align: center;
                                    }
                                </style> <title> Ajouter Playlist</title>
                            </head>
                            <body>
                                <div>Nom invalide</div>
                            </body>
                        </html>
                    FIN;
                }
            }
        }
        
    }
}

<?php
namespace deefy\action;
use \deefy\repository\DeefyRepository;

class SupPlaylistAction extends Action{
    public function execute(){

        if ($this->http_method === "GET"){
            $html = "";
            if (isset($_SESSION["Playlist"])){
                $html = <<<FIN
                    <!DOCTYPE html>
                    <html lang="fr">
                        <head>
                            <meta charset="UTF-8">
                            <style>
                                body {
                                    color: white;
                                    text-align: center;
                                }
                            </style><title> Supprimer Playlist</title>
                        </head>
                        <body>
                            <form action="?action=sup-playlist" method="post">
                                <input type="submit" value="Supprimer Playlist">
                            </form>
                        </body>
                    </html>
                FIN;
            } else {
                $html = <<<FIN
                    <!DOCTYPE html>
                    <html lang="fr">
                        <head>
                            <meta charset="UTF-8">
                            <style>
                                body {
                                    color: white;
                                    text-align: center;
                                }
                            </style><title> Supprimer Playlist</title>
                        </head>
                        <body>
                            <div>Aucune playlist créée</div>
                        </body>
                    </html>
                FIN;
            }
            return $html;
        } else {
            $pdo = DeefyRepository::getInstance();
            $pdo->supPlaylist(unserialize($_SESSION["Playlist"])->__get("ID"));
            $_SESSION["Playlist"] = null;

            return <<<FIN
                <!DOCTYPE html>
                <html lang="fr">
                    <head>
                        <meta charset="UTF-8">
                        <style>
                            body {
                                background-color: #333;
                                color: white;
                                text-align: center;
                                margin: 20px;
                            }
                        </style><title> Supprimer Playlist</title>
                    </head>
                    <body>
                        <div>Playlist supprimée</div>
                    </body>
                </html>
            FIN;
        }
    }
}
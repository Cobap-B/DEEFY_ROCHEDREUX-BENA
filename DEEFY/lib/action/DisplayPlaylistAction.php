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
                        </style>
                    </head>
                    <body>
                        {$render->render(2)}
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
                                text-align: center;                            }
                        </style>
                    </head>
                    <body>
                        <div>Aucune playlist créée</div>
                    </body>
                </html>
            FIN;
        }
        return $html;
    }
}

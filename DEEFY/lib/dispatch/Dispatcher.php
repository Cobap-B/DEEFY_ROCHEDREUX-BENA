<?php
namespace deefy\dispatch;

use \deefy\action as act;


class Dispatcher{

    public string $action;

    public function __construct(){
        if (isset($_GET['action'])){
            $this->action = $_GET['action'];
        }else
            $this->action = "sign";
    }

    private function renderPage(string $html){
        echo <<<FIN
            <?doctype>
            <html lang="fr">
                <head>
                    <meta charset="UTD-8">
                    <title>DEFI</title>
                </head>
                <body>
                    <h1>DeffyApp</h1>
            FIN;
        if ($this->action != "sign"){
            echo <<<FIN
                <nav>
                    <ul>
                    <li><a href="?action=default">Acceuil</a></li>

                    <li><a href="?action=playlist">Affiche playlist</a></li>

                    <li><a href="?action=add-playlist">Add playlist</a></li>

                    <li><a href="?action=add-track">Add track</a></li>

                    <li><a href="?action=sup-playlist">Sup Playlist</a></li>

                    </ul>
                </nav>
                FIN;
        }
        echo <<<FIN
                <br>
                
                $html
            </body>
        </html>
        FIN;
    }
    
    public function run(){
        $a = null;
        switch ($this->action ) {
            case 'playlist' :
                $a = new act\DisplayPlaylistAction();
                break ;
            case 'add-playlist' :
                $a = new act\AddPlaylistAction();
                break ;
            case 'add-track' :
                $a = new act\AddPodcastTrackAction();
                break ;
            case 'sup-playlist' :
                $a = new act\SupPlaylistAction();
                break ;
            case 'sign' :
                $a = new act\SignAction();
                break ;
            default :
                $a = new act\DefaultAction();
                break;
        }
        $this->renderPage($a->execute());
    }

}
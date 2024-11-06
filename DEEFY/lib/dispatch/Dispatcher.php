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

    private function renderPage(string $html): void
    {
        echo <<<FIN
        <!DOCTYPE html>
        <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <title>DEFI</title>
                <link rel="stylesheet" href="css/rendupage.css">
            </head>
            <body>
                <div class="container">
                    <h1 class="text-primary">DeefyApp</h1>
                    <nav>
                        <ul class="nav">
                            <li class="nav-item"><a class="nav-link" href="?action=default">Accueil</a></li>
                            <li class="nav-item"><a class="nav-link" href="?action=playlist">Afficher la playlist</a></li>
                            <li class="nav-item"><a class="nav-link" href="?action=add-playlist">Ajouter une playlist</a></li>
                            <li class="nav-item"><a class="nav-link" href="?action=add-track">Ajouter une track</a></li>
                            <li class="nav-item"><a class="nav-link" href="?action=sup-playlist">Supprimer la Playlist</a></li>
                            <li class="nav-item"><a class="nav-link" href="?action=sign">Creer un compter</a></li>
                            <li class="nav-item"><a class="nav-link" href="?action=authentification">Se connecter</a></li>
                        </ul>
                    </nav>
                    <br>
                    $html
                </div>
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
            case 'authentification' :
                    $a = new act\AuthentificationAction();
                    break ;
        
            default :
                $a = new act\DefaultAction();
                break;
        }
        $this->renderPage($a->execute());
    }

}
<?php
namespace deefy\action;
use \deefy\audio\list\Playlist;
use \deefy\audio\track\PodcastTrack;
use \deefy\repository\DeefyRepository;

class AddPodcastTrackAction extends Action {
    public function execute() {
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
                if ($this->http_method === "GET") {
                    $html .= '<body><div>Ajouter un audio :</div>
                    <form action="?action=add-track" method="post" enctype="multipart/form-data">
                    Playlist : <select name="Playlist" size="1">';
                    foreach($pl as $p){
                        $id = $p->__get("ID");
                        $name = $p->__get("nom");
                        $html .= "<option value='$id'> $name </option>"; 
                    }
                    $html .= '</select><br><br>';
                   
                    $html .= '
                            Titre : <input type="text" name="nom"><br><br>
                            Fichier : <input type="file" name="audio" accept="audio/mpeg"/>
                            <br><br>';            
                    $html .= '<input type="submit" value="Créer"></form></body>';
    
                } else {
                    $html .= "";
                    if (isset($_POST["nom"]) && isset($_FILES['audio'])) {
                        $name = filter_var($_POST["nom"], FILTER_SANITIZE_STRING);
                        $playlist = $pdo->findPlaylistById($_POST["Playlist"]);
                        if (! isset($playlist)) {
                            $html .= "<body><div> Aucune playlist selectionner ??? </div><a href='?action=add-playlist'>Ajouter une playlist</a></body>";
                        } else {
                            if ($_FILES['audio']['type'] === 'audio/mpeg') {
                                $upload_dir = 'audio/';
                                $tmp = $_FILES['audio']['tmp_name'];
    
                                if ($_FILES['audio']['error'] === UPLOAD_ERR_OK) {
                                    $dest = $upload_dir . $_FILES['audio']['name'];
                                    move_uploaded_file($tmp, $dest);
                                }
                            }
    
                            
                            $pdo = DeefyRepository::getInstance();
                            $id = $pdo->findLastIdTrack() + 1;
                            $track = new PodcastTrack($id, $name, $_FILES['audio']['name']);
    
                            $playlist->addPiste($track);
                            $_SESSION['Playlist'] = serialize($playlist);
    
                            $pdo->saveTrack($track);
                            $pdo->addTrackToPlaylist($id, $playlist->__get("ID"));
    
                            $html .="<body>
                                    <div> Ajout de $name réussi </div>
                                    <a href='?action=add-track'>Ajouter un nouveau morceau</a>
                                </body>
                            ";
                        }
                    } else {
                        $html .= "<body><div> Nom invalide </div><a href='?action=add-track'>Ajouter un nouveau morceau</a></body>";
                    }
                }
            }
        }
        $html .= "</html>";
        return $html;
    }
}

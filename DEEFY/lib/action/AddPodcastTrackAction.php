<?php
namespace deefy\action;
use \deefy\audio\list\Playlist;
use \deefy\audio\track\PodcastTrack;
use \deefy\repository\DeefyRepository;

class AddPodcastTrackAction extends Action {
    public function execute() {
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
                        </style>
                    </head>
                    <body>
                        <div>Ajouter un audio :</div>
                        <form action="?action=add-track" method="post" enctype="multipart/form-data">
                            Titre : <input type="text" name="nom"><br><br>
                            Fichier : <input type="file" name="audio" accept="audio/mpeg"/>
                            <br><br>
                            <input type="submit" value="Créer">
                        </form>
                    </body>
                </html>
            FIN;
        } else {
            $html = "";
            if (!isset($_SESSION["User"])) {
                $html = "<div> Il faut un compte </div><a href='?action=authentification'>Authentification</a>";
            } elseif (isset($_POST["nom"]) && isset($_FILES['audio'])) {
                $name = filter_var($_POST["nom"], FILTER_SANITIZE_STRING);

                if (!isset($_SESSION["Playlist"])) {
                    $html = "<div> Aucune playlist créée </div><a href='?action=add-playlist'>Ajouter une playlist</a>";
                } else {
                    if ($_FILES['audio']['type'] === 'audio/mpeg') {
                        $upload_dir = 'audio/';
                        $tmp = $_FILES['audio']['tmp_name'];

                        if ($_FILES['audio']['error'] === UPLOAD_ERR_OK) {
                            $dest = $upload_dir . $_FILES['audio']['name'];
                            move_uploaded_file($tmp, $dest);
                        }
                    }

                    $playlist = unserialize($_SESSION["Playlist"]);
                    $pdo = DeefyRepository::getInstance();
                    $id = $pdo->findLastIdTrack() + 1;
                    $track = new PodcastTrack($id, $name, "audio/" . $_FILES['audio']['name']);

                    $playlist->addPiste($track);
                    $_SESSION['Playlist'] = serialize($playlist);

                    $pdo->saveTrack($track);
                    $pdo->addTrackToPlaylist($id, $playlist->__get("ID"));

                    $html = <<<FIN
                        <div> Ajout de $name réussi </div>
                        <a href="?action=add-track">Ajouter un nouveau morceau</a>
                    FIN;
                }
            } else {
                $html = "<div> Nom invalide </div><a href='?action=add-track'>Ajouter un nouveau morceau</a>";
            }

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
                        </style>
                    </head>
                    <body>
                        $html
                    </body>
                </html>
            FIN;
        }
    }
}

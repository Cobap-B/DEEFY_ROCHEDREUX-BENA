<?php
namespace deefy\action;
use \deefy\audio\list\Playlist; 
use \deefy\audio\track\PodcastTrack; 
use \deefy\repository\DeefyRepository;

class AddPodcastTrackAction extends Action{
    public function execute(){
        if ($this->http_method === "GET"){
            return <<<FIN
                <div>Ajouter un audio : </div>

                <form action="?action=add-track" method="post" enctype="multipart/form-data">
                    Nom : <input type="text" name="nom">
                    <br>
                    File : <input type="file" name="audio" accept="audio/mpeg"/>


                    <input type="submit" value"Creer">
                </form>
            FIN;
        }else{
            $html = "";
            if (! isset($_SESSION["User"])){
                $html = "<div> Il faut un compte </div>".'<a href="?action=authentification">Authentification</a>';
            }elseif ((isset($_POST["nom"]) && (isset($_FILES['audio'])))){
                $name = filter_var($_POST["nom"], FILTER_SANITIZE_STRING);

                if (! isset($_SESSION["Playlist"])){
                    $html ="<div> Aucune playlist créer  </div>".'<a href="?action=add-playlist">Add playlist</a>';
                }else{

                    if($_FILES['audio']['type'] === 'audio/mpeg'){
                        $upload_dir ='audio/';
                        $tmp = $_FILES['audio']['tmp_name'];
                        echo $tmp;
                        if (($_FILES['audio']['error'] === UPLOAD_ERR_OK) &&
                            ($_FILES['audio']['type'] === 'audio/mpeg') ) {
                            $dest = $upload_dir.$_FILES['audio']['name'];
                            move_uploaded_file($tmp, $dest);
                        }
                    }
                    $playlist = unserialize($_SESSION["Playlist"]);
                    
                    $pdo =  DeefyRepository::getInstance();
                    $id = $pdo->findLastIdTrack()+1;
                    $track = new PodcastTrack($id, $name, "audio/".$_FILES['audio']['name']);
                    /*
                        $t->__set("artiste", filter_var($_POST['artiste'], FILTER_SANITIZE_STRING));
                        $t->__set("genre", filter_var($_POST['genre'], FILTER_SANITIZE_STRING));
                        $t->__set("duree", intval(filter_var($_POST['duree'], FILTER_SANITIZE_NUMBER_INT)));
                        $t->__set("annee", filter_var($_POST['date'], FILTER_SANITIZE_STRING));
                    */
                    

                    $playlist->addPiste($track);
                    $_SESSION['Playlist'] = serialize($playlist);

                    
                    $pdo->saveTrack($track);
                    $pdo->addTrackToPlaylist($id, $playlist->__get("ID"));

                    $html = <<<FIN
                                <div> Ajout de $name fait </div>
                            FIN .'<a href="?action=add-track">Add new track</a>';
                    
                }
            }else{
                $html = "<div> Nom invalide </div>".'<a href="?action=add-track">Add new track</a>';
            }
            
        
            return $html;
        }
    }
}
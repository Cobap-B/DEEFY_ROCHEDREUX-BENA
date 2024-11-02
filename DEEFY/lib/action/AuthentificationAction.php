<?php
namespace deefy\action;
use \deefy\repository\DeefyRepository;
use \deefy\exception\AuthentificationException;

class AuthentificationAction extends Action{


    public function execute(){
        if ($this->http_method === "GET"){
            return <<<FIN
                <div>Authentification : </div>

                <form action="?action=authentification" method="post">
                    User : <input type="text" name="nom"><br>
                    Mdp : <input type="text" name="mdp"><br>
                    <input type="submit" value"Envoyer">
                </form>
            FIN;
        }else{
            $nom = filter_var($_POST['nom'], FILTER_SANITIZE_EMAIL);
            $mdp = $_POST['mdp'];

            $r = DeefyRepository::getInstance();

            $bool = false;
            try{
                $bool = $r->authentification($nom, $mdp);
                $res = "<p>Bienvenu $nom</p>";
            }catch(AuthentificationException $e){
                $res = "<p>Identifiant ou mot de passe invalide</p>";
            }

            
            return $res;
        }
        
    }
}
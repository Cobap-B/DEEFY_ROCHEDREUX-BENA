<?php
namespace deefy\action;
use \deefy\repository\DeefyRepository;

class SignAction extends Action{


    public function execute(){
        if ($this->http_method === "GET"){
            return <<<FIN
                <div>Sign in</div>

                <form action="?action=sign" method="post">
                    User : <input type="text" name="nom"><br>
                    Mdp : <input type="text" name="mdp"><br>
                    Mdp : <input type="text" name="mdp2"><br>
                    <input type="submit" value"Envoyer">
                </form>
            FIN;
        }else{
            $nom = filter_var($_POST['nom'], FILTER_SANITIZE_EMAIL);;
            $mdp = $_POST['mdp'];
            $mdp2 = $_POST['mdp2'];
            if($mdp === $mdp2){
                $r = DeefyRepository::getInstance();

                $res = "<p>".$r->signIn($nom, $mdp)."</p>";
            }else{
                $res = <<<FIN
                    <p>Mot de passe différent</p>
            
                    <div>Affichage de la page d'acceuil dans le cas GET</div>

                    <form action="?action=sign" method="post">
                        User : <input type="text" name="nom" value=$nom><br>
                        Mdp : <input type="text" name="mdp"><br>
                        Mdp : <input type="text" name="mdp2"><br>
                        <input type="submit" value"Envoyer">
                    </form>
                FIN;
            }

        
            return $res;
        }
        
    }
}
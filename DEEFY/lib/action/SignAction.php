<?php
namespace deefy\action;

class SignAction extends Action{


    public function execute(){
        if ($this->http_method === "GET"){
            return <<<FIN
                <div>Affichage de la page d'acceuil dans le cas GET</div>

                <form action="?action=sign" method="post">
                    User : <input type="text" name="nom"><br>
                    Mdp : <input type="text" name="mdp"><br>
                    <input type="submit" value"Envoyer">
                </form>
            FIN;
        }else{
            $nom = $_POST['nom'];
            $mdp = $_POST['mdp'];

            $r = DeefyRepository::getInstance();    
            //$hash=$r->getHash($);
            if (password_verify($password, $hash)) {
                echo "marche";
            }
            //add user
            
            return "<div>Affichage de la page d'acceuil dans le cas POST $x</div>";
        }
        
    }
}
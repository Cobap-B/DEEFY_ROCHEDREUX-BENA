<?php
namespace deefy\action;

class DefaultAction extends Action{


    public function execute(){
        if ($this->http_method === "GET"){
            return <<<FIN
                <div>Affichage de la page d'acceuil dans le cas GET</div>

                <form action="?action=default" method="post">
                    Nom : <input type="text" name="nom">
                    <input type="submit" value"Envoyer">
                </form>
            FIN;
        }else{
            $x = $_POST['nom'];
            return "<div>Affichage de la page d'acceuil dans le cas POST $x</div>";
        }
        
    }
}
<?php
namespace deefy\action;
use \deefy\repository\DeefyRepository;

class SignAction extends Action{


    public function execute(){
        if ($this->http_method === "GET"){
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
                        form {
                            display: inline-block;
                        }

                        label {
                            display: block;
                            float: left;
                            width : 150px;
                        }

                    </style> 
                    
                    <title> Compte </title>
                </head>
                <body>
                    <div>
                        <h2>Creer un compte</h2>
                        <form action="?action=sign" method="post">
                            <label>Identifiant :</label> <input type="text" name="nom"><br><br>
                            <label>Mot de passe :</label> <input type="password" name="mdp"><br><br>
                            <label>Confirmer Mot de passe :</label> <input type="password" name="mdp2"><br><br>
                            <br>
                            <input type="submit" value="Envoyer">
                        </form>
                    </div>
                </body>
            </html>
        FIN;
        } else {
            $nom = filter_var($_POST['nom'], FILTER_SANITIZE_EMAIL);
            $mdp = $_POST['mdp'];
            $mdp2 = $_POST['mdp2'];

            if($mdp === $mdp2){
                $r = DeefyRepository::getInstance();
                $res = $r->signIn($nom, $mdp);
                $res = "<p>$res</p>";
            } else {
                $res = <<<FIN
                <!DOCTYPE html>
                <html lang="fr">
                    <head>
                        <meta charset="UTF-8">
                        <style>
                            body {
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                height: 100vh;
                                background-color: #333;
                                color: white;
                                text-align: center;
                            }
                            form {
                                display: inline-block;
                            }
                        </style><title> Se connecter</title>
                    </head>
                    <body>
                        <p>Mot de passe différent</p>
                        <div>
                            <h2>Sign In</h2>
                            <form action="?action=sign" method="post">
                                User : <input type="text" name="nom" value="$nom"><br>
                                Mdp : <input type="password" name="mdp"><br>
                                Confirmer Mdp : <input type="password" name="mdp2"><br>
                                <input type="submit" value="Envoyer">
                            </form>
                        </div>
                    </body>
                </html>
            FIN;
            }

            return $res;
        }
    }

}
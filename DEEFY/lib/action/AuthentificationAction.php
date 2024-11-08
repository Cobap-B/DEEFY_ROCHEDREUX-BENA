<?php
namespace deefy\action;
use \deefy\repository\DeefyRepository;
use \deefy\exception\AuthentificationException;

class AuthentificationAction extends Action {

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

                            form {
                            display: inline-block;
                            }

                            label {
                            display: block;
                            float: left;
                            width : 150px;
                        }
                        </style>
                    </head>
                    <body>
                        <div> Se connecter : </div>
                        <form action="?action=authentification" method="post">
                            <label>Identifiant :</label> <input type="text" name="nom"><br>
                            <label>Mot de passe :</label> <input type="password" name="mdp"><br>
                            <br>
                            <input type="submit" value="Envoyer">
                            <br><br><br><br>
                            <input type="submit" name="Deco" value="Déconnexion">
                        </form>
                    </body>
                </html>
            FIN;
        } else {
            if (isset($_POST["Deco"])){
                session_destroy();
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
                        Déconnexion réussite !
                    </body>
                </html>
                FIN;
            }
            $nom = filter_var($_POST['nom'], FILTER_SANITIZE_EMAIL);
            $mdp = $_POST['mdp'];

            $r = DeefyRepository::getInstance();

            try {
                $r->authentification($nom, $mdp);
                $res = "<p style='color: white; text-align: center;'>Bienvenue $nom</p>";
            } catch (AuthentificationException $e) {
                $res = "<p style='color: white; text-align: center;'>Identifiant ou mot de passe invalide</p>";
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
                        $res
                    </body>
                </html>
            FIN;
        }
    }
}

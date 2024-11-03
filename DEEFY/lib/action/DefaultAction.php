<?php
namespace deefy\action;

class DefaultAction extends Action {

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
                        <div>Affichage de la page d'accueil dans le cas GET</div>

                        <form action="?action=default" method="post">
                            Nom : <input type="text" name="nom">
                            <input type="submit" value="Envoyer">
                        </form>
                    </body>
                </html>
            FIN;
        } else {
            $x = $_POST['nom'];
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
                        <div>Affichage de la page d'accueil dans le cas POST $x</div>
                    </body>
                </html>
            FIN;
        }
    }
}

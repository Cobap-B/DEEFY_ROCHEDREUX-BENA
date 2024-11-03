<?php
namespace deefy\action;

class DefaultAction extends Action {

    public function execute() {
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
                    <div>Bienvenu</div>
                    <a href="?action=authentification">Se connecter</a>
                </body>
            </html>
        FIN;
        
    }
}

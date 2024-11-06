<?php
declare(strict_types=1);
namespace deefy\render;


class PodcastTrackRender extends AudioTrackRender
{

    //Méthode pour le rendu format court
    public function renderCompact(): string
    {
        return '
        <div class="podcast compact">
            <ul>
                <li>Informations sur le podcast : <pre>' . $this->album->__toString() . '</pre></li>
                <li>
                    <audio controls>
                        <source src="' . $this->album->nomFichier . '" type="audio/mpeg">
                    </audio>
                </li>
            </ul>
        </div>';
    }

    //Méthode pour le rendu format long
    public function renderLong(): string
    {
        return '
        <div class="podcast long" style="width: 100%; height: 100vh; display: flex; align-items: center; justify-content: center;">
            <div>
                <h2>Infos sur le podcast :</h2>
                <pre>' . $this->album->__toString() . '</pre>
            </div>
            <audio controls style="width: 100%; max-width: 600px;">
                <source src="' . $this->album->nomFichier . '" type="audio/mpeg">
            </audio>
        </div>';
    }
}
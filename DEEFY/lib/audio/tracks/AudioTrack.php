<?php
declare(strict_types=1);
namespace deefy\audio\tracks;
use \deefy\exception\InvalidPropertyNameExcepetion;

abstract class AudioTrack{
    protected int $ID;
    protected string $titre;
    protected string $artiste;
    protected string $annee;
    protected string $genre; 
    protected int $duree;  
    protected string $nomFichier;

    public function __construct(int $id, string $titre, string $nom) {
        $this->ID = $id;
        $this->titre = $titre;
        $this->nomFichier = $nom;
        $this->artiste = "";
        $this->annee = "";
        $this->duree = 1;
        $this->genre = "";
    }

    function __toString(): string {
        return sprintf(
            "AudioTrack Titre: %s, Artiste: %s\n Année: %s\n Genre: %s\n Durée: %d sec\n Nom de fichier: %s",
            $this->titre,
            $this->artiste ?: "Inconnu",
            $this->annee ?: "Inconnue",
            $this->genre ?: "Non spécifié",
            $this->duree,
            $this->nomFichier
        );
    }


    public function  __get(string $at):mixed{
        if (property_exists ($this, $at)) return $this->$at;
        throw new InvalidPropertyNameExcepetion ("$at: invalid proprety");
    }

}
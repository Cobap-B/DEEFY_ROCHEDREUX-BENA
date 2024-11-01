<?php
declare(strict_types=1);
namespace deefy\audio\tracks;
use \deefy\exception\InvalidPropertyNameExcepetion;

abstract class AudioTrack{
    protected string $titre;
    protected string $artiste;
    protected string $année;
    protected string $genre; 
    protected int $duree;  
    protected string $nomFichier;

    public function __construct(string $titre, string $nom) {
        $this->titre = $titre;
        $this->nomFichier = $nom;
        $this->duree = 1;
    }

    function __toString():string{
        return  json_encode($this);
    }

    public function  __get(string $at):mixed{
        if (property_exists ($this, $at)) return $this->$at;
        throw new InvalidPropertyNameExcepetion ("$at: invalid proprety");
    }

}
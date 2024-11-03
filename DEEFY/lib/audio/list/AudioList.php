<?php
declare(strict_types=1);
namespace deefy\audio\list;
use \deefy\exception\InvalidPropertyNameExcepetion;

class AudioList{ 
    protected string $nom;
    protected int $taille;
    protected int $duree;
    protected array $list;
    protected int $ID;


    public function __construct(int $id, string $nom, array $tab = []) {
        $this->ID = $id;
        $this->nom = $nom;
        $this->taille = sizeof($tab);
        $d = 0;
        for ($i=0; $i < $this->taille; $i++) { 
            $d+= $tab[$i]->duree;
        }
        $this->duree = $d;
        $this->list = $tab;
        
    }

    function __toString():string{
        return  json_encode($this);
    }

    public function  __get(string $at):mixed{
        if (property_exists ($this, $at)) return $this->$at;
        throw new InvalidPropertyNameExcepetion ("$at: invalid proprety");
    }

}
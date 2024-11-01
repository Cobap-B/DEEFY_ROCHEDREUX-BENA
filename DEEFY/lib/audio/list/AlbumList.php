<?php
declare(strict_types=1);
namespace deefy\audio\list;

class AlbumList extends AudioList{ 
    protected string $artiste;
    protected string $date;
    


    public function __construct(string $nom, array $tab) {
        parent::__construct($nom, $tab);
    }

    public function setArtiste(string $a){
        $this->artiste = $a;
    }
    public function setDate(string $a){
        $this->date = $a;
    }

   

}
<?php
namespace deefy\repository;
use \deefy\audio\list\Playlist; 
use \deefy\exception\AuthentificationException;

use PDO;

class DeefyRepository{
    private ?PDO $pdo;
    private static ?DeefyRepository $instance = null;
    private static array $config = [ ];
    
    private function __construct(array $conf) {
        $this->pdo = new PDO($conf['dsn'], $conf['user'], $conf['pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }
    public static function getInstance(){
        if (is_null(self::$instance)) {
            self::$instance = new DeefyRepository(self::$config);
        }
        return self::$instance;
    }
    public static function setConfig(string $file) {
        $conf = parse_ini_file($file);
        if ($conf === false) {
            throw new \Exception("Error reading configuration file");
        }
        $driver = $conf['driver'];
        $host = $conf['host'];
        $database = $conf['database'];
        $dsn = "$driver:host=$host;dbname=$database;charset=utf8";

        self::$config = [ 'dsn'=>$dsn,'user'=> $conf['username'],'pass'=> $conf['password'] ];
    }



    public function signIn(string $user, string $mdp){
        $res = "Echec";
        $min = 10;


        $bd = $this->pdo;
        $r = $bd->prepare('SELECT passwd FROM User WHERE email = ?');
        $r->bindParam(1,$user);
        $r->execute();
        $d = $r->fetchall(PDO::FETCH_ASSOC);

        if((strlen($mdp) >= $min)&&(sizeof($d)==0)){
    
            $hash = password_hash($mdp, PASSWORD_DEFAULT,['cost'=>10]); //hash
        
            $insert = "INSERT into user (email, passwd) values(?,?)";
            $r = $bd->prepare($insert);
            $r->bindParam(1,$user);
            $r->bindParam(2,$hash);
            
            if($r->execute()){
                $res = "Bienvenu $user";
            }
        }
        
        //A changer
        $data['role'] = 1;


        $_SESSION['User']['name']=$user;
        $_SESSION['User']['role']=$data['role'];
        return $res;
    }

    public function authentification(string $user, string $mdp){
        $bd = $this->pdo;

        $r = $bd->prepare("SELECT passwd, role from User where email = ? ");
        $r->bindParam(1,$user);
        $bool = $r->execute();
        $data =$r->fetch(PDO::FETCH_ASSOC);
        $hash=$data['passwd'];
        if (!password_verify($mdp, $hash)&&$bool)throw new AuthentificationException("Mot de passe Incorrect");

        $_SESSION['User']['name']=$user;
        $_SESSION['User']['role']=$data['role'];
    }



    //FONCTION A MODIF
    public function findPlaylistById(int $id): array
    {
        $stmt = self::getInstance()->prepare('SELECT * FROM playlist WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAllPlaylists(): array {
        $stmt = self::getInstance()->prepare("SELECT * FROM playlist");
        $stmt->execute();

        $playlists = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Assure-toi que le constructeur de Playlist prend bien id et name
            $playlist = new Playlist($row['id'], $row['nom']);
            $playlists[] = $playlist;
        }
        return $playlists;
    }


    public function saveEmptyPlaylist(Playlist $playlist): bool {
        $stmt = self::getInstance()->prepare(
            "INSERT INTO playlist (nom, description) VALUES (:name, :description)"
        );
        $stmt->bindValue(':nom', $playlist->getNom(), PDO::PARAM_STR);
        $stmt->bindValue(':description', $playlist->getDescription(), PDO::PARAM_STR); // Ajout de description
        return $stmt->execute();
    }


    public function saveTrack(AudioTrack $track): bool
    {
        $pdo = self::getInstance();

        $stmt = $pdo->prepare("INSERT INTO track (titre, genre, duree, nomFichier) VALUES (:titre, :genre, :duree, :nomFichier)");

        $stmt->bindValue(':titre', $track->getTitre(), PDO::PARAM_STR);
        $stmt->bindValue(':genre', $track->getGenre(), PDO::PARAM_INT);
        $stmt->bindValue(':duree', $track->getDuree(), PDO::PARAM_STR);
        $stmt->bindValue(':filename', $track->getNomFichier(), PDO::PARAM_STR);

        return $stmt->execute();
    }


    public function addTrackToPlaylist(int $trackId, int $playlistId): bool
    {
        $pdo = DeefyRepository::getInstance();

        $stmt = $pdo->prepare("INSERT INTO playlist2track (id_pl, id_track) VALUES (:playlistId, :trackId)");

        $stmt->bindValue(':id_pl', $playlistId, PDO::PARAM_INT);
        $stmt->bindValue(':id_track', $trackId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    
}
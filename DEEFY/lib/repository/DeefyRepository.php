<?php
namespace deefy\repository;
use \deefy\audio\list\Playlist; 
use \deefy\exception\AuthentificationException;
use \deefy\audio\track\PodcastTrack;

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

        $bd = $this->pdo;

        $r = $bd->prepare('SELECT passwd FROM User WHERE email = ?');
        $r->bindParam(1,$user);
        $r->execute();
        $d = $r->fetchall(PDO::FETCH_ASSOC);

        if((strlen($mdp) >= 10)&&(sizeof($d)==0)
        && preg_match("#[\d]#", $mdp)
        && preg_match("#[\W]#", $mdp)
        && preg_match("#[\a-z]#", $mdp)
        && preg_match("#[\A-Z]#", $mdp)){
    
            $hash = password_hash($mdp, PASSWORD_DEFAULT,['cost'=>10]); //hash
        
            $insert = "INSERT into user (email, passwd) values(?,?)";
            $r = $bd->prepare($insert);
            $r->bindParam(1,$user);
            $r->bindParam(2,$hash);
            
            if($r->execute()){
                $res = "Bienvenu $user";
            }

            
            $data['role'] = 1;
            $id = $bd->lastInsertId();
            $_SESSION['User']['id']=$id;
            $_SESSION['User']['name']=$user;
            $_SESSION['User']['role']=$data['role'];
        }
        
        
        return $res;
    }

    public function authentification(string $user, string $mdp){
        $bd = $this->pdo;

        $r = $bd->prepare("SELECT id, passwd, role from User where email = ? ");
        $r->bindParam(1,$user);
        $bool = $r->execute();
        $data = $r->fetch(PDO::FETCH_ASSOC);
        if (! isset($data) || $data == false) throw new AuthentificationException("Mail pas bon");
        $hash=$data['passwd'];
        if (!password_verify($mdp, $hash)&&$bool)throw new AuthentificationException("Mot de passe Incorrect");

        $_SESSION['User']['id']=$data['id'];
        $_SESSION['User']['name']=$user;
        $_SESSION['User']['role']=$data['role'];
    }


    //Pas la meilleur facon de faire
    //Utiliser LastInsertId !!!
    public function findLastIdTrack():int{
        $stmt = $this->pdo->prepare('SELECT max(id) as ID from track');
        $stmt->execute();
        return $stmt->fetch()['ID'];
    }

    public function findLastIdPlaylist():int{
        $stmt = $this->pdo->prepare('SELECT max(id) as ID from playlist');
        $stmt->execute();
        return $stmt->fetch()['ID'];
    }


    public function findPlaylistById(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM playlist WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();
        $id_pl = $row['id'];
        $name_pl = $row['nom'];

        $stmt = $this->pdo->prepare("SELECT id, titre, filename FROM track INNER JOIN playlist2track on track.id = playlist2track.id_track where playlist2track.id_pl = :id");
        $stmt->bindValue(':id', $id_pl, PDO::PARAM_INT);
        $stmt->execute();

        $tracks = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $track =  $track = new PodcastTrack($row['id'], $row['titre'], "audio/" . $row['filename']);
            $tracks[] = $track;
        }
        $pl = new Playlist($id_pl, $name_pl, $tracks);


        return $pl;
    }

    public function findAllPlaylists(): array {
        $stmt;
        if ($_SESSION['User']['role'] == 100){
            $stmt = $this->pdo->prepare("SELECT * FROM playlist"); 
        }else{
            $stmt = $this->pdo->prepare("SELECT * FROM playlist INNER JOIN user2playlist on playlist.id = user2playlist.id_pl where user2playlist.id_user = :id_user"); 
            $stmt->bindValue(':id_user', $_SESSION['User']['id'], PDO::PARAM_INT);  
        }   
       
        $stmt->execute();
        
        $playlists = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $playlist = new Playlist($row['id'], $row['nom']);
            $playlists[] = $playlist;
        }
        return $playlists;
    }


    public function saveEmptyPlaylist(Playlist $playlist): bool {
        $stmt = $this->pdo->prepare(
            "INSERT INTO playlist (id, nom) VALUES (:id, :nom)"
        );
        $stmt->bindValue(':id', $playlist->__get("ID"), PDO::PARAM_INT);
        $stmt->bindValue(':nom', $playlist->__get("nom"), PDO::PARAM_STR);
        $stmt->execute();

        $stmt = $this->pdo->prepare(
            "INSERT INTO user2playlist (id_user, id_pl) VALUES (:id_user, :id_pl)"
        );
        $stmt->bindValue(':id_user',$_SESSION['User']['id'], PDO::PARAM_INT);
        $stmt->bindValue(':id_pl',$playlist->__get("ID"), PDO::PARAM_INT);
        return $stmt->execute();
    }


    public function saveTrack($track): bool
    {
        $pdo = $this->pdo;

        $stmt = $pdo->prepare("INSERT INTO track (id, titre, genre, duree, filename) VALUES (:id, :titre, :genre, :duree, :filename)");

        $stmt->bindValue(':id', $track->__get("ID"), PDO::PARAM_INT);
        $stmt->bindValue(':titre', $track->__get("titre"), PDO::PARAM_STR);
        $stmt->bindValue(':genre', $track->__get("genre"), PDO::PARAM_INT);
        $stmt->bindValue(':duree', $track->__get("duree"), PDO::PARAM_STR);
        $stmt->bindValue(':filename', $track->__get("nomFichier"), PDO::PARAM_STR);

        return $stmt->execute();
    }


    public function addTrackToPlaylist(int $trackId, int $playlistId): bool
    {
        $pdo = $this->pdo;

        $stmt = $pdo->prepare("INSERT INTO playlist2track (id_pl, id_track) VALUES (:playlistId, :trackId)");

        $stmt->bindValue(':playlistId', $playlistId, PDO::PARAM_INT);
        $stmt->bindValue(':trackId', $trackId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function supPlaylist(int $playlistId): bool
    {
        $pdo = $this->pdo;

        $stmt = $pdo->prepare("SELECT id_track from playlist2track where id_pl = :id_pl");
        $stmt->bindValue(':id_pl', $playlistId, PDO::PARAM_INT);
        $stmt->execute();
        $ar = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("DELETE from playlist2track where id_pl = :id_pl");
        $stmt->bindValue(':id_pl', $playlistId, PDO::PARAM_INT);
        $stmt->execute();
        if ($ar){
            foreach($ar as $id){
                $stmt = $pdo->prepare("DELETE from track where id =:id");
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        $stmt = $pdo->prepare("SELECT id_user as user from user2playlist where id_pl = :id_pl");
        $stmt->bindValue(':id_pl', $playlistId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch()["user"];

        $stmt = $pdo->prepare("DELETE from user2playlist where id_user = :id_user and id_pl = :id_pl");
        $stmt->bindValue(':id_user', $user, PDO::PARAM_INT);
        $stmt->bindValue(':id_pl', $playlistId, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $pdo->prepare("DELETE from playlist where id = :id_pl");
        $stmt->bindValue(':id_pl', $playlistId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    
}
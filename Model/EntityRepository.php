<?php

namespace Model;

class EntityRepository{
    // entity représente les tables en BDD
    // repository représente les classes qui vont servir à faire les requetes liées à l'action de l'utilisateur

    // propriété qui va stocker les informations liées à la connexion à la BDD (objet pdo de la classe PDO)
    private $pdo;
    // propriété qui va stocker le nom de la table de la BDD
    public $table;
    // si plusieurs tables dans la BDD, en declarer autant
    // public $table2;
    // public $table3;
    // public $table4;

    // méthode qui va détailler la procédure pour se connecter à la BDD
    public function getPdo(){

        // si l'objet courant ($this) ne pointe pas vers $pdo, cela veut dire que je ne suis pas connecté, je coderai donc dans le if, comment se connecter
        if(!$this->pdo){
            // première étape, récupérer les infos stcokées dans le config.xml. Le test se fait dans un try/catch
            try{
                // fonction prédéfinie (simplexml_load_file) qui va permettre d'extraire les informations codées dans un fichier xml
                // je lui donne en argument le chemin vers ce fichier
                // je stocke ses infos dans une variable $xml
                $xml = simplexml_load_file('App/config.xml');
                // echo "<pre>"; print_r($xml) ;echo "</pre>";
                // je stocke dans la propriété table (en pointant avec $this) le résultat stocké dans la variable $xml, qui pointe vers la balise table
                $this->table = $xml->table;

                // je teste la connexion à la BDD dans un bloc try
                try{
                    // en pointant avec this sur pdo, je stocke la connexion dans la propriété pdo ligne 8 de ce fichier
                    // j'utilise à nouveau $xml pour récupérer les valeurs stockées dans chaque balise du config.xml
                    // dnas le array, je définis le mode Exception pour recevoir les erreurs, puis l'encodage en UTF8
                    $this->pdo = new \PDO("mysql:host=$xml->host; dbname=$xml->dbname", "$xml->user", "$xml->password", array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8'));
                    // echo "Connexion réussie";
                }
                // Je travaille désormais avec PDO Exception, plus spécifique à la classe PDO
                catch(\PDOException $erreur){
                    echo "Erreur: " . $erreur->getMessage() . ". Le problème provient peut-etre des données insérées dans le config.xml <br> Erreur liée à la ligne " . $erreur->getLine() . " dans le fichier " . $erreur->getFile() ;
                }



            }
            catch(\Exception $erreur){
                echo "Impossible d'extraire les informations du fichier config.xml.<br>
                Erreur: " . $erreur->getMessage() . "<br> Il ya une erreur ligne " . $erreur->getLine() . " dans le fichier " . $erreur->getFile() . '<br>';
            }
        }
        // si au contraire $htis peut pointer sur $pdo, alors cela veut dire que je suis connecté, alors je ne fais que retourner le résultat stocké dans $pdo
        return $this->pdo;
    }

    // méthode qui va afficher tous les employés, qui va necessiter la requete SELECT *
    public function selectAllEntityRepo(){
        // $data va récupérer le résultat du query select sur la table employe($this->table)
        $data = $this->getPdo()->query("SELECT * FROM $this->table");
        // $afficheEmployes récupère le résultat du fetchAll (qui va m'économiser une ligne plus tard, mais pas très conseillé, moins performant qu'un fetch si la BDD est exhaustive)
        $afficheEmployes = $data->fetchAll(\PDO::FETCH_ASSOC);
        return $afficheEmployes;
    }

    public function saveEntityRepo(){
        // echo "J'affiche un formulaire d'ajout ou de modification";

        if(isset($_GET['id'])){
            // si un indice id existe dans l'URL, je récupère sa valeur et l'affecte à la variable $id
            $id = $_GET['id'];
            // cet id servira pour modifier un profil, avec un REPLACE
            // je fais référence à cet id dans la requete pour cibler l'employé dont le profil va etre modifié
            // implode(array_keys permet de récupérer les noms des colonnes dans la requete (via le formulaire) en vue de la modification
            // le second implode permet de récupérer chque valeur renseigné dans le formulaire)
            $data = $this->getPdo()->query("REPLACE INTO " . $this->table . "(id" . ucfirst($this->table) . "," . implode(',' , array_keys($_POST)) . ") VALUES (" . $id . ",". "'" . implode("','" , $_POST ). "'" . ")" );
        }else{
            // si pas d'id dans la requete, c'est une requete d'insertion, sans faire référence à un id (quin'existe pas encore en BDD)
            $data = $this->getPdo()->query("INSERT INTO " . $this->table . "(" . implode("," , array_keys($_POST)) . ") VALUES (" . "'" . implode("','" , $_POST) . "'" . ")" );
        }
    }

    // méthode qui requiert en argument l'id de l'employé dont elle doit afficher le profil
    public function selectEntityRepo($id){
        // echo "J'affiche un seul employé";
        $data = $this->getPdo()->query("SELECT * FROM $this->table WHERE id" . ucfirst($this->table) . "=" . (int) $id);
        $afficheUnEmploye = $data->fetch(\PDO::FETCH_ASSOC);
        return $afficheUnEmploye;

    }
    // prend en argument l'id de l'employé qui va etre supprimé du fichier 
    public function deleteEntityRepo($id){
        // echo "suppression de la fiche de l'employé";
        // requete Delete simple, qui pointe sur l'id de l'employé
        $this->getPdo()->query("DELETE FROM " . $this->table . " WHERE id" .(ucfirst($this->table)) . "= " . (int) $id );
    }

    public function getFields(){
        // DESC est pour Description, concaténé a la table employe
        $data = $this->getPdo()->query("DESC " . $this->table);
        $afficheEntetes = $data->fetchAll(\PDO::FETCH_ASSOC);
        return $afficheEntetes;
    }

}
// instanciation de la classe pour pointer ensuite sur la méthode getPdo pour vérifier si je récupère les infos stcokées dans le config.xml
// $et = new EntityRepository;
// $et->getPdo();
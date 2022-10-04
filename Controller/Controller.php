<?php

namespace Controller;

class Controller{

    // propriété qui va stocker toutes les requetes qui vont etre codée dans le fichier EntityRepository.php, pour pouvoir les utiliser dans le Controller
    private $dbEntityRepository;

    public function __construct(){
        // echo "Instanciation de la classe ok";

        // pour que dbEntityRepository stocke au fur et a mesure tout ce qui est codé dans la classe EntityRepository, on poite dessus avec $this, et on affecte le résultat de l'instanciation de cette classe.
        // comme $pdo avait stocké le résultat de la connexion à la BDD, toutes les infos codées dans la classe seront stockées dans dbEntityRepository
        // le code ci dessous est a relier avec le fichier index.php dans lequel on instancie la classe Controller. Une fois instanciée, son constructeur s'auto-exécute et instancie à son tour la classe EntityRepository
        $this->dbEntityRepository = new \Model\EntityRepository;
    }

    public function redirect($location){
        header('location: ' . $location);

    }

    public function handleRequest(){
        // ternaire pour savoir si un indice choixUser a été passé dans l'URL, et si oui, on affecte son résultat dans la variable $choixUser (dans le cas contraire, on affecte rien)
        $choixUser = isset($_GET['choixUser']) ? $_GET['choixUser'] : NULL;

        // l'équivalent de la ternaire du dessus avec une condition 'classique'
        // if(isset($_GET['choixUser'])){
        //     $choixUser = $_GET['choixUser'];
        // }else{
        //     $choixUser = '';
        // }

        try{
            // si dans l'URL l'indice choixUser contient add ou update
            if($choixUser == 'add' || $choixUser == 'update')
                // l'objet courant pointera vers la méthode save (qui prend en argument $choixUser) 
                $this->save($choixUser);
            // si choixUser est select 
            elseif($choixUser == 'select')
                // $this pointera vers la méthode select
                $this->select();
            // si choixUser est egal à delete etc... 
            elseif($choixUser == 'delete')
                $this->delete();
            else
                // pour le else, pas de choixUser dans l'URL, on pointe vers la méthode selectAll, celle qui va afficher tous les employés dans un tableau
                $this->selectAll();
        }

        catch(\Exception $erreur){
            echo "Impossible de récupérer cette action de l'utilisateur. Erreur: " . $erreur->getMessage() . "<br> Erreur à la ligne " . $erreur->getLine() .  " dans le fichier " . $erreur->getFile();
        }

    }

    // méthode pour afficher les éléments sur les vues
    // prend trois arguments. Le layout global, le template particulier puis les parametres (dans un array) destinés a s'aficher dans le template
    public function render($layout, $template, $parametres = array()){

        // fonction prédéfinie qui va permettre d'appeler directement l'indice du tableau sans passer par le nom du tableau (eg: $employe plutot que $parametres['employe'])
        extract($parametres);

        // début du processus de mise en mémoire tampon des différents éléments qui vont devoir s'afficher sur le layout gabarit final
        ob_start();
        // récupère le contenu injecté dans le template
        require ("view/$template");
        // contenu que j'affecte à la variable $content (avec ob_get_clean, qui ensuite va effacer ça de la mémoire temporaire, en attente de contenu supplémetaire)
        $content = ob_get_clean();
        // aurait pu etre l'équivalent de la syntaxe ci-dessous, sauf que codé de cette manière, $content ne récupérera qu'une chaine de caractères et non un contenu (de $template)
        // $content = "view/$template";

        // suite du processus de mise en mémoire tampon, en appelant le layout
        ob_start();
        require ("view/$layout");

        // une fois récupéré tous les différents contenus, ob_end_flush libère ce même contenu à destination du navigateur
        return ob_end_flush();

    }

    public function selectAll(){
        // echo "J'affiche tous les employés";
        // pour récupérer le résultat de la requete select all codée dans EntityRepository.php j'utilise $this->dbEntityRepository qui stocke tout les résultats des requetes de cette classe, dbEntityRepository pointant ensuite vers la méthode selectAll()
        // $resultat = $this->dbEntityRepository->selectAllEntityRepo();
        // rapide affichage non-conventionnel pour voir si je récupère tous les employés de la table, avant de tenter un affichage plus ambitieux
        // echo "<pre>"; print_r($resultat) ;echo "</pre>";

        // pour afficher tous les employés (selectAll) pour la méthode render je renseigne les trois arguments suivants
        // layout.php pour $layout
        // affichage_employes.php pour $template
        // un tableau avec des valeurs pour $parametres (qui est un array)
        $this->render('layout.php','affichage_employes.php',[
            // un indice title auquel je relie sa valeur
            'title' => "Affichage des employés",
            // un second indice data, qui récupèrera tout ce qui est stocké dans la méthode selectAllEntityRepo (dispo dans $dbEntityRepository)
            'data' => $this->dbEntityRepository->selectAllEntityRepo(),
            'fields' => $this->dbEntityRepository->getFields(),
            'id' => 'id' . ucfirst(($this->dbEntityRepository->table))
        ]);
    }

    public function save($choixUser){
        //echo "J'affiche un formulaire d'ajout ou de modification";
        $id = isset($_GET['id']) ? $_GET['id'] : NULL;
        $values  = ($choixUser == 'update') ? 
        $this->dbEntityRepository->selectEntityRepo($id) : NULL ;

        if($_POST){
            $this->dbEntityRepository->saveEntityRepo();
            $this->redirect('index1.php');
        }
        $this->render('layout.php', 'contact_form.php', [
            'title' => "Formulaire",
            'choixUser' => $choixUser,
            'fields' => $this->dbEntityRepository->getFields(),
            'values' => $values
        ]);
    }

    public function select(){
        //echo "J'affiche un seul employé";
        $id = isset($_GET['id']) ? $_GET['id'] : NULL;
        $this->render('layout.php','fiche_employe.php',[
            // un indice title auquel je relie sa valeur
            'title' => "Profil de ",
            // un second indice data, qui récupèrera tout ce qui est stocké dans la méthode selectAllEntityRepo (dispo dans $dbEntityRepository)
            'data' => $this->dbEntityRepository->selectEntityRepo($id)
        ]);
    }

    public function delete(){
        //echo "suppression de la fiche de l'employé";
        $id = isset($_GET['id']) ? $_GET['id'] : NULL;
        $this->dbEntityRepository->deleteEntityRepo($id);
        $this->redirect('index1.php');
    }
    ///public function getFields(){
        //$data = $this->getPdo->query("DESC " . $this->table);
    //}
}
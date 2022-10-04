<?php

class Autoload{

    // je mets cette méthode en static pour qu'elle appartienne à la classe et non à un éventuel objet si on instancie la classe Autoload
//inclusionAuto reçoit via spl_autoload_register l'argument suivant Controller\Controller, stocké dans $className
    public static function inclusionAuto($className){
        // j'utilise str_replace pour modifier tous les anti-slash en slash. Pour ceux qui naviguent sur Mac, c'est une obligation, sinon erreur PHP. Pour Windows, pas de pbs, mais comme on doit prendre en compte tous les cas de figure (attention, deux anti-slash...caractère d'echappement) ...
        require_once __DIR__ . '/' . str_replace('\\', '/', $className .'.php') ;
        //echo __DIR__ . '/' . str_replace('\\', '/', $className .'.php') ;
    } 
}

// Dans un tableau array, en premier argument de spl_autoload_register, je donne le nom de la classe Autoload, puis le nom de la fonction utilisateur qui sera codée dans cette classe
spl_autoload_register(array('Autoload', 'inclusionAuto'));

// instanciation de la classe Conroller, dans le namespace Controller
// $controller = new Controller\Controller;

// echo __DIR__ . '<br>';
// echo __FILE__;
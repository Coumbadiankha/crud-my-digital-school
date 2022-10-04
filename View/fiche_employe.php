<?php
// echo "<pre>"; print_r($data) ;echo "</pre>";
?>

<h2 class="text-center pb-5"><?= ucfirst($data['prenom']) . " " . ucfirst($data['nom']) ?></h2>

<!-- syntaxe rapide avec une boucle pour récupérer toutes les valeurs liés a cet employé -->
<?php foreach($data as $key => $value): ?>
    <div><span><?= $key ?></span> : <span><?= $value ?></span></div>
<?php endforeach; ?>

<!-- syntaxe plus longue, pour mieurx formater la récupération des valeurs -->

<div class="col-6 shadow p-5 my-5">
<p>Prénom : <?= ucfirst($data['prenom']) ?></p>
<p>Nom : <?= ucfirst($data['nom']) ?></p>
<p>Sexe : <?= $data['sexe'] ?></p> 
<p>Service : <?= $data['service'] ?></p>
<!-- pour formater la date d'embauche (format France), j'affecte la valeur en BDD dans une variable $date, pour ensuite la travailler -->
<?php $date = $data['dateEmbauche'] ?>
<p>Date d'embauche : <?= date('d/m/Y', strtotime($date)) ?></p>
<p>Salaire : <?= $data['salaire'] ?> €/mois</p>
<p>Secteur : <?= $data['idSecteur'] ?></p>
</div>

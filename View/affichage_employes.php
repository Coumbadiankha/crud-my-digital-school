<?php

//echo "<pre>";  print_r($data) ;echo "</pre>";

?>

<table class="table table-dark text-centre my-5">
   <thead>
        <tr>
            <?php foreach($fields as $value): ?>
            <th><?= $value['Field'] ?></th>
            <?php endforeach; ?>
            <th>Voir</th>
            <th>Modifier</th>
            <th>Supprimer</th>
        </tr>
   </thead>
   <tbody>
    <?php foreach($data as $dataEmployes): ?>
        <tr>
            <td><?= implode('</td><td>', $dataEmployes) ?></td>
            <td><a href="?choixUser=select&id=<?= $dataEmployes[$id] ?>" class="btn btn-success"><i class="bi bi-eye"></i></td>
            <td><a href="?choixUser=update&id=<?= $dataEmployes[$id] ?>" class="btn btn-warning"><i class="bi bi-pencil"></i></td>
            <td><a href="?choixUser=delete&id=<?= $dataEmployes[$id] ?>" class="btn btn-danger"><i class="bi bi-trash"></i></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

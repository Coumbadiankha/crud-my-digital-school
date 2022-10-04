<h2 class="text-center"><?= ($choixUser == "add") ? " d'ajout d'employé" : "de modification de profil" ?></h2>

<form class="col-3 my-5" method="POST" action="">
    <?php foreach($fields as $field): ?>
        <?php if($field['Field'] != 'idEmploye'): ?>
            <div class="mb-3">
                <label class="form-label" for="<?= $field['Field'] ?>"><?= $field ['Field'] ?></label><br>
                <input class="form-control" id="<?= $field['Field'] ?>" name="<?=$field['Field'] ?>" type="text" value="<?= ($choixUser == 'update') ? $values[$field['Field']] : '' ; ?>">
                
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
    <button type= "submit" class="btn btn-primary mb-3">Confirm identity</button>
</form>
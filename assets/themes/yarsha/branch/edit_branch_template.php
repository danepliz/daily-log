<?php
$id = '';
$name = '';
$description = '';
$enabled = 'checked="checked"';

if( isset($branch) and !is_null($branch) ){
    $id = $branch->id();
    $name = $branch->getName();
    $description = $branch->getDescription();
    if( !$branch->isActive() ){
        $enabled = '';
    }
}
?>

<input type="hidden" name="id" value="<?php echo $id ?>" />

<?php

echo inputWrapper('name', 'Name', $name, 'class="form-control required" placeholder="group name"');
echo textAreaWrapper('description', 'Description', $description, 'class="form-control" placeholder="add description"');

?>



<div class="form-group-sm">
    <label for="status">Enabled</label>
    <div class="col-md-12">
        <input type="checkbox" class="simple" value="1" <?php echo $enabled ?> name="status" />
    </div>
</div>
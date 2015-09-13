<?php
$id = '';
$name = '';
$description = '';

if( isset($grade) and !is_null($grade) ){
    $id = $grade->id();
    $name = $grade->getName();
    $description = $grade->getDescription();
}
?>

    <input type="hidden" name="id" value="<?php echo $id ?>" />

<?php

echo inputWrapper('name', 'Name', $name, 'class="form-control required" placeholder=" hotel grade name"');
echo textAreaWrapper('description', 'Description', $description, 'class="form-control" placeholder="add description"');

?>
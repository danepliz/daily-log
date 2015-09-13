<?php
$id = '';
$name = '';
$description = '';

if( isset($category) and !is_null($category) ){
    $id = $category->id();
    $name = $category->getName();
    $name = $category->getName();
    $name = $category->getName();
    $description = $category->getDescription();
}
?>

<input type="hidden" name="id" value="<?php echo $id ?>" />

<?php

echo inputWrapper('name', 'Name', $name, 'class="form-control required" placeholder="category name"');
echo textAreaWrapper('description', 'Description', $description, 'class="form-control" placeholder="add description"');

?>
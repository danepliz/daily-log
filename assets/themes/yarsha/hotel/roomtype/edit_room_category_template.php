<?php
$id = '';
$name = '';
$description = '';

if( isset($room_category) and !is_null($room_category) ){
    $id = $room_category->id();
    $name = $room_category->getName();
    $description = $room_category->getDescription();
}
?>

    <input type="hidden" name="id" value="<?php echo $id ?>" />

<?php

echo inputWrapper('category', 'Category', $name, 'class="form-control required" placeholder="Category"');
echo textAreaWrapper('description', 'Description', $description, 'class="form-control" placeholder="Add Description"');

?>
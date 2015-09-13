<?php
$id = '';
$name = '';
$description = '';

if( isset($service) and !is_null($service) ){
    $id = $service->id();
    $name = $service->getName();
    $description = $service->getDescription();
}
?>

    <input type="hidden" name="id" value="<?php echo $id ?>" />
    <input type="hidden" name="name_old" value="<?php echo $name?>"/>

<?php

echo inputWrapper('name', 'Name', $name, 'class="form-control required" placeholder="service name"');
echo textAreaWrapper('description', 'Description', $description, 'class="form-control" placeholder="add description"');

?>
<?php
$id = '';
$name = '';


if( isset($hs) and !is_null($hs) ){
    $id = $hs->id();
    $name = $hs->getName();

}
?>

    <input type="hidden" name="id" value="<?php echo $id ?>" />
    <input type="hidden" name="name_old" value="<?php echo $name?>"/>

<?php

echo inputWrapper('name', 'Name', $name, 'class="form-control required" placeholder="season name"');

?>
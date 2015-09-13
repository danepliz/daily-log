<?php
$id = '';
$name = '';
$description = '';

if( isset($room_plan) and !is_null($room_plan) ){
    $id = $room_plan->id();
    $name = $room_plan->getName();
    $description = $room_plan->getDescription();
}
?>

    <input type="hidden" name="id" value="<?php echo $id ?>" />

<?php

echo inputWrapper('plan', 'Plan', $name, 'class="form-control required" placeholder="Plan"');
echo textAreaWrapper('description', 'Description', $description, 'class="form-control" placeholder="Add Description"');

?>
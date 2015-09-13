<?php
$id = '';
$name = '';
$quantity ='';
$description = '';

if( isset($room_type) and !is_null($room_type) ){
    $id = $room_type->id();
    $name = $room_type->getName();
    $quantity = $room_type->getQuantity();
    $description = $room_type->getDescription();
}
?>

    <input type="hidden" name="id" value="<?php echo $id ?>" />

<?php

echo inputWrapper('type', 'Type', $name, 'class="form-control required" placeholder="Types"');
echo inputWrapper('quantity', 'No Of Pax', $quantity, 'class="form-control required number_only greaterThan[0]" placeholder="No Of Pax"', 'quantity');
echo textAreaWrapper('description', 'Description', $description, 'class="form-control" placeholder="Add Description"');

?>
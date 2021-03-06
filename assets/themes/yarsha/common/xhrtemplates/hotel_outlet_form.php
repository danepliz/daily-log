<?php

$name = "";
$description = "";
$readOnly = "";

if( isset($outlet) and !is_null($outlet)){

    $name = $outlet->getName();
    $description = $outlet->getDescription();

    $readOnly = 'readonly="readonly"';
}
?>



<div class="form-group-sm col-md-12">
    <label for="name">Name<em class="required">*</em></label>
    <input type="text" name="name" class="form-control required" value="<?php echo $name ?>" />
</div>

<div class="form-group-sm col-md-12">
    <label for="description">Description</label>
    <input type="text" name="description" class="form-control" value="<?php echo $description ?>" />
</div>

<div class="clear"></div>
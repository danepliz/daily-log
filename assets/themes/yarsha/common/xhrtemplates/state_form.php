<?php

$name = "";
$shortName = "";

if( isset($state) and !is_null($state)){

    $name = $state->getName();
    $shortName = $state->getShortName();

    $readOnly = 'readonly="readonly"';
}
?>

<div class="form-group">
    <label for="name">Name<em class="required">*</em></label>
    <input type="text" name="name" class="form-control required" value="<?php echo $name ?>" />
</div>

<div class="form-group">
    <label for="short_name">Short Name<em class="required">*</em></label>
    <input type="text" name="short_name" class="form-control required" value="<?php echo $shortName ?>" />
</div>
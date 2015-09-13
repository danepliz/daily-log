<?php

$name = "";

if( isset($city) and !is_null($city)){
    $name = $city->getName();
    $readOnly = 'readonly="readonly"';
}
?>

<div class="form-group">
    <label for="name">Name<em class="required">*</em></label>
    <input type="text" name="name" class="form-control required" value="<?php echo $name ?>" />
</div>

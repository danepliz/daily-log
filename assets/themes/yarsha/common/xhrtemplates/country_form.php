<?php

$name = "";
$nationality = "";
$iso3 = "";
$iso2 = "";
$dialing_code = "";

if( isset($country) and !is_null($country)){

    $name = $country->getName();
    $nationality = $country->getNationality();
    $iso3 = $country->getIso_3();
    $iso2 = $country->getIso_2();
    $dialing_code = $country->getDialingCode();

    $readOnly = 'readonly="readonly"';
}
?>

<div class="form-group">
    <label for="name">Name<em class="required">*</em></label>
    <input type="text" name="name" class="form-control required" value="<?php echo $name ?>" />
</div>

<div class="form-group">
    <label for="iso_2">Nationality<em class="required">*</em></label>
    <input type="text" name="nationality" class="form-control required" value="<?php echo $nationality ?>" />
</div>

<div class="form-group">
    <label for="iso_2">ISO 2<em class="required">*</em></label>
    <input type="text" name="iso_2" class="form-control required" value="<?php echo $iso2 ?>" />
</div>

<div class="form-group">
    <label for="iso_3">ISO 3<em class="required">*</em></label>
    <input type="text" name="iso_3" class="form-control required" value="<?php echo $iso3 ?>" />
</div>

<div class="form-group">
    <label for="dialing_code">Dialing Code</label>
    <input type="text" name="dialing_code" class="form-control" value="<?php echo $dialing_code ?>" />
</div>
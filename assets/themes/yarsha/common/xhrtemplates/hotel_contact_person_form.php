    <?php

$name = "";
$designation = "";
$address = "";
$skype = "";
$phone1 = "";
$phone2 = "";
$email1 = "";
$email2 = "";
$readOnly = "";

if( isset($person) and !is_null($person)){

    $name = $person->getName();
    $designation = $person->getDesignation();
    $address = $person->getAddress();
    $skype = $person->getSkype();
    $phones = $person->getPhones();
    $emails = $person->getEmails();

    $phone1 = (isset($phones[0])) ? $phones[0] : '';
    $phone2 = (isset($phones[1])) ? $phones[1] : '';
    $email1 = (isset($emails[0])) ? $emails[0] : '';
    $email2 = (isset($emails[1])) ? $emails[1] : '';

    $readOnly = 'readonly="readonly"';
}
?>



<div class="form-group-sm col-md-12">
    <label for="name">Name<em class="required">*</em></label>
    <input type="text" name="name" class="form-control required" value="<?php echo $name ?>" />
</div>

<div class="form-group-sm col-md-12">
    <label for="designation">Designation</label>
    <input type="text" name="designation" class="form-control" value="<?php echo $designation ?>" />
</div>

<div class="form-group-sm col-md-12">
    <label for="address">Address</label>
    <textarea name="address" class="form-control" ><?php echo $address ?></textarea>
</div>

<div class="form-group-sm col-md-12">
    <label for="designation">Skype Id</label>
    <input type="text" name="skype" class="form-control" value="<?php echo $skype ?>" />
</div>

<div class="form-group-sm col-md-6">
    <label for="phone1">Mobile</label>
    <input type="text" name="phone1" class="form-control number_only"  value="<?php echo $phone1 ?>" />
</div>

<div class="form-group-sm col-md-6">
    <label for="phone2">Phone/Mobile</label>
    <input type="text" name="phone2" class="form-control number_only" value="<?php echo $phone2 ?>" />
</div>

<div class="clear"></div>

<div class="form-group-sm col-md-6">
    <label for="email1">Email 1<em class="required">*</em></label>
    <input type="text" name="email1" class="form-control email required" value="<?php echo $email1 ?>" />
    <input type="hidden" name="email1_old" value="<?php echo $email1 ?>" />
</div>

<div class="form-group-sm col-md-6">
    <label for="email2">Email 2</label>
    <input type="text" name="email2" class="form-control email"  value="<?php echo $email2 ?>" />
    <input type="hidden" name="email2_old" value="<?php echo $email2 ?>" />
</div>

<div class="clear"></div>
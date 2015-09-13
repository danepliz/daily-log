<?php
$id = '';
$name = '';
$description = '';
$enabled = 'checked="checked"';
$currency = NULL;

if( isset($market) and !is_null($market) ){
    $id = $market->id();
    $name = $market->getName();
    $description = $market->getDescription();
    if( !$market->isActive() ){
        $enabled = '';
    }
    $currency= ( $market->getCurrency() )? $market->getCurrency()->id() : NULL;
}


echo 'kkkkkk'.$currency;
?>

<input type="hidden" name="id" value="<?php echo $id ?>" />

<?php

echo inputWrapper('name', 'Name', $name, 'class="form-control required" placeholder="group name"');
echo textAreaWrapper('description', 'Description', $description, 'class="form-control" placeholder="add description"');


?>

<div class="form-group-sm">
    <label for="currency">Currency</label>
    <?php getCurrencySelectElement('currency', $currency,'class="form-control"') ?>
</div>

<div class="form-group-sm">
    <label for="status">Enabled</label>
    <div class="col-md-12">
        <input type="checkbox" class="simple" value="1" <?php echo $enabled ?> name="status" />
    </div>
</div>
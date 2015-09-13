<?php
$id = '';
$name = '';
$description = '';
$symbol = '';
$iso_3 = '';


if( isset($currency) and !is_null($currency) ){
    $id = $currency->id();
    $name = $currency->getName();
    $description = $currency->getDescription();
    $symbol = $currency->getSymbol();
    $iso_3 = $currency->getIso3();
}
?>

<input type="hidden" name="id" value="<?php echo $id ?>" />

<?php

echo inputWrapper('name', 'Name', $name, 'class="form-control required" placeholder="Currency Name"');
echo textAreaWrapper('description', 'Description', $description, 'class="form-control" placeholder="Description"');
echo inputWrapper('iso_3', 'ISO 3 Code', $iso_3, 'class="form-control required" placeholder="ISO 3 Code eg. USD"');
echo inputWrapper('symbol', 'Symbol', $symbol, 'class="form-control" placeholder="Symbol eg. $"');
?>
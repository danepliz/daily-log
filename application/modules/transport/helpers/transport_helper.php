<?php

use transport\models\Transport;

function getTransportStatusSelectElement($name, $selected = Transport::TRANSPORT_STATUS_ACTIVE, $attributes){
    $statusArray = Transport::$transport_status;
    echo form_dropdown($name, $statusArray, $selected, $attributes);
}
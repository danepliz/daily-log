<?php
use hotel\models\Hotel;
?>

<style>
    table.print{
        font-size: 12px;
    }

    table.print table {
        margin-bottom: 0px;
    }

    table.print tr td, table.print tr th{
        vertical-align: middle;
        padding:0 0 0 1rem;
    }
</style>

<?php
$templates = array('hotel', 'agent');

if( user_access('view account copy') ){
    $templates[] = 'account';
}

$templateDetail = [
    'hotel' => [
        'printDetail' => 'HOTEL COPY',
        'isAccountCopy' => FALSE
    ],
    'agent' => [
        'printDetail' => 'AGENT/CLIENT COPY',
        'isAccountCopy' => FALSE
    ],
    'account' => [
        'printDetail' => 'ACCOUNT COPY',
        'isAccountCopy' => TRUE
    ]
];

$isForEmail = FALSE;

if(isset( $emailFor ) and $emailFor !== ""){
    $emailFor = ( $emailFor == 'client' )? 'agent' : $emailFor;
    $templates = array($emailFor);
    $isForEmail = TRUE;
}

$hasData = count($data);

$issuedTo = ($hasData)? $data[0]['hotel'] : '';
$pax = ($hasData)? $data[0]['pax'] : 0;
$children = ($hasData)? $data[0]['child'] : 0;
$infants = ($hasData)? $data[0]['infants'] : 0;
$market = ($hasData)? $data[0]['market'] : 0;
$client = ($hasData)? $data[0]['client'] : 0;
$nationality = ($hasData)? $data[0]['nationality'] : 0;
$fileNumber = ($hasData)? $data[0]['fileNumber'] : 0;
$arrivalDesc = ($hasData and $data[0]['arrivalDesc'] != '')? $data[0]['arrivalDesc'] : 'N/A';
$departureDesc = ($hasData and $data[0]['departureDesc'] != '')? $data[0]['departureDesc'] : 'N/A';
$description = ($hasData and $data[0]['description'] != '') ? $data[0]['description'] : 'N/A';
$confirmationNumber = ($hasData and $data[0]['confirmationNumber'] != '') ? $data[0]['confirmationNumber'] : 'N/A';

$accountTd = '<td class="account" style="border-bottom:1px solid #ccc">';
$accountTdPadding = '<td class="account" style="padding: 0.5rem">';
$tdOpen = '<td>';
$tdClose = '</td>';
$tableHeadPadding = '<th style="padding:0.5rem">';
$tableHeadClose = '</th>';




foreach($templates as $template){
    $printDetail = $templateDetail[$template]['printDetail'];
    $isAccountCopy = $templateDetail[$template]['isAccountCopy'];
    ?>
    <table class="table print bg-white" id="<?php echo $template ?>" style="width: 100%; max-width: 100%;
  margin-bottom: 20px; font-size: 12px; background: #ffffff;">
        <tr>
            <td style="vertical-align: middle; padding:0 0 0 1rem; border-bottom: 1px solid #ddd;" >
                <table class="table no-border" style="width: 100%; max-width: 100%; margin-bottom: 0px;">
                    <tr>
                        <td width="50%"><img src="<?php echo $logoSrc ?>" alt="Yetibilling" width="350" /></td>
                        <td style="vertical-align: middle"><strong><?php echo $printDetail ?></strong></td>
                        <td style="vertical-align: middle" width="40%" class="text-center">
                            <span style="font-size: 8px; letter-spacing: 2px"><?php echo 'TPIN '.Options::get('config_tpin',''); ?></span><br />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle; padding:0 0 0 1rem;">
                <table class="table no-border" style="width: 100%; max-width: 100%; margin-bottom: 0px;">
                    <tr>
                        <th width="15%">Issued To</th><td><?php echo $issuedTo ?></td>
                        <th width="15%">&nbsp;</th><td>&nbsp;</td>
                    </tr>

                    <tr>
                        <th>Client/Group</th><td><?php echo $client ?></td>
                        <th>File#</th><td><?php echo $fileNumber ?></td>
                    </tr>

                    <tr>
                        <th>Nationality</th><td><?php echo $nationality ?></td>
                        <th>Market</th><td><?php echo $market ?></td>
                    </tr>

                    <tr>
                        <th>Arrival Mode</th><td><?php echo $arrivalDesc ?></td>
                        <th>Departure Mode</th><td><?php echo $departureDesc ?></td>
                    </tr>

                    <tr>
                        <th>No. Of Pax</th>
                        <td colspan="3">
                            <?php
                            echo ( $children or $infants )
                                    ? $pax . ' ( '.$children.' child, '.$infants.' infants )'
                                    : $pax;
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Special Requirement</th>
                        <td colspan="3"><?php echo $description; ?></td>
                        <th>Confirmation Number</th>
                        <td colspan="3"><?php echo $confirmationNumber; ?></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="vertical-align: middle; padding:0 0 0 1rem; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd;">
                <table class="table no-border" style="width: 100%; max-width: 100%; margin-bottom: 0px;">
                    <tbody>

                    <?php
                        echo '<tr class="bg-gray" style="background: #eaeaec">';
                        echo $tableHeadPadding . 'SN' . $tableHeadClose;
                        echo $tableHeadPadding . 'Ex-Order' . $tableHeadClose;
                        echo $tableHeadPadding . 'Arrival Date' . $tableHeadClose;
                        echo $tableHeadPadding . 'Departure Date' . $tableHeadClose;
                        echo $tableHeadPadding . 'Descriptions' . $tableHeadClose;
                        if($isAccountCopy){
                            echo $accountTdPadding . 'Payable Amount' . $tdClose;
                            echo $accountTdPadding . 'Margin' . $tdClose;
                            echo $accountTdPadding . 'Billing Amount' . $tdClose;
                            echo $accountTdPadding . 'Remarks' . $tdClose;
                        }
                        echo '</tr>';

                        $count = 1;
                        $tbl = '';
                        foreach($data as $desc){
//                            show_pre($desc);
                            $descriptions = $desc['descriptions'];
                            $descCount = count($descriptions);
                            $bookingType = $desc['bookingType'];

                            $departureDate = ( $bookingType == Hotel::HOTEL_BOOKING_TYPE_SERVICE_BASIS )? '&nbsp;' : substr($desc['departureDate'], 0, 10);


                            $rowSpanTd = '<td rowspan="'.count($descriptions).'" style="border-bottom:1px solid #ccc">';

                            $tbl  .= '<tr>';

                            $tbl  .= $rowSpanTd.$count.$tdClose;
                            $tbl  .= $rowSpanTd.$desc['xoNumber'].$tdClose;
                            $tbl  .= $rowSpanTd.substr($desc['arrivalDate'], 0, 10).$tdClose;
                            $tbl  .= $rowSpanTd.$departureDate.$tdClose;

                            $descCount = 1;
                            foreach($descriptions as $d){
                                $bookingType = $d['bookingType'];
                                $main = $d['main'];
                                $descStr = ( $template == 'hotel' )? $main['desc']['account'] : $main['description'];
                                $isSpecialRateApplied = ( isset($d['isSpecialRateApplied']) and $d['isSpecialRateApplied'] == TRUE )? TRUE : FALSE;

                                if( $bookingType == Hotel::HOTEL_BOOKING_TYPE_PACKAGE_BASIS and count($d['child']) > 0){
                                    $childCount = 1;

                                    $chDesc['desc'][0] = $descStr;
                                    $chDesc['payable'][0] = $main['payableRate'];
                                    $chDesc['margin'][0] = $main['margin'];
                                    $chDesc['billing'][0] = $main['billingRate'];
                                    $chDesc['remarks'][0] = $main['remarks'];

                                    foreach($d['child'] as $child){
                                        $chDesc['desc'][$childCount] = $child['description'];

                                        if(  $isSpecialRateApplied === FALSE ){
                                            $chDesc['payable'][$childCount] = $child['payableRate'];
                                            $chDesc['margin'][$childCount] = $child['margin'];
                                            $chDesc['billing'][$childCount] = $child['billingRate'];
                                        }

                                        $chDesc['remarks'][$childCount] = $child['remarks'];

                                        $childCount++;
                                    }

                                    $activityDescription = implode('<br />', $chDesc['desc']);
                                    $payingRate = implode('<br />', $chDesc['payable']);
                                    $margin = implode('<br />', $chDesc['margin']);
                                    $billingRate = implode('<br />', $chDesc['billing']);
                                    $remarks = implode('<br />', $chDesc['remarks']);

                                }else{
                                    $activityDescription = $descStr;
                                    $payingRate = $main['payableRate'];
                                    $margin = $main['margin'];
                                    $billingRate = $main['billingRate'];
                                    $remarks = $main['remarks'];
                                }

                                $tbl  .= '<td style="border-bottom:1px solid #ccc">'.$activityDescription.'</td>';

                                if( $isAccountCopy ){
                                    $tbl  .= $accountTd.$payingRate.$tdClose;
                                    $tbl  .= $accountTd.$margin.$tdClose;
                                    $tbl  .= $accountTd.$billingRate.$tdClose;
                                    $tbl  .= $accountTd.$remarks.$tdClose;
                                }

                                $descCount++;
                                if( $descCount <= count($descriptions) ){
                                    $tbl  .= '</tr><tr style="border-bottom:1px solid #ccc">';
                                }
                            }
                            $tbl  .= '</tr>';
                            $count++;
                        }

                        echo $tbl;
                    ?>
                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td style="vertical-align: middle; padding:0 0 0 1rem;">
                <table class="table no-border" style="width: 100%; max-width: 100%; margin-bottom: 0px;">
                    <tr>
                        <td>Please collect all extras directly</td>
                        <td><?php echo $data[0]['xoGeneratedBy'] ?></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <?Php /* class="<?php echo (! $isForEmail)? 'stampholder' : '';  ?>" */ ?>
            <td class="stampholder" style="border:none; font-size: 80%; text-align: justify">
                <p><strong>Payment Instruction:</strong> after services have been provided please send this voucher with your bill within 30 days for payment. No Payment will be made for service not mentioned in this voucher. Extras or more expensive accommodation etc. To be collected from client direct.</p>
                <p>In making arrangements for the subject tours, Yeti Express and or their representatives act only as agent for the hotels, steampship companies, railways, airlines or contractors providing accommodation,
                    transportation or other services. Exchange order, coupons, receipts and tickets are issued subject to any all tariffs, terms and conditions under which any accommodation, transportation or any other services whatsoever are provided by such entities.</p>
                <p>Yeti Express and or their agents shall not be liable or responsible for irregularities loss, injury or damage to the person, property or otherwise in connection with any accommodation, transportation or other service resulting directly or indirectly,
                    from act of Gods, disturbances, strikes, quarantine, thieves or pilferage or cancellation or change schedules beyond the company control. Nor will be company and their associates accept responsibility for losses or additional expenses due to delays or changes in plans caused by the after mentioned reasons.</p>
                <?php
                $stamp = Options::get('config_stamp', '');
                $stampStyle = 'position: absolute; left: 45%; top: 10%; opacity: .3; -webkit-transform: rotate(-30deg); -moz-transform: rotate(-30deg); -o-transform: rotate(-30deg); -ms-transform: rotate(-30deg); width: 12rem;';
                if( !$isForEmail and $stamp !== "" ){
                    echo '<img src="'.$stampSrc.'"  class="stamp" style="'.$stampStyle.'" />'; //width="15%"
                }
                ?>
            </td><!--class="stampholder"  position:relative; width:100%; height:100%;  -->
        </tr>

    </table>

    <div style="page-break-after: always"></div>
<?php } ?>

<?php if( ! isset($emailFor)){ ?>
    <div class="row no-print">

        <div class="col-md-12">

            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><h2 class="panel-title">Print Options</h2></div>
                    <div class="panel-body">
                        <div class="input-group-sm col-md-4"><input type="checkbox" checked="checked" value="hotel" class="simple print-option" /> &nbsp; Hotel Copy </div>
                        <div class="input-group-sm col-md-4"><input type="checkbox" checked="checked" value="agent" class="simple print-option" /> &nbsp; Agent/Client Copy </div>
                        <?php if(user_access('view account copy')){ ?>
                        <div class="input-group-sm col-md-4"><input type="checkbox" checked="checked" value="account" class="simple print-option" /> &nbsp; Account Copy </div>
                        <?php } ?>
                    </div>
                    <div class="panel-footer">
                        <?php if( user_access('print xo') ){ ?>
                            <a href="#" class="btn btn-primary" id="print"><i class="fa fa-print"></i> &nbsp; PRINT</a>
                        <?php } ?>

<!--                        --><?php //if( user_access('email xo') ){ ?>
<!--                            <button class="btn btn-primary" data-toggle="modal" data-target="#emailSelection">Email</button>-->
<!--                        --><?php //}?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade no-print" id="emailSelection" tabindex="-1" role="dialog" aria-labelledby="emailSelectionLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="emailSelectionLabel">Select Emails</h4>
                </div>

                <form action="<?php echo site_url('file/xo/mergedEmail') ?>" method="post" role="form" class="validate" id="mergedXoForm">
                    <div class="modal-body">
                        <input type="hidden" name="activityID" value="<?php echo $data[0]['activityID'] ?>" />
                        <input type="hidden" name="fileNumber" value="<?php echo $tourFileNumber ?>" />
                        <?php
                        foreach($merged_xos as $xo){
                            echo '<input type="hidden" name="merged_xos[]" value="'.$xo.'" />';
                        }

                        $keyArray = ['agent', 'hotel', 'account', 'client'];
                        if(count($data[0]['emails']) >0 ){
                            foreach($data[0]['emails'] as $key => $emails){
                                if( ! in_array($key, $keyArray) ) continue;
                                if( count($emails) == 0 ) continue;

                                echo '<div class="col-md-3">';
                                echo '<table class="table no-border">';
                                echo '<tr><th>'.ucfirst($key).'</th></tr>';
                                foreach($emails as $em){
                                    echo '<tr><td>'.form_checkbox('emails['.$key.'][]', $em, false, 'class="simple checkEmail"').'&nbsp;'.$em.'</td></tr>';
                                }
                                echo '</table>';
                                echo '</div>';
                            }
                        }
                        ?>

                        <div class="col-md-3">
                            <table class="table no-border">
                                <tr><th>Client Email</th></tr>
                                <tr><td><?php echo form_input('emails[client][]', '', 'class="form-control email"'); ?></td></tr>
                            </table>
                        </div>

                        <div class="clear"></div>

                    </div>

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="SEND EMAIL" id="sendEmailBtn"/>
                        <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                    </div>

                </form>

            </div>
        </div>
    </div>

<?php } ?>




<?php
if( $isForEmail ){
    loadJS(array('jquery'));
}
?>

<script type="text/javascript">
    $(document).ready(function(){


        <?php if( isset($emailFor) and $emailFor !== "" ){ ?>
        var wrap = '<?php echo $emailFor ?>';

        if( wrap == 'client' ) wrap = 'agent';

        console.log($('table.print').not('#'+wrap));

        $('table.print').not('#'+wrap).hide();
        <?php } ?>

        $('.print-option').click(function(){

            var template = $(this).val(),
                obj = $('#'+template);

            if( $(this).is(':checked') ) {
                obj.css({'display': 'block'});
            }else{
                obj.css({'display': 'none'});
            }

            console.log($(this).val()+ ' :: '+ $(this).is(':checked'));
        });

        $('#print').click(function(){
            window.print();
        });

        $('#mergedXoForm').submit(function(){
            var _form = $(this);
            var _btn = $('#sendEmailBtn');

            $('.modal-dialog').mask('Sending Email ...');

//            _btn.attr('disabled', 'disabled');
//            _btn.attr('value', 'sending....');
            $.ajax({
                url: Yarsha.config.base_url + 'file/ajax/mergedEmail',
                type: 'POST',
                data: _form.serialize(),
                success: function(res){
                    console.log(res);
                    var data = $.parseJSON(res);

                    alert(data.message);

                    $('.modal-dialog').unmask('Updating Category ...');
                    $('#emailSelection').modal('close');
//                    _btn.removeAttr('disabled');
//                    _btn.attr('value', 'SEND EMAIL');
                },
                error:function(res){
                    console.log('error'); console.log(res);
                }
            });
            return false;
        });

    });
</script>
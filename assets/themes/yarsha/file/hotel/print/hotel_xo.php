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

$viewAccountCopy = user_access('view account copy');

if( $viewAccountCopy ){
    $templates[] = 'account';
}

if(isset( $emailFor ) and $emailFor !== ""){
    $emailFor = ( $emailFor == 'client' )? 'agent' : $emailFor;
    $templates = array($emailFor);
}

foreach($templates as $template){
    $printDetail = '';
    $isAccountCopy = FALSE;

    if( $template == 'hotel' ){
        $printDetail = 'HOTEL COPY';
    }

    if( $template == 'agent' ){
        $printDetail = 'AGENT/CLIENT COPY';
    }

    if( $template == 'account' ){
        $isAccountCopy = TRUE;
        $printDetail = 'ACCOUNT COPY';
    }
    ?>
    <table class="table print bg-white" id="<?php echo $template ?>">
        <tr>
            <td>
                <table class="table no-border">
                    <tr>
                        <td width="50%"><img src="<?php echo base_url().'assets/themes/yarsha/resources/images/brand.png' ?>" width="50%"  /></td>
                        <td style="vertical-align: middle"><strong><?php echo $printDetail ?></strong></td>
                        <td style="vertical-align: middle" width="40%" class="text-center">
                            <span style="font-size: 8px; letter-spacing: 2px"><?php echo 'TPIN '.Options::get('config_tpin',''); ?></span><br />
                            <strong>EXCHANGE ORDER<br/><?php echo $data['xoNumber']?></strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table class="table no-border">
                    <tr>
                        <th width="15%">Issued To</th><td><?php echo $data['hotel'] ?></td>
                        <th width="15%">X/O Date</th><td><?php echo $data['xoDate'] ?></td>
                    </tr>

                    <tr>
                        <th>Client/Group</th><td><?php echo $data['client'] ?></td>
                        <th>File#</th><td><?php echo $data['fileNumber'] ?></td>
                    </tr>

                    <tr>
                        <th>Nationality</th><td><?php echo $data['nationality'] ?></td>
                        <th>Market</th><td><?php echo $data['market'] ?></td>
                    </tr>

                    <tr>
                        <th     >No. Of Pax</th>
                        <td>
                            <?php
                            $pax = $data['pax'];
                            if( $data['child'] or $data['infants'] ){
                                $pax .= ' ( '.$data['child'].' child, '.$data['infants'].' infants )';
                            }
                            echo $pax;
                            ?>
                        </td>
                        <th><?php echo ($isAccountCopy)? 'Currency' : '&nbsp;' ?></th>
                        <td><?php echo ($isAccountCopy)? $data['currency'] : '&nbsp;' ?></td>
                    </tr>

                    <tr>
                        <th>Arrival Date</th><td><?php echo $data['arrivalDate'] ?></td>
                        <th>Arrival Note</th><td><?php echo ( $data['arrivalDesc'] != '' )? $data['arrivalDesc'] : 'N/A' ?></td>
                    </tr>

                    <tr>
                        <th>Departure Date</th><td><?php echo $data['departureDate'] ?></td>
                        <th>Departure Note</th><td><?php echo ( $data['departureDesc'] != '' )? $data['departureDesc'] : 'N/A' ?></td>
                    </tr>

                    <tr>
                        <th>Special Requirement</th>
                        <td colspan="3"><?php echo ( $data['description']!='' )? $data['description'] : 'N/A' ?></td>
                    </tr>

                </table>
            </td>
        </tr>

        <tr>
            <td>
                <table class="table no-border">
                    <tbody>
                    <tr class="bg-gray">
                        <th>SN</th>
                        <th>Description</th>
                        <?php if($isAccountCopy){ ?>
                            <th class="account">Payable Rate</th>
                            <th class="account">Margin</th>
                            <th class="account">Billing Rate</th>
                            <th class="account">Remarks</th>
                        <?php } ?>
                    </tr>

                    <?php
                    $count = 1;
                    foreach($data['descriptions'] as $desc){
                        ?>

                        <tr>
                            <td><?php echo $count ?></td>
                            <td><?php echo ($isAccountCopy)? $desc['desc']['account'] : $desc['desc']['others']; ?></td>
                            <?php if($isAccountCopy){ ?>
                                <td class="account"><?php echo $desc['payableRate'] ?></td>
                                <td class="account"><?php echo $desc['margin'] ?></td>
                                <td class="account"><?php echo $desc['billingRate'] ?></td>
                                <td class="account"><?php echo $desc['remarks'] ?></td>
                            <?php } ?>
                        </tr>
                        <?php $count++; } ?>
                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <table class="table no-border">
                    <tr>
                        <td>Please collect all extras directly</td>
                        <td><?php echo $data['xoGeneratedBy'] ?></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td class="stampholder" style="border:none; font-size: 80%; text-align: justify">
                <p><strong>Payment Instruction:</strong> after services have been provided please send this voucher with your bill within 30 days for payment. No Payment will be made for service not mentioned in this voucher. Extras or more expensive accommodation etc. To be collected from client direct.</p>
                <p>In making arrangements for the subject tours, Yeti Express and or their representatives act only as agent for the hotels, steampship companies, railways, airlines or contractors providing accommodation,
                    transportation or other services. Exchange order, coupons, receipts and tickets are issued subject to any all tariffs, terms and conditions under which any accommodation, transportation or any other services whatsoever are provided by such entities.</p>
                <p>Yeti Express and or their agents shall not be liable or responsible for irregularities loss, injury or damage to the person, property or otherwise in connection with any accommodation, transportation or other service resulting directly or indirectly,
                    from act of Gods, disturbances, strikes, quarantine, thieves or pilferage or cancellation or change schedules beyond the company control. Nor will be company and their associates accept responsibility for losses or additional expenses due to delays or changes in plans caused by the after mentioned reasons.</p>
                <?php
                $stamp = Options::get('config_stamp', '');
                if( $stamp !== "" ){
                    echo '<img src="'.$stamp.'"  width="15%"  class="stamp" />';
                }
                ?>
            </td>
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
                        <?php if($viewAccountCopy){ ?><div class="input-group-sm col-md-4"><input type="checkbox" checked="checked" value="account" class="simple print-option" /> &nbsp; Account Copy </div><?php } ?>
                    </div>
                    <div class="panel-footer"><a href="#" class="btn btn-primary" id="print"><i class="fa fa-print"></i> &nbsp; PRINT</a></div>
                </div>
            </div>

        </div>
    </div>
<?php } ?>


<?php
if( isset($emailFor) and $emailFor !== "" ){
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

    });
</script>
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
$templates = ['hotel', 'agent' ];

if(user_access('view account copy')){
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
    <table class="table print bg-white" id="<?php echo $template ?>" style="width: 100%; max-width: 100%;
  margin-bottom: 20px; font-size: 12px; background: #ffffff;">

        <tr>
            <td style="vertical-align: middle; padding:0 0 0 1rem; border-bottom: 1px solid #ddd;" >
                <table class="table no-border" style="width: 100%; max-width: 100%; margin-bottom: 0px;">
                    <tr>
                        <td width="50%">
                            <img src="<?php echo $logoSrc ?>" alt="Yetibilling" width="350" /></td>
                        <td style="vertical-align: middle">
                            <strong><?php echo $printDetail ?></strong>
                        </td>
                        <td style="vertical-align: middle" width="40%" class="text-center">
                            <span style="font-size: 8px; letter-spacing: 2px"><?php echo 'TPIN '.Options::get('config_tpin',''); ?></span><br />
                            <strong>EXCHANGE ORDER<br/><?php echo $data['xoNumber']?></strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: middle; padding:0 0 0 1rem; position: relative">
                <?php if( $data['status'] == \file\models\TourFileActivity::ACTIVITY_STATUS_VOID ) { ?>
                    <div class="rubber_stamp">VOID</div>
                <?php } ?>
                <table class="table no-border" style="width: 100%; max-width: 100%; margin-bottom: 0px;">
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
                        <th>No. Of Pax</th>
                        <td colspan="3">
                            <?php
                                $pax = $data['pax'];
                                if( $data['child'] or $data['infants'] ){
                                    $pax .= ' ( '.$data['child'].' child, '.$data['infants'].' infants )';
                                }
                                echo $pax;
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Arrival Date</th><td><?php echo $data['arrivalDate'] ?></td>
                        <th>Arrival Note</th><td><?php echo ( $data['arrivalDesc'] != '' )? $data['arrivalDesc'] : 'N/A' ?></td>
                    </tr>

                    <?php if( $data['bookingType'] != \hotel\models\Hotel::HOTEL_BOOKING_TYPE_SERVICE_BASIS ){ ?>
                    <tr>
                        <th>Departure Date</th><td><?php echo $data['departureDate'] ?></td>
                        <th>Departure Note</th><td><?php echo ( $data['departureDesc'] != '' )? $data['departureDesc'] : 'N/A' ?></td>
                    </tr>
                    <?php }?>

                    <tr>
                        <th>Special Requirement</th>
                        <td><?php echo ( $data['description']!='' )? $data['description'] : 'N/A' ?></td>
                        <th>Confirmation Number</th><td><?php echo ( $data['confirmationNumber']!='' )? $data['confirmationNumber'] : 'N/A' ?></td>
                    </tr>

                </table>
            </td>        </tr>

        <tr>
            <td style="vertical-align: middle; padding:0 0 0 1rem; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd;">
                <table class="table no-border" style="width: 100%; max-width: 100%; margin-bottom: 0px;">
                    <tbody>
                    <tr class="bg-gray" style="background: #eaeaec">
                        <th style="padding:0.5rem">SN</th>
                        <th style="padding:0.5rem">Description</th>
                        <?php if($isAccountCopy){ ?>
                            <th class="account"  style="padding:0.5rem">Payable Rate</th>
                            <th class="account" style="padding:0.5rem">Margin</th>
                            <th class="account" style="padding:0.5rem">Billing Rate</th>
                            <th class="account" style="padding:0.5rem">Remarks</th>
                        <?php } ?>
                    </tr>
                    <?php
                    $count = 1;

                    foreach($data['descriptions'] as $desc){
                        $main = $desc['main'];
                        $children = $desc['child'];
                        $descStr = ( $template == 'hotel' )? $main['desc']['account'] : $main['description'];
                        ?>

                        <tr>
                            <td><?php echo $count ?></td>
                            <td><?php echo $descStr ?></td>
                            <?php if($isAccountCopy){ ?>
                                <td class="account"><?php echo $main['payableRate'] ?></td>
                                <td class="account"><?php echo $main['margin'] ?></td>
                                <td class="account"><?php echo $main['billingRate'] ?></td>
                                <td class="account"><?php echo $main['remarks'] ?></td>
                            <?php } ?>
                        </tr>
                        <?php
                            if(count($children)){
                                foreach($children as $child){
                        ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td><?php echo $child['description'] ?></td>
                                <?php if($isAccountCopy){ ?>
                                    <td class="account"><?php echo $child['payableRate'] ?></td>
                                    <td class="account"><?php echo $child['margin'] ?></td>
                                    <td class="account"><?php echo $child['billingRate'] ?></td>
                                    <td class="account"><?php echo $child['remarks'] ?></td>
                                <?php } ?>
                            </tr>
                        <?php
                                }
                            }
                            $count++;
                        }
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
                        <td><?php echo $data['xoGeneratedBy'] ?></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr><!--class="stampholder"  position:relative; width:100%; height:100%;  -->
            <td  style=" position:relative; border:none; font-size: 80%; text-align: justify; vertical-align: middle; padding:0 0 0 1rem;">
                <p>
                    <strong>Payment Instruction:</strong> after services have been provided please send this voucher with your bill within 30 days for payment. No Payment will be made for service not mentioned in this voucher. Extras or more expensive accommodation etc. To be collected from client direct.

                </p>
                <p>In making arrangements for the subject tours, Yeti Express and or their representatives act only as agent for the hotels, steampship companies, railways, airlines or contractors providing accommodation,
                    transportation or other services. Exchange order, coupons, receipts and tickets are issued subject to any all tariffs, terms and conditions under which any accommodation, transportation or any other services whatsoever are provided by such entities.</p>
                <p>Yeti Express and or their agents shall not be liable or responsible for irregularities loss, injury or damage to the person, property or otherwise in connection with any accommodation, transportation or other service resulting directly or indirectly,
                    from act of Gods, disturbances, strikes, quarantine, thieves or pilferage or cancellation or change schedules beyond the company control. Nor will be company and their associates accept responsibility for losses or additional expenses due to delays or changes in plans caused by the after mentioned reasons.</p>
                <?php
                $stamp = Options::get('config_stamp', '');
                $stampStyle = 'position: absolute; left: 50%; top: 20%; opacity: .3; -webkit-transform: rotate(-30deg); -moz-transform: rotate(-30deg); -o-transform: rotate(-30deg); -ms-transform: rotate(-30deg); width: 20rem;';
                if( $stamp !== "" ){
                    echo '<img src="'.$stampSrc.'"  class="stamp" style="position: absolute; left: 50%; top: 20%; opacity: .6; -webkit-transform: rotate(-30deg); -moz-transform: rotate(-30deg); -o-transform: rotate(-30deg); -ms-transform: rotate(-30deg); width: 20rem;" />'; //width="15%"
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
                    <?php if(user_access('view account copy')){?>
                    <div class="input-group-sm col-md-4"><input type="checkbox" checked="checked" value="account" class="simple print-option" /> &nbsp; Account Copy </div><?php }?>
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
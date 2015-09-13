<?php
use hotel\models\Hotel;
use file\models\TourFileActivity;

?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">

                <div class="col-md-12 border-bottom margin-bottom">
                    <div class="col-md-2 ">
                        <label>File No.</label>
                        <span><?php echo $data['fileNumber'] ?></span>
                    </div>

                    <div class="col-md-3 pull-right">
                        <label>EXCHANGE ORDER &nbsp;:&nbsp; </label>
                        <span><?php echo $data['xoNumber']?></span>
                    </div>
                </div>
                 <div class="desc-block col-md-12">
                    <label>Issued To</label>
                    <span><?php echo strtoupper($data['hotel']) ?></span>
                </div>

                <div class="desc-block col-md-6">
                    <label>Agent</label>
                    <span><?php echo strtoupper($data['agent']) ?></span>
                </div>
                <div class="desc-block col-md-6">
                    <label>Contact Person</label>
                    <span><?php echo strtoupper($data['contactPerson']) ?></span>
                </div>

                <div class="clear"></div>

                <div class="desc-block col-md-6">
                    <label>Client /Group</label>
                    <span><?php echo strtoupper($data['client']) ?></span>
                </div>
                <div class="desc-block col-md-6">
                    <label>Tour Officer</label>
                    <span><?php echo strtoupper($data['tourOfficer']) ?></span>
                </div>

                <div class="clear"></div>

                <div class="desc-block col-md-6">
                    <label>Nationality</label>
                    <span><?php echo strtoupper($data['nationality']) ?></span>
                </div>
                <div class="desc-block col-md-6">
                    <label>Market</label>
                    <span><?php echo $data['market'] ?></span>
                </div>

                <div class="clear"></div>

                <div class="desc-block col-md-4">
                    <label>No. Of Pax</label>
                    <span><?php echo $data['pax'] ?></span>
                </div>
                <div class="desc-block col-md-4">
                    <label>Child</label>
                    <span><?php echo $data['child'] ?></span>
                </div>
                <div class="desc-block col-md-4">
                    <label>Infants</label>
                    <span><?php echo $data['infants'] ?></span>
                </div>

                <div class="clear"></div>

                <div class="desc-block col-md-4">
                    <label>Arrival Date</label>
                    <span><?php echo $data['arrivalDate'] ?></span>
                </div>
                <div class="desc-block col-md-8">
                    <label>Arrival Note</label>
                    <span><?php echo ($data['arrivalDesc'] != '')? $data['arrivalDesc'] : 'N/A' ?></span>
                </div>

                <div class="clear"></div>

                <?php if( $data['bookingType'] != Hotel::HOTEL_BOOKING_TYPE_SERVICE_BASIS  ){ ?>
                    <div class="desc-block col-md-4">
                        <label>Departure Date</label>
                        <span><?php echo $data['departureDate'] ?></span>
                    </div>
                    <div class="desc-block col-md-8">
                        <label>Departure Note</label>
                        <span><?php echo ($data['departureDesc'] != '')? $data['departureDesc'] : 'N/A' ?></span>
                    </div>

                    <div class="clear"></div>

                    <div class="desc-block col-md-12">
                        <label>No. of Nights</label>
                        <span><?php echo $data['nights'] ?></span>
                    </div>
                    <div class="clear"></div>
                <?php } ?>


                <div class="desc-block col-md-12">
                    <label>Details</label>
                    <span><?php echo $data['description'] ?></span>
                </div>
                <div class="clear"></div>

                <div class="desc-block col-md-4">
                    <label>Status</label>
                    <?php

                        if( $data['status'] == TourFileActivity::ACTIVITY_STATUS_VOID ){
                            $statusString = 'VOID';
                            $statusClass = 'bg-orange';
                        }elseif( $data['status'] == TourFileActivity::ACTIVITY_STATUS_DELETED){
                            $statusString = 'DELETED';
                            $statusClass = 'bg-maroon';
                        }else{
                            $statusString = 'ACTIVE';
                            $statusClass = 'bg-olive';
                        }
                    ?>
                   <span><small class="badge <?php echo $statusClass ?>"> <?php echo $statusString ?> </small>  </span>
                </div>

                <div class="desc-block col-md-8">
                    <label>Confirmation Number</label>
                    <span><?php echo $data['confirmationNumber'] ?> </span>
                </div>

                <table class="table no-border">
                    <tbody>
                    <tr class="border-bottom bg-blue">
                        <td>SN</td>
                        <td>Description</td>
                        <?php
                        if(user_access('view account copy')){
                            echo '<td>Payable Amount</td>';

                            if( user_access('view update margins') ){
                                echo '<td>Margin</td>';
                                echo '<td>Billing Amount</td>';
                            }
                        }
                        ?>
                        <td>Remarks</td>
                    </tr>

                    <?php
                    $count = 1;
                    foreach($data['descriptions'] as $desc){

                        $main = $desc['main'];
                        $children  = $desc['child'];
                        ?>

                        <tr>
                            <td><?php echo $count ?></td>
                            <td><?php echo $main['desc']['account'] ?></td>
                            <?php
                                if(user_access('view account copy')){
                                    echo '<td>'.$main['payableRate'].'</td>';

                                    if( user_access('view update margins') ){

                                        echo '<td>'.$main['margin'].'</td>';
                                        echo '<td>'.$main['billingRate'].'</td>';

                                    }
                                }
                            ?>
                            <td><?php echo $main['remarks'] ?></td>
                        </tr>
                        <?php

                        if(count($children)){
                            foreach($children as $child){
                                echo '<tr>';
                                echo '<td>&nbsp;</td>';
                                echo '<td>'.$child['description'].'</td>';

                                if(user_access('view account copy')){
                                    echo '<td>'.$child['payableRate'].'</td>';

                                    if( user_access('view update margins') ){
                                        echo '<td>'.$child['margin'].'</td>';
                                        echo '<td>'.$child['billingRate'].'</td>';
                                    }
                                }

                                echo '<td>'. $child['remarks'].'</td>';
                                echo '</tr>';

                            }
                        }
                        $count++; }
                    ?>
                    </tbody>
                </table>

                <div class="col-md-12 margin">
                    <?php if( user_access('print xo') ){ ?>
                        <a href="<?php echo site_url('file/activity/detail/'.$data['activityID'].'/print') ?>" class="btn btn-primary">Print Preview</a>
                    <?php } ?>

<!--                    --><?php //if( user_access('email xo') ){ ?>
<!--                        <button class="btn btn-primary" data-toggle="modal" data-target="#emailSelection">Email</button>-->
<!--                    --><?php //}?>
                    <?php
                 $total_reverted_times= $data['revertedTimes'];
                    if($data['status'] == \file\models\TourFileActivity::ACTIVITY_STATUS_ACTIVE){
                        if( $total_reverted_times <  Options::get('config_revert_time_xo') or user_access('revert xo') ) {
                            echo '<a href="' . site_url('file/xo/revertActivityXo/' . $data['activityID']) . '" class="btn btn-primary" >Revert</a>';
                        }
                    }

                     ?>
                    <?php
                    if($total_reverted_times <= Options::get('config_revert_time_xo') && user_access('void xo')
                    && $data['status'] == \file\models\TourFileActivity::ACTIVITY_STATUS_ACTIVE ){
                        echo '<button class="btn btn-danger" onclick="return voidXO('.$data['activityID'].')" style="padding-right: 10px">Void</a></button>';
                    }


                    ?>

                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="emailSelection" tabindex="-1" role="dialog" aria-labelledby="emailSelectionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="emailSelectionLabel">Select Emails</h4>
            </div>

            <form action="<?php echo site_url('file/activity/email') ?>" method="post" role="form" class="validate">
                <div class="modal-body">
                    <input type="hidden" name="activityID" value="<?php echo $data['activityID'] ?>" />
                    <?php
                    $keyArray = ['agent', 'hotel', 'account', 'client'];
                    if(count($data['emails']) >0 ){
                        foreach($data['emails'] as $key => $emails){
                            if( ! in_array($key, $keyArray) ) continue;
                            if( count($emails) == 0 ) continue;

                            echo '<div class="col-md-3">';
                            echo '<table class="table no-border">';
                            echo '<tr><th>'.ucfirst($key).'</th></tr>';
                            foreach($emails as $em){
                                echo '<tr><td>'.form_checkbox('emails['.$key.'][]', $em, false, 'class="simple"').'&nbsp;'.$em.'</td></tr>';
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
                    <input type="submit" class="btn btn-primary" value="SEND EMAIL" />
                    <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                </div>

            </form>

        </div>
    </div>
</div>

<script type="text/javascript">

        function voidXO(id) {

            if (confirm('Are you sure to void Exchange Order?')) {
                $('body').mask('Processing ...');
                $.ajax({
                    type: 'get',
                    url: Yarsha.config.base_url + 'file/ajax/voidXo/' + id,
                    success: function (res) {
                        var data = $.parseJSON(res);
                        if (data.status && data.status == 'success') {
                            window.location = Yarsha.config.base_url + 'file/detail/<?php echo $data['tourFileID'] ?>';
                            return true;
                        } else {
                            $('body').unmask();
                            Yarsha.notify('warn', data.message);
                            return false;
                        }
                    }
                });
            } else {
                return false;
            }
        }

</script>
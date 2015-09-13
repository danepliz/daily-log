<?php

use file\models\TourFileActivity;
use hotel\models\Hotel;


$agentId = $file->getAgent() ? $file->getAgent()->id() : NULL;
$nationalityId = $file->getNationality() ? $file->getNationality()->id() : NULL;
$marketId = $file->getMarket() ? $file->getMarket()->id() : NULL;
$activities = $file->getActivities();
$hotelActivities = array();
$travelActivities = array();
$agentContactPerson = $file->getAgentContactPerson() ? $file->getAgentContactPerson()->id() : '';
$tourOfficer = $file->getTourOfficer() ? $file->getTourOfficer()->id() : NULL;
$fileCreator = $file->getCreatedBy() ? $file->getCreatedBy()->getFullname() : NULL;
?>
<form role="form" action="" class="validate" method="post">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title" >
                        Tour File
                        <span class="right-side" style="float: right">
                            <?php echo ($fileCreator) ? 'Created By : <strong>'.$fileCreator.'</strong>' : '';  ?>
                        </span>
                    </h3>
                </div>
                <div class="panel-body">

                    <div class="form-group-sm col-md-4">
                        <label for="file">File #</label>
                        <input type="text" name="file" class="form-control required"
                               value="<?php echo $file->getFileNumber() ?>"/>
                    </div>

                    <div class="form-group-sm col-md-4">
                        <label for="agent">Agent</label>
                        <?php getAgentSelectionElementForXO('agent', $agentId, 'class="form-control required" id="agent"', $file->getCreatedBy()->id()) ?>

                    </div>

                    <div class="form-group-sm col-md-4">
                        <label for="agent">Contact Person</label>
                        <?php echo form_dropdown('agentContactPerson', array('' => ' -- SELECT CONTACT PERSON --'), NULL, 'class="form-control" id="agentContactPerson"'); ?>
                    </div>

                    <div class="clear"></div>

                    <div class="form-group-sm col-md-4">
                        <label for="client">Client</label>
                        <input type="text" class="form-control required" name="client"
                               value="<?php echo $file->getClient() ?>">
                    </div>

                    <div class="form-group-sm col-md-4">
                        <label for="market">Nationality</label>
                        <?php getCountrySelectionElementForXo('nationality', $nationalityId, 'class="form-control required" id="" ') ?>
                    </div>

                    <div class="form-group-sm col-md-4">
                        <label for="market">Market</label>
                        <?php getMarketSelectionElementForXo('market', $marketId, 'class="form-control required" id="market"') ?>
                    </div>

                    <div class="clear"></div>

                    <div class="form-group-sm col-md-4">
                        <label for="pax">No. Of Pax</label>
                        <input type="text" class="form-control required" name="pax"
                               value="<?php echo $file->getNumberOfPax() ?>">
                    </div>

                    <div class="form-group-sm col-md-4">
                        <label for="child">Child</label>
                        <input type="text" class="form-control" name="child"
                               value="<?php echo $file->getNumberOfChildren() ?>">
                    </div>

                    <div class="form-group-sm col-md-4">
                        <label for="infants">Infants</label>
                        <input type="text" class="form-control" name="infants"
                               value="<?php echo $file->getNumberOfInfants() ?>">
                    </div>

                    <div class="form-group-sm col-md-6">
                        <label for="tourOfficer">Tour Officer</label>
                        <?php getTourOfficersSelectElement('tourOfficer', $tourOfficer, 'class="form-control required" id="tourOfficer"') ?>
                    </div>

                    <div class="form-group-sm col-md-6">
                        <label for="instructions">Instructions</label>
                        <textarea name="instructions"
                                  class="form-control"><?php echo $file->getInstructions(); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 btn-activity">
            <?php
                if (user_access('manage tour file')) {
                    echo form_submit('submit', 'UPDATE FILE', 'class="btn btn-primary"' );
                }

                if (user_access('add activity')) {
                    echo anchor(site_url('file/activity/hotel/' . $file->id()), 'ADD HOTEL ACTIVITIES', 'class="btn btn-primary margin"');
                }

                if (user_access('add activity')) {
                    echo anchor(site_url('file/activity/service/' . $file->id()), 'ADD SERVICE ACTIVITIES', 'class="btn btn-primary margin"');
                }

                if( $file->getCreatedBy()->id() == Current_User::user()->id() ) {
                    echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#permittedUserForm">ADD PERMITTED USERS</button>';
                }


//            if (user_access('add activity')) {
//                echo '<a href="" class="btn btn-danger margin" onclick="return voidFile('.$file->id().')">Void</a>';
//            }
//            ?>

        </div>

    </div>
</form>

<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">Activities</h3></div>
    <?php if (count($activities) > 0) { ?>
        <div class="table-responsive">
        <table class="table font-small">
            <tbody>
            <tr>
                <th>#</th>
                <th>Activity</th>
                <th>Arrival Date</th>
                <th>Departure Date</th>
                <th>Details</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
            <?php
            $count = 1;
            foreach ($activities as $ha) {

                $activityRepo = $this->doctrine->em->getRepository('file\models\TourFileActivityDetail');
                $activityDetails = $activityRepo->getActivitiesByTourFile($ha->id());
                $activityStatus = $ha->getStatus();

                if($ha->isDeleted()) continue;

//                $activityDetails = $ha->getDetails();
                $hotelName = strtoupper($ha->getHotel()->getName());
                $activityString = '';
                if (count($activityDetails) > 0) {
                    $break = '<br />';

                    foreach ($activityDetails as $ad) {

                        $bookingType = $ad->getTourActivity()->getBookingType();

                        if( $bookingType == Hotel::HOTEL_BOOKING_TYPE_SERVICE_BASIS ){

                            $quantity = $ad->getNumberOfRooms();
                            $outlet = ( $ad->getOutlet() )? $ad->getOutlet()->getName() : '';
                            $service = ( $ad->getService() )? $ad->getService()->getName() : '';

                            $activityString .= $quantity.' '.strtoupper($service);
                            if( $outlet!= '' ){
                                $activityString .= ' AT '.strtoupper($outlet);
                            }else{
                                $activityString .= ' AT ANY OUTLET';
                            }
                            $activityString .= '<br />';

                        }elseif( $bookingType == Hotel::HOTEL_BOOKING_TYPE_PACKAGE_BASIS ){
                            $mainPackage = $ad->getPackage()->getName();
                            $childActivities = $ad->getChildren();
                            $childPackage = [];
                            $activityString .= $mainPackage;
                            if( count($childActivities) ){
                                foreach( $childActivities as $ca){
                                    $childPackage[] = $ca->getNumberOfRooms().' '.$ca->getPackage()->getName();
                                }
                                $activityString .= '( '.implode(', ',$childPackage ).')';
                            }

                            $activityString .= '<br />';

                        }else{
                            $totalRooms = $ad->getNumberOfRooms();
                            $roomDesc = ($totalRooms == 1) ? ' ROOM' : ' ROOMS';
                            $roomCat = ($ad->getRoomCategory()) ? $ad->getRoomCategory()->getName() : '';
                            $roomType = $ad->getNickNameForRoomType();
                            $roomType = ($roomType == '' and $ad->getRoomType()) ? $ad->getRoomType()->getName() : $roomType;
                            $roomPlan = ($ad->getRoomPlan()) ? $ad->getRoomPlan()->getName() : '';
                            $res = array(
                                str_pad($totalRooms, 2, '0', STR_PAD_LEFT),
                                $roomCat,
                                $roomType,
                                $roomDesc,
                                ' AT ' . $hotelName,
                                ' ON ' . $roomPlan . ' BASIS'
                            );

                            $activityString .= str_replace('  ', ' ', strtoupper(implode(' ', $res)) . $break);

                            if( $ad->getExtraBed() > 0 ){
                                $activityString .= ' + '.$ad->getExtraBed() .' Extra Bed ';
                            }
                        }


                    }
                } else {
                    $activityString = 'N/A';
                }

                $editActionAttr = array(
                    'id' => 'edit',
                    'title' => 'Edit Activity'
                );

                $typeDesc = 'hotel';
                $typeAccommodation = 'HOTEL ACCOMMODATION';
                $departureDate = $ha->getDepartureDate()->format('Y-m-d');

                if( $ha->getBookingType() == Hotel::HOTEL_BOOKING_TYPE_SERVICE_BASIS ){
                    $typeDesc = 'service';
                    $typeAccommodation = 'SERVICE ACCOMMODATION';
                    $departureDate  = '';
                }


                $editAction = action_button('edit', 'file/activity/'.$typeDesc.'/' . $file->id() . '/' . $ha->id(), $editActionAttr);
                $generateXo = action_button('generate', '#', array('title' => 'Generate Exchange Order', 'onclick'=>'return checkXO('.$ha->id().')'));

                $detailLink = '';
                $isXoGenerated = $ha->isXoGenerated();
                $status = $ha->getStatus();
                $allowGenerateXo = ( ! $isXoGenerated and $status == TourFileActivity::ACTIVITY_STATUS_ACTIVE and user_access('generate xo') )? TRUE : FALSE;
                if ($isXoGenerated) {
                    $detailLink = action_button('view', 'file/activity/detail/' . $ha->id(), array('title' => 'View Detail'));
                }

                if (!user_access('edit activity') or $isXoGenerated) {
                    $editAction = '';
                }

                if (! $allowGenerateXo) { //!user_access('generate xo') or $isXoGenerated
                    $generateXo = '';
                }
                if( $activityStatus == TourFileActivity::ACTIVITY_STATUS_VOID ){
                    $rowClass = 'bg-void';
                    $generateXo = '';
                    $editAction = '';

                }elseif($activityStatus == TourFileActivity::ACTIVITY_STATUS_DELETED){
                    $rowClass = 'bg-deleted';
                    $generateXo = '';
                    $editAction = '';
                }else{
                    $rowClass = '';
                }

                $deleteLink ='';
                if($editAction!='' && $generateXo !=''){

                    $deleteLink = action_button('delete', '#', array('title' => 'Delete Activity', 'onclick'=>'return deleteXO('.$ha->id().')'));
                }
                $out = '<tr class="'.$rowClass.'">';
                $out .= '<td>' . $count . '</td>';
                $out .= '<td>' . $typeAccommodation . '</td>';
                $out .= '<td>' . $ha->getArrivalDate()->format('Y-m-d') . '</td>';
                $out .= '<td>' . $departureDate . '</td>';
                $out .= '<td>' . $activityString . '</td>';
                $out .= '<td>' . $ha->getDescription() . '</td>';
                $out .= '<td>' . $editAction . $generateXo . $detailLink . $deleteLink.'</td>';
                $out .= '</tr>';

                echo $out;
                $count++;
            }
            ?>
            </tbody>
        </table>
        </div>
    <?php } else {
        no_results_found('No Activities found.');
    }
    ?>
</div>


<div class="modal fade" id="permittedUserForm" tabindex="-1" role="dialog" aria-labelledby="permittedUserFormLabel" aria-hidden="true">
    <div class="modal-dialog  modal-xlg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="permittedUserFormLabel">Permitted User</h4>
            </div>

            <form role="form" action="<?php echo site_url('file/addPermittedUsers'); ?>" method="post" class="validate" id="permittedUsersForm" data-file="<?php echo $file->id() ?>">
                <input type="hidden" name="file_id" value="<?php echo $file->id() ?>"/>

                <div class="alert alert-danger hidden" id="show_error"></div>
                <div class="modal-body">

                    <div class="form-group-sm">
                        <label for="permitted_users[]">File Permitted User</label>
                        <?php getUserMultiselectElement('permitted_users[]', $permitted_users, 'class="form-control multiselect" size="25"'); ?>
                    </div>

                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="SAVE ACTIVITY"/>
                    <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- hotel category edit form -->
<div class="modal fade" id="categoryForm" tabindex="-1" role="dialog" aria-labelledby="categoryFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="marketFormLabel">Hotel Category | Edit</h4>
            </div>

            <form role="form" class="validate" id="formCategory">

                <div class="col-md-12 alert alert-danger" id="market-alert" style="display:none"></div>

                <div class="modal-body">

                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="SAVE CATEGORY" />
                    <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- end hotel category edit form -->


<script type="text/javascript">
    $(document).ready(function () {
        $('#test').modal('show');
        $('#agent, #market, #nationality, #tourOfficer').select2();
        $('#agentContactPerson').select2({
            placeholder: "-- SELECT CONTACT PERSON --"
        });
        $('#xoForm').on('show.bs.modal', function (e) {
            var modal = $(this),
                button = $(e.relatedTarget),
                xo_type = button.attr('data-xo-type'),
                remoteFunction = '',
                form_type = button.attr('data-form-type'),
                file_id = '<?php echo $file->id()?>',
                activityId = button.attr('data-activity-id');

            $('#show_error').html('').addClass('hidden');

            if (xo_type == 'tour') {
                remoteFunction = 'getTourActivityForm';
            } else if (xo_type == 'travel') {
                remoteFunction = 'getTravelActivityForm';
            } else {
                remoteFunction = 'getHotelActivityForm';
            }

            var remoteUrl = Yarsha.config.base_url + 'file/ajax/' + remoteFunction + '/' + file_id;

            if (activityId == "") {
                modal.find('form').attr('data-activity-id', activityId);
            } else {
                remoteUrl = Yarsha.config.base_url + 'file/ajax/getActivityForm/' + file_id + '/' + activityId;
                modal.find('form').attr('data-activity-id', '');
            }

            $.ajax({
                type: 'GET',
                url: remoteUrl,
                success: function (res) {
                    modal.find('.modal-body').html(res);
                }
            });

        });

        $('#activitiesForm').submit(function (e) {

            e.preventDefault();

            var form = $(this);

            if (form.valid() !== true) return false;

            var activity_id = form.attr('data-activity-id');

            var post = form.serialize();

            $.ajax({
                type: 'POST',
                url: Yarsha.config.base_url + 'file/ajax/submitActivityForm',
                data: post,
                success: function (res) {
                    var data = $.parseJSON(res);

                    console.log(data);

                    if (data.status && data.status == 'success') {
                        window.location = '<?php echo site_url('file/detail/'.$file->id()) ?>';
                    } else {
                        console.log(data.message);
                        Yarsha.notify('error', data.message);
                        $('#show_error').html(data.message).addClass('hidden');
                    }
                }
            });
        });

        $('#agent').bind('change', function () {

            var obj = $(this),
                agentID = obj.val(),
                contactObj = $('#agentContactPerson'),
                option = '<option value="">-- SELECT CONTACT PERSON --</option>';

            if (agentID == 'undefined' || agentID == "") {
                contactObj.html(option);
                return false;
            }

            var selectedAgent = '<?php echo $agentContactPerson ?>';

            var remote_url = Yarsha.config.base_url + 'agent/ajax/getContactPersonByAgent/' + agentID;

            if( selectedAgent ){
                remote_url = remote_url +'/'+selectedAgent;
            }

            $.ajax({
                type: 'GET',
                url: remote_url,
                success: function (res) {
                    var data = $.parseJSON(res);
                    if (data.length > 0) {
                        for (var i = 0; i < data.length; i++) {
                            var deleted = ( data[i].deleted == true )? 'disabled="disabled"' : '';
                            option = option + '<option value="' + data[i].id + '" '+deleted+' >' + data[i].name + '</option>';
                        }
                    }

                    contactObj.html(option);
                    contactObj.val('<?php echo $agentContactPerson ?>').trigger('change');

            }
            });

        });

        $('#agent').trigger('change');
    });

    function deleteXO(id){

        if( confirm('Are you sure to delete Exchange Order?') ){
            $('body').mask('Processing ...');
            $.ajax({
                type: 'get',
                url: Yarsha.config.base_url + 'file/ajax/deleteXo/'+id,
                success: function(res){
                    var data = $.parseJSON(res);
                    if( data.status && data.status == 'success' ){
                        window.location = Yarsha.config.base_url+'file/detail/'+<?php echo $file->id()?>;
                        return true;
                    }else{
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

    function checkXO(id){
        if( confirm('Are you sure to generate exchange order?') ){
            $('body').mask('Checking for Exchange Order Generation ...');
            $.ajax({
                type: 'get',
                url: Yarsha.config.base_url + 'file/ajax/checkXo/'+id,
                success: function(res){
                    var data = $.parseJSON(res);
                    if( data.status && data.status == 'success' ){
                        window.location = Yarsha.config.base_url+'file/activity/generateXo/'+id;
                        return true;
                    }else{
                        $('body').unmask();
                        Yarsha.notify('warn', 'Margin is not set for activity detail.');
                        return false;
                    }
                }
            });
        } else {
            return false;
        }
    }

</script>

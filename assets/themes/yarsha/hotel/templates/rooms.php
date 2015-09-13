<?php
$roomCategories = $hotel->getRoomCategories();
$room_categories_options = [];
if( count($roomCategories) > 0 ){
    foreach($roomCategories as $rc){
        $room_categories_options[] = $rc->id();
    }
}

$roomTypes = $hotel->getRoomTypes();
$room_types_options = [];
if( count($roomTypes) > 0 ){
    foreach($roomTypes as $rt){
        $room_types_options[] = $rt->id();
    }
}

$roomPlans = $hotel->getRoomPlans();
$room_plans_options = [];
if( count($roomPlans) > 0 ){
    foreach($roomPlans as $rp){
        $room_plans_options[] = $rp->id();
    }
}
?>

<div class="col-md-12">
    <div class="panel panel-default" id="roomsDetail">
        <div class="panel-heading">
            <h3 class="panel-title">Room Information</h3>
        </div>
        <div class="table-responsive">
            <table class="table data-table">
                <tbody>
                <tr>
                    <th>Room Categories</th>
                    <td><?php echo implode(", ", $room_categories) ?></td>
                </tr>
                <tr>
                    <th>Room Types</th>
                    <td><?php echo implode(", ", $room_types) ?></td>
                </tr>
                <tr>
                    <th>Room Plans</th>
                    <td><?php echo implode(", ", $room_plans) ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <button id="updateRooms" class="btn btn-primary">UPDATE ROOMS</button>
        </div>
    </div>

    <div class="panel panel-default hidden" id="roomsForm">
        <div class="panel-heading">
            <h3 class="panel-title">Room Information</h3>
        </div>
        <form method="post" action="<?php echo site_url('hotel/updateRooms/'.$hotel->id()) ?>" >
            <div class="panel-body">
                <!-- hotel room categories -->
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Available Room Categories</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group-sm">
                                <label for="room_categories">Room Categories</label>
                                <?php getSelectHotelRoomCategories('room_categories[]',$room_categories_options,"size = 20, class='required multiselect form-control', id='room_categories'");?>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end hotel room categories -->

                <!-- hotel room types -->
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Available Room Types</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group-sm">
                                <label for="room_types">Room Types</label>
                                <?php getSelectHotelRoomTypes('room_types[]',$room_types_options,"size = 20, class='required multiselect form-control', id='room_types'");?>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end hotel room types -->

                <!-- hotel room plans -->
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Available Room Plans</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group-sm">
                                <label for="room_plans">Room Plans</label>
                                <?php getSelectHotelRoomPlans('room_plans[]',$room_plans_options,"size = 20, class='required multiselect form-control', id='room_plans'");?>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end hotel room plans -->
            </div>
            <div class="panel-footer">
                <input type="submit" class="btn btn-primary" value="UPDATE ROOMS" name="updateRooms">
                <button id="cancelUpdate" class="btn btn-danger">CANCEL</button>
            </div>
        </form>
    </div>

</div>

<script type="text/javascript">
    $(document).ready(function(){

        $('#updateRooms').click(function(){
            $('#roomsDetail').hide();
            $('#roomsForm').removeClass('hidden');
        });
        $('#cancelUpdate').click(function(){
            $('#roomsDetail').show();
            $('#roomsForm').addClass('hidden');
            return false;
        });



    });
</script>
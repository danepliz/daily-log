<?php loadJS(array('jquery.sheepit.min'));

$city = $hotel->getCity();
$state = $city->getState();
$countryID = $state->getCountry()->id();

?>
<form role="form" method="post" action="<?php echo site_url('hotel/edit/'.$hotel->slug()) ?>" class="" >
<div class="row">

    <div class="col-md-6">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Basic Information</h3>
            </div>
            <div class="panel-body">
                <div class="form-group-sm">
                    <label for="name">Hotel Category</label>
                    <!--                    search-select-->
                    <?php getHotelCategorySelectElement('hotel_category', $hotel->getCategory()->id(), 'class="required form-control" id="hotel-category-select"') ?>
                </div>

                <div class="form-group-sm">
                    <label for="name">Hotel Grade</label>
                    <?php getHotelGradeSelectElement('hotel_grade', $hotel->getGrade()->id(), 'class="required form-control" id="hotel-grade-select"') ?>
                </div>

                <div class="form-group-sm">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="required form-control" value="<?php echo $hotel->getName() ?>"/>
                </div>

                <div class="form-group-sm">
                    <label for="name">Hotel Status</label>
                    <?php getHotelStatusSelectElement('hotel_status', $hotel->getStatus(), 'class="required form-control" id="hotel-status-select"') ?>
                </div>
            </div>
        </div>

        <!-- contact information -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Contact Information</h3>
            </div>
            <div class="panel-body">
                <div class="form-group-sm">
                    <label for="fax">Fax</label>
                    <input type="text" class="form-control number_only" name="fax" value="<?php echo $hotel->getFax() ?>" />
                </div>

                <div class="form-group-sm">
                    <label for="website1">Website 1</label>
                    <input type="text" class="form-control website" name="website1"  value="<?php echo $hotel->getWebsite1() ?>" />
                </div>

                <div class="form-group-sm">
                    <label for="website2">Website 2</label>
                    <input type="text" class="form-control website" name="website2"  value="<?php echo $hotel->getWebsite2() ?>" />
                </div>

                <?php
                $phoneElements = array(
                    array(
                        'type'=> 'text',
                        'classes' => ' form-control required number_only',
                        'name' => 'contact_phone'
                    )
                );
                getSheepitFromElement('contact_phone', $phoneElements);
                ?>

                <?php
                $emailElements = array(
                    array(
                        'type'=> 'text',
                        'classes' => ' form-control required email',
                        'name' => 'contact_email'
                    )
                );
                getSheepitFromElement('contact_email', $emailElements);
                ?>
            </div>
        </div>
        <!-- end contact information -->
    </div>


    <div class="col-md-6">
        <!-- location -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Hotel Location</h3>
            </div>
            <div class="panel-body">
                <div class="box-body">

                    <div class="form-group-sm">
                        <label for="country">Country</label>
                        <?php getCountrySelectElement('country', $countryID, 'class="form-control required" id="country"') ?>
                    </div>

                    <div class="form-group-sm">
                        <label for="state">State</label>
                        <?php echo form_dropdown('state', array('' => ' -- SELECT STATE -- '), NULL, 'class="form-control required" id="state"') ?>
                    </div>

                    <div class="form-group-sm">
                        <label for="city">City</label>
                        <?php echo form_dropdown('city', array('' => ' -- SELECT CITY -- '), NULL, 'class="form-control required" id="city"') ?>
                    </div>

                    <div class="form-group-sm">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" class="required form-control"><?php echo $hotel->getAddress() ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <!-- ! location -->

        <!-- others -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Others</h3>
            </div>
            <div class="panel-body">
                <div class="form-group-sm">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" placeholder="other details" style="min-height: 45rem;" ><?php echo $hotel->getOthers() ?></textarea>
                </div>
            </div>
        </div>
        <!-- end others -->
    </div>

    <div class="clear"></div>

    <!-- hotel room categories -->
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Available Room Categories</h3>
            </div>
            <div class="panel-body">
                <div class="form-group-sm">
                    <label for="room_categories">Room Categories</label>
                    <?php getSelectHotelRoomCategories('room_categories[]',$room_categories,"size = 20, class='required multiselect form-control', id='room_categories'");?>
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
                    <?php getSelectHotelRoomTypes('room_types[]',$room_types,"size = 20, class='required multiselect form-control', id='room_types'");?>
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
                    <?php getSelectHotelRoomPlans('room_plans[]',$room_plans,"size = 20, class='required multiselect form-control', id='room_plans'");?>
                </div>

            </div>
        </div>
    </div>
    <!-- end hotel room plans -->


    <div class="col-md-12">
        <input type="submit" value="SAVE HOTEL" class="btn btn-primary">
        <input type="reset" value="CLEAR DATA" class="btn btn-primary">
        <a href="<?php echo site_url('hotel') ?>" class="btn btn-danger" >CANCEL</a>
    </div>

</div>
</form>


<script type="text/javascript">

    $(document).ready(function(){

        $('#country').bind('change',function(){
            getStatesByCountry($('#country').val());
        });

        $('#state').bind('change',function(){
            getCitiesByState($('#state').val());
        });

        $('#country').trigger('change');


        function getStatesByCountry(country_id){

            if( country_id == 'undefined' || country_id == '' ){ return }

            $.ajax({
                type: 'GET',
                url: Yarsha.config.base_url + 'location/ajax/getStates/'+country_id,
                success: function(res){
                    $('#state').html(res).val('<?php echo $state->id(); ?>');
                    $('#state').trigger('change');
                }
            });
        }

        function getCitiesByState(state_id){

            if( state_id == 'undefined' || state_id == '' ){ return }

            $.ajax({
                type: 'GET',
                url: Yarsha.config.base_url + 'location/ajax/getCities/'+state_id,
                success: function(res){
                    $('#city').html(res).val('<?php echo $city->id() ?>');
                }
            });
        }



    });


</script>




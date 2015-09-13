<?php
$city = $hotel->getCity();
$country = ( $hotel->getCountry() )? $hotel->getCountry() : NULL;
$address = array($hotel->getAddress(), $city);
//    $contactPersons = $hotel->getHotelContactPersons();
?>
<div class="row detail-page">

    <div class="col-md-6">

        <div class="panel panel-default">
            <div class="panel-heading bg-gray">
                <h3 class="panel-title">Basic Information</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <td class="text-bold">Name</td>
                        <td><?php echo $hotel->getName(); ?></td>
                    </tr>
                    <tr>
                        <td class="text-bold">Category</td>
                        <td><?php echo $hotel->getCategory()->getName() ?></td>
                    </tr>
                    <tr>
                        <td class="text-bold">Grade</td>
                        <td><?php echo $hotel->getGrade()->getName() ?></td>
                    </tr>
                    <tr>
                        <td class="text-bold">Status</td>
                        <td><?php echo $hotel->getStatus() ?></td>
                    </tr>
                    <tr>
                        <td class="text-bold">Remarks</td>
                        <td><?php echo $hotel->getOthers() ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <!-- location -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Contact Information</h3>
            </div>
            <div class="table-responsive">
                <table class="table data-table">
                    <tbody>
                    <tr>
                        <td class="text-bold">Country</td>
                        <td><?php echo $country ?></td>
                    </tr>
                    <tr>
                        <td class="text-bold">Address</td>
                        <td><?php echo implode(", ", $address); ?></td>
                    </tr>
                    <tr>
                        <td class="text-bold">Phone</td>
                        <td><?php echo implode("</br>", $hotel->getPhones()) ?></td>
                    </tr>
                    <tr>
                        <td class="text-bold">Website</td>
                        <td class="email">
                            <?php
                            $hWebsite1 = $hotel->getWebsite1();
                            $hWebsite2 = $hotel->getWebsite2();
                            $hWebsiteArr = [];
                            if( $hWebsite1 != '' ){
                                $link1 = formatWebsite($hWebsite1);
                                $hWebsiteArr[] = '<a href="'.$link1.'" target="_blank">'.$hWebsite1.'</a>';
                            }
                            if( $hWebsite2 != '' ){
                                $link2 = formatWebsite($hWebsite2);
                                $hWebsiteArr[] = '<a href="'.$link2.'" target="_blank">'.$hWebsite2.'</a>';
                            }
                            echo implode("</br>", $hWebsiteArr);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-bold">Email</td>
                        <td class="email">
                            <?php
                            $hEmails = $hotel->getEmails();
                            $hEmailsArr = [];
                            foreach($hEmails as $he){
                                $hEmailsArr[] = '<a href="mailto:'.$he.'">'.$he.'</a>';
                            }
                            echo implode("</br>", $hEmailsArr)
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-bold">Fax</td>
                        <td><?php echo $hotel->getFax() ?></td>
                    </tr>
                    </tbody>
                </table>
            </div></div>
        <!-- ! location -->
    </div>

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Payment</h3>
            </div>
            <div class="table-responsive">
                <table class="table data-table">
                    <tbody>
                    <tr>
                        <th>Payment Strategy</th>
                        <td><?php echo \hotel\models\Hotel::$paymentStrategies[$hotel->getPaymentStrategy()] . ' ( '.$hotel->getPaymentStrategyPercent().'% )' ?></td>
                    </tr>
                    <tr>
                        <th>Payable Currencies</th>
                        <td><?php echo implode(", ", $payable_currencies) ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="panel panel-default">
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
        </div></div>

    <!-- controls -->
    <div class="col-md-12">
        <br/>
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#contactPersons" id="showContactPersons">VIEW CONTACT PERSONS</a>
        <a href="<?php echo site_url('hotel/rate/show/'.$hotel->slug()) ?>" class="btn btn-primary" id="viewHotelRates">VIEW HOTEL RATES</a>
        <?php if( user_access('administer hotel') ){?>
            <a href="<?php echo site_url('hotel/edit/'.$hotel->slug()) ?>" class="btn btn-primary" >EDIT HOTEL</a>
        <?php } ?>
        <a href="<?php echo site_url('hotel') ?>" class="btn btn-danger">CANCEL</a>
    </div>
    <!-- controls -->


    <!-- contact persons modal -->
    <div class="modal fade" id="contactPersons" tabindex="-1" role="dialog" aria-labelledby="contactPersonsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Contact Persons | <?php echo ucwords($hotel->getName()) ?></h4>
                </div>

                <div class="modal-body">
                    <table class="table table-striped data-table">
                        <tbody>
                        <tr>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Address</th>
                            <th>Skype</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                        <?php $count = 1; foreach($contactPersons as $cp){ $cpID = $cp->id(); ?>
                            <tr id="cp-data-<?php echo $cpID ?>">
                                <td><?php echo $cp->getName() ?></td>
                                <td><?php echo $cp->getDesignation() ?></td>
                                <td><?php echo $cp->getAddress() ?></td>
                                <td><?php echo $cp->getSkype() ?></td>
                                <td><?php echo implode("<br /> ", $cp->getPhones()) ?></td>
                                <td class="email">
                                    <?php
                                    $pEmails = $cp->getEmails();
                                    $pEmailsArr = [];
                                    foreach($pEmails as $pe){
                                        $pEmailsArr[] = '<a href="mailto:'.$pe.'">'.$pe.'</a>';
                                    }
                                    echo implode("<br /> ", $pEmailsArr);
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if(user_access('edit hotel contact persons')){ //contactPersonsForm //hotel/contactPerson/edit/'.$cp->id()
                                        echo action_button('edit', '#', array('title' => 'Edit' .$cp->getName(),'class'=>"edit-contact-person",'data-person-id' => $cpID, 'data-toggle' => 'modal', 'data-target' => '#contactPersonsForm', 'data-form-type'=>'E'));
                                    }
                                    echo action_button('delete', '#', array('data-bb' => 'custom_delete', 'title' => 'Delete' .$cp->getName(), 'data-id' => $cp->id()));
                                    ?>
                                </td>
                            </tr>
                            <?php $count++; } ?>
                        </tbody>
                    </table>

                </div>

                <div class="modal-footer">

                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#contactPersonsForm">ADD CONTACT PERSON</a>
                    <a href="#" class="btn btn-danger" data-dismiss="modal">CLOSE</a>

                </div>
            </div>
        </div>
    </div>
    <!-- end contact persons modal -->

    <!-- contact persons form -->
    <div class="modal fade" id="contactPersonsForm" tabindex="-1" role="dialog" aria-labelledby="contactPersonsFormLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="contactPersonsFormLabel">Contact Persons | <?php echo ucwords($hotel->getName()) ?></h4>
                </div>

                <form role="form" class="validate" id="formContactPerson" data-person="0" data-hotel="<?php echo $hotel->id() ?>">
                    <div class="modal-body">

                    </div>

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="SAVE PERSON" />
                        <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <!-- end contact persons form -->



</div>




<script type="text/javascript">
    $(document).ready(function(){

        $('#contactPersonsForm').on('show.bs.modal', function (e) {
            var modal = $(this),
                button = $(e.relatedTarget),
                form_type = button.attr('data-form-type'),
                remoteUrl = Yarsha.config.base_url+'hotel/ajax/getPersonForm';

            modal.find('form').attr('data-person', 0);

            if( form_type == 'E' ){
                var _person_id = button.attr('data-person-id');
                modal.find('form').attr('data-person', _person_id);
                remoteUrl = remoteUrl + '/' + _person_id;
            }

            $.ajax({
                type: 'GET',
                url: remoteUrl,
                success: function(res){
                    modal.find('.modal-body').html(res);
                }
            });

        });

        $('#formContactPerson').submit(function(e){
            e.preventDefault();

            var _form = $(this);

            if( ! _form.valid() ){ return false; }

            var postData = _form.serialize(),
                person_data = _form.attr('data-person'),
                hotelId = _form.attr('data-hotel'),
                baseURL = Yarsha.config.base_url + 'hotel/ajax/savePerson/'+hotelId,
                isEditing = ( person_data && person_data !== '0' && person_data !== "" )? true : false,
                remoteURL = isEditing ? baseURL + '/' + person_data : baseURL;

            $('#contactPersonsForm').mask('Processing Request ...');

            $.ajax({
                type: 'POST',
                url: remoteURL,
                data: postData,
                success: function(res){
                    var data = $.parseJSON(res);
                    console.log(data);
                    if( data.status == 'success' ){
                        if( isEditing ){
                            $('#contactPersons').find('table tr#cp-data-'+person_data).html(data.data.table_data);
                        }else{
                            $('#contactPersons').find('table tbody').append(data.data.table_data);
                        }
//                        _form.clear();
                    }else{
                        console.log(data.message);
                    }

                    $('#contactPersonsForm').unmask();
                    $('#contactPersonsForm').modal('hide');
                }
            });

            return false;

        });

    });

    $("body").on('click', "a[data-bb='custom_delete']",function(e){
        var $me = $(this);
        bootbox.confirm('Are you sure you want to delete?',function(result){
            if(result==true){
                removeData($me.attr("data-id"));
            }
        });
        function removeData(id) {
            $.ajax({
                type: "POST",
                url:  Yarsha.config.base_url + 'hotel/ajax/deleteContactPerson',
                data: {id: id},
                success: function(res){
                    var data = $.parseJSON(res);
                    if(data.status && data.status == 'success'){
                        $('#cp-data-'+id).remove();
//                        window.location = '<?php //eho site_url('hotel/detail') ?>//'
                    }else{
                        $("#alertarea").html('<div class="alert alert-success" role="alert">'+data.message+'</div>');
                    }
                    return true;
                }
            });
        }
        return false;
    });
</script>



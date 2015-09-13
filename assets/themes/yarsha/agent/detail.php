<?php
use agent\models\Agent;

$city = $agent->getCity();
$country = ( $agent->getCountry() )? $agent->getCountry()->getName() : '';
$phones = array($agent->getPhone1(), $agent->getPhone2());
$emails = array($agent->getEmail1(), $agent->getEmail2());
//$website = array($agent->getWebsite1(), $agent->getWebsite2());
$websites = [];
if( $agent->getWebsite1() != '' ){
    $website1 = formatWebsite($agent->getWebsite1());
    $websites[] = anchor($website1, $website1, 'target="_blank"');
}

if( $agent->getWebsite2() != '' ){
    $website2 = formatWebsite($agent->getWebsite2());
    $websites[] = anchor($website2, $website2, 'target="_blank"');
}

$address = [];
if( $agent->getAddress() != '' ){ $address[] = $agent->getAddress(); }
if( $city != '' ){ $address[] = $city; }

?>
<div class="row detail-page">

    <div class="col-md-6">

        <div class="panel panel-default">
            <div class="panel-heading bg-gray">
                <h3 class="panel-title">Basic Information</h3>
            </div>

            <table class="table table-striped">
                <tbody>
                <tr>
                    <td class="text-bold">Name</td>
                    <td><?php echo $agent->getName(); ?></td>
                </tr>
                <tr>
                    <td class="text-bold">Phone</td>
                    <td><?php echo implode("</br>", $phones) ?></td>
                </tr>
                <tr>
                    <td class="text-bold">Email</td>
                    <td>
                        <?php
                        foreach($emails as $email){
                            echo '<a href="mailto:'.$email.'">'.$email.'</a><br />';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-bold">Website</td>
                    <td>
                        <?php
                            echo implode("</br>", $websites);
                        ?>

                    </td>
                </tr>
                <tr>
                    <td class="text-bold">Fax</td>
                    <td><?php echo $agent->getFax() ?></td>
                </tr>
                <tr>
                    <td class="text-bold">PO BOX</td>
                    <td><?php echo $agent->getPOBox() ?></td>
                </tr>
                <tr>
                    <td class="text-bold">Status</td>
                    <td><?php echo Agent::$status_desc[$agent->getStatus()] ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>


    <div class="col-md-6">
        <!-- location -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Contact Information</h3>
            </div>
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
                    <td class="text-bold">Remarks</td>
                    <td><?php echo $agent->getDescription() ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- ! location -->
    </div>

    <!-- controls -->
    <div class="col-md-12">
          <?php
        if(user_access('view agent contact person')) {?>
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#contactPersons" id="showContactPersons">VIEW CONTACT PERSONS</a>
         <?php }?>
        <?php if(user_access('edit agent')) {?>
        <a href="<?php echo site_url('agent/edit/'.$agent->getSlug()) ?>" class="btn btn-primary" >EDIT AGENT</a>
        <?php } ?>
        <a href="#" class="btn btn-primary" data-id="<?php echo $agent->id()?>" data-target="#permittedUserForm" data-toggle="modal" title="Permitted User <?php echo $agent->getName() ?>">PERMITTED USERS</a>

        <a href="<?php echo site_url('agent') ?>" class="btn btn-danger">CANCEL</a>
    </div>
    <!-- controls -->


    <!-- contact persons modal -->
    <div class="modal fade" id="contactPersons" tabindex="-1" role="dialog" aria-labelledby="contactPersonsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Contact Persons | <?php echo ucwords($agent->getName()) ?></h4>
                </div>

                <div class="modal-body">
                    <table class="table table-striped data-table">
                        <tbody>
                        <tr>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Address</th>
                            <th>Skype Id</th>
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
                                <td><?php echo implode("/ ", $cp->getPhones()) ?></td>
                                <td class="email">
                                    <?php
                                    $emails = $cp->getEmails();
                                    foreach($emails as $email){
                                        echo '<a href="mailto:'.$email.'">'.$email.'</a><br />';
                                    }
                                    ?>
<!--                                    <a href="#">--><?php //echo implode("/ ", $cp->getEmails()) ?><!--</a>-->
                                </td>
                                <td>
                                    <?php
                                    if(user_access('administer agent contact person')){ //contactPersonsForm //hotel/contactPerson/edit/'.$cp->id()
                                        echo action_button('edit', '#', array('title' => 'Delete' .$cp->getName(), 'class'=>"edit-contact-person",'data-person-id' => $cpID, 'data-toggle' => 'modal', 'data-target' => '#contactPersonsForm', 'data-form-type'=>'E'));
                                        echo action_button('delete', '#', array('data-bb' => 'custom_delete', 'title' => 'Delete' .$cp->getName(), 'data-id' => $cp->id()));
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php $count++; } ?>
                        </tbody>
                    </table>

                </div>

                <div class="modal-footer">
                    <?php
                    if(user_access('administer agent contact person')){ ?>
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#contactPersonsForm">ADD CONTACT PERSON</a>
                    <?php } ?>
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
                    <h4 class="modal-title" id="contactPersonsFormLabel">Contact Persons | <?php echo ucwords($agent->getName()) ?></h4>
                </div>

                <form role="form" class="validate" id="formContactPerson" data-person="0" data-hotel="<?php echo $agent->id() ?>">
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

    <div class="modal fade" id="permittedUserForm" tabindex="-1" role="dialog" aria-labelledby="permittedUserFormLabel" aria-hidden="true">
        <div class="modal-dialog  modal-xlg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="permittedUserFormLabel">Permitted User</h4>
                </div>

                <form role="form" action="<?php echo site_url('agent/addPermittedUsers'); ?>" method="post" class="validate" id="permittedUsersForm" data-file="<?php echo $agent->id() ?>">
                    <input type="hidden" name="agent_id" value="<?php echo $agent->id() ?>"/>
                    <div class="alert alert-danger hidden" id="show_error"></div>
                    <div class="modal-body">

                        <div class="form-group-sm">
                            <label for="permitted_users[]"> Add Permitted User</label>
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



</div>




<script type="text/javascript">
    $(document).ready(function(){

        $('#contactPersonsForm').on('show.bs.modal', function (e) {
            var modal = $(this),
                button = $(e.relatedTarget),
                form_type = button.attr('data-form-type'),
                remoteUrl = Yarsha.config.base_url+'agent/ajax/getPersonForm';

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
                agentID = _form.attr('data-hotel'),
                baseURL = Yarsha.config.base_url + 'agent/ajax/savePerson/'+agentID,
                isEditing = ( person_data && person_data !== '0' && person_data !== "" )? true : false,
                remoteURL = isEditing ? baseURL + '/' + person_data : baseURL;

            $('#contactPersonsForm').mask('Processing Request ...');

            $.ajax({
                type: 'POST',
                url: remoteURL,
                data: postData,
                success: function(res){
                    var data = $.parseJSON(res);
                    if( data.status == 'success' ){
                        if( isEditing ){
                            $('#contactPersons').find('table tr#cp-data-'+person_data).html(data.data.table_data);
                        }else{
                            $('#contactPersons').find('table tbody').append(data.data.table_data);
                        }
                        $('#contactPersonsForm').unmask();
                        $('#contactPersonsForm').modal('hide');
                    }else{
                        Yarsha.notify('warn', data.message);
                        $('#contactPersonsForm').unmask();
                    }
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
                url:  Yarsha.config.base_url + 'agent/ajax/deleteContactPerson',
                data: {id: id},
                success: function(res){
                    var data = $.parseJSON(res);
                    if(data.status && data.status == 'success'){
                        $('#cp-data-'+id).remove();
//                        window.location = '<?php //echo site_url('agent/detail') ?>//'
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



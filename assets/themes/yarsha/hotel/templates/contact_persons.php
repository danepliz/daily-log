<div class="col-md-12">
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
                    echo action_button('delete', '#', array('data-bc' => 'custom_delete', 'title' => 'Delete' .$cp->getName(), 'data-id' => $cp->id()));
                    ?>
                </td>
            </tr>
            <?php $count++; } ?>
        </tbody>
    </table>

    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#contactPersonsForm">ADD CONTACT PERSON</a>
</div>

<!-- contact persons form -->
<div class="modal fade" id="contactPersonsForm" tabindex="-1" role="dialog" aria-labelledby="contactPersonsFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="contactPersonsFormLabel">Contact Persons | <?php echo ucwords($hotel->getName()) ?></h4>
            </div>

            <form role="form" class="validate" id="formContactPerson" data-person="0" data-hotel="<?php echo $hotel->id() ?>">
                <div class="alert alert-danger alert-dismissable personError hidden"></div>
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

<script type="text/javascript">
    $(document).ready(function(){
        $('th').click(function(){
            console.log('clicked');
        });

        /* CONTACT PERSON FORM RENDERING */
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

        /* CONTACT PERSON FORM SUBMISSION */
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
            $('body').mask('Processing Request ...');
            $.ajax({
                type: 'POST',
                url: remoteURL,
                data: postData,
                success: function(res){
                    var data = $.parseJSON(res);
                    if( data.status == 'success' ){
                        window.location = Yarsha.config.base_url+'hotel/detail/<?php echo $hotel->slug() ?>?t=contactPersons';
//                        $('#contactPersonsForm').modal('hide');
                    }else{
                        $('.personError').html(data.message).removeClass('hidden');
                    }
                    $('body').unmask();
                }
            });
            return false;
        });

        $("body").on('click', "a[data-bc='custom_delete']", function (e) {
            var $me = $(this);
            bootbox.confirm('Are you sure you want to delete?', function (result) {
                if (result == true) {
                    removeData($me.attr("data-id"));
                }
            });
            return false;
        });

        function removeData(id) {
            $.ajax({
                type: "POST",
                url: Yarsha.config.base_url + 'hotel/ajax/deleteContactPerson',
                data: {id: id},
                success: function (res) {
                    var data = $.parseJSON(res);
                    if( data.status == 'success' ) {
                        window.location = Yarsha.config.base_url + 'hotel/detail/<?php echo $hotel->slug() ?>?t=contactPersons';
                    }
//                        alert(res)
                }
            });
        }




    });
</script>
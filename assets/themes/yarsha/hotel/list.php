<?php

$fName = isset($post['name']) ? $post['name'] : '';
$fCountry = isset($post['country'])? $post['country'] : NULL;
//$fState = isset($post['state'])? $post['state'] : NULL;
$fCity = isset($post['city'])? $post['city'] : NULL;
$fStatus = isset($post['status'])? $post['status'] : NULL;
$fCategory = isset($post['category'])? $post['category'] : NULL;
$fGrade = isset($post['grade'])? $post['grade'] : NULL;

?>

<div class="row">
<!--    --><?php
//    if(user_access('administer hotel')) {?>
<!--    <div class="col-md-12"><a href="--><?php //echo site_url('hotel/add') ?><!--" class="btn btn-primary btn-margin">ADD NEW HOTEL</a></div>-->
<!--    --><?php //} ?>

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Search Hotel</h3> </div>
            <div class="panel-body">
                <form action="" method="get" role="form" name="h_filter" id="hotelFilterForm">
                    <div class="form-group-sm col-md-3">
                        <input type="text" placeholder="Hotel Name" name="name" class="form-control" value="<?php echo $fName ?>" id="name">
                    </div>

                    <div class="form-group-sm col-md-3">
                        <?php getHotelCategorySelectElement('category', $fCategory, 'class="form-control" id="category"') ?>
                    </div>

                    <div class="form-group-sm col-md-3">
                        <?php getHotelGradeSelectElement('grade', $fGrade, 'class="form-control" id="grade"') ?>
                    </div>

                    <div class="form-group-sm col-md-3">
                        <?php getSelectHotelStatus('status', $fStatus, 'class="form-control" id="status"') ?>
                    </div>

                    <div class="clear"></div>

                    <div class="form-group-sm col-md-3">
                        <?php getCountrySelectElement('country', $fCountry, 'class="form-control" id="country"') ?>
                    </div>

<!--                    <div class="form-group-sm col-md-3">-->
<!--                        --><?php //echo form_dropdown('state', array('' => '-- SELECT STATE --'), $fState, 'class="form-control" id="state"') ?>
<!--                    </div>-->

                    <div class="form-group-sm col-md-3">
                        <?php echo form_input('city', $fCity, 'class="form-control" id="city" placeholder="city"' ); ?>
                        <?php //echo form_dropdown('city', array('' => '-- SELECT CITY --'), $fCity, 'class="form-control" id="city"') ?>
                    </div>

                    <div class="form-group-sm col-md-3">

                        <input type="submit" name="submit" value="SEARCH" class="btn btn-primary">
                        <input type="reset" name="reset" value="RESET" class="btn btn-danger">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xs-12" id="hotel-category-list">
        <div class="panel panel-default">
            <div class="panel-heading bg-gray">

                <h3 class="panel-title">Hotel List</h3>
            </div>

            <?php if( count($hotels) > 0 ) { ?>
            <div class="table-responsive">
                <table class="table">
                    <tbody>

                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Country</th>
                        <th>Address</th>
                        <th>Contacts</th>
                        <th>Emails</th>
                        <th>Website</th>
                        <th>Status</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>

                    <?php
                    $count = ($offset)? $offset + 1 : 1;
                    foreach( $hotels as $h ){
                        $city = $h->getCity();
                        ?>
                        <tr class="<?php echo strtolower($h->getStatus())?>">
                            <td><?php echo $count ?></td>
                            <td><?php echo $h->getName() ?></td>
                            <td><?php echo $h->getCountry()->getName() ?></td>
                            <td><?php echo $h->getAddress() ?></td>
                            <td><?php echo implode('<br />', $h->getPhones()) ?></td>
                            <td class="email">
                                <?php
                                    $emails = $h->getEmails();
                                    foreach($emails as $e){
                                        echo '<a href="mailto:'.$e.'">'.$e.'</a><br />';
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                if( $h->getWebsite1() != '' ){
                                    $website1 = formatWebsite($h->getWebsite1());
                                    echo anchor($website1, $h->getWebsite1(), 'target="_blank"');
                                }
                                if( $h->getWebsite2() != '' ){
                                    echo '<br />';
                                    $website2 = formatWebsite($h->getWebsite2());
                                    echo anchor($website2, $h->getWebsite2(), 'target="_blank"');
                                }
                                ?>
<!--                                <a target="_blank" href="--><?php //echo $h->getWebsite1() ?><!--">--><?php //echo $h->getWebsite1() ?><!--</a>-->
                            </td>
                            <td><?php echo $h->getStatus()?></td>
                            <td ><?php
                                 if(user_access('view hotel')){
                                     echo action_button('view', 'hotel/detail/'.$h->slug(), array('title'=>'view detail'));}
                                 if(user_access('view hotel rates')){
                                     echo action_button('rate', 'hotel/rate/show/'.$h->slug(), array('title'=>'view rates'));}
                                 if( user_access('administer hotel') ){
                                     echo action_button('edit', 'hotel/edit/'.$h->slug(), array('title'=>'Edit' .$h->getName())); }?>
                            </td>
                        </tr>

                        <?php
                        $count++;
                    }
                    ?>

                    </tbody>
                </table>

                <?php if( isset($pagination) ){ echo '<div class="pagination">'.$pagination.'</div>'; }?>

            <?php }else{ no_results_found('No Hotels Found.'); } ?>

        </div>
    </div>

</div>
    </div>



<script type="text/javascript">
    $(document).ready(function(){
        $('#category, #grade, #status, #country').select2();

        var resetBtn = $('input[type="reset"]');

        resetBtn.click(function(e){
            e.preventDefault();
            $('#name, #category, #grade, #status, #city').val('');
            if( $('#country').find('option').length > 1 ){
                $('#country').val('');
            }
        })


    });

</script>

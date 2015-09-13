<?php

$packages = $hotel->getPackages();
$preFilledMainPackages = [];
$preFilledExtraPackages = [];

$mainPackagesData = [];
$extraPackagesData = [];

$typeMain = \hotel\models\HotelPackage::PACKAGE_TYPE_MAIN;
$typeExtra = \hotel\models\HotelPackage::PACKAGE_TYPE_EXTRA;

if( count($packages) ){
    foreach($packages as $p){
        if( $p->getType() == \hotel\models\HotelPackage::PACKAGE_TYPE_MAIN ){
            $id = 'main_db_'.$p->id();
            $preFilledMainPackages[] = $id;
            $mainPackagesData[$id]['name'] = $p->getName();
            $mainPackagesData[$id]['description'] = $p->getDescription();
            $mainPackagesData[$id]['id'] = $p->id();
            $mainPackagesData[$id]['nights'] = $p->getNumberOfNights();
        }else{
            $id = 'extra_db_'.$p->id();
            $preFilledExtraPackages[] = $id;
            $extraPackagesData[$id]['name'] = $p->getName();
            $extraPackagesData[$id]['description'] = $p->getDescription();
            $extraPackagesData[$id]['id'] = $p->id();
            $extraPackagesData[$id]['nights'] = $p->getNumberOfNights();
        }
    }
}

?>


<div class="col-md-12">

    <table class="table data-table" id="packagesDetail">
        <tr> <th>Name</th><th>No of Nights</th> <th>Description</th> </tr>
        <tr> <td colspan="3" class="bg-gray">PACKAGES</td> </tr>
        <?php
        if(count($mainPackagesData)){
            foreach($mainPackagesData as $k =>$mp) {
                echo '<tr> <td>'.$mp['name'].'</td>

                <td>'.$mp['nights'].'</td>

                 <td>'.$mp['description'].'</td> </tr>';
            }
        }else{
            echo '<tr><td colspan="3"><div class="alert alert-danger">No Packages Added.</div></td></tr> ';
        }
        ?>
        <tr> <td colspan="3" class="bg-gray">EXTRA</td> </tr>
        <?php
        if(count($extraPackagesData)){
            foreach($extraPackagesData as $xk => $xp) {
                echo '<tr> <td>'.$xp['name'].'</td><td>'.$xp['nights'].'</td> <td>'.$xp['description'].'</td> </tr>';
            }
        }else{
            echo '<tr><td colspan="3"><div class="alert alert-danger">No Extras Added.</div></td></tr> ';
        }
        ?>
        <tr><td colspan="3"><button id="addUpdatePackages" class="btn btn-primary">ADD/UPDATE PACKAGES</button></td> </tr>
    </table>

    <form id="packagesForm" class="validate hidden" method="post" action="<?php echo site_url('hotel/updatePackages/'.$hotel->id()) ?>" >

        <table class="table table-striped data-table form-group-sm">
            <tbody id="hotelMainPackages">
                <tr><th colspan="4"  class="bg-gray">PACKAGES</th></tr>
                <tr>
                    <th>Name</th>
                    <th>Number of Nights</th>
                    <th>Description</th>
                    <th>&nbsp;</th>
                </tr>

                <tr id="hotelMainPackages_template">
                    <td>
                        <?php echo form_input("packages[#index#][name]",NULL,'id="hotelMainPackages_#index#_name" class="form-control required"')?>
                        <input type="hidden" name="packages[#index#][old_name]" id="hotelMainPackages_#index#_old_name" value="" />
                    </td>
                    <td><?php echo form_input("packages[#index#][nights]", 1,'id="hotelMainPackages_#index#_nights"  class="form-control required"') ?></td>
                    <td><?php echo form_input("packages[#index#][description]",NULL,'id="hotelMainPackages_#index#_description"  class="form-control required"') ?></td>
                    <td>
                        <input type="hidden" name="packages[#index#][id]" id="hotelMainPackages_#index#_id" value="" />
                        <a id="hotelMainPackages_remove_current"><i class="icon fa fa-times"></i></a>
                    </td>
                </tr>

                <?php
                    if( count($mainPackagesData) ){
                        foreach($mainPackagesData as $dk => $dv){
                ?>
                    <tr id="<?php echo $dk ?>">
                        <td>
                            <?php echo form_input("packages[#index#][name]",$dv['name'],'id="hotelMainPackages_#index#_name" class="form-control required"')?>
                            <input type="hidden" name="packages[#index#][old_name]" id="hotelMainPackages_#index#_old_name" value="<?php echo $dv['name'] ?>" />
                        </td>
                        <td><?php echo form_input("packages[#index#][nights]",$dv['nights'],'id="hotelMainPackages_#index#_nights"  class="form-control required"') ?></td>
                        <td><?php echo form_input("packages[#index#][description]",$dv['description'],'id="hotelMainPackages_#index#_description"  class="form-control required"') ?></td>
                        <td>
                            <input type="hidden" name="packages[#index#][id]" id="hotelMainPackages_#index#_id" value="<?php echo $dv['id'] ?> " />
                            <a id="hotelMainPackages_remove_current"><i class="icon fa fa-times"></i></a>
                        </td>
                    </tr>
                <?php
                        }
                    }
                ?>

                <tr id="hotelMainPackages_noforms_template">
                    <td colspan="4">No Main Packages</td>
                </tr>

                <tr id="hotelMainPackages_controls">
                    <td colspan="4"><a class="btn btn-default" id="hotelMainPackages_add"><i class="fa fa-plus-square"></i>&nbsp; Add Another</a></td>
                </tr>
            </tbody>
        </table>

        <hr />

        <table class="table table-striped data-table form-group-sm">
            <tbody id="hotelExtraPackages">
            <tr><th colspan="3"  class="bg-gray">EXTRA</th></tr>
            <tr>
                <th>Name</th>
                <th>No of Nights</th>
                <th>Description</th>
                <th>&nbsp;</th>
            </tr>

            <tr id="hotelExtraPackages_template">
                <td>
                    <?php echo form_input("extra[#index#][name]",NULL,'id="hotelExtraPackages_#index#_name" class="form-control required"')?>
                    <input type="hidden" name="extra[#index#][old_name]" id="hotelExtraPackages_#index#_old_name" value="" />
                </td>
                <td><?php echo form_input("extra[#index#][nights]",1,'id="hotelExtraPackages_#index#_nights"  class="form-control  numeric required"') ?>


                </td>

                <td><?php echo form_input("extra[#index#][description]",NULL,'id="hotelExtraPackages_#index#_description"  class="form-control required"') ?></td>
                <td>
                    <input type="hidden" name="extra[#index#][id]" id="hotelExtraPackages_#index#_id" value="" />
                    <a id="hotelExtraPackages_remove_current"><i class="icon fa fa-times"></i></a>
                </td>
            </tr>

            <?php
            if( count($extraPackagesData) ){
                foreach($extraPackagesData as $dxk => $dxv){
                    ?>
                    <tr id="<?php echo $dxk ?>">
                        <td>
                            <?php echo form_input("extra[#index#][name]",$dxv['name'],'id="hotelExtraPackages_#index#_name" class="form-control required"')?>
                            <input type="hidden" name="extra[#index#][old_name]" id="hotelExtraPackages_#index#_old_name" value="<?php echo $dxv['name']?>" />
                        </td>
                        <td><?php echo form_input("extra[#index#][nights]",$dxv['nights'],'id="hotelExtraPackages_#index#_nights"  class="form-control required"') ?></td>

                        <td><?php echo form_input("extra[#index#][description]",$dxv['description'],'id="hotelExtraPackages_#index#_description"  class="form-control required"') ?></td>
                        <td>
                            <input type="hidden" name="extra[#index#][id]" id="hotelExtraPackages_#index#_id" value="<?php echo $dxv['id'] ?> " />
                            <a id="hotelExtraPackages_remove_current"><i class="icon fa fa-times"></i></a>
                        </td>
                    </tr>
                <?php
                }
            }
            ?>

            <tr id="hotelExtraPackages_noforms_template">
                <td colspan="3">No Extra</td>
            </tr>

            <tr id="hotelExtraPackages_controls">
                <td colspan="3"><a class="btn btn-default" id="hotelExtraPackages_add"><i class="fa fa-plus-square"></i>&nbsp; Add Another</a></td>
            </tr>
            <tr>
                <td colspan="3">
                    <input type="submit" class="btn btn-primary" value="UPDATE PACKAGES">
                    <button id="cancelPackagesUpdate" class="btn btn-danger">CANCEL</button>
                </td>
            </tr>
            </tbody>
        </table>


    </form>




</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#addUpdatePackages').click(function(){
            $('#packagesDetail').hide();
            $('#packagesForm').removeClass('hidden');
        });
        $('#cancelPackagesUpdate').click(function(){
            $('#packagesDetail').show();
            $('#packagesForm').addClass('hidden');
            return false;
        });


        var hotelMainPackageSheepIt = $('#hotelMainPackages').sheepIt({
            separator: '',
            allowRemoveLast: true,
            allowRemoveCurrent: true,
            allowRemoveAll: true,
            allowAdd: true,
//            allowAddN: true,
//            maxFormsCount: 4,
            minFormsCount: 1,
            iniFormsCount: 1,
            pregeneratedForms: <?php echo json_encode($preFilledMainPackages); ?>

        });

        var hotelExtraPackageSheepIt = $('#hotelExtraPackages').sheepIt({
            separator: '',
            allowRemoveLast: true,
            allowRemoveCurrent: true,
            allowRemoveAll: true,
            allowAdd: true,
//            allowAddN: true,
//            maxFormsCount: 4,
            minFormsCount: 1,
            iniFormsCount: 1,
            pregeneratedForms: <?php echo json_encode($preFilledExtraPackages); ?>

        });
    });
</script>
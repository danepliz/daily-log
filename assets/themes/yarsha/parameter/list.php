<?php

$radioElements = array(
    array(
        'name' => 'travel_xo',
        'label'=> 'Travel X/O',
        'value' => 0
    ),
    array(
        'name' => 'transport_xo',
        'label'=> 'Transport X/O',
        'value' => false
    ),
    array(
        'name' => 'hotel_xo',
        'label'=> 'Hotel X/O',
        'value' => false
    ),
    array(
        'name' => 'entrance_xo',
        'label'=> 'Entrance X/O',
        'value' => false
    ),
    array(
        'name' => 'other_xo',
        'label'=> 'Other X/O',
        'value' => false
    )
);

$options = array(
    '0' => 'NO',
    '1' => 'YES'
);

?>

<div class="row">

    <div class="col-xs-12 margin">
        <?php
        if(user_access('administer parameter')) {?>
        <a href="javascript:void(0)" class="btn btn-primary" id="add-parameter-btn" >Add New Parameter</a>
        <?php } ?>
    </div>

    <div class="col-md-12 margin" id="add-parameter-form-wrapper" style="display: none">

        <div class="panel panel-default">

            <div class="panel-body">
                <form role="form" method="post" action="" >

                    <div class="col-md-12">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control required" placeholder="activity name" autocomplete="off" />
                    </div>
                    </div>

<!--                    <div class="form-group">-->
<!--                        <label for="description">Description</label>-->
<!--                        <textarea name="description" class="form-control" placeholder="add description"></textarea>-->
<!--                    </div>-->

                    <?php
                        foreach($radioElements as $radio) {
                            $elemName = $radio['name'];
                            $name = $radio['label'];
                            echo '<div class="col-md-4">';
                            echo '<div class="form-group">';
                            echo '<label for="' . $elemName . '">' . $name . '</label>';
                            echo form_dropdown($elemName, $options, '0', 'class="form-control"');
                            echo '</div>';
                            echo '</div>';
                        }
                    ?>

                    <div class="clear"></div>

                    <div class="form-group">
                        <input type="submit" value="SAVE" class="btn btn-primary" />
                        <input type="reset" value="CLEAR" class="btn btn-primary" />
                        <input type="button" value="CANCEL" class="btn btn-danger" id="cancel-add-parameter"/>
                    </div>

                </form>
            </div>

        </div>

    </div>

    <div class="col-md-12" id="parameter-list">

        <div class="panel panel-default">
            <div class="panel-heading bg-gray">
                <h3 class="panel-title">Parameter List</h3>

            </div>

            <?php if( count($parameters) > 0 ){ ?>
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <?php
                        foreach($radioElements as $rd){
                            echo '<th>'.$rd['label'].'</th>';
                        }
                        ?>
                    </tr>

                    <?php
                        $count = 1;
                        foreach($parameters as $param){
                            $out = '<tr>';
                            $out .= '<td>'.$count.'</td>';
                            $out .= '<td>'.$param['name'].'</td>';

                            foreach($radioElements as $rd){
                                $rd_val = $param[$rd['name']] == TRUE ? 'fa-check-circle' : 'fa-times-circle';
                                $out .= '<td><i class="fa '.$rd_val.'"></i></td>';
                            }
                            $out .= '</tr>';

                            echo $out;
                            $count++;
                        }
                    ?>
                </tbody>
            </table>
            <?php }else{ no_results_found('No Tour Activity Parameters Found.'); } ?>
        </div>
    </div>


</div>

<script type="text/javascript">

    $(document).ready(function(){

        $('#add-parameter-btn').click(function(){
            $('#add-parameter-btn, #parameter-list').hide();
            $('#add-parameter-form-wrapper').show();
        });

        $('#cancel-add-parameter').click(function(){
            $('#add-parameter-btn, #parameter-list').show();
            $('#add-parameter-form-wrapper').hide();
        });

        <?php if( isset($hasError) and $hasError == TRUE ){?>
        $('#add-parameter-btn').trigger('click');
        <?php } ?>


    });

</script>
<?php

$fromDay = array() ;
$fromMonth = array();
$toDay= array();
$toMonth= array();
$season_name= array();
?>
<div class="col-md-12 ">
    <?php

    foreach($hotel_season as $h_season){
        array_push($season_name, $h_season->getName());
          $dateRanges = $h_season->getDateRanges();
            foreach ($dateRanges as $dr) {
                array_push($fromMonth,$dr->getFromDate()->format('m'));
                array_push($fromDay,$dr->getFromDate()->format('d'));
                array_push($toMonth,$dr->getToDate()->format('m'));
                array_push($toDay,$dr->getToDate()->format('d'));
           }
    }
    show_pre($fromMonth); show_pre($fromDay);
    show_pre($toMonth); show_pre($toDay);
    show_pre($season_name);
    ?>

    <!-- Main sheepIt Form -->
    <form method="post" action="<?php echo site_url('hotel/Season/add') ?>" >
        <input type="hidden" name="hotel" value="<?php echo $hotel->id() ?>" />
    <table class="table table-responsive form-group-sm">

        <tbody  id="hotel_seasons">
        <tr>
            <th>Name</th>
            <th>Date Ranges</th>
            <th>&nbsp;</th>
        </tr>

               <tr id="hotel_seasons_template">
            <td><input id="hotel_seasons_SSSSSSSS_name" name="season[SSSSSSSS][name]" type="text" class="form-control" value=""/></td>
            <td>
                <table class="table table-responsive" style="border-top: none">
                    <tbody  id="hotel_seasons_SSSSSSSS_dateRange">
                    <tr>
                        <th colspan="2" style="border-top: none">Starting From</th>
                        <th colspan="2"  style="border-top: none">Ends At</th>
                        <th style="border-top: none">&nbsp;</th>
                    </tr>
                    <tr id="hotel_seasons_SSSSSSSS_dateRange_template">
                     <?php foreach($fromMonth as $fm)
                    echo '<td>'.getMonthDropDown('season[SSSSSSSS][range][DDDDDD][fromMonth]',NULL, 'class="form-control months" id="hotel_seasons_SSSSSSSS_dateRange_DDDDDD_month"').$fm.
                     '</td>';
                     ?>
                    <td>
                          <?php getDayDropDown('season[SSSSSSSS][range][DDDDDD][fromDay]', NULL, 'class="form-control"
                          id="hotel_seasons_SSSSSSSS_dateRange_DDDDDD_day"')?>
                    </td>
                    <td>
                         <?php getMonthDropDown('season[SSSSSSSS][range][DDDDDD][toMonth]', NULL,'class="form-control months"
                           id="hotel_seasons_SSSSSSSS_dateRange_DDDDDD_month"')?>
                    </td>
                    <td>
                        <?php getDayDropDown('season[SSSSSSSS][range][DDDDDD][toDay]', NULL,'class="form-control" id="hotel_seasons_SSSSSSSS_dateRange_DDDDDD_day"')?>
                   </td>
                    <td>
                        <a id="hotel_seasons_SSSSSSSS_dateRange_remove_current"><i class="fa fa-remove"></i> </a></td>
                   </tr>
                    <tr id="hotel_seasons_SSSSSSSS_dateRange_noforms_template"><td colspan="3">No dateRange</td></tr>
                    <tr id="hotel_seasons_SSSSSSSS_dateRange_controls" class="controls">
                    <td id="hotel_seasons_SSSSSSSS_dateRange_add"><a class="btn-sm btn-primary"><span>Add Date Range</span></a></td>
                    <td colspan="4"></td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td><a id="hotel_seasons_remove_current"><i class="fa fa-remove"></i> </a></td>
        </tr>

        <tr id="hotel_seasons_noforms_template"><td colspan="3">No Seasons</td> </tr>

        <tr id="hotel_seasons_controls" class="controls">
            <td id="hotel_seasons_add"><a class="btn-sm btn-flat btn-primary"><span>Add Another</span></a></td>
            <td colspan="2">&nbsp;</td>
        </tr>
        </tbody>

    </table>
    <div class="panel-footer">
        <input type="submit" class="btn btn-primary" value="SAVE DATERANGE">
        <button id="cancelUpdate" class="btn btn-danger">CANCEL</button>
    </div>
    </form>


</div><!-- end wrapper -->




<script type="text/javascript">

    $(document).ready(function() {

        var hotelSeasonSheepItForm = $("#hotel_seasons").sheepIt({
            separator: '',
            allowRemoveLast: true,
            allowRemoveCurrent: true,
            allowRemoveAll: true,
            allowAdd: true,
            allowAddN: true,
            indexFormat:'SSSSSSSS',

            // Limits
            maxFormsCount: 10,
            minFormsCount: 1,
            iniFormsCount: 1,
            nestedForms: [
                {
                    id: 'hotel_seasons_SSSSSSSS_dateRange',
                    options: {
                        indexFormat: 'DDDDDD',
                        maxFormsCount: 5
                    }
                }
            ]

        });

//        console.log($(this).find('form'));

    });

</script>
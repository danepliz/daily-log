<?php
$preFilledForms = [];
$dateRanges = $season->getDateRanges();

?>
<div class="col-md-12">

        <!-- Main sheepIt Form -->
    <input type="hidden" name="season" value="<?php echo $season->id() ?>" />
    <input type="hidden" name="hotel" value="<?php echo $hotelID ?>" />
    <table class="table table-responsive" style="border-top: none">
        <tbody  id="date_range">
        <tr>
            <th colspan="2" style="border-top: none">Starting From</th>
            <th colspan="2"  style="border-top: none">Ends At</th>
            <th style="border-top: none">&nbsp;</th>
        </tr>
        <tr id="date_range_template">
            <td>
                <input type="hidden" name="date_range[#index#][id]" id="date_range_#index#_id" />
                <?php getMonthDropDown('date_range[#index#][fromMonth]',NULL, 'class="form-control months" id="date_range_#index#_month"') ?>
            </td>
            <td> <?php getDayDropDown('date_range[#index#][fromDay]', NULL, 'class="form-control" id="date_range_#index#_day"')?> </td>
            <td> <?php getMonthDropDown('date_range[#index#][toMonth]', NULL,'class="form-control months" id="date_range_#index#_month"')?> </td>
            <td> <?php getDayDropDown('date_range[#index#][toDay]', NULL,'class="form-control" id="date_range_#index#_day"')?> </td>
            <td> <a id="date_range_remove_current"><i class="fa fa-remove"></i> </a></td>
        </tr>

        <?php
            if( count( $dateRanges ) ){
                foreach($dateRanges as $dr){
                    $rangeId = $dr->id();
                    $id = 'dateRangeOld_'.$rangeId;
                    $preFilledForms[] = $id;
                    $startDate = $dr->getFromDate();
                    $endDate = $dr->getToDate();

                    $fromDay = $startDate->format('d');
                    $fromMonth = $startDate->format('m');

                    $toDay = $endDate->format('d');
                    $toMonth = $endDate->format('m');

        ?>
        <tr id="<?php echo $id ?>">
            <td>
                <input type="hidden" name="date_range[#index#][id]" id="date_range_#index#_id" value="<?php echo $rangeId ?>" />
                <?php getMonthDropDown('date_range[#index#][fromMonth]',$fromMonth, 'class="form-control months" id="date_range_#index#_month"') ?>
        </td>
        <td> <?php getDayDropDown('date_range[#index#][fromDay]', $fromDay, 'class="form-control" id="date_range_#index#_day"')?> </td>
        <td> <?php getMonthDropDown('date_range[#index#][toMonth]', $toMonth,'class="form-control months" id="date_range_#index#_month"')?> </td>
        <td> <?php getDayDropDown('date_range[#index#][toDay]', $toDay,'class="form-control" id="date_range_#index#_day"')?> </td>
        <td> <a id="date_range_remove_current"><i class="fa fa-remove"></i> </a></td>
        </tr>
         <?php
                }
            }
        ?>

        <tr id="date_range_noforms_template"><td colspan="3">No dateRange</td></tr>
        <tr id="date_range_controls" class="controls">
            <td id="date_range_add"><a class="btn-sm btn-primary"><span>Add Date Range</span></a></td>
            <td colspan="4"></td>
        </tr>
        </tbody>
    </table>


</div><!-- end wrapper -->


<div class="clear"></div>

<script type="text/javascript">

    $(document).ready(function() {

        var hotelSeasonSheepItForm = $("#date_range").sheepIt({
            separator: '',
            allowRemoveLast: true,
            allowRemoveCurrent: true,
            allowRemoveAll: true,
            allowAdd: true,
            allowAddN: true,
            maxFormsCount: 10,
            minFormsCount: 1,
            pregeneratedForms: <?php echo  json_encode($preFilledForms); ?>

        });

    });

</script>






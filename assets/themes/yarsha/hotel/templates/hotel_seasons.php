<?php
//$seasons = $hotel->getSeasons();
//$seasonsArr = [];
//if(count($seasons)){
//    foreach($seasons as $s){
//        $rangeString = [];
//        $dateRanges = $s->getDateRanges();
//        if( count($dateRanges) ){
//            foreach($dateRanges as $dr){
//                $rangeString[] = $dr->getFromDate()->format('d M') . ' - ' . $dr->getToDate()->format('d M');
//            }
//        }
//
//        $seasonsArr[] = [
//            'id' => $s->id(),
//            'name' => $s->getName(),
//            'dateRange' => implode('<br />', $rangeString)
//        ];
//    }
//}
//$seasonCount = count($seasonsArr);
//show_pre($seasonsArr);
//
//?>


<div class="col-md-12">
    <div class="panel panel-default hidden" id="add-season-form-wrapper">
        <div class="panel-body">
            <form role="form" method="post" action="<?php echo site_url('hotel/Season/addSeason') ?>" class="validate">
                <input type="hidden" name="hotel" value="<?php echo $hotel->id() ?>"/>

                <div class="form-group-sm">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control required" placeholder="season name"/>
                </div>

                <div class="form-group-sm">
                    <input type="submit" value="SAVE" class="btn btn-primary"/>
                    <input type="button" value="CANCEL" class="btn btn-danger" id="cancel-add-hotel-service"/>
                </div>
            </form>
        </div>
    </div>

    <table class="table table-striped data-table" id="seasonDataList">
        <tbody>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Date Ranges</th>
            <th>Actions</th>
        </tr>
        <?php
        $count = 1;

        foreach ($hotel_season as $hs){
        ?>
        <tr>
            <td><?php echo $count ?></td>
            <td><?php echo $hs->getName(); ?></td>
            <td><?php
                $rangeString = [];
                $dateRanges = $hs->getDateRanges();
                if (count($dateRanges)) {
                    foreach ($dateRanges as $dr) {
                        echo $dr->getFromDate()->format('d M') . ' - ' . $dr->getToDate()->format('d M') . '<br/>';
                    }
                }?>
            </td>
            <td>
                <?php
                $editAction = '';
                $deleteAction = '';
                    echo action_button('add', '#', array('title' => 'Add DateRanges', 'class' => "add-date-range", 'data-season-id' => $hs->id(), 'data-toggle' => 'modal', 'data-target' => '#hotelSeasonForm'));
                    echo action_button('delete', '#', array('data-bb' => 'custom_delete', 'title' => 'Delete Season' . $hs->getName(), 'data-id' => $hs->id()));

                ?>
            </td>
            <?php $count++;
            } ?>
        </tr>
        <tr>
            <td colspan="4"><a href="javascript:void(0)" class="btn btn-primary btn-margin" id="add-season-btn">Add
                    SEASON</a></td>
        </tr>

        </tbody>
    </table>

</div>


<!-- date range form -->
<div class="modal fade" id="hotelSeasonForm" tabindex="-1" role="dialog" aria-labelledby="hotelSeasonFormLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="hotelSeasonFormLabel"> Season DateRanges
                    For <?php echo ucwords($hotel->getName()) ?></h4>
            </div>

            <form role="form" class="validate" id="formHotelSeason" data-person="0"
                  data-hotel="<?php echo $hotel->id() ?>"
                  action="<?php echo site_url('hotel/season/addSeasonDateRanges/' . $hotel->id()) ?>">
                <div class="alert alert-danger alert-dismissable personError hidden"></div>
                <div class="modal-body">

                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="SAVE DATERANGES"/>
                    <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- end date range form -->

<div class="modal fade" id="seasonEditForm" tabindex="-1" role="dialog" aria-labelledby="serviceFormLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="marketFormLabel">Hotel Season| Edit</h4>
            </div>

            <form role="form" class="validate" id="formService">

                <div class="alert alert-danger" id="market-alert" style="display:none"></div>

                <div class="modal-body">

                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="UPDATE SEASON"/>
                    <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                </div>

            </form>

        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {

        /* HOtel SEASON FORM RENDERING */
        $('#hotelSeasonForm').on('show.bs.modal', function (e) {
            var modal = $(this),
                button = $(e.relatedTarget),
                form_type = button.attr('data-form-type');
            var season_id = button.attr('data-season-id');
            var hotel_id = '<?php echo $hotel->id() ?>';
            var remoteUrl = Yarsha.config.base_url + 'hotel/ajax/getDateRangeForm/' + season_id + '/' + hotel_id;
            $.ajax({
                type: 'GET',
                url: remoteUrl,
                success: function (res) {
                    var data = $.parseJSON(res);
                    if (data.status && data.status == 'success') {
                        modal.find('.modal-body').html(data.html);
                    } else {
                        Yarsha.notify('warn', data.message)
                    }

                }
            });
        });

        /* FORM HOTEL SEASON DATE RANGES SUBMISSION */
        $('#formHotelSeason').submit(function (e) {
            e.preventDefault();
            var _form = $(this);
            if (!_form.valid()) {
                return false;
            }
            var postData = _form.serialize(),
                remoteURL = Yarsha.config.base_url + 'hotel/ajax/saveDateRanges/';

            $('#hotelSeasonForm').mask('Processing Request ...');
            $.ajax({
                type: 'POST',
                url: remoteURL,
                data: postData,
                success: function (res) {
                    var data = $.parseJSON(res);
                    if (data.status == 'success') {
                        window.location = Yarsha.config.base_url + 'hotel/detail/<?php echo $hotel->slug() ?>?t=hotelSeasons';
                    } else {
                        $('.personError').html(data.message).removeClass('hidden');
                    }
                    $('#hotelSeasonForm').unmask();
                }
            });
            return false;
        });

        $('#add-season-btn').click(function () {
            $('#seasonDataList').hide();
            $('#add-season-form-wrapper').removeClass('hidden');
        });

        $('#cancel-add-hotel-service').click(function () {
            $('#seasonDataList').show();
            $('#add-season-form-wrapper').addClass('hidden');
        });


        $('#seasonEditForm').on('show.bs.modal', function (e) {
            var modal = $(this),
                button = $(e.relatedTarget),
                seasonID = button.data('season-id');
            remoteUrl = Yarsha.config.base_url + 'hotel/ajax/getSeasonEditForm/' + seasonID;

            modal.find('.alert').hide();

            $.ajax({
                type: 'GET',
                url: remoteUrl,
                success: function (res) {
                    modal.find('.modal-body').html(res);
                    modal.find('form').addClass('validate');
                }
            });

        });

        $("body").on('click', "a[data-bb='custom_delete']", function (e) {
            var $me = $(this);
            bootbox.confirm('Are you sure you want to delete?', function (result) {
                if (result == true) {
                    removeData($me.attr("data-id"));
                }
            });

            function removeData(id) {
                $.ajax({
                    type: "POST",
                    url: Yarsha.config.base_url + 'hotel/ajax/deleteSeason',
                    data: {id: id},
                    success: function (res) {
                        var data = $.parseJSON(res);
                        if( data.status == 'success' ) {
                            window.location = Yarsha.config.base_url + 'hotel/detail/<?php echo $hotel->slug() ?>?t=hotelSeasons';
                        }
//                        alert(res)
                    }
                });
            }

            return false;
        });

    });


</script>


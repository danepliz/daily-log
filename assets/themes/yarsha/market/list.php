<div class="row">

    <?php if(user_access('administer market')){ ?>
    <div class="col-xs-12">
        <a href="javascript:void(0)" class="btn btn-primary btn-margin" id="add-market-btn" >Add New Market Category</a>
    </div>
    <?php } ?>

    <div class="col-xs-12 margin" id="add-market-form-wrapper" style="display: none">
        <div class="panel panel-default">
            <div class="panel-body">
                <form role="form" method="post" action="<?php echo site_url('market/add') ?>" class="validate" >

                    <div class="form-group-sm">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control required" placeholder="market category name" />
                    </div>

                    <div class="form-group-sm">
                         <label for="description">Description</label>
                        <textarea name="description" class="form-control" placeholder="add description"></textarea>
                    </div>
                    <div class="form-group-sm">
                        <label for="currency">Currency</label>
                        <?php getCurrencySelectElement('currency', NULL,'class="form-control required"') ?>
                    </div>

                    <div class="form-group-sm">
                        <input type="submit" value="SAVE" class="btn btn-primary" />
                        <input type="reset" value="CLEAR" class="btn btn-primary" />
                        <input type="button" value="CANCEL" class="btn btn-danger" id="cancel-add-market"/>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="col-md-12" id="market-list">
        <div class="panel panel-default">
            <div class="panel-heading bg-gray">
                <h3 class="panel-title">Market List</h3>
            </div>

            <?php if( count($hotel_grades) > 0 ){ ?>
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Currency</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>

                    <?php
                        $count = 1;
                        foreach($hotel_grades as $grade){

                            $editAction = '';
                            $statusUpdateLink = '';
                            if( user_access('administer market') ){
                                $editAction = action_button('edit', '#', array('data-toggle' => 'modal', 'title' => 'Edit' .$grade->getName(), 'data-target' =>'#marketForm', 'data-market-id' => $grade->id()));
                                $statusUpdateLink = 'market/ajax/toggleMarketStatus';
                            }
                            $out = '<tr>';
                            $out .= '<td>'.$count.'</td>';
                            $out .= '<td>'.$grade->getName().'</td>';
                            $currencyName = ($grade->getCurrency())?$grade->getCurrency()->getIso3():"";
                            $out .= '<td>'.$currencyName.'</td>';
                            $out .= '<td>'.getStatusActionWrapper($grade->id(), $grade->isActive(), $statusUpdateLink).'</td>';
                            $out .= '<td>'.$grade->getDescription().'</td>';
                            $out .= '<td>'.$editAction.'</td>';
                            $out .= '</tr>';

                            echo $out;
                            $count++;
                        }
                    ?>
                </tbody>
            </table>
            <?php }else{ no_results_found('No Markets were found.'); } ?>
        </div>
    </div>


</div>

<!-- market edit form -->
<div class="modal fade" id="marketForm" tabindex="-1" role="dialog" aria-labelledby="marketFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="marketFormLabel">Market | Edit</h4>
            </div>

            <form role="form" class="validate" id="formMarket">

                <div class="col-md-12 alert alert-danger" id="market-alert" style="display:none"></div>

                <div class="modal-body">

                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="SAVE MARKET" />
                    <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- end market edit form -->

<script type="text/javascript">

    $(document).ready(function(){

        $('#add-market-btn').click(function(){
            $('#add-market-btn, #market-list').hide();
            $('#add-market-form-wrapper').show();
        });

        $('#cancel-add-market').click(function(){
            $('#add-market-btn, #market-list').show();
            $('#add-market-form-wrapper').hide();
        });

        $('#marketForm').on('show.bs.modal', function (e) {
            var modal = $(this),
                button = $(e.relatedTarget),
                branchID = button.data('market-id');
            remoteUrl = Yarsha.config.base_url+'market/ajax/getMarketForm/'+branchID;

            modal.find('.alert').hide();

            $.ajax({
                type: 'GET',
                url: remoteUrl,
                success: function(res){
                    modal.find('.modal-body').html(res);
                    modal.find('form').addClass('validate');
                }
            });

        });

        $('#formMarket').submit(function(e){

            e.preventDefault();
            var _form = $(this);
            if( ! _form.valid() ){ return false; }

            var postData = _form.serialize();

            $('.modal-dialog').mask('Updating Market ...');

            $.ajax({
                type: 'POST',
                url: Yarsha.config.base_url + 'market/ajax/saveMarket',
                data: postData,
                success: function(res){
                    var data = $.parseJSON(res);
                    console.log(data);
                    if( data.status == 'success' ){
                        window.location = '<?php echo site_url('market') ?>';
                    }else{
                        $('#market-alert').html(data.message).show();
                    }
                    $('.modal-dialog').unmask();
                }
            });

            return false;

        });


    });

</script>
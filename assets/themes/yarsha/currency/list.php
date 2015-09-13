<?php
//$fName = isset($post['name']) ? $post['name'] : '';
//$fEmail = isset($post['email'])? $post['email'] : '';
//$fCountry = isset($post['country'])? $post['country'] : NULL;
//$fStatus = isset($post['status'])? $post['status'] : NULL;
?>

<div class="row">
    <?php
    if(user_access('add currency')) {?>
        <div class="col-xs-12 btn-margin">
            <a href="javascript:void(0)" class="btn btn-primary" id="add-currency-btn" >Add New Currency</a>
        </div>
    <?php } ?>

    <div class="col-md-12" id="add-currency-form-wrapper" style="display: none">
        <div class="panel panel-default">
            <div class="panel-body">
                <form role="form" method="post" action="<?php echo site_url('currency/add') ?>" class="validate" >

                    <div class="form-group-sm">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control required" placeholder="Currency Name" />
                    </div>

                    <div class="form-group-sm">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control" placeholder="add description"></textarea>
                    </div>

                    <div class="form-group-sm">
                        <label for="iso_3">ISO 3</label>
                        <input type="text" name="iso_3" class="form-control required" placeholder="ISO 3 Code eg. USD" />
                    </div>

                    <div class="form-group-sm">
                        <label for="iso_3">Symbol</label>
                        <input type="text" name="symbol" class="form-control" placeholder="symbol eg. $" />
                    </div>

                    <div class="form-group-sm">
                        <input type="submit" value="SAVE" class="btn btn-primary" />
                        <input type="reset" value="CLEAR" class="btn btn-primary" />
                        <input type="button" value="CANCEL" class="btn btn-danger" id="cancel-add-currency"/>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php /* ?>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Search Agent</h3> </div>
            <div class="panel-body">
                <form action="" method="get" role="form" name="a_filter">
                    <div class="form-group-sm col-md-3">
                        <input type="text" placeholder="Agent Name" name="name" class="form-control" value="<?php echo $fName ?>">
                    </div>

                    <div class="form-group-sm col-md-3">
                        <input type="text" placeholder="Agent Email" name="email" class="form-control" value="<?php echo $fEmail ?>">
                    </div>

                    <div class="form-group-sm col-md-3">
                        <?php getCountrySelectElement('country', $fCountry, 'class="form-control" id="country"') ?>
                    </div>

                    <div class="form-group-sm col-md-3">
                        <?php getAgentStatusOptions('status', $fStatus, 'class="form-control" id="status"') ?>
                    </div>

                    <div class="form-group-sm col-md-2">
                        <input type="submit" name="submit" value="SEARCH" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
 <?php */ ?>

    <div class="col-md-12" id="currency-list">

        <div class="panel panel-default">

            <div class="panel-body">
                <?php if(isset($currencies) && count($currencies)>0){ ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <th class="serial" width="3%">#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>ISO 3</th>
                            <th>Symbol</th>
                            <th width="12%" class="actions">Actions</th>
                        </tr>
                        <?php
                        $count = ( $offset )? $offset + 1 : 1;
                        foreach($currencies as $c):
                            ?>
                            <tr>
                                <td><?php echo ++$counter;?></td>
                                <td><?php echo $c['name'];?></td>
                                <td><?php echo $c['description'];?></td>
                                <td><?php echo $c['iso_3'] ?></td>
                                <td><?php echo $c['symbol'] ?></td>
                                <td class="actions"><?php
//                                    if(user_access('view currency')) {
//                                        echo action_button('view', 'currency/view' . $c['id'], array('title' => 'view detail'));
//                                    }
                                    if(user_access('update currency')){
                                        echo action_button('edit', '#', array('data-toggle' => 'modal', 'title' =>'Edit ' .$c['name'], 'data-target' =>'#currencyForm', 'data-currency-id' => $c['id']));
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                    <?php }else{ no_results_found('No Currencies to list.'); } ?>
                </div>

                <div class="panel-footer">
                    <?php echo (isset($pagination))? $pagination : ''; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- currency edit form -->
<div class="modal fade" id="currencyForm" tabindex="-1" role="dialog" aria-labelledby="currencyFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="currencyFormLabel">Currency | Edit</h4>
            </div>

            <form role="form" class="validate" id="formCurrency">

                <div class="col-md-12 alert alert-danger" id="currency-alert" style="display:none"></div>

                <div class="modal-body">

                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="SAVE CURRENCY" />
                    <a href="#" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- end branch edit form -->



<script type="text/javascript">
    $(document).ready(function(){

        $('#add-currency-btn').click(function(){
            $('#add-currency-btn, #currency-list').hide();
            $('#add-currency-form-wrapper').show();
        });

        $('#cancel-add-currency').click(function(){
            $('#add-currency-btn, #currency-list').show();
            $('#add-currency-form-wrapper').hide();
        });

        $('#currencyForm').on('show.bs.modal', function (e) {
            var modal = $(this),
                button = $(e.relatedTarget),
                branchID = button.data('currency-id');
            remoteUrl = Yarsha.config.base_url+'currency/ajax/getCurrencyForm/'+branchID;

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

        $('#formCurrency').submit(function(e){

            e.preventDefault();
            var _form = $(this);
            if( ! _form.valid() ){ return false; }

            var postData = _form.serialize();

            $.ajax({
                type: 'POST',
                url: Yarsha.config.base_url + 'currency/ajax/saveCurrency',
                data: postData,
                success: function(res){
                    var data = $.parseJSON(res);
                    console.log(data);
                    if( data.status == 'success' ){
                        window.location = '<?php echo site_url('currency') ?>';
                    }else{
                        $('#currency-alert').html(data.message).show();
                    }
                }
            });

            return false;

        });

        <?php if( isset($has_error) and $has_error == TRUE ){ ?> $('#add-currency-btn').trigger('click') <?php } ?>


    });
</script>





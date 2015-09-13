<?php
    $hotelID = ( isset($post['hotel']) and $post['hotel'] !== '' )? $post['hotel'] : NULL;
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Search Rates</h3> </div>
            <div class="panel-body">
                <form action="" method="get" role="form" name="h_filter" id="hotelFilterForm">
                    <div class="form-group-sm col-md-6">
                        <?php echo getSelectHotel('hotel', $hotelID, 'class="form-control" id="hotel"',TRUE ); ?>
                    </div>

                    <div class="form-group-sm col-md-3">
                        <input id="searchBtn" type="submit" value="SEARCH" class="btn btn-primary"  onclick="submitMe(this)">
                        <input type="reset" name="reset" value="RESET" class="btn btn-danger">
                    </div>
                </form>
            </div>
        </div>
    </div><!-- filter -->

    <?php
    if(!isset($_GET['hotel'])){
    ?>
    <div id="searchDefault" class="col-md-8 detail-page rates-table">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php if( count($rates) > 0 ){ ?>
                <?php
                    echo '<table class="table table-responsive table-bordered">';
                    echo '<tr>
                                    <th rowspan="2">Plan</th>
                                    <th colspan="2">rates</th>
                                    <th rowspan="2">Expiry Date</th>
                                </tr>
                                <tr>
                                    <th>Payable</th>
                                    <th>Billing</th>
                                </tr>';
                        echo '<tr><th colspan="4" class="rate-hotel-name">'.'No Hotel Selected'.'</th></tr>';

                    echo '<tr>';
                    echo '<td colspan="4">'.'No Rates Available'.'</td>';
                    echo '</tr>';
                    echo '</table>';
                ?>

                <?php }else{ no_results_found('No Rates Available'); } ?>
            </div>
        </div>
    </div>

    <div class="col-md-4 detail-page rates-table">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-responsive table-bordered">
                    <tr>
                        <th>Service Name</th>
                        <th>Price</th>
                    </tr>
                    <tr><th colspan="2" class="rate-hotel-name"><?php echo 'No Hotel Selected';?></th>
                    <tr>
                        <td colspan="2"><?php echo 'No Service Available';?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <?php }?>


    <?php
    if(isset($_GET['hotel'])){
    ?>
    <div id="searchResult" class="col-md-8 detail-page rates-table">
        <br/>
        <div class="panel panel-default">
            <div class="panel-body">
                <?php if( count($rates) > 0 ){ ?>
                    <?php
                    echo '<table class="table table-responsive table-bordered">';
                    echo '<tr>
                                <th rowspan="2">Plan</th>
                                <th colspan="3">Rates</th>
                                <th rowspan="2">Expiry Date</th>
                            </tr>
                            <tr>
                                <th>Currency</th>
                                <th>Charge</th>
                                <th>Payable Amount</th>
                            </tr>';

                    foreach( $rates as $hotel => $details ){
                        echo '<tr><th colspan="5" class="rate-hotel-name">'.$hotel.'</th></tr>';


                        foreach( $details as $market => $values ){
                            echo '<tr><th colspan="5"  class="rate-hotel-market">'.$market.'</th></tr>';

                            foreach( $values as $val ) {


                                $rates = $val['rates'];
                                $curCount = count($rates);

                                $count = 1;
                                foreach($rates as $k => $r){

                                    $payableAmount = number_format($r['payableAmount'], 3, '.', '');

                                    if( $count == 1 ){
                                        echo '<tr>';
                                        echo '<td rowspan="'.$curCount.'" >'.$val['plan'].'</td>';
                                        echo '<td>'.$k.'</td>';
                                        echo '<td>'.$r['charge'].'</td>';
                                        echo '<td>'.$payableAmount.'</td>';
                                        echo '<td  rowspan="'.$curCount.'">'.$val['expiryDate'].'</td>';
                                        echo '</tr>';
                                    }else{
                                        echo '<tr>';
                                        echo '<td>'.$k.'</td>';
                                        echo '<td>'.$r['charge'].'</td>';
                                        echo '<td>'.$payableAmount.'</td>';
                                        echo '</tr>';
                                    }

                                    $count++;
                                }

                            }
                        }
                    }
                    echo '</table>';
                    echo (isset($pagination))? $pagination : '';
                    ?><?php }else{ no_results_found('No Rates Available'); } ?>

            </div>
        </div>
    </div>

    <div class="col-md-4 detail-page rates-table">
        <br/>
        <div class="panel panel-default">
            <div class="panel-body">
                <?php if( count($services) > 0 ){ ?>
                    <table class="table table-responsive table-bordered">
                            <tr>
                                <th>Service Name</th>
                                <th>Price</th>
                            </tr>
                        <?php foreach($services as $val){
//                            $serviceName = $val->getServiceName();
//                            $servicePrice = $val->getPrice();
                        ?>
                            <tr>
                                <td><?php echo $val['service_name']; ?></td>
                                <td><?php echo $val['price']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php }else{ no_results_found('No Services Available'); } ?>
            </div>
        </div>
    </div>
    <?php }?>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#hotel').select2();
    });
</script>


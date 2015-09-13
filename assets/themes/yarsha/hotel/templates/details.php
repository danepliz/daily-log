<?php
use hotel\models\Hotel;
$city = $hotel->getCity();
$country = ( $hotel->getCountry() )? $hotel->getCountry() : NULL;
$address = array($hotel->getAddress(), $city);
$rateVariationStrategies = Hotel::$rateVariationStrategies;
$rateSFromDb = $hotel->getRateVariationStrategy();
$rateStrategy =  ( array_key_exists($rateSFromDb, $rateVariationStrategies) ) ? $rateVariationStrategies[$rateSFromDb] : '';

$bookingTypes = Hotel::$bookingTypes;
$bookingDb = $hotel->getBookingType();
$bookingType = (array_key_exists($bookingDb,$bookingTypes))?
  $bookingTypes[$bookingDb] : '';

?>

<div class="col-md-12">

    <div class="col-md-6">

        <div class="panel panel-default">
            <div class="panel-heading bg-gray">
                <h3 class="panel-title">Basic Information</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <td class="text-bold">Name</td>
                        <td><?php echo $hotel->getName(); ?></td>
                    </tr>
                    <tr>
                        <td class="text-bold">Category</td>
                        <td><?php echo $hotel->getCategory()->getName() ?></td>
                    </tr>
                    <tr>
                        <td class="text-bold">Grade</td>
                        <td><?php echo $hotel->getGrade()->getName() ?></td>
                    </tr>
                    <tr>
                        <td class="text-bold">Status</td>
                        <td><?php echo $hotel->getStatus() ?></td>
                    </tr>

                    <tr>
                        <th>Payment Strategy</th>
                        <td><?php echo \hotel\models\Hotel::$paymentStrategies[$hotel->getPaymentStrategy()] . ' ( '.$hotel->getPaymentStrategyPercent().'% )' ?></td>
                    </tr>
                    <tr>
                        <th>Booking Type</th>
                        <td>
                            <?php
                                $booking_type=[];
                                if($hotel->hasBookingTypePackageBasis()){
                                    $booking_type[] ="Package Basis";
                                }
                                if($hotel->hasBookingTypeRoomBasis())
                            {
                                $booking_type[]="Room Basis";}
                           echo  implode(", ",$booking_type);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Rate Variation Strategy</th>
                        <td> <?php echo $rateStrategy ?> </td>
                    </tr>

                      <tr>
                        <td class="text-bold">Remarks</td>
                        <td><?php echo $hotel->getOthers() ?></td>

                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <!-- location -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Contact Information</h3>
            </div>
            <div class="table-responsive">
                <table class="table data-table">
                    <tbody>
                    <tr>
                        <td class="text-bold">Country</td>
                        <td><?php echo $country ?></td>
                    </tr>
                    <tr>
                        <td class="text-bold">Address</td>
                        <td><?php echo implode(", ", $address); ?></td>
                    </tr>
                    <tr>
                        <td class="text-bold">Phone</td>
                        <td><?php echo implode("</br>", $hotel->getPhones()) ?></td>
                    </tr>
                    <tr>
                        <td class="text-bold">Website</td>
                        <td class="email">
                            <?php
                            $hWebsite1 = $hotel->getWebsite1();
                            $hWebsite2 = $hotel->getWebsite2();
                            $hWebsiteArr = [];
                            if( $hWebsite1 != '' ){
                                $link1 = formatWebsite($hWebsite1);
                                $hWebsiteArr[] = '<a href="'.$link1.'" target="_blank">'.$hWebsite1.'</a>';
                            }
                            if( $hWebsite2 != '' ){
                                $link2 = formatWebsite($hWebsite2);
                                $hWebsiteArr[] = '<a href="'.$link2.'" target="_blank">'.$hWebsite2.'</a>';
                            }
                            echo implode("</br>", $hWebsiteArr);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-bold">Email</td>
                        <td class="email">
                            <?php
                            $hEmails = $hotel->getEmails();
                            $hEmailsArr = [];
                            foreach($hEmails as $he){
                                $hEmailsArr[] = '<a href="mailto:'.$he.'">'.$he.'</a>';
                            }
                            echo implode("</br>", $hEmailsArr)
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-bold">Fax</td>
                        <td><?php echo $hotel->getFax() ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <a href="<?php echo site_url('hotel/edit/'.$hotel->slug())?>" class="btn btn-primary">EDIT</a>
            </div>

        </div>
        <!-- ! location -->

    </div></div>










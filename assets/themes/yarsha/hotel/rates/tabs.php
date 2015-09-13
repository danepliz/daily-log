<?php
use hotel\models\Hotel;

$tabsArray = [];
$rateStrategy = $hotel->getRateVariationStrategy();
$seasons = $hotel->getSeasons();
$percent = $hotel->getPaymentStrategyPercent();
$defaultCurrencyFromOption = Options::get('config_market_currency','');
$currency = NULL;
if( $defaultCurrencyFromOption != '' ){
    $currency = $this->doctrine->em->find('currency\models\Currency', $defaultCurrencyFromOption);
}
$defaultCurrency = ( !is_null($currency) )? $currency->getIso3() : 'USD';
$seasonsArr = [];
if(count($seasons)){
    foreach($seasons as $s){
        if( $s->isDeleted()){ continue; }
        $rangeString = [];
        $dateRanges = $s->getDateRanges();
        if( count($dateRanges) ){
            foreach($dateRanges as $dr){
                $rangeString[] = $dr->getFromDate()->format('d M') . ' - ' . $dr->getToDate()->format('d M');
            }
        }

        $seasonsArr[] = [
            'id' => $s->id(),
            'name' => $s->getName(),
            'dateRange' => implode('<br />', $rangeString)
        ];
    }
}

if( $hotel->hasBookingTypeRoomBasis() and $rateStrategy == Hotel::HOTEL_RATE_VARIATION_STRATEGY_SEASONAL ){
    $tabsArray['roomBasisSeasonal'] = [
        'label' => 'Room Basis Seasonal',
        'template' => 'hotel/rates/room_basis_seasonal',
        'data' => ['seasonsArr' => $seasonsArr, 'defaultCurrency' => $defaultCurrency]
    ];
}

if( $hotel->hasBookingTypeRoomBasis() and $rateStrategy == Hotel::HOTEL_RATE_VARIATION_STRATEGY_NONE ){
    $tabsArray['roomBasisNonSeasonal'] = [
        'label' => 'Room Basis Non Seasonal',
        'template' => 'hotel/rates/room_basis_non_seasonal',
        'data' => ['defaultCurrency' => $defaultCurrency]
    ];
}

if( $hotel->hasBookingTypePackageBasis() and $rateStrategy == Hotel::HOTEL_RATE_VARIATION_STRATEGY_SEASONAL ){
    $tabsArray['packageBasisSeasonal'] = [
        'label' => 'Package Basis Seasonal',
        'template' => 'hotel/rates/package_basis_seasonal',
        'data' => ['seasonsArr' => $seasonsArr, 'defaultCurrency' => $defaultCurrency]
    ];
}

if( $hotel->hasBookingTypePackageBasis() and $rateStrategy == Hotel::HOTEL_RATE_VARIATION_STRATEGY_NONE ){
    $tabsArray['packageBasisNonSeasonal'] = [
        'label' => 'Package Basis Non Seasonal',
        'template' => 'hotel/rates/package_basis_non_seasonal',
        'data' => ['defaultCurrency' => $defaultCurrency]
    ];
}

$tabsArray['hotelServiceRates'] = [
    'label' => 'Service Rates',
    'template' => 'hotel/rates/services',
    'data' => ['defaultCurrency' => $defaultCurrency]
];

$tabsArray['viewDetails'] = [
    'label' => 'View Hotel Detail',
    'type' => 'link',
    'link' => 'hotel/detail/'.$hotel->slug(),
    'template' => '',
    'data' => []
];



$currentTab = ( isset($_GET['t']) and array_key_exists($_GET['t'], $tabsArray))? $_GET['t'] : '';

echo getTabsTemplate($tabsArray, $currentTab);

?>

<script type="text/javascript">

    function calculateTotal(obj){
        var percent = parseFloat('<?php echo $percent ?>'),
            self = $(obj),
            objId = self.attr('id'),
            amount = parseFloat(self.val()),
            total = amount + ( ( percent / 100 ) * amount );
        $('#'+objId+'_billing').html(total);
    }

    function showCurrency(obj){
        var self = $(obj),
            market = self.val(),
            currencyObj = $('#'+self.attr('id')+'_currency'),
            remoteUrl = Yarsha.config.base_url + 'market/ajax/getCurrencyByMarket';

        if(market != ""){
            remoteUrl = remoteUrl + '/' + market;
        }

        console.log(remoteUrl);

        $.ajax({
            type: 'GET',
            url: remoteUrl,
            success: function(res){
                var data = $.parseJSON(res);
                if( data.status && data.status == 'success' ){
                    currencyObj.html(data.currency.iso_3);
                }
            }
        });
    }

</script>
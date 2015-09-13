<?php
namespace hotel\models;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use JMS\Serializer\Tests\Serializer\DateIntervalFormatTest;

class HotelRateRepository extends EntityRepository{

    public function listHotelRates( $offset = NULL, $perPage = NULL, $filters = array() ){
        $qb = $this->_em->createQueryBuilder();

        $qb->select(array('rate.id as rate_id','cur.iso_3 as currency','rd.payableRate', '0 as billingRate', 'rate.expiryDate','roomCategory.id as roomCategoryID',
            'roomCategory.name as roomCategoryName', 'hotel.payment_strategy_percent as s_percent',
            '( rd.payableRate + ( hotel.payment_strategy_percent / 100 ) * rd.payableRate ) as payingAmount',
            'roomPlan.id as roomPlanID', 'roomPlan.name as roomPlanName', 'roomType.id as roomTypeID', 'roomType.name as roomTypeName',
            'hotel.id as hotelID', 'hotel.name as hotelName', 'market.id as marketID', 'market.name as marketName'))
            ->from('hotel\models\Rate','rate')
            ->leftJoin('rate.hotel', 'hotel')
            ->leftJoin('rate.roomCategory','roomCategory')
            ->leftJoin('rate.roomPlan','roomPlan')
            ->leftJoin('rate.roomType','roomType')
            ->leftJoin('rate.market','market')
            ->leftJoin('rate.rateDetails', 'rd')
            ->leftJoin('rd.currency', 'cur')
            ->where('1=1')
            ->andWhere("hotel.status='ACTIVE'");

        $hasFilter = FALSE;

        if(array_key_exists('hotel', $filters) && $filters['hotel'] != ''){
            $qb->andWhere('hotel.id = '.$filters['hotel']);
            $hasFilter = TRUE;
        }

        if( !$hasFilter ){

            if(!is_null($offset))
                $qb->setFirstResult($offset);

            if(!is_null($perPage))
                $qb->setMaxResults($perPage);
        }

        $qb->orderBy('hotel.name', 'asc');
        $query = $qb->getQuery();

        $pagination = new Paginator($query, $fetchJoin = true);
        return $pagination;
    }

    public function listHotelServices($offset = NULL, $perPage = NULL, $filters = array()){
        $qb = $this->_em->createQueryBuilder();


        $qb->select('h.id as hotelId','h.name as hotelName','service.price','service.service_name')
            ->from('hotel\models\HotelServices','service')
            ->leftJoin('service.hotel', 'h')
            ->where('1=1')
            ->andWhere("h.status='ACTIVE'");

        $hasFilter = FALSE;

        if(array_key_exists('hotel', $filters) && $filters['hotel'] != ''){
            $qb->andWhere('h.id = '.$filters['hotel']);
            $hasFilter = TRUE;
        }

        if( !$hasFilter ){

            if(!is_null($offset))
                $qb->setFirstResult($offset);

            if(!is_null($perPage))
                $qb->setMaxResults($perPage);
        }


        $qb->orderBy('h.name', 'asc');
        $query = $qb->getQuery();

        $pagination = new Paginator($query, $fetchJoin = true);
        return $pagination;
    }


    public function getRatesByHotel2($hotel_id, $data = TRUE)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select(array('r.id as rate_id', 'h.id as hotel_id', 'rc.id as category_id', 'rt.id as type_id',
        'rp.id as plan_id', 'r.payableRate', 'r.billingRate'))
            ->from('hotel\models\Rate', 'r')
            ->leftJoin('r.hotel', 'h')
            ->leftJoin('r.roomCategory', 'rc')
            ->leftJoin('r.roomType', 'rt')
            ->leftJoin('r.roomPlan', 'rp')
            ->where('h.id = :hotelID')
            ->setParameter('hotelID', $hotel_id);

        $rates = $qb->getQuery()->getResult();

        if( $data ){
            $ratesArray = array();
            if(count($rates)>0){
                foreach($rates as $k => $v){
                    $ratesArray[$v['category_id']][$v['type_id']][$v['plan_id']] = $v['price'];
                }
            }
            return $ratesArray;
        }else{
            return $rates;
        }
    }

    public function getHotelRate($hotel_id, $filters){
        $qb = $this->_em->createQueryBuilder();

        $qb->select(array('r.id', 'r.payableRate','r.billingRate', 'r.expiryDate'))
            ->from('hotel\models\Rate', 'r')
            ->leftJoin('r.hotel', 'h')
            ->leftJoin('r.roomCategory', 'rc')
            ->leftJoin('r.roomType', 'rt')
            ->leftJoin('r.roomPlan', 'rp')
            ->leftJoin('r.market', 'm')
            ->where('h.id = '.$hotel_id);

        $filtersColumnsParams = array(
            'roomCategory' => 'rc.id',
            'roomType' => 'rt.id',
            'roomPlan' => 'rp.id',
            'market' => 'm.id'
        );

        foreach( $filtersColumnsParams as $key => $column ){
            if( array_key_exists($key, $filters) and $filters[$key] != "" ){
                $qb->andWhere($column .' = '. $filters[$key]);
            }else{
                $qb->andWhere($column.' IS NULL');
            }
        }

        try{
            return $qb->getQuery()->getSingleResult();
        }catch(\Exception $e){
            return null;
        }


    }

    public function getHotelRateByCurrency($hotel_id, $currency, $filters){
        $qb = $this->_em->createQueryBuilder();

        $qb->select(array('r.id', 'rd.payableRate', 'r.expiryDate'))
            ->from('hotel\models\Rate', 'r')
            ->leftJoin('r.hotel', 'h')
            ->leftJoin('r.roomCategory', 'rc')
            ->leftJoin('r.roomType', 'rt')
            ->leftJoin('r.roomPlan', 'rp')
            ->leftJoin('r.market', 'm')
            ->leftJoin('r.rateDetails', 'rd')
            ->where('h.id = '.$hotel_id)
            ->andWhere('IDENTITY(rd.currency) = '.$currency);

        $filtersColumnsParams = array(
            'roomCategory' => 'rc.id',
            'roomType' => 'rt.id',
            'roomPlan' => 'rp.id',
            'market' => 'm.id'
        );

        foreach( $filtersColumnsParams as $key => $column ){
            if( array_key_exists($key, $filters) and $filters[$key] != "" ){
                $qb->andWhere($column .' = '. $filters[$key]);
            }else{
                $qb->andWhere($column.' IS NULL');
            }
        }

        try{
            return $qb->getQuery()->getSingleResult();
        }catch(\Exception $e){
            return null;
        }
    }

    function getRatesForCalculation($filters){
        $qb = $this->_em->createQueryBuilder();
        $qb->select(
                    [
                        'r.id as rateID', 'rd.id as rateDetailsID',
                        'rd.singleSupplementPerNight as supplement', 'rd.additionalChargePerNight as additional',
                        'rd.charge as amount', 'rd.extraBedCharge as extraBed', 'r.expiryDate as expiryDate',
                        'o.id as outletID', 'o.name as outlet', 'svc.id as serviceID', 'svc.name as service'
                    ]
        )
            ->from('hotel\models\Rate', 'r')
            ->leftJoin('r.hotel', 'h')
            ->leftJoin('r.rateDetails', 'rd')
            ->leftJoin('r.market', 'm')
            ->leftJoin('r.roomCategory','rc')
            ->leftJoin('r.roomType', 'rt')
            ->leftJoin('r.roomPlan', 'rp')
            ->leftJoin('r.package', 'p')
            ->leftJoin('rd.season', 's')
            ->leftJoin('s.dateRanges', 'dr')
            ->leftJoin('r.outlet', 'o')
            ->leftJoin('r.service', 'svc')
            ->where('r.status = 1');

        $filtersColumnsParams['hotel'] = 'h.id';
        $filtersColumnsParams['market'] = 'm.id';

        if( $filters['isServiceBasis'] ){
            $filtersColumnsParams['outlet'] = 'o.id';
            $filtersColumnsParams['service'] = 'svc.id';
        }else{
            if( $filters['isPackageBasis'] and $filters['isPackageBasis'] == TRUE ){
                $filtersColumnsParams['package'] = 'p.id';
            }else{
                $filtersColumnsParams['roomType'] = 'rt.id';
                $filtersColumnsParams['roomCategory'] = 'rc.id';
                $filtersColumnsParams['roomPlan'] = 'rp.id';
            }
        }

        foreach( $filtersColumnsParams as $key => $column ){
            if( array_key_exists($key, $filters) and $filters[$key] != "" ){
                $qb->andWhere($column .' = '. $filters[$key]);
            }else{
                $qb->andWhere($column.' IS NULL');
            }
        }

        if( $filters['applySeasonalRate'] == TRUE){
            list($year, $month, $day) = explode('-', $filters['arrivalDate']);
            $arrivalDateString = '0000-'.$month.'-'.$day;
            $qb->andWhere('s.status = 1')->andWhere('s.deleted = 0');
            $qb->andWhere($qb->expr()->andX("'$arrivalDateString' >= dr.fromDate", "'$arrivalDateString' <= dr.toDate"));
        }else{
            $qb->andWhere('s.id IS NULL');
        }
//        die($qb->getQuery()->getSQL());
        try{
            return $qb->getQuery()->getSingleResult();
        }catch(\Exception $e){
            $filters['market'] = '';
            return $this->getRatesForCalculationWithOutMarket($filters);
        }
    }

    function getRatesForCalculationWithOutMarket($filters){
        $qb = $this->_em->createQueryBuilder();
        $qb->select(
            [
                'r.id as rateID', 'rd.id as rateDetailsID',
                'rd.singleSupplementPerNight as supplement', 'rd.additionalChargePerNight as additional',
                'rd.charge as amount', 'rd.extraBedCharge as extraBed', 'r.expiryDate as expiryDate',
                'o.id as outletID', 'o.name as outlet', 'svc.id as serviceID', 'svc.name as service'
            ]
        )
            ->from('hotel\models\Rate', 'r')
            ->leftJoin('r.hotel', 'h')
            ->leftJoin('r.rateDetails', 'rd')
            ->leftJoin('r.market', 'm')
            ->leftJoin('r.roomCategory','rc')
            ->leftJoin('r.roomType', 'rt')
            ->leftJoin('r.roomPlan', 'rp')
            ->leftJoin('r.package', 'p')
            ->leftJoin('rd.season', 's')
            ->leftJoin('s.dateRanges', 'dr')
            ->leftJoin('r.outlet', 'o')
            ->leftJoin('r.service', 'svc')
            ->where('r.status = 1');

        $filtersColumnsParams['hotel'] = 'h.id';
        $filtersColumnsParams['market'] = 'm.id';

        if( $filters['isServiceBasis'] ){
            $filtersColumnsParams['outlet'] = 'o.id';
            $filtersColumnsParams['service'] = 'svc.id';
        }else{
            if( $filters['isPackageBasis'] and $filters['isPackageBasis'] == TRUE ){
                $filtersColumnsParams['package'] = 'p.id';
            }else{
                $filtersColumnsParams['roomType'] = 'rt.id';
                $filtersColumnsParams['roomCategory'] = 'rc.id';
                $filtersColumnsParams['roomPlan'] = 'rp.id';
            }
        }

        foreach( $filtersColumnsParams as $key => $column ){
            if( array_key_exists($key, $filters) and $filters[$key] != "" ){
                $qb->andWhere($column .' = '. $filters[$key]);
            }else{
                $qb->andWhere($column.' IS NULL');
            }
        }

        if( $filters['applySeasonalRate'] == TRUE){
            list($year, $month, $day) = explode('-', $filters['arrivalDate']);
            $arrivalDateString = '0000-'.$month.'-'.$day;
            $qb->andWhere('s.status = 1')->andWhere('s.deleted = 0');
            $qb->andWhere($qb->expr()->andX("'$arrivalDateString' >= dr.fromDate", "'$arrivalDateString' <= dr.toDate"));
        }else{
            $qb->andWhere('s.id IS NULL');
        }

        try{
            return $qb->getQuery()->getSingleResult();
        }catch(\Exception $e){
            return null;
        }
    }

	public function getMarketByHotelRates($filters = []){
        $qb = $this->_em->createQueryBuilder();

        $qb->select([
            'hr.type',
            'm.name as market', 'm.id as marketID', 'cur.iso_3 as currency'
        ])
            ->from('hotel\models\Rate','hr')
            ->leftJoin('hr.market','m')
            ->leftJoin('m.currency', 'cur')
            ->where('hr.status = 1');

        if( array_key_exists('hotel', $filters) and $filters['hotel'] != '' ){
            $qb->andWhere('IDENTITY(hr.hotel) = :hotel')->setParameter('hotel', $filters['hotel']);
        }

        if( array_key_exists('type', $filters) ){
            if( $filters['type'] == Rate::RATE_TYPE_SERVICE ){
                $qb->andWhere('hr.type = :type')->setParameter('type', Rate::RATE_TYPE_SERVICE);
            }elseif( $filters['type'] == Hotel::HOTEL_BOOKING_TYPE_PACKAGE_BASIS){
                $qb->andWhere('hr.package IS NOT NULL');
            }else{
                $qb->andWhere('hr.package IS NULL');
                $qb->andWhere(
                    $qb->expr()->orX('hr.type IS NULL', 'hr.type != :r_type')
                )->setParameter('r_type', Rate::RATE_TYPE_SERVICE );
            }

        }

        if( array_key_exists('strategy', $filters) and $filters['strategy'] != '' ){
            $qb->andWhere('hr.rateStrategy = :strategy')->setParameter('strategy', $filters['strategy']);
        }

        $qb->groupBy('m.id');
        $qb->orderBy('m.name','asc');

        return $qb->getQuery()->getArrayResult();
    }


}
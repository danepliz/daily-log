<?php 

    
  class client{ 
   
   function process($data = array()){
   	
      	
   	$MemberId = "CCC0006945";
   	$MemberPwd = "1210010103";
   	$RequestType = "N";
   	$ReasonCode = "3";
   	$RefNum = "e12111";
   	
   	
   	//$MB_WSDL_URL = "https://sdkstage.microbilt.com/WebServices/MBCLV11/MBCLV.svc?wsdl";

	$MB_WSDL_URL = "https://sdkstage.microbilt.com/WebServices/MBRVD/MBRVD.svc?wsdl";
      
        try
        {
            $MyRq    = new StdClass();
            $MyRq->MsgRqHdr    = new StdClass();
            $MyRq->MsgRqHdr->MemberId = $MemberId;
            $MyRq->MsgRqHdr->MemberPwd = $MemberPwd;
            
	    	$MyRq->MsgRqHdr->RequestType = $RequestType;
            $MyRq->MsgRqHdr->ReasonCode = $ReasonCode;
            $MyRq->MsgRqHdr->RefNum = $RefNum;

            $MyRq->PersonInfo = new StdClass();
            $MyRq->PersonInfo->PersonName = new StdClass();
            $MyRq->PersonInfo->PersonName->FirstName  = $data['FirstName'];
            $MyRq->PersonInfo->PersonName->LastName  = $data['LastName'];

            $MyRq->PersonInfo->ContactInfo[0]   = new StdClass();
            $MyRq->PersonInfo->ContactInfo[0]->PostAddr = new StdClass();
            $MyRq->PersonInfo->ContactInfo[0]->PostAddr->Addr1= $data['Addr1'];
            $MyRq->PersonInfo->ContactInfo[0]->PostAddr->StateProv  = $data['StateProv'];
          
            $MyRq->PersonInfo->ContactInfo[0]->PhoneNum = new StdClass();
            $MyRq->PersonInfo->ContactInfo[0]->PhoneNum->Phone = $data['Phone'];

            $MyRq->PersonInfo->ContactInfo[0]->EmailAddr = $data['EmailAddr'];
            
            $MyRq->CheckAmt = new StdClass();
            $MyRq->CheckAmt->Amt = $data['Amt'];

           
            $MyRq->RuleNum = $data['RuleNum'];
            $report = new StdClass();
            $report->inquiry = $MyRq;

            $client = new SoapClient($MB_WSDL_URL);
            $result = $client->GetReport($report);
            
            return $result;
            
           }
        catch (Exception $e)
        {

        }
   }  

  }

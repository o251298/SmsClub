<?php
require_once './../vendor/autoload.php';
use library\Smsclub\SmsClubJSON;
use library\Smsclub\SmsClubXML;
use library\Smsclub\SmsClubHTTP;

//  =========================================================================  JSON  =====================================================================================
//
//$json = [
//    "phone" => ["380508047845"],
//    "message" => 'test message hello1s',
//    "src_addr" => "Shop Zaksaz",
//];

//$msgJSON = SmsClubJSON::create($json);


// ==========================================================================   XML  ======================================================================================

/*$xml = "<?xml version='1.0' encoding='utf-8'?>
<request_sendsms>
<username><![CDATA[123]]></username>
<password><![CDATA[42342]]></password>
<from><![CDATA[sdf]]></from>
<to><![CDATA[23423423]]></to>
<text><![CDATA[xcv]]></text>
</request_sendsms>";
*/

//$msgXML = SmsClubXML::create($xml);
// OR
//$msgXML = SmsClubXML::create($json);


// ==========================================================================   HTTP  ======================================================================================

//$http = "https://gate.smsclub.mobi/token/?username=380508047845&token=yaztHCfBWLfmGv_&from=Shop Zakaz&to=380962540183&text=Test message";


//$msgHTTP = SmsClubHTTP::create($http);
// OR
//$msgHTTP = SmsClubHTTP::create($json);


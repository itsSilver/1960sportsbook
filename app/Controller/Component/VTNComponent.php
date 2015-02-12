<?php

class VTNComponent extends Component {

    //private $vtn_validate_url = "https://www.vtnsandbox.com/merchant/CheckPayment.asp";
    private $vtn_validate_url = "https://www.virtualterminalnetwork.com/merchant/CheckPayment.asp";
    private $values;


    /* '================================================================================================
      '	The following process are done by the ProcessPage() Function
      '	1. Get the ipn_refno by using $_POST["ipn_refno"]
      '	2. if $_POST["ipn_refno"] <> "" then only the XML Post is done
      '	3. After that the SendIPN_RefNoXMLPost() Function is Proccessed
      '	4. After that the GetValuesFromXML() Function is Proccessed
      '================================================================================================ */

    function ProcessPage($ipn_refno) {
        CakeLog::write('debug', 'pre send ref');
        $this->SendIPN_RefNoXMLPost($ipn_refno);        
        CakeLog::write('debug', 'post send ref');        
        return $this->GetValuesFromXML();
    }

    /* '================================================================================================
      '	The following process are done by the SendIPN_RefNoXMLPost(IPN_RefNo) Function
      ' the INPUT for this Function is ipn_refno
      ' then we get the Transaction Details in XML Format by xmlDoc object
      '================================================================================================ */

    function SendIPN_RefNoXMLPost($IPN_RefNo) {
        
        $sUrl = $this->vtn_validate_url;
        $ch = curl_init();
        // set the target url
        curl_setopt($ch, CURLOPT_URL, $sUrl);
        $ret = curl_setopt($ch, CURLOPT_HEADER, 1);        
        $ret = curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ret = curl_setopt($ch, CURLOPT_TIMEOUT, 30);
// Do a POST
        $data = array('ipn_refno' => $IPN_RefNo); //'ipn_refno='.$IPN_RefNo
        $ret = curl_setopt($ch, CURLOPT_POST, true);
        $ret = curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
        
// grab URL, and print
        $xmlDoc = curl_exec($ch);
        curl_close($ch);
        
        $xml_parse = xml_parser_create();
        xml_parser_set_option($xml_parse, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($xml_parse, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($xml_parse, $xmlDoc, $this->values);        
        xml_parser_free($xml_parse);
    }

    /* '================================================================================================
      '	The following process are done by the GetValuesFromXML() Function
      ' The following tags are in XML Format
      ' <message>					==> Message sent from Server
      ' <ipn_refno>				==> Transaction Ref. No
      ' <merchant_email>	==> Merchant email is also sent for cross verification by the merchant
      ' <item>						==> Item name
      ' <amount>					==> Amount
      ' <customer_email>	==> Customer email is also sent for cross verification by the merchant
      ' <created_date>		==> Transaction Date
      ' <custom_remarks>	==> Custom Data sent by the merchant, is returned back
      ' <status>					==> Status of the Transaction. If Status = 1 Then Good Else Bad
      '================================================================================================ */

    function GetValuesFromXML() {
        $a = array();
        $a['Deposit']['deposit_id'] = $this->GetElementText("ipn_refno");
        $a['Deposit']['amount'] = $this->GetElementText("amount");
        $a['Deposit']['type'] = 'VTN';
        $a['Deposit']['user_id'] = $this->GetElementText("item");
        $a['Deposit']['date'] = gmdate('Y-m-d H:i:s');
        $a['Deposit']['status'] = '1';
        $a['Deposit']['bonus_code'] = $this->GetElementText("custom_remarks");
        
        $a['Deposit']['strMessage'] = $this->GetElementText("message");        
        $a['Deposit']['strMerchant_email'] = $this->GetElementText("merchant_email");                
        $a['Deposit']['strCustomer_email'] = $this->GetElementText("customer_email");
        
        $a['Deposit']['strStatus'] = $this->GetElementText("status");

        if ($a['Deposit']['strStatus'] == "1") {
            CakeLog::write('debug', 'ok');            
            return $a;
        }
        CakeLog::write('debug', 'not ok');
        return array();
    }

    /* '================================================================================================
      '	The following process are done by the GetElementText(Tagname) Function
      ' the INPUT for this Function is Tagnames for Example - ipn_refno
      ' then we get the value for the respective tags
      '================================================================================================ */

    function GetElementText($Tagname) {
        foreach ($this->values as $value) {
            if ($value['tag'] == $Tagname) {
                $element[$Tagname][] = $value['value'];
                return $value['value'];
            }
        }
    }

}

?>
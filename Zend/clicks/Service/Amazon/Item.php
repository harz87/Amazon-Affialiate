<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Item
 *
 * @author h_titz
 */
class Zend_clicks_Service_Amazon_Item
    extends Zend_Service_Amazon_Item
{
    public $images = array();
    public function  __construct($dom) {
        try {
           parent::__construct($dom);
        } catch (Exception $exc) {
            throw new $exc;
        }

        $xpath = new DOMXPath($dom->ownerDocument);
        $xpath->registerNamespace('az', 'http://webservices.amazon.com/AWSECommerceService/2005-10-05');
        $result = $xpath->query('./az:Offers', $dom);
        $resultSummary = $xpath->query('./az:OfferSummary', $dom);
        if ($result->length > 1 || $resultSummary->length == 1) {
            $this->Offers = new Zend_clicks_Service_Amazon_OfferSet($dom);
        }

       foreach (array('SmallImage', 'MediumImage', 'LargeImage') as $im) {
            $result = $xpath->query("./az:ImageSets/az:ImageSet[@Category='variant']/az:$im",$dom);
            $this->images[$im] = array();
            for ($i = 0; $i < $result->length; $i++) {
                /**
                 * @see Zend_Service_Amazon_Image
                 */
                require_once 'Zend/Service/Amazon/Image.php';
                $this->images[$im][$i] = new Zend_Service_Amazon_Image($result->item($i));

       }
                



        }

    }

}
?>

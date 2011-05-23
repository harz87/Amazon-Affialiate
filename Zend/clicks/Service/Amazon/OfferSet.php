<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OfferSet
 *
 * @author h_titz
 */
class Zend_clicks_Service_Amazon_OfferSet
    extends Zend_Service_Amazon_OfferSet
{
    public function  __construct(DOMElement $dom) {
        try {
           parent::__construct($dom);
        } catch (Exception $exc) {
            throw new $exc;
        }
        $xpath = new DOMXPath($dom->ownerDocument);
        $xpath->registerNamespace('az', 'http://webservices.amazon.com/AWSECommerceService/2005-10-05');
        $offers = $xpath->query('./az:Offers/az:Offer', $dom);
        $this->setOffers($offers);
    }

    private function setOffers($offers){
         $this->Offers = null;
         if ($offers->length >= 1) {
            foreach ($offers as $offer) {

                $this->Offers[] = new Zend_clicks_Service_Amazon_Offer($offer);
            }
        }
    }
}
?>

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Offer
 *
 * @author h_titz
 */
class Zend_clicks_Service_Amazon_Offer
    extends Zend_Service_Amazon_Offer
{
    /**
     *
     * @var string
     */
    public $FormattedPrice;

    public function  __construct(DOMElement $dom) {
        parent::__construct($dom);
        $this->formatPrice();
    }
    private function formatPrice(){
        $length = strlen($this->Price);
        $this->FormattedPrice = "";
        $this->FormattedPrice .= $this->CurrencyCode . ' ' . substr($this->Price,0,$length-2) . ',' . substr($this->Price,-2);
        return $this->FormattedPrice;
    }
}
?>

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ResultSet
 *
 * @author h_titz
 */
class Zend_clicks_Service_Amazon_ResultSet
    extends Zend_Service_Amazon_ResultSet
{
    public $skip = array();
    public function  __construct(DOMDocument $dom) {
        parent::__construct($dom);
    }

    public function  current() {

            return new Zend_clicks_Service_Amazon_Item($this->_results->item($this->_currentIndex));



    }

    public function getResults() {
        return $this->_results;
    }


}
?>

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Amazon
 *
 * @author h_titz
 */
class Zend_clicks_Service_Amazon
    extends Zend_Service_Amazon
{
        
    public function  __construct($appId, $countryCode = 'DE', $secretKey = null) {
        parent::__construct($appId, $countryCode, $secretKey);
    }

    public function itemSearch(array $options)
    {
        $client = $this->getRestClient();
        $client->setUri($this->_baseUri);

        $defaultOptions = array('ResponseGroup' => 'Small');
        $options = $this->_prepareOptions('ItemSearch', $options, $defaultOptions);
        $client->getHttpClient()->resetParameters();
        $response = $client->restGet('/onca/xml', $options);

        if ($response->isError()) {
            /**
             * @see Zend_Service_Exception
             */
            require_once 'Zend/Service/Exception.php';
            throw new Zend_Service_Exception('An error occurred sending request. Status code: '
                                           . $response->getStatus());
        }

        $dom = new DOMDocument();
        $dom->loadXML($response->getBody());
        self::_checkErrors($dom);

        /**
         * @see Zend_Service_Amazon_ResultSet
         */
          return new Zend_clicks_Service_Amazon_ResultSet($dom);
    }

    public function itemLookup($asin, array $options = array())
    {
        $client = $this->getRestClient();
        $client->setUri($this->_baseUri);
        $client->getHttpClient()->resetParameters();

        $defaultOptions = array('ResponseGroup' => 'Small');
        $options['ItemId'] = (string) $asin;
        $options = $this->_prepareOptions('ItemLookup', $options, $defaultOptions);
        $response = $client->restGet('/onca/xml', $options);

        if ($response->isError()) {
            /**
             * @see Zend_Service_Exception
             */
            require_once 'Zend/Service/Exception.php';
            throw new Zend_Service_Exception(
                'An error occurred sending request. Status code: ' . $response->getStatus()
            );
        }

        $dom = new DOMDocument();
        $dom->loadXML($response->getBody());
        self::_checkErrors($dom);
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('az', 'http://webservices.amazon.com/AWSECommerceService/2005-10-05');
        $items = $xpath->query('//az:Items/az:Item');

      /* if ($items->length == 1) {
            /**
             * @see Zend_Service_Amazon_Item
             */
          /*  require_once 'Zend/Service/Amazon/Item.php';
            return new Zend_Service_Amazon_Item($items->item(0));
        }*/

        return new Zend_clicks_Service_Amazon_ResultSet($dom);
    }
}
?>

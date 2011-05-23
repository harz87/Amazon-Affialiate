<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once ("import_functions.php");
function aa_admin_import_page() {
    global $keyword, $$selectedSearchIndex, $itempage;
    $appid = get_option('dbaa_amazon_appid');
    $coutryCode= get_option('dbaa_amazon_coutnrycode');
    $secretKey = get_option('dbaa_amazon_secretkey');
    $associatetag = get_option('dbaa_amazon_associatetag');
    if($appid == '' || $coutryCode == ''|| $secretKey == '') {
        echo '<H2>Bitte gehen Sie zu den Einstellungen und geben Sie Ihre Daten zur Kommunikation mit der Amazon Api ein.</H2>';
    }
    else {
    $defaultOptions = array('AssociateTag' => $associatetag, 'ResponseGroup' => 'Small, OfferFull, ItemAttributes, Images, Variations');

    $amazon = new Zend_clicks_Service_Amazon($appid, $coutryCode,$secretKey);
    $itemResults=array();
    if ($_REQUEST['action']=='search' && !empty ($_POST['keyword']) && empty($_POST['asin'])) {
        $keyword = $_POST['keyword'];
        $selectedSearchIndex = $_POST['searchIndex'];
        $itempage = 1;
        $options = array('SearchIndex' => $selectedSearchIndex,'Keywords' => $keyword, 'ItemPage' => $itempage);
        $options = array_merge($options, $defaultOptions);
        $itemResults = $amazon->itemSearch($options);
    }
    elseif ($_REQUEST['action']=='search' && empty ($_POST['keyword']) && !empty($_POST['asin'])){
        $asin = $_POST['asin'];
        $selectedSearchIndex = $_POST['searchIndex'];
        $itemResults = $amazon->itemLookup($asin, $defaultOptions);
    }
    elseif (($_REQUEST['action']=='back' && !empty ($_POST['itempage']))||($_REQUEST['action']=='next' && !empty ($_POST['itempage']))){
       $keyword = $_POST['keyword'];
       $selectedSearchIndex = $_POST['searchIndex'];
       $itempage = $_POST['itempage'];
       $options = array('SearchIndex' => $selectedSearchIndex,'Keywords' => $keyword, 'ItemPage' => $itempage);
       $options = array_merge($options, $defaultOptions);
       $itemResults = $amazon->itemSearch($options);
       
    }
    elseif($_REQUEST['action']=='save' && !empty($_POST['title'])){
        if(saveItem($_POST)){
            echo '<div class="updated"><p><strong>Produkt erfolgreich importiert</strong></p></div>';
        }
        
    }
    
    echo '<h2>Produkte importieren</h2>';
    searchItem($keyword, $asin, $selectedSearchIndex, $itempage); // Suchformular einblenden;
    foreach($itemResults as $result){
        formatResult($result);
    }
    }



}
?>

<?php
$cookie = '__ngDebug=true; sessionid=NTQ1MjM5NTQ0; recentlyVisitedAppHubs=570; webTradeEligibility=%7B%22allowed%22%3A1%2C%22allowed_at_time%22%3A0%2C%22steamguard_required_days%22%3A15%2C%22sales_this_year%22%3A132%2C%22max_sales_per_year%22%3A-1%2C%22forms_requested%22%3A0%2C%22new_device_cooldown_days%22%3A7%7D; steamRememberLogin=76561198032255024%7C%7Cdac93fde2d8413cf9a07587d4b3c1a99; steamLogin=76561198032255024%7C%7C206D72EE094F069EDC4213C1C73B9D97FA411D79; Steam_Language=russian; strInventoryLastContext=570_2; steamCC_95_79_111_115=RU; timezoneOffset=14400,0; __utma=268881843.1558054940.1392546100.1398976815.1399061165.50; __utmb=268881843.0.10.1399061165; __utmc=268881843; __utmz=268881843.1398976815.49.21.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided); _ga=GA1.2.1558054940.1392546100';

$url = $_GET["url"];
//print_r($url);

//$url = 'http://steamcommunity.com/market/listings/570/Iceforged%20Cape';
//$url = 'http://steamcommunity.com/market/listings/570/Treasure%20of%20Vermilion%20Renewal';

$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, $url);
//curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
//curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_COOKIE, $cookie);
//curl_setopt ($ch, CURLOPT_POSTFIELDS, 'email='.$username.'&password='.$password);
//curl_setopt ($ch, CURLOPT_POST, 1);
$result = curl_exec ($ch);
$exectime = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
curl_close($ch);

$data = [
  'time' => $exectime,
  'link' => $url,
  'prices' => [],
  'profit' => 0
];

if ($result) {
    require_once 'simple_html_dom.php';
    $html = str_get_html($result);
    $marketItems = $html->find('span[class=market_table_value]');
//
//$doc = new DOMDocument('1.0', 'UTF-8');
//$doc->loadHTML ('<?xml encoding="UTF-8">' . $data['result']);
//$dom->preserveWhiteSpace = false;
//$marketItems = $doc->getElement
    $count = count($marketItems);
    for ($i = 0; $i < $count; $i++) {
        foreach($marketItems[$i]->find('span') as $span){
            $float = str_replace(",",".",$span->innertext);
            $float = floatval($float);
            $data['prices'][$i][] = $float;
        };
    }
    $data['profit'] = $data['prices'][1][1] - $data['prices'][0][0];
}

echo json_encode($data);
?>

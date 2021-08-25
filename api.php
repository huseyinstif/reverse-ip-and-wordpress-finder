<html>
<style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}
</style>

<table id="customers">
  <tr>
    <th>Taranan Site</th>
    <th>Wordpress Mi?</th>
  </tr>



<?php
set_time_limit(95000);
$ipAdress = $_GET["ip"];

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://api.hackertarget.com/reverseiplookup/?q=" . $ipAdress);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($curl);

$siteler = array();
$wordpressOlanlar = array();

$ayir = explode("\n", $response);
$toplamSayi = count($ayir);

for($i=0; $i < $toplamSayi; $i++) {
    array_push($siteler, $ayir[$i]);
}

echo '<center><font color="#04AA6D"><h2>'. $toplamSayi. ' Adet Web Sitesi Bulundu. Taramaya Başlanıyor..</h2></font></center>';

foreach ($siteler as $value) {
    $wordpressTamUrl = "https://" . $value . "/wp-login.php";
    curl_setopt($curl, CURLOPT_URL, $wordpressTamUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $donut = curl_exec($curl);
    $araBul = strpos($donut,"wordpress");
        if ($araBul == false) {
           ?> <tr>
            <td><?php echo $value; ?></td>
            <td><?php echo '<font color="red">Wordpress Değil</font>'; ?></td>
          </tr>
<?php            
         flush();
         ob_get_contents();
         ob_flush();
        
        } else { ?>
        <tr>
            <td><?php echo $value; ?></td>
            <td><?php echo '<font color="green">Wordpress !</font>'; ?></td>
            <?php array_push($wordpressOlanlar, $wordpressTamUrl); ?>
          </tr>
<?php         
         flush();
         ob_get_contents();
         ob_flush();
                 
        }
}

$benzersizTxtID = md5(rand(1337, 1337133713371337) * date("His"));
file_put_contents($benzersizTxtID.'.txt', print_r($wordpressOlanlar, TRUE));

echo '<center><font color="#04AA6D"><h2>'.'<a href="'. $benzersizTxtID.'.txt' . '">'.'Tarama Bitti.. Wordpress Site Listesi İçin Tıklayın' .'</a></h2></font></center>';


curl_close($curl);
?>



</table>
</html>
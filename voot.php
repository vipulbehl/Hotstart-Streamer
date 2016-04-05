<?php

$idcontent = substr($argv[1], -6);
$data = '{"initObj":{"Locale":{"LocaleLanguage":"","LocaleCountry":"","LocaleDevice":"","LocaleUserState":"Unknown"},"Platform":"Web","SiteGuid":"","DomainID":0,"UDID":"","ApiUser":"tvpapi_225","ApiPass":"11111"},"MediaID":"' . $idcontent . '","mediaType":0,"picSize":"full","withDynamic":false}';

$time_start = microtime(true);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://tvpapi-as.ott.kaltura.com/v3_4/gateways/jsonpostgw.aspx?m=GetMediaInfo');
curl_setopt($ch, CURLOPT_POST, 1);   
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=utf-8'));
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_REFERER, $argv[1]);
curl_setopt($ch, CURLOPT_ENCODING , "gzip");
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux i686; rv:41.0) Gecko/20100101 Firefox/41.0 Iceweasel/41.0");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$result = curl_exec($ch);

curl_close ($ch);

preg_match('/"Format":"dash Main".*?CoGuid":"(.*?)"/', $result, $ids);
preg_match('/"MediaName":"(.*?)"/', $result, $name);
$name= str_ireplace(' ', '-', $name[1]);

preg_match('/(.*?)_0_/', $ids[1], $entryId);
preg_match('/(.*?),/', $ids[1], $low);
preg_match('/0_.*?_(.*)/', $low[1], $lflavorId);

preg_match('/,(.*?),/', $ids[1], $medium);
preg_match('/0_.*?_(.*)/', $medium[1], $mflavorId);

preg_match('/.*?,.*?,(.*)/', $ids[1], $high);
preg_match('/0_.*?_(.*)/', $high[1], $hflavorId);

if ($argv[4]=='low'){
	$flavorId=$lflavorId[1];
	} 
	else{
	if ($argv[4]=='medium'){	
	    $flavorId=$mflavorId[1];
	    }
		else{
		$flavorId=$hflavorId[1];
            }		
	}

$link = 'http://video.voot.com/enc/fhls/p/1982551/sp/198255100/serveFlavor/entryId/' . $entryId[1] . '/v/2/pv/1/flavorId/' . $flavorId . '/name/a.mp4/index.m3u8';

echo "$link\n\n";

echo "Starting  livestreamer...\n\n";
	echo shell_exec("$argv[3]livestreamer \"hls://$link\" best -o \"$argv[2]$name.ts\" &");
	echo "Done.\n";


?>

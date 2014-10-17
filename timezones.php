<?php
function gettime_timezone($timeZone, $datetime="")
{
	if ($timeZone == '')
		$timeZone = 'Europe/London';
	$userTimezone = new DateTimeZone($timeZone);
	if ($datatime == "")
		$mydatetime = new DateTime();
	else
		$mydatetime = new DateTime($datetime);
		
	$mydatetime->setTimezone($userTimezone);
	$current_time = $mydatetime->format('Y-m-d H:i:s');
	$timestamp=strtotime($current_time);
	return $timestamp;
}
$timeZones = array();
$timeZones["Pacific/Midway"] = "(GMT-11:00) Midway Island, Samoa";
$timeZones["America/Adak"] = "(GMT-10:00) Hawaii-Aleutian";
$timeZones["Etc/GMT+10"] = "(GMT-10:00) Hawaii";
$timeZones["Pacific/Marquesas"] = "(GMT-09:30) Marquesas Islands";
$timeZones["Pacific/Gambier"] = "(GMT-09:00) Gambier Islands";
$timeZones["America/Anchorage"] = "(GMT-09:00) Alaska";
$timeZones["America/Ensenada"] = "(GMT-08:00) Tijuana, Baja California";
$timeZones["Etc/GMT+8"] = "(GMT-08:00) Pitcairn Islands";
$timeZones["America/Los_Angeles"] = "(GMT-08:00) Pacific Time (US & Canada)";
$timeZones["America/Denver"] = "(GMT-07:00) Mountain Time (US & Canada)";
$timeZones["America/Chihuahua"] = "(GMT-07:00) Chihuahua, La Paz, Mazatlan";
$timeZones["America/Dawson_Creek"] = "(GMT-07:00) Arizona";
$timeZones["America/Belize"] = "(GMT-06:00) Saskatchewan, Central America";
$timeZones["America/Cancun"] = "(GMT-06:00) Guadalajara, Mexico City, Monterrey";
$timeZones["Chile/EasterIsland"] = "(GMT-06:00) Easter Island";
$timeZones["America/Chicago"] = "(GMT-06:00) Central Time (US & Canada)";
$timeZones["America/New_York"] = "(GMT-05:00) Eastern Time (US & Canada)";
$timeZones["America/Havana"] = "(GMT-05:00) Cuba";
$timeZones["America/Bogota"] = "(GMT-05:00) Bogota, Lima, Quito, Rio Branco";
$timeZones["America/Caracas"] = "(GMT-04:30) Caracas";
$timeZones["America/Santiago"] = "(GMT-04:00) Santiago";
$timeZones["America/La_Paz"] = "(GMT-04:00) La Paz";
$timeZones["Atlantic/Stanley"] = "(GMT-04:00) Faukland Islands";
$timeZones["America/Campo_Grande"] = "(GMT-04:00) Brazil";
$timeZones["America/Goose_Bay"] = "(GMT-04:00) Atlantic Time (Goose Bay)";
$timeZones["America/Glace_Bay"] = "(GMT-04:00) Atlantic Time (Canada)";
$timeZones["America/St_Johns"] = "(GMT-03:30) Newfoundland";
$timeZones["America/Araguaina"] = "(GMT-03:00) UTC-3";
$timeZones["America/Montevideo"] = "(GMT-03:00) Montevideo";
$timeZones["America/Miquelon"] = "(GMT-03:00) Miquelon, St. Pierre";
$timeZones["America/Godthab"] = "(GMT-03:00) Greenland";
$timeZones["America/Argentina/Buenos_Aires"] = "(GMT-03:00) Buenos Aires";
$timeZones["America/Sao_Paulo"] = "(GMT-03:00) Brasilia";
$timeZones["America/Noronha"] = "(GMT-02:00) Mid-Atlantic";
$timeZones["Atlantic/Cape_Verde"] = "(GMT-01:00) Cape Verde Is.";
$timeZones["Atlantic/Azores"] = "(GMT-01:00) Azores";
$timeZones["Europe/Belfast"] = "(GMT) Greenwich Mean Time : Belfast";
$timeZones["Europe/Dublin"] = "(GMT) Greenwich Mean Time : Dublin";
$timeZones["Europe/Lisbon"] = "(GMT) Greenwich Mean Time : Lisbon";
$timeZones["Europe/London"] = "(GMT) Greenwich Mean Time : London";
$timeZones["Africa/Abidjan"] = "(GMT) Monrovia, Reykjavik";
$timeZones["Europe/Amsterdam"] = "(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna";
$timeZones["Europe/Belgrade"] = "(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague";
$timeZones["Europe/Brussels"] = "(GMT+01:00) Brussels, Copenhagen, Madrid, Paris";
$timeZones["Africa/Algiers"] = "(GMT+01:00) West Central Africa";
$timeZones["Africa/Windhoek"] = "(GMT+01:00) Windhoek";
$timeZones["Asia/Beirut"] = "(GMT+02:00) Beirut";
$timeZones["Africa/Cairo"] = "(GMT+02:00) Cairo";
$timeZones["Asia/Gaza"] = "(GMT+02:00) Gaza";
$timeZones["Africa/Blantyre"] = "(GMT+02:00) Harare, Pretoria";
$timeZones["Asia/Jerusalem"] = "(GMT+02:00) Jerusalem";
$timeZones["Europe/Minsk"] = "(GMT+02:00) Minsk";
$timeZones["Asia/Damascus"] = "(GMT+02:00) Syria";
$timeZones["Europe/Moscow"] = "(GMT+03:00) Moscow, St. Petersburg, Volgograd";
$timeZones["Africa/Addis_Ababa"] = "(GMT+03:00) Nairobi";
$timeZones["Asia/Tehran"] = "(GMT+03:30) Tehran";
$timeZones["Asia/Dubai"] = "(GMT+04:00) Abu Dhabi, Muscat";
$timeZones["Asia/Yerevan"] = "(GMT+04:00) Yerevan";
$timeZones["Asia/Kabul"] = "(GMT+04:30) Kabul";
$timeZones["Asia/Yekaterinburg"] = "(GMT+05:00) Ekaterinburg";
$timeZones["Asia/Tashkent"] = "(GMT+05:00) Tashkent";
$timeZones["Asia/Kolkata"] = "(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi";
$timeZones["Asia/Katmandu"] = "(GMT+05:45) Kathmandu";
$timeZones["Asia/Dhaka"] = "(GMT+06:00) Astana, Dhaka";
$timeZones["Asia/Novosibirsk"] = "(GMT+06:00) Novosibirsk";
$timeZones["Asia/Rangoon"] = "(GMT+06:30) Yangon (Rangoon)";
$timeZones["Asia/Bangkok"] = "(GMT+07:00) Bangkok, Hanoi, Jakarta";
$timeZones["Asia/Krasnoyarsk"] = "(GMT+07:00) Krasnoyarsk";
$timeZones["Asia/Hong_Kong"] = "(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi";
$timeZones["Asia/Irkutsk"] = "(GMT+08:00) Irkutsk, Ulaan Bataar";
$timeZones["Australia/Perth"] = "(GMT+08:00) Perth";
$timeZones["Australia/Eucla"] = "(GMT+08:45) Eucla";
$timeZones["Asia/Tokyo"] = "(GMT+09:00) Osaka, Sapporo, Tokyo";
$timeZones["Asia/Seoul"] = "(GMT+09:00) Seoul";
$timeZones["Asia/Yakutsk"] = "(GMT+09:00) Yakutsk";
$timeZones["Australia/Adelaide"] = "(GMT+09:30) Adelaide";
$timeZones["Australia/Darwin"] = "(GMT+09:30) Darwin";
$timeZones["Australia/Brisbane"] = "(GMT+10:00) Brisbane";
$timeZones["Australia/Hobart"] = "(GMT+10:00) Hobart";
$timeZones["Asia/Vladivostok"] = "(GMT+10:00) Vladivostok";
$timeZones["Australia/Lord_Howe"] = "(GMT+10:30) Lord Howe Island";
$timeZones["Etc/GMT-11"] = "(GMT+11:00) Solomon Is., New Caledonia";
$timeZones["Asia/Magadan"] = "(GMT+11:00) Magadan";
$timeZones["Pacific/Norfolk"] = "(GMT+11:30) Norfolk Island";
$timeZones["Asia/Anadyr"] = "(GMT+12:00) Anadyr, Kamchatka";
$timeZones["Pacific/Auckland"] = "(GMT+12:00) Auckland, Wellington";
$timeZones["Etc/GMT-12"] = "(GMT+12:00) Fiji, Kamchatka, Marshall Is.";
$timeZones["Pacific/Chatham"] = "(GMT+12:45) Chatham Islands";
$timeZones["Pacific/Tongatapu"] = "(GMT+13:00) Nuku'alofa";
$timeZones["Pacific/Kiritimati"] = "(GMT+14:00) Kiritimati";
?>
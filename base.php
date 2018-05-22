<?php
if(isset($_GET['base64']))
{
$password=$_GET['base64'];
$str="WvqersLeDj84MePla7bBLG+K838yOGI3";
$decode=base64_decode($str);
//echo $decode."<br>";
$salt=substr($decode,20);
//echo $salt."<br>";
$a=md5($password);
//echo "md5.".$a."<br>";
$addsaltsha1=sha1($a.$salt,true);
//echo "sha1: ".$addsaltsha1."<br>";
$hash = base64_encode( $addsaltsha1 . $salt );
echo $hash;
    exit(0);
}
?>
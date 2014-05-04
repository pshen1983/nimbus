<?php
include_once ("SecurityUtil.php");

session_start();
SecurityUtil::checkLogin($_SERVER['REQUEST_URI']);

$file = new stdClass();
$fileElement = $_POST['fileElement'];
$fileSize = $_POST['fileSize'];
$fileName = $_POST['fileName'];

if( (($_FILES[$fileElement]["type"] == "image/gif") || 
	 ($_FILES[$fileElement]["type"] == "image/jpeg") || 
	 ($_FILES[$fileElement]["type"] == "image/pjpeg")|| 
	 ($_FILES[$fileElement]["type"] == "image/jpg")|| 
	 ($_FILES[$fileElement]["type"] == "image/png")) && 
	 ($_FILES[$fileElement]["size"] < $fileSize*1048576) &&
	 (isset($fileName) && !empty($fileName)) ) 
{
	if ($_FILES[$fileElement]["error"] > 0)
	{
		$file->error = 2;
	}
	else
	{
		if( move_uploaded_file ( $_FILES[$fileElement]["tmp_name"], $fileName ) )
		{
			list($width, $height, $type, $attr) = getimagesize($fileName);			
			$file->error = 0;			
			$file->url=$fileName.'?t='.time();
			$file->width=$width;
			$file->height=$height;
			$file->type=$type;
		}
		else
		{
			$file->error = 3;
		}
	}
}
else
{
	$file->error = 1;
}

echo json_encode($file);

?>
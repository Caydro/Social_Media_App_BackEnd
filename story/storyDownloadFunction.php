<?php



function postImageDetails($filter){


if (isset($_POST[$filter])){
return  htmlspecialchars(strip_tags($_POST[$filter])); 
}
else{
echo json_encode(array("request"=>"empty"));
return;
}    

}

function uploadImageFunc($imageRequest){

    global $errMessage;
    define("MB", 1048576);
$image = $_FILES[$imageRequest];
$imageSize = $image['size'];
$tmp_name = $image['tmp_name'];
$imageName =rand(1000,10000). $image['name'];

$allowExtentions = array("jpg", "jpeg", "png","gif","mp3","pdf");

$imageExt = explode("." , $imageName);
$ext = end($imageExt);
$ext = strtolower($ext);
if(!empty($imageName) && !in_array( $ext, $allowExtentions)){
$errMessage[] = "Wrong Extention";
} if ($imageSize > 8 * MB){
    $errMessage[] = "Size is bigger than required";
}
if (empty($errMessage)){
    move_uploaded_file($tmp_name, "../story/storiesHome/" .$imageName);
    return $imageName;
}else{
return "Failed";
}

}

function deleteFunction($dir,$imageName){
    if(file_exists($dir."/".$imageName)){
        unlink($dir ."/".$imageName);
    }
}


?>
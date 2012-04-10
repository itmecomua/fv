<?php
if (!empty($_FILES))
{
    $tempFile = $_FILES['Filedata']['tmp_name'];
    $targetPath = $_SERVER['DOCUMENT_ROOT'] . $_GET['folder'] . '/';
    $tmp = explode(".",$_FILES['Filedata']['name']);
    $fileName = uniqid().md5(mktime()).".".$tmp[count($tmp)-1];    

    $targetFile =  str_replace('//','/',$targetPath) . $fileName;


    // Uncomment the following line if you want to make the directory if it doesn't exist
    @mkdir(str_replace('//','/',$targetPath), 0775, true);
    if(move_uploaded_file($tempFile,$targetFile))
    {
        echo $fileName;
    }
    else
    {
        switch($_FILES['Filedata']['error'])
        {
            case 1:
            {
                echo "Превышен максимальный размер файла. Это настрокая сервера Apache.";
            }
            default:
            {
                echo $_FILES['Filedata']['error'];
            }
        }
    }
}

?>

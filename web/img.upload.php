  <?php

if (!empty($_FILES))
{
    
    $tempFile = $_FILES['file']['tmp_name'];
    $subDir = '/upload/redactor/';
    $targetPath = $_SERVER['DOCUMENT_ROOT'] . $subDir;

    $tmp = explode(".",$_FILES['file']['name']);
    $fileName = md5(mktime()).".".$tmp[ count( $tmp ) - 1 ];    

    $targetFile =  str_replace('//','/',$targetPath) . $fileName;

    // Uncomment the following line if you want to make the directory if it doesn't exist
    @mkdir(str_replace('//','/',$targetPath), 0775, true);
    if( move_uploaded_file( $tempFile, $targetFile ) )
    {
        echo $subDir.$fileName;
    }
    else
    {
        switch($_FILES['Filedata']['error'])
        {
            case 1:
            {
                echo "Превышен максимальный размер файла. Это настрока сервера Apache.";
            }
            default:
            {
                echo $_FILES['Filedata']['error'];
            }
        }
    }
}

?>
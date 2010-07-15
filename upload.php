<?php

    $uploaddir = 'upload/tmp/';
    $file = $uploaddir . basename($_FILES['uploadfile']['name']);
    $size = 0.001 * $_FILES['uploadfile']['size'];
    $valid_extensions = strtolower($_POST['valid_extensions']);
    $maxfilesize = $_POST['maxfilesize'];
    $preview = $_POST['preview'];

    $valid_extensions_array = explode(",", $valid_extensions);

    for ($i = 0; $i < count($valid_extensions_array); $i++)
        $valid_extensions_array[$i] = trim($valid_extensions_array[$i]);
    
    $pathinfo = pathinfo($_FILES['uploadfile']['name']);
    $ext = strtolower($pathinfo['extension']);

    // check to ensure that the file does not cross the maximum file size
    if($size > $maxfilesize)
    {
        $return = array(
                        "success" => false,
                        "msg" => "Sorry, This file (".$size.") is too large. Only files upto ".$maxfilesize." KB are allowed"
                    );

        echo json_encode($return);
    }
    // check to see that this file type is allowed
    // it is also  checked at the client side, but jst double checking
    else if (!in_array($ext, $valid_extensions_array))
    {
        $return = array(
                        "success" => false,
                        "msg" => "Sorry, This file extension (".$ext.") is not allowed !"
                    );

        echo json_encode($return);
    }

    // If this is just a preview, don't save the file
    if ($preview)
    {

        if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file))
        {
            $return = array(
                        "success" => true,
                        "size"    => $size,
                        "name"    => basename($file),
                        "ext"     => $ext,
                        "msg"     => "The file has been successfuly uploaded."
                    );
            echo json_encode($return);

        }
    }
    else 
    {    // if everything went fine and the file was uploaded successfuly,
         // send the file related info back to the client
        if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file))
        {
            $return = array(
                        "success" => true,
                        "size"    => $size,
                        "name"    => basename($file),
                        "ext"     => $ext,
                        "msg"     => "The file has been successfuly uploaded"
                    );
            echo json_encode($return);
        }
        // if there was some error, report error message
        else
        {
            $return = array(
                        "success" => false,
                        "msg" => "Unknown error"
                    );
            echo json_encode($return);
        }
    }
?>
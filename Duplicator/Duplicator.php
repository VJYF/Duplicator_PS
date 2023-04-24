<?php

//LOCAL TO PROD
$sitename = "Glenwood"; //Nom du site

function compress($a, $b, $name){

    // Get real path for our folder
    $rootPath = realpath($a);
    $toPath = realpath($b);
    
    // Initialize archive object
    $zip = new ZipArchive();
    $zip->open($toPath."/".$name.".zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);
    
    // Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    
    foreach ($files as $name => $file)
    {
        // Skip directories (they would be added automatically)
        if (!$file->isDir())
        {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);
            $filesname = substr($filePath, strlen($rootPath) + 9);
            $trimmedPath = trim($filePath, "param.php");


        
            if($filesname !== "param.php"){
                
                echo '<br>';
                echo "AJOUT DES FICHIERS DANS LE ZIP";
                // echo '<br>';
                // echo '<strong>'.$filesname.'</strong>';
                echo '<br>';
                echo "filePath => ".$filePath;
                echo '<br>';
                echo "relativePath => ".$relativePath;
                echo '<br>';
                $zip->addFile($filePath, $relativePath);
                echo $filePath." => ajouté au zip";
                echo '<br>';
                
            }
            
        }
    }   
    // $filePath = $file->getRealPath();

    // $relativePath = substr($filePath, strlen($rootPath) + 1);
    // echo '<br>';
    // echo '<strong>'.$relativePath.'</strong>';

    // $filesname = substr($filePath, strlen($rootPath) + 5);
    // echo '<br>';
    // echo '<strong>'.$filesname.'</strong>';

    // $trimmedPath = trim($relativePath, 'www/');
    // echo '<br>';
    // echo '<strong>'.$trimmedPath.'</strong>';
    $trimmedPath = trim($filePath, "www.php");
    $Prod = $trimmedPath."OLD-param.php";
    $Local = $trimmedPath."app\param.php";

    //create a tmp file and put content of param.php in
    $tmp = fopen($trimmedPath.'tmp', 'wb+');
    $path_tmp = stream_get_meta_data($tmp);
    $path_tmp = $path_tmp['uri'];
    
    // echo '<br>';
    // echo '<strong>'.$trimmedPath.'</strong>';
    echo '<br>';
    echo 'filePath => ' . $path_tmp;
    // echo '<br>';
    // echo 'relativePath => ' . $relativePath;
    //echo 'trimmedPath => ' . $trimmedPath;
    // echo '<br>';
    // echo '<br>';
    //return
    echo '<br>';
    echo 'trimmedPath => ' . $trimmedPath;
    echo '<br>';
    echo 'Local => ' . $Local;
    //read the entire param.php
    $str=file_get_contents($Local);
    
    //Write the entire param.php in the tmp file
    fwrite($tmp, $str);
    
    //replace something in the file string - this is a VERY simple example
    $str=file_get_contents($path_tmp);
    
    //database_user replace
    $oldregex_username = "'database_user' => 'root',";
    $newregex_username = "'database_user' => 'username',";
    $oldMessage = $oldregex_username;
    $deletedFormat = $newregex_username;
    $str=str_replace($oldMessage, $deletedFormat,$str);
    
    //database_password replace
    $oldregex_pass = "'database_password' => '',";
    $newregex_pass = "'database_password' => 'password',";
    $oldMessage = $oldregex_pass;
    $deletedFormat = $newregex_pass;
    $str=str_replace($oldMessage, $deletedFormat,$str);
    
    //database_name replace
    $oldregex_name = "'database_name' => 'glenwood_prod',";
    $newregex_name = "'database_name' => 'name',";
    $oldMessage = $oldregex_name;
    $deletedFormat = $newregex_name;
    $str=str_replace($oldMessage, $deletedFormat,$str);

    echo '<br>';
    echo "Rename param(Local) => ".$Local;
    $tmp_c=file_get_contents($Local);
    $tmp_b=file_get_contents($path_tmp);
    echo "<pre>".print_r($tmp_b,true)."</pre>";

    //write the entire string
    file_put_contents($path_tmp, $str);
    fwrite($tmp, $str);
        
    // Add current file to archive
    echo '<br>';
    echo "AJOUT  DU PARAM DANS LE ZIP";
    echo '<br>';
    echo "rename path_tmp => ".$path_tmp;
    echo '<br>';
    echo "Local => ".$Local;
    echo '<br>';
    
    $zip->addFile($path_tmp, $Local);
    
    echo $Local." => ajouté au zip";
    echo '<br>';
    
    //rename($Local , $trimmedPath."LOCAL-param.php");
    
    
    // echo '<br>';
    // $Prod = $rootPath."\www\app\OLD-param.php";
    // $Local = $rootPath."\www\app\param.php";
    // echo $Prod;
    // echo '<br>';
    // echo $Local;
    // rename($Prod, $Local);
    
    // Zip archive will be created only after closing object
    $zip->close();
    echo '<br>';
    echo 'zip added => ' . $toPath;
}
compress("to-zip/", "zipped", $sitename);

/////////////////////////////////////////////

// function uncompress($a, $b, $name){

//     // Get real path for our folder
//     $rootPath = realpath($a);
//     $toPath = realpath($b);
    
//     // Initialize archive object
//     $zip = new ZipArchive();
//     $zip->open($toPath."/".$name.".zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);
    
//     // Create recursive directory iterator
//     /** @var SplFileInfo[] $files */
//     $files = new RecursiveIteratorIterator(
//         new RecursiveDirectoryIterator($rootPath),
//         RecursiveIteratorIterator::LEAVES_ONLY
//     );
    
//     foreach ($files as $name => $file)
//     {
//         // Skip directories (they would be added automatically)
//         if (!$file->isDir())
//         {
//             // Get real and relative path for current file
//             $filePath = $file->getRealPath();
//             $relativePath = substr($filePath, strlen($rootPath) + 1);
            
//             // Add current file to archive
//             $zip->addFile($filePath, $relativePath);
//         }
//         echo $file;
//     }
//     echo 'zip added => ' . $toPath;
    
//     // Zip archive will be created only after closing object
//     $zip->close();
// }
// // compress("to-zip/www/", "zipped", "test")

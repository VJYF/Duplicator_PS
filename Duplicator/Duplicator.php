<?php
// require( "ziplib/zip.lib.php" ) ; //indiquez le chemin d'accès à la lib 

//$zipname = "sitename"; //Dossier du racine du site XXXX/sitename
// $rootPath = "to-zip/";
// $zippeddir = "zipped/";

//     function uncompress($srcName, $dstName) {
//         $string = implode("", gzfile($srcName));
//         $fp = fopen($dstName, "w");
//         fwrite($fp, $string, strlen($string));
//         fclose($fp);
//     } 
    
//     function compressF($rootPath, $sitename ,$zippeddir)
//     {
//         // Get real path for our folder
//         //$rootPath = realpath('folder-to-zip');
        
//         // Initialize archive object
//         $zip = new ZipArchive();
//         $zip->open($zippeddir.$sitename.".zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);
        
//         // Create recursive directory iterator
//         /** @var SplFileInfo[] $files */
//         $files = new RecursiveIteratorIterator(
//             new RecursiveDirectoryIterator($rootPath),
//             RecursiveIteratorIterator::LEAVES_ONLY
//         );
        
//         foreach ($files as $name => $file)
//         {
//             // Skip directories (they would be added automatically)
//             if (!$file->isDir())
//             {
//                 // Get real and relative path for current file
//                 $filePath = $file->getRealPath();
//                 $relativePath = substr($filePath, strlen($rootPath) + 1);
        
//                 // Add current file to archive
//                 $zip->addFile($filePath, $relativePath);
//             }
//         }
        
//         // Zip archive will be created only after closing object
//         $zip->close();
//     }
//     compressF($rootPath, $sitename, $zippeddir);

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

            //si le nom n'est pas param alors:
            // - add to zip
            // sinon:
            // - copy file to temp
            // - update temp file
            // - add temp file to zip with old relative path
            echo 'filePath => ' . $filePath;
            echo '<br>';
            echo 'relativePath => ' . $relativePath;
            echo '<br>';
            
            if($filesname !== "param.php"){

                $zip->addFile($filePath, $relativePath);      

            }else{

                // //read the entire string
                echo '<br>';
                $str=file_get_contents($file);
                echo 'param.php file => ' .$file;
                echo '<br>';
                echo 'Origin array';
                echo "<pre>".print_r($str,true)."</pre>";
                
                // //create a temp file and put content of param.php in
                $tmp = tmpfile();
                $path_tmp = stream_get_meta_data($tmp);
                $path_tmp = $path_tmp['uri'];
                echo '<br>';
                echo 'tmp file p => ' . $path_tmp;
                echo '<br>';

                fwrite($tmp, $str);
                
                //replace something in the file string - this is a VERY simple example
                $str=file_get_contents($path_tmp);
                
                //database_user replace
                $oldregex_username = "'database_user' => 'root'";
                $newregex_username = "'database_user' => 'username'";
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
                
                //write the entire string
                file_put_contents($path_tmp, $str);
                
                echo 'Modified array';
                echo "<pre>".print_r($str,true)."</pre>";
                
                
                fclose($tmp);

                // rename($path_tmp , 'param.php');
                //rename($path_tmp , $filePath.".bak.php");
                
                
                // 
                // copy($filePath, $path_tmp);
                
        }
            
            // Add current file to archive
            
        }

    }
    echo '<br>';
    echo 'zip added => ' . $toPath;
    
    // Zip archive will be created only after closing object
    $zip->close();
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
?>
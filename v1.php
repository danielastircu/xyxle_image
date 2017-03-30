<?php
$directory_v1 = __DIR__ . '/images/v1/';
$files        = scandir($directory_v1);
$google       = new GoogleConnection();
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        $filePath = $directory_v1 . $file;
        $exist    = ($db->GetOne("SELECT id FROM `v1` WHERE `name` = '{$file}'"));
        if (!$exist) {
            $result = $google->getImageData($filePath);
            $db->Execute("INSERT INTO  `v1`  (`name`, `path`, `response`) VALUES ('{$file}','{$filePath}', '{$result}')");
        }

    }
    //do your work here
}

//FOR FILES
$directory_v1 = __DIR__ . '/images/v1/files/';
$files        = scandir(__DIR__ . '/images/v1/');
foreach ($files as $file) {
    if ($file != '.' && $file != '..' && $file != 'files') {
        $filePath = __DIR__ . '/images/v1/' . $file;
        $exist    = ($db->GetOne("SELECT id FROM `v1` WHERE `name` = '{$file}'"));

        var_dump($file);
        if (!$exist) {
//            var_dump();
//            var_dump($file);
            $result = $google->getImageData($filePath);
            $myfile = fopen($directory_v1 . str_replace('.jpg', '.txt', $file), "w") or die("Unable to open file!");
            $db->Execute("INSERT INTO  `v1`  (`name`, `path`) VALUES ('{$file}','{$filePath}')");

            fwrite($myfile, $result);
            fclose($myfile);
        }


    }
    //do your work here
}



$directory_v1 = __DIR__ . '/images/v1/files/';
$files        = scandir(__DIR__ . '/images/v1/');
$google       = new GoogleConnection();
foreach ($files as $file) {
    if ($file != '.' && $file != '..' && $file != 'files') {
        $filePath = __DIR__ . '/images/v1/' . $file;

//            var_dump();
        if (!file_exists($directory_v1 . str_replace('.jpg', '.txt', $file))) {

            $result = $google->getImageData($filePath);
            $myfile = fopen($directory_v1 . str_replace('.jpg', '.txt', $file), "w") or die("Unable to open file!");

            fwrite($myfile, $result);
            fclose($myfile);
        }


    }
    //do your work here
}
die;
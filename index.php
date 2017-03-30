<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Database.php';
require __DIR__ . '/GoogleConnection.php';


$db = new Database('localhost', 'root', '', 'xyxle_image');
for ($i = 1; $i <= 12; $i++) {
    $file_1 = 'product_' . '2' . '_1.jpg';
    $file_2 = 'product_' . '2' . '_2.jpg';


    $file_1_path = 'product_' . '2' . '_2.txt';
    $data        = file_get_contents(__DIR__ . '/images/v1/files/' . $file_1_path);
    $obj_1       = unserialize($data);


    $data  = file_get_contents(__DIR__ . '/images/v1/document/' . $file_1_path);
    $obj_2 = unserialize($data);

//    var_dump($obj_1->info()['textAnnotations']);die;
    var_dump($obj_2->info()['fullTextAnnotation']['pages'][0]['blocks']);die;
    var_dump($obj_2->info()['fullTextAnnotation']['pages'][0]['blocks'][5]['paragraphs']);
    var_dump($obj_2->info()['fullTextAnnotation']['pages'][0]['blocks'][6]['paragraphs']);die;
//    var_dump($obj_2);
    foreach ($obj_2->info()['fullTextAnnotation']['pages'][0]['blocks'] as $key => $row) {

        foreach ($row['paragraphs'] as $k => $r) {
            var_dump($k);
            var_dump($r);
        }

        die;
    }
//
//    foreach ($obj_2->info() as $key => $row) {
//        var_dump($key);
//        var_dump($row);
//    }


//    var_dump($obj_1);
    die;


}



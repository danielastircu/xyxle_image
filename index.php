<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Database.php';
require __DIR__ . '/GoogleConnection.php';
require __DIR__ . '/ExtractData.php';


$db        = new Database('localhost', 'root', '', 'xyxle_image');
$extractor = new ExtractData();

function kj($obj_data)
{
    $data = [];
    preg_match_all('/\d+[\s]?k([\s]|j|v)/', strtolower($obj_data['text']), $matches);
    if ($matches) {
        foreach ($matches[0] as $key => $match) {
            preg_match_all('/\d+/', $match, $value);
            if ($key > 0) {
//                this is for products that have for more than 100g information
//                and it is consider that the first value is for 100g
                continue;
            }
            $data['100'] = $value[0][0];
        }
    }

    return $data;
}

function kcal($obj_data)
{
    $data = [];
    preg_match_all('/\d+[\s]?kcal/', strtolower($obj_data['text']), $matches);
    if ($matches) {
        foreach ($matches[0] as $key => $match) {
            preg_match_all('/\d+/', $match, $value);
            if ($key > 0) {
//                this is for products that have for more than 100g information
//                and it is consider that the first value is for 100g
                continue;
            }
            $data['100'] = $value[0][0];
        }
    }

    return $data;
}

$products = [];
for ($i = 1; $i <= 12; $i++) {

    var_dump("---------------------------------");
    var_dump($i);

    $file_1_path = 'product_' . $i . '_2.txt';
    $data        = file_get_contents(__DIR__ . '/images/v1/document/' . $file_1_path);
    $obj_2       = unserialize($data);


    $obj_data['text']   = $extractor->getFullText($obj_2);
    $obj_data['words']  = $extractor->getWords($obj_2);
    $obj_data['blocks'] = $extractor->getBlocks($obj_2);


//    $products[$i]['kj']   = kj($obj_data);
//    $products[$i]['kcal'] = kcal($obj_data);


    if ($start = strpos(strtolower($obj_data['text']), 'zutaten')) {

        $end = strpos(strtolower($obj_data['text']), '.', $start);
//            case 0.5 (number)
        while (true) {
            if (!(is_numeric($obj_data['text'][$end - 1]) and is_numeric($obj_data['text'][$end + 1]))) {
                break;
            }
            $end = strpos(strtolower($obj_data['text']), '.', $end + 1);
        }
        $length          = $end - $start;
        $products[$i][0] = substr($obj_data['text'], $start, $length);
    }


    foreach ($obj_data['blocks'] as $keyBlock => $block) {
        foreach ($block as $keyParagraph => $paragraph) {
            if (strpos(strtolower($paragraph), 'zutaten')) {
                $products[$i][1] = $paragraph;

            }
//            $products[$i]['ingredients']['german'] =
//            var_dump($paragraph);
        }
    }

//    die;

}
//foreach ($products as $r)
//echo($products[10][0]);
//die;

var_dump($products);
die;
die;






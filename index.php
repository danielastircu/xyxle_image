<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Database.php';
require __DIR__ . '/GoogleConnection.php';
require __DIR__ . '/ExtractData.php';

function levenshtein_php($str1, $str2)
{
    $length1 = mb_strlen($str1, 'UTF-8');
    $length2 = mb_strlen($str2, 'UTF-8');
    if ($length1 < $length2) return levenshtein_php($str2, $str1);
    if ($length1 == 0) return $length2;
    if ($str1 === $str2) return 0;
    $prevRow    = range(0, $length2);
    $currentRow = [];
    for ($i = 0; $i < $length1; $i++) {
        $currentRow    = [];
        $currentRow[0] = $i + 1;
        $c1            = mb_substr($str1, $i, 1, 'UTF-8');
        for ($j = 0; $j < $length2; $j++) {
            $c2            = mb_substr($str2, $j, 1, 'UTF-8');
            $insertions    = $prevRow[$j + 1] + 1;
            $deletions     = $currentRow[$j] + 1;
            $substitutions = $prevRow[$j] + (($c1 != $c2) ? 1 : 0);
            $currentRow[]  = min($insertions, $deletions, $substitutions);
        }
        $prevRow = $currentRow;
    }

    return $prevRow[$length2];
}

$google = New GoogleConnection();
$result = $google->getImageData(__DIR__ . "/cropper/crop.jpg");
$myfile = fopen(__DIR__ . '/object.txt', 'w') or die("Unable to open file!");
var_dump(unserialize($result));
fwrite($myfile, $result);
fclose($myfile);


die;

similar_text('butter', 'buWer',$percent);
var_dump($percent);
similar_text('butter', 'peanutbutter',$percent);
var_dump($percent);
similar_text('butter', 'butt',$percent);
var_dump($percent);

similar_text('kj', 'kv',$percent);
var_dump($percent);
similar_text('kj', 'ki',$percent);
var_dump($percent);
similar_text('kj', 'kv',$percent);
var_dump($percent);
similar_text('kj', 'kjla',$percent);
var_dump($percent);
//die;


var_dump("-----------------------------------------------------------------------------");


var_dump(levenshtein('notre', 'votre'));
 var_dump(levenshtein('notre', 'nôtre'));

similar_text('notre', 'nôtre',$percent);
var_dump($percent);
similar_text('notre', 'votre',$percent);
var_dump($percent);


var_dump(levenshtein('kj', 'kv'));
var_dump(levenshtein('kj', 'ki'));
var_dump(levenshtein('kj', 'kv'));
var_dump(levenshtein('kj', 'kjla'));

var_dump("-----------------------------------------------------------------------------");


var_dump(levenshtein_php('kj', 'kv'));
var_dump(levenshtein_php('kj', 'ki'));
var_dump(levenshtein_php('kj', 'kv'));
var_dump(levenshtein_php('kj', 'kjla'));
var_dump(levenshtein_php('notre', 'nôtre'));
var_dump(levenshtein_php('notre', 'votre'));
die;

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






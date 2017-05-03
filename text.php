<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Database.php';
require __DIR__ . '/GoogleConnection.php';
require __DIR__ . '/ExtractData.php';

$extractor = new ExtractData();


$file_1_path = 'product_10_2.txt';
$data        = file_get_contents(__DIR__ . '/images/v1/document/' . $file_1_path);
$obj_2       = unserialize($data);

//var_dump($extractor->getBlocks($obj_2));die;

$blocksData = $extractor->getBlocksAsWords($obj_2);

$blocks = [];

//TODO DELETE
//unset($blocksData[0]);

$blocksData = $blocksData[6];
var_dump($blocksData);die;
foreach ($blocksData as $blockKey => $paragraphs) {
//    var_dump($paragraphs);die;
//    unset($paragraphs[0]);
    $newParagraph = [];
    foreach ($paragraphs as $keyParagraph => $paragraph) {
        $columns        = [];
        $columnsSpecial = [];
        $column         = 0;
        if (count($paragraph) > 1) {
            $columns[$column][]        = $paragraph[0]['word'];
            $columnsSpecial[$column][] = $paragraph[0];
            for ($i = 0; $i < count($paragraph) - 1; $i++) {
                $foundColumn = false;
                if ($column > 0) {
                    $element         = $columnsSpecial[0][0];
                    $SpecialDistance = abs($element['coordinateX']['minX'] - $paragraph[$i + 1]['coordinateX']['minX']);
                    if ($SpecialDistance < 10) {
                        $column      = 0;
                        $foundColumn = true;
                    }
                }

                if (!$foundColumn) {
                    $distance = abs($paragraph[$i]['coordinateX']['maxX'] - $paragraph[$i + 1]['coordinateX']['minX']);
                    if ($distance > 50) {
                        $column += 1;
                    }
                }

                $columns[$column][]        = $paragraph[$i + 1]['word'];
                $columnsSpecial[$column][] = $paragraph[$i + 1];
            }


        } else {
            $columns = $paragraph[0]['word'];
        }
//        var_dump($columns);
//        die;
        $newParagraph[] = $columns;
        $columns        = [];
//        var_dump($row);
    }
    $blocks[] = $newParagraph;

//    die;
//
}
var_dump($blocks);die;
//var_dump($blocks[6]);die;

new DOMDocument();
//var_dump($extractor->getBlocksAsWords($obj_2)[1]);



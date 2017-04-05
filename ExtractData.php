<?php


class ExtractData
{

    public function getFullText($obj)
    {
        return $obj->fullText()->info()['text'];
    }

    /**
     * @param $obj
     *
     * @return array -> all words from the picture
     */
    public function getWords($obj)
    {
        $data = [];
        foreach ($obj->info()['textAnnotations'] as $key => $row) {
            if ($key == 0) {
                continue;
            }
            $data[] = $row['description'];
        }

        return $data;
    }

    /**
     * @param $obj
     *
     * @return array -> $data[block_number][paragraph_number] -> string
     */
    public function getBlocks($obj)
    {
        $blocks   = $obj->info()['fullTextAnnotation']['pages'][0]['blocks'];
        $newBlock = [];
        foreach ($blocks as $blockKey => $block) {
            $paragraphs   = $block['paragraphs'];
            $newParagraph = [];
            foreach ($paragraphs as $paragraphKey => $paragraph) {
                $words  = $paragraph['words'];
                $string = "";
                foreach ($words as $workKey => $word) {
                    $symbols = $word['symbols'];
                    foreach ($symbols as $keySymbol => $symbol) {
                        $string .= $symbol['text'];
                    }
                    $string .= ' ';
                }
                $newParagraph[] = $string;
            }
            $newBlock[] = $newParagraph;
        }

        return $newBlock;
    }

    /**
     * @param $obj
     *
     * @return array
     */
    public function getWordAndLanguage($obj)
    {
        $blocks = $obj->info()['fullTextAnnotation']['pages'][0]['blocks'];
        $data   = [];
        foreach ($blocks as $blockKey => $block) {
            $paragraphs = $block['paragraphs'];
            foreach ($paragraphs as $paragraphKey => $paragraph) {
                $words = $paragraph['words'];
                foreach ($words as $workKey => $word) {
                    $string  = "";
                    $newWord = [];
                    $symbols = $word['symbols'];
                    foreach ($symbols as $keySymbol => $symbol) {
                        $string .= $symbol['text'];
                    }
                    $newWord['word']     = $string;
                    $newWord['language'] = $word['property']['detectedLanguages'];

                    $data[] = $newWord;
                }
            }
        }

        return $data;
    }

    public function getBlocksLanguage($obj)
    {
        $blocks   = $obj->info()['fullTextAnnotation']['pages'][0]['blocks'];
        $newBlock = [];
        foreach ($blocks as $blockKey => $block) {
            $paragraphs   = $block['paragraphs'];
            $newParagraph = [];
            foreach ($paragraphs as $paragraphKey => $paragraph) {
                $words   = $paragraph['words'];
                $string  = "";
                $newData = [];
                foreach ($words as $workKey => $word) {
                    $symbols = $word['symbols'];
                    foreach ($symbols as $keySymbol => $symbol) {
                        $string .= $symbol['text'];
                    }
                    $string .= ' ';
                }
                $newData['language']  = $paragraph['property']['detectedLanguages'];
                $newData['paragraph'] = $string;
                $newParagraph[]       = $newData;
            }
            $newBlock[] = $newParagraph;
        }

        return $newBlock;
    }
}
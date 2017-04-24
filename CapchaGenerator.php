<?php

/**
 * Created by PhpStorm.
 * User: maksim
 * Date: 23.04.17
 * Time: 10:25
 */
class CapchaGenerator
{
    private $width;
    private $height;
    private $textSize;
    private $fontSize;
    private $fontPath;
    private $savePath;

    /**
     * CapchaGenerator constructor.
     * @param $count
     * @param $width
     * @param $height
     * @param $textSize
     * @param $fontSize
     * @param $fontPath
     * @param $savePath
     */
    public function __construct( $width, $height, $textSize, $fontSize, $fontPath, $savePath)
    {
        $this->width = $width;
        $this->height = $height;
        $this->textSize = $textSize;
        $this->fontSize = $fontSize;
        $this->fontPath = $fontPath;
        $this->savePath = $savePath;
    }

    public function generate($count){
        $results = array();
        for($i=0;$i<$count;$i++){
            $text = $this->generateRandomString($this->textSize);
            $creator = new GDCapchaMaker($text,$this->width,$this->height,$this->fontSize,$this->fontPath,10,10);
            $creator->create($this->savePath.'/'.$i.'.jpg');
            $results[] = array('path'=>$this->savePath.'/'.$i.'.jpg','solution'=>$text);
        }
        return $results;
    }
    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


}
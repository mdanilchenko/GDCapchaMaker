<?php

/**
 * Created by PhpStorm.
 * User: maksim
 * Date: 23.04.17
 * Time: 7:51
 */
class GDCapchaMaker
{
    private $text;
    private $ttfPath = "";
    private $xMaxShift = 0;
    private $yMaxShift = 0;
    private $width = 130;
    private $height = 80;
    private $fontSize = 12;
    private $linesCount = 4;

    private $xCoef = 0;
    private $yCoef = 0;

    /**
     * GDCapchaMaker constructor.
     * @param $text
     * @param null $xFuncition
     * @param null $yFunction
     * @param string $ttfPath
     * @param int $xMaxShift
     * @param int $yMaxShift
     */
    private function xFunction($a){
        $sign = rand(0,1);
        if($sign===0){
            $sign=-1;
        }
        if($this->xCoef===0){
            $this->xCoef = $sign*rand(5,12);
        }
        return cos($a*$this->xCoef);
    }
    private function yFunction($a){
        $sign = rand(0,1);
        if($sign===0){
            $sign=-1;
        }
        if($this->yCoef===0){
            $this->yCoef = $sign*rand(3,8);
        }
        return sin($a*$this->yCoef);
    }

    public function __construct($text,$width=130,$height=80,$fontSize=24, $ttfPath = "fonts/PT.ttf", $xMaxShift=0, $yMaxShift=0,$linesCount=4)
    {
        $this->text = $text;
        $this->width = $width;
        $this->height = $height;
        $this->ttfPath = $ttfPath;
        $this->xMaxShift = $xMaxShift;
        $this->yMaxShift = $yMaxShift;
        $this->fontSize = $fontSize;
        $this->linesCount = $linesCount;
    }
    public function create($path){
        $image = $this->getTemplateImage();
        if($this->linesCount>0){
            $clear = $this->addLines();
        }else{
            $clear = $this->getClearImage();
        }
        $output = $this->uglifyImage($clear,$image);


        imagejpeg($output, $path);

        imagedestroy($output);
        imagedestroy($image);
        imagedestroy($clear);
    }
    private function addLines(){
        $image = imagecreatetruecolor( $this->width , $this->height );
        $grey = imagecolorallocate ( $image , 100 , 100 , 100 );
        $white = imagecolorallocate ( $image , 255 , 255 , 255 );
        imagefill($image,0,0,$white);
        for($i=0;$i<$this->linesCount;$i++){
            $this->imagelinethick($image, 0, rand(0,$this->height),$this->width,rand(0,$this->height),$grey, rand(2,3));
        }
        return $image;
    }
    private function getTemplateImage(){
        $image = imagecreatetruecolor( $this->width , $this->height );
        imagealphablending($image, false);
        $transparency = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $transparency);
        imagesavealpha($image, true);
        //$white = imagecolorallocate ( $image , 255 , 255 , 255 );
        //imagefill($image,0,0,$white);
        $black = imagecolorallocate ( $image , 0 , 0 , 0 );
        $fontHeight = $this->fontSize;

        $textbox = imagettfbbox ( $fontHeight , 0 , $this->ttfPath , $this->text );
        $txtWidth = abs($textbox[4] - $textbox[0]) ;
        $txtHeight = abs($textbox[5] - $textbox[1]);
        imagettftext($image, $fontHeight, 0, round(($this->width-$txtWidth)*0.5), round(($this->height-$txtHeight)*0.5+$txtHeight*0.75), $black, $this->ttfPath, $this->text);
        return $image;
    }
    private function getClearImage(){
        $image = imagecreatetruecolor( $this->width , $this->height );
        $white = imagecolorallocate ( $image , 255 , 255 , 255 );
        imagefill($image,0,0,$white);
        return $image;
    }
    private function uglifyImage($template,$crp){
        $template_cp= imagecreatetruecolor($this->width, $this->height);
        imagealphablending($template_cp, false);
        $transparency = imagecolorallocatealpha($template_cp, 0, 0, 0, 127);
        imagefill($template_cp, 0, 0, $transparency);
        imagesavealpha($template_cp, true);
        imagecopy($template_cp, $template, 0, 0, 0, 0, $this->width, $this->height);

        if(($this->xMaxShift>0)){
            for($i=0;$i<$this->height;$i++){
                $pers = round($i/$this->height,3);
                $diff = round($this->xMaxShift*$this->xFunction($pers));
                imagecopyresampled($template, $crp, $diff, $i, 0, $i, $this->width, 1, $this->width, 1);
            }
        }
        if(($this->yMaxShift>0)){
            for($i=0;$i<$this->width;$i++){
                $pers = round($i/$this->width,3);
                $diff = round($this->yMaxShift*$this->yFunction($pers));
                imagecopyresampled($template_cp, $template,  $i,$diff, $i, 0, 1, $this->height,  1,$this->height);
            }
        }
        return $template_cp;
    }
    private function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1)
    {
        if ($thick == 1) {
            return imageline($image, $x1, $y1, $x2, $y2, $color);
        }
        $t = $thick / 2 - 0.5;
        if ($x1 == $x2 || $y1 == $y2) {
            return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
        }
        $k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
        $a = $t / sqrt(1 + pow($k, 2));
        $points = array(
            round($x1 - (1+$k)*$a), round($y1 + (1-$k)*$a),
            round($x1 - (1-$k)*$a), round($y1 - (1+$k)*$a),
            round($x2 + (1+$k)*$a), round($y2 - (1-$k)*$a),
            round($x2 + (1-$k)*$a), round($y2 + (1+$k)*$a),
        );
        imagefilledpolygon($image, $points, 4, $color);
        return imagepolygon($image, $points, 4, $color);
    }

}
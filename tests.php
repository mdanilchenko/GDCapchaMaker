<?php
/**
 * Created by PhpStorm.
 * User: maksim
 * Date: 23.04.17
 * Time: 8:40
 */
ini_set("display_errors","on");

require 'GDCapchaMaker.php';
require 'CapchaGenerator.php';

$generator = new CapchaGenerator(200,100,8,24,"fonts/PT.ttf",'output');
$capchas = $generator->generate(30);
print_r($capchas);
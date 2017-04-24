# GDCapchaMaker (PHP and GD)
Easy to use PHP script for random capcha creation. Based on GD liprary.

## Funcitonality

### GDCapchaMaker Class
Creates singlre capcha image based on settings (constructor params):
```
text:String  - text to be placed on image
width:Integer - image wight (pixels)
height:Integer - image height (pixels)
fontSize:Integer - font size
ttfPath:String - path to font file (.ttf)
xMaxShift:Integer - maximal x-axis shifting when uglifying image
yMaxShift:Integer - maximal y-axis shifting when uglifying image
linesCount:Integer - number of random lines drawn in background
```

Methods:
```
private xFunction(float[0:1] $percent) - defines x-axis shifting. Should return values in [-1:1] (always return 0 to disable x-shifting)
private yFunction(float[0:1] $percent) - defines y-axis shifting. Should return values in [-1:1] (always return 0 to disable y-shifting)
public create(String $path) - creates capcha-image using settings passed in constructor and saves as file on specified $path
private addLines() - adds linse on capcha background
private getTemplateImage() - returns image resource with plane text writen
private getClearImage() - returns background image resource
private uglifyImage() - makes shiftingn for template image and comines it with background
private imagelinethick(...) - raws line on image with selected thick
```

**Usage Example:**
Creates 200x100 image with "Hello" text, font size: 21px, font: PT.ttf, 10px of maximal shifting on both axis and 3 lines on background. 
Result saves to file output/1.jpg
```
$creator = new GDCapchaMaker("Hello",200,100,21,'fonts/PT.ttf',10,10,3);
$creator->create('output/1.jpg');
```
### CapchaGenerator Class
Simple class for fast random capcha generation. Creates as many images as you wish.

**Options**
```
 width:Integer - images widt
 height:Integer - images height
 textSize:Integer - number of letters on capcha image
 fontSize: Integer - font size in px
 fontPath:String - path to ttf-file
 savePath:String - save folder path (Example: "/home/user/output")
```

Methods:
```
public generate(Integer $count) - generates amount of images specified with $count and returns array of elements? containing information about generated capchas:
Returns: Array of {
    path=>Path to image
    solution=>Text written on image
}

private generateRandomString(Integer $length) - generates random string selected length
```

**Usage Example**
Creates 30 200x100 images with random 8-chars text, font size: 24px, font: PT.ttf and stores in 'output' folder (shifting and background lines added by default configurations of GDCapchaMaker class). 
Result saves to file output/1.jpg
```
$generator = new CapchaGenerator(200,100,8,24,"fonts/PT.ttf",'output');
$capchas = $generator->generate(30);
```

## License
Feel free to use and modify this code. 
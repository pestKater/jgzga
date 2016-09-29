<?php 
    define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include('../common.php');
require_once('php_image_magician.php');

$pictures = array();
$sql = "SELECT id, name FROM phpbb_gallery";
$result = $db->sql_query($sql);

while($row = $db->sql_fetchrow($result)) {
    $pictures[$row['id']]['id'] = $row['id'];
    $pictures[$row['id']]['name'] = $row['name'];
}

$picture = array_rand($pictures);

$pictureId = $pictures[$picture]['id'];
$pictureName = $pictures[$picture]['name'];

if(strlen($pictureName) >= 20) {
    $pictureName = substr($pictureName, 0, 15) . '..';
}

$path = '/var/www/html/forum/images/gallery/';

$im = new imageLib($path . $pictureId . '.jpg');
$height = $im->getOriginalHeight();
$width  = $im->getOriginalWidth();

if($height > $width) {
    $class = 'portrait';
} else {
    $class = 'landscape';
}

?>

<html>
    <head>
            <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
            <link rel="stylesheet" type="text/css" href="style.css">
            <title>Zufallsbild</title>
    </head>
    <body>

    <div class="widget-randompicture">
        <div>
            <a target="_parent" href="../gallery.php?view=image&id=<?php echo $pictureId; ?>">
                <img class="<?php echo $class; ?>" src="../images/gallery/<?php echo $pictureId; ?>.jpg">
            </a>
        </div>
        <div>
            <p><?php echo $pictureName; ?></p>
        </div>
    </div>

    </body>
</html>

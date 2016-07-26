<?php 
    define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include('../common.php');
require_once('php_image_magician.php');

$sql = "SELECT id FROM phpbb_gallery ORDER BY id DESC LIMIT 1";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);

$maxId = $row['id'];

$sql = "SELECT id FROM phpbb_gallery ORDER BY id ASC LIMIT 1";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);

$minId = $row['id'];

$random = mt_rand($minId, $maxId);

$pictureId = false;
while($pictureId == false) {
    $sql = "SELECT id, name FROM phpbb_gallery WHERE id = " . $random;
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    
    $pictureId = $row['id'];
    $pictureName = $row['name'];
}

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

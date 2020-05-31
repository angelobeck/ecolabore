<?php

class eclFilter_modImg
{ // class eclFilter_modImg

static function create ($fieldName, $control, $formulary)
{ // function create
$local['name'] = $fieldName;
$local['type'] = 'file';

// help
if (isset ($control['flags']['help']) and !isset ($control['flags']['help_msg']))
$local['help_msg'] = 'system_msg_filterImgHelp';

$formulary->appendChild ($control, $local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $io, $store;
$flags = $formulary->flags;
$received = $formulary->received;

if ($formulary->errorMsg !== false)
return;

if (!$io->request->uploaded)
{ // error
if (isset ($control['flags']['required']))
return $formulary->setRequiredMsg ($control, $fieldName, 'system_msg_filterImgNotUploaded');
return false;
} // error

$uploaded = reset ($io->request->uploaded);
$uploaded = $uploaded[0];

if (!isset ($uploaded['type']))
{ // error
if (isset ($control['flags']['required']))
return $formulary->setRequiredMsg ($control, $fieldName, 'system_msg_filterImgNotUploaded');
return false;
} // error

isset ($flags['img_size']) or $flags['img_size'] = 3840;
isset ($flags['img_mini_size']) or $flags['img_mini_size'] = 256;
isset ($flags['img_interlace']) ? $flags['img_interlace'] = true : $flags['img_interlace'] = false;

$uploaded = $uploaded['tmp_name'];

if (@getimagesize ($uploaded) === false)
{ // error
if (isset ($control['flags']['error']))
return $formulary->setErrorMsg ($control, $fieldName, 'system_msg_filterImgNotUploaded');
return false;
} // error

list ($width_orig, $height_orig, $type) = getimagesize ($uploaded);

$ratio_orig = $width_orig / $height_orig;

// Calculate big image size
if ($width_orig > $flags['img_size'] or $height_orig > $flags['img_size'])
{ // resize big image
$img_width = $flags['img_size'];
$img_height = $flags['img_size'];
if ($img_width / $img_height > $ratio_orig)
$img_width = $img_height * $ratio_orig;
else
$img_height = $img_width / $ratio_orig;
} // resize big image
else
{ // keep image size
$img_width = $width_orig;
$img_height = $height_orig;
} // keep image size
settype ($img_width, 'int');
settype ($img_height, 'int');

// Calculate miniature size
if ($width_orig > $flags['img_mini_size'] or $height_orig > $flags['img_mini_size'])
{ // resize miniature
$mini_width = $flags['img_mini_size'];
$mini_height = $flags['img_mini_size'];
if ($mini_width / $mini_height > $ratio_orig)
$mini_width = $mini_height * $ratio_orig;
else
$mini_height = $mini_width / $ratio_orig;
} // resize miniature
else
{ // keep image size
$mini_width = $width_orig;
$mini_height = $height_orig;
} // keep image size
settype ($mini_width, 'int');
settype ($mini_height, 'int');

// Suported formats are
if ($type == 1)
$ext = '.gif';
elseif ($type == 2)
$ext = '.jpg';
elseif ($type == 3)
$ext = '.png';
else
return 'system_msg_alertImgWrongFormat';

// Opens received file
if ($type == 1)
$image_orig = @imagecreatefromgif ($uploaded);
elseif ($type == 2)
$image_orig = @imagecreatefromjpeg ($uploaded);
elseif ($type == 3)
$image_orig = @imagecreatefrompng ($uploaded);

if (!$image_orig)
return 'system_msg_alertImageError';

if (isset ($flags['target']))
$name = $formulary->application->data['name'] . CHR_FNS . $flags['target'];
else
$name = $formulary->data['name'] . CHR_FNS . 'img_0';

$filename = PATH_DOMAINS . $formulary->document->domain->name . '/' . $name;

// Resample big image
$image_big = imagecreatetruecolor ($img_width, $img_height);
imagecopyresampled ($image_big, $image_orig, 0, 0, 0, 0, $img_width, $img_height, $width_orig, $height_orig);

// save big image
if ($type == 1)
$saved = imagegif ($image_big, $filename . $ext);
elseif ($type == 2)
$saved = imagejpeg ($image_big, $filename . $ext, 100);
elseif ($type == 3)
{ // png file
$saved = move_uploaded_file ($uploaded['tmp_name'], $filename . $ext);
$saved = true;
// $saved = imagepng ($image_big, $filename . $ext);
} // png file
if (!$saved)
return 'system_msg_alertImgError';

// Resample miniature
$image_mini = imagecreatetruecolor ($mini_width, $mini_height);
imagecopyresampled ($image_mini, $image_orig, 0, 0, 0, 0, $mini_width, $mini_height, $width_orig, $height_orig);

// save miniature
if ($type == 1)
$saved = imagegif ($image_mini, $filename . CHR_FNS . 'mini.gif');
elseif ($type == 2)
$saved = imagejpeg ($image_mini, $filename . CHR_FNS . 'mini.jpg', 100);
elseif ($type == 3)
{ // png
$saved = true;
} // png

if (!$saved)
return 'system_msg_alertImgError';

// save image data
if (isset ($flags['target']))
{ // section image
$data = &$formulary->data;
} // section image
else
{ // find target
if (!isset ($formulary->data['extras']))
$formulary->data['extras'] = [];
if (!isset ($formulary->data['extras']['img_0']))
$formulary->data['extras']['img_0'] = [];
$data = &$formulary->data['extras']['img_0'];
} // find target

$data['files']['img'] = $name . $ext;

$data['img_width'] = $img_width;
$data['img_height'] = $img_height;
if ($type == 3)
{ // png hack
$data['files']['img_mini'] = $name . $ext;
$data['img_mini_width'] = $img_width;
$data['img_mini_height'] = $img_height;
} // png hack
else
{ // normal
$data['files']['img_mini'] = $name . CHR_FNS . 'mini' . $ext;
$data['img_mini_width'] = $mini_width;
$data['img_mini_height'] = $mini_height;
} // normal
if ($width_orig < $height_orig)
$data['portrait'] = 'portrait';
else
$data['landscape'] = 'landscape';
} // function save

} // class eclFilter_modImg

?>
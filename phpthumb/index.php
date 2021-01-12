<?php

$img_path = isset($_GET['thumb'])?$_GET['thumb']:false;

require_once 'thumb/ThumbLib.inc.php';
/**
 * 包含Uri的解析
 */
require_once THUMBLIB_BASE_PATH . '/Uri.php';
/*
 * 包含配置文件
 */
require_once THUMBLIB_BASE_PATH . '/Config.php';
/*
 * 包含参数验证文件
 */
require_once THUMBLIB_BASE_PATH . '/CheckParam.php';

$uri = new Thumb\Uri($img_path);

$checkParam = new Thumb\CheckParam();
$load_img = $uri->getImg();

$load_img = file_exists($load_img)?$load_img:\Thumb\Config::defaultImg;
$thumb = PhpThumbFactory::create($load_img);
$CurrentDimensions = $thumb->getCurrentDimensions();

//截图，前两个参数分别是需要解出的图片的右上角的坐标X,Y。 后面两个参数是需要解出的图片宽，高。
isset($uri->uri->crop) and 
$thumb->crop($uri->uri->crop[0], $uri->uri->crop[1], $uri->uri->crop[2], $uri->uri->crop[3]);


//把图片等比缩小到最大宽度 100px或者最高100px，当只输入一个参数的时候，是限制最宽的尺寸。
isset($uri->uri->size) and $checkParam->resize($uri->uri->size) and $thumb->resize($uri->uri->size['width'], $uri->uri->size['height']);

//把图片等比缩小到原来的百分数，比如50就是原来的50%。
isset($uri->uri->p) and $checkParam->resizePercent($uri->uri->p)  and $thumb->resizePercent($uri->uri->p);

//截取一个175px * 175px的图片，注意这个是截取，超出的部分直接裁切掉，不是强制改变尺寸。
isset($uri->uri->a) and $checkParam->adaptiveResize($uri->uri->a)  and $thumb->adaptiveResize($uri->uri->a['width'], $uri->uri->a['height']);


//从图片的中心计算，截取200px * 100px的图片。
isset($uri->uri->cc) and $checkParam->cropFromCenter($uri->uri->cc)  and $thumb->cropFromCenter($uri->uri->cc['width'], $uri->uri->cc['height']);

//把图片顺时针反转180度
isset($uri->uri->r) and $checkParam->rotateImageNDegrees($uri->uri->r)  and $thumb->rotateImageNDegrees($uri->uri->r);

//保存（生成）图片,你可以保存其他格式，详细参考文档
($img_path != \Thumb\Config::defaultImg) and $thumb->save($img_path);

/**
 * 所有选项
 * $thumb->getOptions();
 */
/**
 * 获得当前尺寸
 * $thumb->getCurrentDimensions();
 */
//var_dump($thumb->getPercent());
//($img_path != $config['default'])?header('Location: '.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']):$thumb->show();
$thumb->show();
<?php
header('content-Type: text/html; charset=utf-8');
include_once('../web_include/init.php');
$lang = 'cn';
// error_reporting(7);
//************************
$cache_url=$_SERVER['REQUEST_URI'];

if (!$smarty->isCached($lang.'/circuit_detail.html', $cache_url)){  
	
	/*************************************基本信息开始***********************************/
	$menu_info1 = array(
		'class_title_cn' => $web_config['Web_SiteTitle_cn'],
		'class_keywords_cn' => $web_config['Web_Keywords_cn'],
		'class_description_cn' => $web_config['Web_Description_cn']
	);  
	$smarty->assign('menu_info1',$menu_info1); 
	$smarty->assign('COOKIE',$_COOKIE);
	$class_id = addslashes(trim($_GET['cid']));
	// $cid=($class_id=='')?get_default_menu_id('3'):get_default_menu_id($class_id);
	$cid=($class_id=='')?'1':$class_id;
	$smarty->assign('first_cid',$cid);   
	$id = addslashes(trim($_GET['id']));
	$smarty->assign('id',$id);
	if(empty($id)){echo "<script> alert('请选择一个比赛');window.history.back();</script>";}
	//公共部分
	include('./common.php');	
	//对于优化 SELECT * FROM  不要全部查询，显示什么，就查什么
	
	//banner图片
	$sql = "select title_cn,subtitle_cn,img_url_cn from `".$db_pre."pageinfo` WHERE class_id = 3";
	$flash = $db->getone($sql);
	$smarty->assign('flash',$flash);
	
	//路线详情
	$luxian=file_get_contents('http://www.wintrax.cc/app/index.php/Pc/query_list?id='.$id);
	$luxian=json_decode($luxian,true);
	$smarty->assign('luxian',$luxian[0]);
	
	//联系
	$sql = "select title_cn,subtitle_cn,img_url_cn from `".$db_pre."pageinfo` WHERE class_id = 11";
	$sql.=" order by add_time desc";
	$lianxi = $db->getone($sql);
	$smarty->assign('lianxi',$lianxi);
	
}
//$smarty->assign('in_index','turein');
//************************
$smarty->display($lang.'/circuit_detail.html');
?>
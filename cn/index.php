<?php
header('content-Type: text/html; charset=utf-8');
include_once('../web_include/init.php');
$lang = 'cn';
// error_reporting(7);
//************************
$cache_url=$_SERVER['REQUEST_URI'];

if (!$smarty->isCached($lang.'/index.html', $cache_url)){  
	
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
	
	//公共部分
	include('./common.php');	
	//对于优化 SELECT * FROM  不要全部查询，显示什么，就查什么
	
	//banner图片
   	$sql = "select title_cn,subtitle_cn,img_url_cn from `".$db_pre."pageinfo` WHERE class_id = 1";
	$flash = $db->getone($sql);
	$smarty->assign('flash',$flash);
	
	//公司理念
     $sql = "select title_cn,img_url_cn from `".$db_pre."pageinfo` WHERE class_id = 2 order by add_time desc limit 3";
     $linian = $db->getall($sql);
     $smarty->assign('linian',$linian);
     
     //比赛线路8条
     $luxian=file_get_contents('http://www.wintrax.cc/app/index.php/Pc/query_list?limit=8');
     $luxian=json_decode($luxian,true);
     $smarty->assign('luxian',$luxian);
     //exit(var_dump($luxian));
     /* 
      * 产品特点
      *  */
     //产品简介
     $sql = "select class_content_cn from `".$db_pre."web_class` WHERE class_id = 4";
     $jianjie = $db->getone($sql);
     $smarty->assign('jianjie',$jianjie);
    //产品特点
     $sql="SELECT title_cn,subtitle_cn,img_url_cn,content_cn FROM `".$db_pre."pageinfo` WHERE  `class_id`=4";
     $sql.=" ORDER BY add_time desc limit 2";
     $tedian = $db->getall($sql);
     $smarty->assign('tedian',$tedian);
     //产品指南
     $sql = "select class_id,class_name_cn from `".$db_pre."web_class` WHERE class_parent_id = 5 ORDER BY class_order_id asc";
     $left_menu = $db->getall($sql);
     $smarty->assign('left_menu',$left_menu);
     if($left_menu){
         foreach($left_menu as $k=>$v){
             $sql="SELECT title_cn,subtitle_cn,img_url_cn,content_cn FROM `".$db_pre."pageinfo` WHERE  `class_id`=".$v['class_id']." ORDER BY add_time desc";
             $zhinan[] = $db->getall($sql);
         }
         $smarty->assign('zhinan',$zhinan);
     }
     //常见问题
     $sql = "select title_cn,subtitle_cn from `".$db_pre."pageinfo` WHERE class_id = 10";
     $sql.=" order by add_time desc";
     $wenti = $db->getall($sql);
     $smarty->assign('wenti',$wenti);
     
     
     //联系
     $sql = "select title_cn,subtitle_cn,img_url_cn from `".$db_pre."pageinfo` WHERE class_id = 11";
     $sql.=" order by add_time desc";
     $lianxi = $db->getone($sql);
     $smarty->assign('lianxi',$lianxi);
	
    //exit(var_dump($left_menu));
	
}
 //$smarty->assign('in_index','turein');
//************************
$smarty->display($lang.'/index.html');
?>
<?php
header('content-Type: text/html; charset=utf-8');
include_once('../web_include/init.php');
$lang = 'cn';
// error_reporting(7);
//************************
function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
}
	
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
	$cid=($class_id=='')?'3':$class_id;
	$smarty->assign('first_cid',$cid);   
	//公共部分
	include('./common.php');	
	//对于优化 SELECT * FROM  不要全部查询，显示什么，就查什么
	
	//banner图片
	$sql = "select title_cn,subtitle_cn,img_url_cn from `".$db_pre."pageinfo` WHERE class_id = 3";
	$flash = $db->getone($sql);
	$smarty->assign('flash',$flash);
	
	//搜索条件
	$search = trim($_GET['search']);
	$smarty->assign('search',$search);
	
	//计算活动的总条数
	$luxian=file_get_contents('http://www.wintrax.cc/app/index.php/Pc/query_list?contest_name='.urlencode($search));
	$luxian=json_decode($luxian,true);
	$count = count($luxian);
	$smarty->assign('count',$count);
	$page_count=ceil($count/6);//总页数，向上取整
	$smarty->assign('page_count',$page_count);
	
	//路线列表
	$page = $_GET['page'];
	$page = ($page>0)?$page:1;
	$url = basename(__FILE__);
	$url_string = $_SERVER['PHP_SELF'];
	$url= substr( $url_string , strrpos($url_string , '/')+1 );
	$url.= ($search=='')?'':is_query_string($url).'search='.$search;
	$url.= is_query_string($url);
	$smarty->assign('url',$url);
	
	//$luxian_list=file_get_contents('http://www.wintrax.cc/app/index.php/pc/query_list?offset='.$page.'&limit=6&contest_name='.$search);
	$dizhi='http://www.wintrax.cc/app/index.php/pc/query_list?offset='.$page.'&limit=6&contest_name='.urlencode($search);
	$luxian_list=httpGet($dizhi,'');
	$luxian_list=json_decode($luxian_list,true);
	$smarty->assign('luxian_list',$luxian_list);
	$smarty->assign('page',$page);//当前页数
	$page_array = list_page($page,$page_count);
	$smarty->assign("page_array",$page_array);
	
	//exit(var_dump($luxian_list ));
	//联系
	$sql = "select title_cn,subtitle_cn,img_url_cn from `".$db_pre."pageinfo` WHERE class_id = 11";
	$sql.=" order by add_time desc";
	$lianxi = $db->getone($sql);
	$smarty->assign('lianxi',$lianxi);
	
	
//$smarty->assign('in_index','turein');
//************************
$smarty->display($lang.'/circuit.html');
?>
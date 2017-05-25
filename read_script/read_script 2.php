<?php
// 数据来源  http://www.stats.gov.cn/tjsj/tjbz/xzqhdm/201703/t20170310_1471429.html
// 参考文章：https://huoding.com/2012/07/31/163
// 读取地区信息脚本，读取到第二级
$list = file('./地区列表.txt');
$result = array();
$current_province = 0;
$level_up = 0;
foreach ($list as $item) {
	$row = trim(str_replace('　', ' ', $item));
	if (!preg_match('/^(\d+)\s+(.+)$/', $row, $matches)) {
        continue;
    }

    list($row, $id, $name) = $matches;

    $level = strlen(preg_replace('/(00){1,2}$/', '', $id)) / 2;

    // 直辖市特殊处理
    if(in_array($current_province, [110000,120000,310000,500000]) && $level == 3) {
        $level = 2;
    }

    // 跳过市辖区
    if($name == '市辖区') {
        continue;
    }

    // 省和自治区的直辖县处理为level2
    if($name == '省直辖县级行政区划' || $name == '自治区直辖县级行政区划' ) {
        $level_up = 1;
        continue;
    }

    if($level_up && $level == 3) {
        $level = 2;
    }

    if($level == 1) {
        $level_up = 0;
    }

    switch ($level) {
    	case 1:
    		$result[$id] = array(
    			'province' => $name,
    		);
    		$current_province = $id;
    		# code...
    		break;
    	case 2:
    		$result[$current_province]['cities'][$id] = $name;
    		# code...
    		break;
    	default:
    		# code...
    		break;
    }

    $str = "<?php\nreturn ".var_export($result,true).";";
    file_put_contents('./result_city.php',$str);
}
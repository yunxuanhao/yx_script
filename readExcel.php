<?php
/**
 *
 * @copyright 2007-2012 Xiaoqiang.
 * @author Xiaoqiang.Wu <jamblues@gmail.com>
 * @version 1.01
 * @modify XuanYun <yunxuanhao@gmail.com>
 */

date_default_timezone_set('Asia/ShangHai');

/** PHPExcel_IOFactory */
require_once '../libraries/PHPExcel/IOFactory.php';


// Check prerequisites
if (!file_exists("pandian.xlsx")) {
    exit("not found pandian.xlsx.\n");
}

$reader = PHPExcel_IOFactory::createReader('Excel2007'); //设置以Excel5格式(Excel97-2003工作簿)
$PHPExcel = $reader->load("pandian.xlsx"); // 载入excel文件
for($i = 1;$i<12;$i++){
    $sheet = $PHPExcel->getSheet($i-1); // 读取第一個工作表
    $highestRow = $sheet->getHighestRow(); // 取得总行数
    $highestColumm = $sheet->getHighestColumn(); // 取得总列数
    $data = array();
    /** 循环读取每个单元格的数据 */
    for ($row = 2; $row <= $highestRow; $row++){//行数是以第2行开始
        $score = $sheet->getCell('E'.$row)->getValue();
        $user_item = array(
            'uid' => $sheet->getCell('B'.$row)->getValue(),
            'score' => sprintf("%.1f", $score)
        );
        $data[] = $user_item;
    }
    $str = "<?php\n".var_export($data,true)."\n?>";
    file_put_contents('../configs/annual_rank/rank_'.$i.'.php',$str);
}


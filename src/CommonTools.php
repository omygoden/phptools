<?php
namespace Jieyu;
class CommonTools{

    /**
     * 数值转中文
     * @param $num int|float 数值
     * @param int $type 1整数，2浮点数
     * @return string
     */
    public function convertNumberToCh($num, $type = 1)
    {
        // 判断输出的是否是否为数字或数字字符串
        if (!is_numeric($num)) {
            return "要转换的金额只能为数字!";
        }

        // 金额为0,则直接输出"零元整"
        if ($num == 0) {
            return "零元整";
        }

        // 数值不能为负数
        if ($num < 0) {
            return "要转换的金额不能为负数!";
        }

        // 数值不能超过万亿,即12位
        if (strlen($num) > 12) {
            return "要转换的金额不能为万亿及更高金额!";
        }

        // 预定义中文转换的数组
        $digital = array('零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
        // 预定义单位转换的数组
        $position = array('仟', '佰', '拾', '亿', '仟', '佰', '拾', '万', '仟', '佰', '拾', '元');

        // 将金额的数值字符串拆分成数组
        $amountArr = explode('.', $num);

        // 将整数位的数值字符串拆分成数组
        $integerArr = str_split($amountArr[0], 1);

        // 将整数部分替换成大写汉字
        $result = '';
        $integerArrLength = count($integerArr); // 整数位数组的长度
        $positionLength = count($position); // 单位数组的长度
        for ($i = 0; $i < $integerArrLength; $i++) {
            // 如果数值不为0,则正常转换
            if ($integerArr[$i] != 0) {
                $result = $result . $digital[$integerArr[$i]] . $position[$positionLength - $integerArrLength + $i];
            } else {
                // 如果数值为0, 且单位是亿,万,元这三个的时候,则直接显示单位
                if (($positionLength - $integerArrLength + $i + 1) % 4 == 0) {
                    $result = $result . $position[$positionLength - $integerArrLength + $i];
                }
            }
        }

        // 如果小数位也要转换
        if ($type == 0) {
            // 将小数位的数值字符串拆分成数组
            $decimalArr = str_split($amountArr[1], 1);
            // 将角替换成大写汉字. 如果为0,则不替换
            if ($decimalArr[0] != 0) {
                $result = $result . $digital[$decimalArr[0]] . '角';
            }
            // 将分替换成大写汉字. 如果为0,则不替换
            if ($decimalArr[1] != 0) {
                $result = $result . $digital[$decimalArr[1]] . '分';
            }
        }
        return $result;
    }

    /**
     * 判断远程文件是否存在
     * 主要判断文件的头信息里是否有返回200
     * @param $url string
     * @return bool
     */

    public function remote_file_exists($url): bool
    {
        return (bool)preg_match('~HTTP/1\.\d\s+200\s+OK~', @current(get_headers($url)));
    }

    /**
     * 生成随机身份证号
     * @return string
     */
    public function generate_random_id_card(): string
    {
        $area_code = '331002';
        $birth = '19931021';
        $order = rand(100, 999);
        $fake = $area_code . $birth . $order;
        //根据前17位生成校验码
        $map = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        $sum = 0;
        for ($i = 0; $i < 17; $i++) {
            $sum += $map[$i] * $fake[$i];
        }
        $remainder = $sum % 11;
        $final_map = [
            "1", "0", "X", "9", "8", "7", "6", "5", "4", "3", "2"
        ];
        return $fake . $final_map[$remainder];
    }

    public function getIP()
    {
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $realip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $realip = !empty($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : '127.0.0.1';
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else {
                $realip = getenv("REMOTE_ADDR");
            }
        }
        return $realip;
    }
}

<?php

/**
 * @author wenlong.
 * @description
 * @createOn 2017/9/26 0026 14:32
 */

/**for sort**/
class KevinSort
{
    protected $rule = '';
    protected $baseRules = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
        "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"];

    public function __construct($rule)
    {
        $this->rule = $rule;
        $this->getNewRules();
    }

    /**
     * 生成新的排序rule
     * ASC
     * @return array
     * @internal param $rule
     * @rule D<j<8<g<G<r<a<f<W<b<U
     * @result 012345679ABCDEFHIJKLMNOPQRSTVXYZcdehij8gGklmnopqrafWbUstuvwxyz
     */
    public function getNewRules()
    {
        $ruleStrings = explode('<', $this->rule);
        for ($i = 0, $j = count($ruleStrings); $i < $j - 1; $i++) {
            $ruleStringLeft = $ruleStrings[$i];
            $ruleStringRight = $ruleStrings[$i + 1];
            $leftIndex = array_search($ruleStringLeft, $this->baseRules);
            $rightIndex = array_search($ruleStringRight, $this->baseRules);
            if ($leftIndex !== false
                && $rightIndex !== false
                && $leftIndex > $rightIndex
            ) {
                array_splice($this->baseRules, $leftIndex + 1, 0, $ruleStringRight);
                array_splice($this->baseRules, $rightIndex, 1);
            }
        }
        return $this->baseRules;
    }

    /**
     * 根据排序规则排序
     * @param $str
     * @return array
     */
    public function baseSort($str)
    {
        $afterSortArr = [];
        $indexArr = [];
        for ($i = 0, $j = strlen($str); $i < $j; $i++) {
            $indexArr[$i] = (int)array_search($str[$i], $this->baseRules);
        }
        asort($indexArr);
        foreach ($indexArr as $index => $value) {
            $afterSortArr[] = $str[$index];
        }
        return $afterSortArr;
    }

    /**
     * 从排序后的数组生成每行的字符串
     * @param array $afterSortArray
     */
    public static function solveArray(Array &$afterSortArray)
    {
        for ($i = 1 ; $i < count($afterSortArray); $i+=2){
            array_splice($afterSortArray, $i+1, 0 , ' ');
            $i++;
        }
        $afterSortArray = implode('', array_reverse($afterSortArray));

    }

    /**
     * 返回结果
     * @param $sourceString
     * @return array|string
     */
    public function getResult($sourceString)
    {
        $afterSplitByEnters = explode("\n", $sourceString);
        $result = [];
        foreach ($afterSplitByEnters as $afterSplitByEnter) {
            if ($afterSplitByEnter) {
                $afterSplitByEnter = str_replace(" ", "", $afterSplitByEnter);
                // 保存结尾的1 2 3 。。
                $head = $afterSplitByEnter[strlen($afterSplitByEnter) -1 ];
                $afterSort = $this->baseSort(mb_substr($afterSplitByEnter, 0, -1));
                self::solveArray($afterSort);
                $result[] = $head . $afterSort;
            }
        }
        $result = implode("\n", $result);
        return $result;
    }
}


$KevinSort = new KevinSort('D<j<8<g<G<r<a<f<W<b<U');
$needSort = file_get_contents('https://raw.githubusercontent.com/Hell0wor1d/computer-based-test/master/data.txt');
$result = $KevinSort->getResult($needSort);

print_r($result);

/*
 * Result
1 yw bb bf ff fa ap 8B 65 51 11
2 ua ap og id cY IF FD BB A7 72
3 ua rn Gg he cT TR OF F9 22 11
4 ut bW Wp GG gh dS RQ 65 32 11
5 yt fr GG ee dR RF F6 43 10 00
 */
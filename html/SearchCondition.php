<?php
class SearchCondition {                        //SearchConditionファイルに コンストラクタ、　配列はメンバ変数

    public $keyword;
    public $category;
    public $size;
    public $price_min;
    public $price_max;
    public $order;
    public $size_fit;

    function __construct($keyword = '', $category = 0, $size = 0, $price_min = 0, $price_max = 0, $order = '', $size_fit = 0) {

        $this->keyword = $keyword;
        $this->category = $category;
        $this->size = $size;
        $this->price_min = $price_min;
        $this->price_max = $price_max;
        $this->order = $order;
        $this->size_fit = $size_fit;
        
    }
}
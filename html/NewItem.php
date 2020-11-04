<?php
class NewItem {

    // pu______
    public $item_name;
    public $price;
    public $category;
    public $size;
    public $img_file;
    public $stock;
    public $status;
    public $column_name;
    public $comment;
    public $createdate;

    function __construct($item_name = '', $price = 0, $category = 0, $size = 0, $img_file = '', $stock = 0, $status = 0, $column_name = '', $comment = '', $createdate = '') {
        
        $this->item_name = $item_name;
        $this->price = $price;
        $this->category = $category;
        $this->size = $size;
        $this->img_file = $img_file;
        $this->stock = $stock;
        $this->status = $status;
        $this->column_name = $column_name;
        $this->comment = $comment;
        $this->createdate = $createdate;
        
    }
}

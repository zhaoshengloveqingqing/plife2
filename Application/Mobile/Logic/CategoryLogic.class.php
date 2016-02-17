<?php
/**
 * Created by PhpStorm.
 * User: IceLight
 * Date: 15/11/20
 * Time: 上午9:02
 */

namespace Mobile\Logic;


class CategoryLogic extends \Think\Model{
    public function __construct(){
        $this->Category = M('Category');
    }
    private $Category;

    public function getCategoryList($pid){
        $data = $this->Category->where(array('pid'=>$pid))->order('id asc')->select();
        return $data;
    }

}

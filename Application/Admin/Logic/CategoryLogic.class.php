<?php
/**
 * Created by PhpStorm.
 * User: IceLight
 * Date: 15/11/20
 * Time: 上午9:02
 */

namespace Admin\Logic;


class CategoryLogic extends \Think\Model{
    public function __construct(){
        $this->Category = M('Category');
    }
    private $Category;

    public function getCategoryTotal($cond = array()){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $num = $this->Category->where($mycond)->count();
        return $num;
    }

    public function getCategoryList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->Category->where($mycond)->order('id asc')->select();
        return $data;
    }

    public function getParentCategoryList($id){
        if($id){
            $data = $this->Category->where(array('pid'=>$id))->select();
            return $data;
        }else{
            return false;
        }
    }

    public function getCategoryById($id){
        if($id){
            $data = $this->Category->where(array('id'=>$id))->find();
            return $data;
        }else{
            return false;
        }
    }
}

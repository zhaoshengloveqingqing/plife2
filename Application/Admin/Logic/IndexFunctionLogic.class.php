<?php
/**
 * Created by PhpStorm.
 * User: IceLight
 * Date: 15/11/20
 * Time: 上午9:02
 */

namespace Admin\Logic;


class IndexFunctionLogic extends \Think\Model{
    public function __construct(){
        $this->IndexFuction = M('Function');
    }
    private $IndexFuction;

    public function getFunctionTotal($cond = array()){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $num = $this->IndexFuction->where($mycond)->where('isdel is null')->count();
        return $num;
    }

    public function getFunctionList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->IndexFuction->where($mycond)->where('isdel is null')->page($pstr)->order('id asc')->select();
        return $data;
    }

    public function getFunctionById($id){
        if($id){
            $data = $this->IndexFuction->getById($id);
            return $data;
        }else{
            return false;
        }
    }
}

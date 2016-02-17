<?php
/**
 * Created by PhpStorm.
 * User: IceLight
 * Date: 15/11/20
 * Time: 上午9:02
 */

namespace Admin\Logic;


class CustomerLogic extends \Think\Model{
    public function __construct(){
        $this->Customer = M('Customer');
    }
    private $Customer;

    public function getCustomerTotal($cond = array()){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $num = $this->Customer->where($mycond)->where('isdel is null')->count();
        return $num;
    }

    public function getCustomerList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->Customer->where($mycond)->where('isdel is null')->page($pstr)->select();
        return $data;
    }
}
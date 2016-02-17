<?php
/**
 * Created by PhpStorm.
 * User: IceLight
 * Date: 15/11/20
 * Time: 上午9:02
 */

namespace Admin\Logic;


class DnsLogic extends \Think\Model{
    public function __construct(){
        $this->Dns = M('Dns');
    }
    private $Dns;

    public function getDnsTotal($cond = array()){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $num = $this->Dns->where($mycond)->where('isdel is null')->count();
        return $num;
    }

    public function getDnsList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->Dns->where($mycond)->where('isdel is null')->page($pstr)->order('sort asc')->select();
        return $data;
    }

    public function getDnsById($id){
        if($id){
            $data = $this->Dns->getById($id);
            return $data;
        }else{
            return false;
        }
    }
}

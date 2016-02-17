<?php
/**
 * Created by PhpStorm.
 * User: IceLight
 * Date: 15/11/20
 * Time: 上午9:02
 */

namespace Admin\Logic;


class PositionLogic extends \Think\Model{
    public function __construct(){
        $this->Position = M('Position');
    }
    private $Banner;

    public function getPositionTotal($cond = array()){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $num = $this->Position->where($mycond)->where('isdel is null')->count();
        return $num;
    }

    public function getPositionList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->Position->where($mycond)->where('isdel is null')->page($pstr)->order('creatime desc')->select();
        return $data;
    }

    public function getPositionById($id){
        if($id){
            $data = $this->Position->getById($id);
            return $data;
        }else{
            return false;
        }
    }
    public function getUuid(){
        $uuid=genUuid();
        $con['uuid']=$uuid;
        $ret=$this->Video->where($con)->select();
        if(!$ret){
            return $uuid;
        }else {
            $uuid=genUuid();
            return $uuid;
        }
        
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: IceLight
 * Date: 15/11/20
 * Time: 上午9:02
 */

namespace Admin\Logic;


class BannerLogic extends \Think\Model{
    public function __construct(){
        $this->Banner = M('Banner');
    }
    private $Banner;

    public function getBannerTotal($cond = array()){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $num = $this->Banner->where($mycond)->where('isdel is null')->count();
        return $num;
    }

    public function getBannerList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->Banner->where($mycond)->where('isdel is null')->page($pstr)->order('sort asc')->select();
        return $data;
    }

    public function getBannerById($id){
        if($id){
            $data = $this->Banner->getById($id);
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

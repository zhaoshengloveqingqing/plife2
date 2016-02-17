<?php
/**
 * Created by PhpStorm.
 * User: IceLight
 * Date: 15/11/20
 * Time: 上午9:02
 */

namespace Mobile\Logic;


class BannerLogic extends \Think\Model{
    public function __construct(){
        $this->Banner = M('Banner');
    }
    private $Banner;

    public function getBannerList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->Banner->where($mycond)->where('isdel is null')->page($pstr)->order('sort asc')->select();
        return $data;
    }
}

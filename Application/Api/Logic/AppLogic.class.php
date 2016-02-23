<?php
/**
 * Created by PhpStorm.
 * User: IceLight
 * Date: 15/11/20
 * Time: 上午9:02
 */

namespace Mobile\Logic;


class AppLogic extends \Think\Model{
    public function __construct(){
        $this->App = M('App');
    }
    private $App;

    public function getAppList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->App->field('id,icon,name,imgs')->where($mycond)->where('isdel is null')->page($pstr)->order('creatime desc')->select();
        return $data;
    }

    // 单独获取应用或游戏的单条记录
    public function getAppsById($id){
        if($id){
            $data = $this->App->getById($id);
            return $data;
        }else{
            return false;
        }
    }
}
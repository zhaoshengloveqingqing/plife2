<?php
/**
 * Created by PhpStorm.
 * User: IceLight
 * Date: 15/11/20
 * Time: 上午9:02
 */

namespace Admin\Logic;


class AppsLogic extends \Think\Model{
    public function __construct(){
        $this->App = M('App');
    }
    private $App;

    public function getGameTotal($cond = array()){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $mycond['apptype'] = 1;
        $num = $this->App->where($mycond)->where('isdel is null')->count();
        return $num;
    }

    public function getGameList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $mycond['apptype'] = 1;
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->App->where($mycond)->where('isdel is null')->page($pstr)->order('creatime desc')->select();
        return $data;
    }

    public function getAppTotal($cond = array()){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $mycond['apptype'] = 2;
        $num = $this->App->where($mycond)->where('isdel is null')->count();
        return $num;
    }

    public function getAppList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->App->where($mycond)->where('isdel is null')->page($pstr)->order('creatime desc')->select();
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
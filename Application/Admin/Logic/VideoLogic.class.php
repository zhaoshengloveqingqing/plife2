<?php
/**
 * Created by PhpStorm.
 * User: IceLight
 * Date: 15/11/20
 * Time: 上午9:02
 */

namespace Admin\Logic;


class VideoLogic extends \Think\Model{
    public function __construct(){
        $this->Video = M('Video');
        $this->App = M('App');
    }
    private $Video;
    private $App;

    public function getVideoTotal($cond = array()){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $num = $this->Video->where($mycond)->where('isdel is null')->count();
        return $num;
    }

    public function getVideoList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->Video->where($mycond)->where('isdel is null')->page($pstr)->order('creatime desc')->select();
        return $data;
    }

    public function getVideoByType($type){
        switch($type){
            case 1:
                $data = $this->Video->select();
                return $data;
            case 2:
                $data = $this->App->select();
                return $data;
            default:
                return false;

        }
    }

    public function getVideoById($id){
        if($id){
            $data = $this->Video->getById($id);
            return $data;
        }else{
            return false;
        }
    }


    public function getUuid(){
        $i=0;
        do{
            $i++;
            $uuid=genUuid();
            $exist= $this->Video->where(array('uuid'=>$uuid))->find();
            $ret = $exist ? false: $uuid;
        }while($exist && ($i<3));
        return $ret;
    }

}

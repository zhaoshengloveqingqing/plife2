<?php
/**
 * Created by PhpStorm.
 * User: IceLight
 * Date: 16/01/03
 * Time: 上午9:02
 */

namespace Mobile\Logic;


class ResourceLogic extends \Think\Model{
    public function __construct(){
        $this->Video = M('Video');
        $this->App = M('App');
        $this->Comment = M('Comment');
    }
    private $Comment;
    private $Video;
    private $App;
    private $VideoOrder = array(1=>"creatime",2=>"viewtimes");   // 排序关键词，1:时间，2:热门
    private $AppOrder = array(1=>"creatime",2=>"downtimes");   // 排序关键词，1:时间，2:热门

    /**
     * 分页带获取视频列表
     * 参数：
     * @param array cond : （可选）搜索条件
     * @param int p : （可选）分页，默认为1 （不需要额外定义列表的数量）
     * @param int o : （可选）排序依据，默认为1:时间逆序，2:热门逆序
     * @param int $limit : （可选）每次获取的记录条数，默认系统分配（也不可超过系统分配限制数）
     * @return json : list
     */
    public function getVideos($cond=array(), $p=1, $o=1, $limit=0){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        if($limit>0 && $limit<C('MOB_REC_PER_PAGE')){
            $pstr = $p.','.$limit;
        }else{
            $pstr = $p.','.C('MOB_REC_PER_PAGE');
        }
        if (array_key_exists($o,$this->VideoOrder)){$order = $this->VideoOrder[$o];}else{$order = $this->VideoOrder[1];}
        $data = $this->Video->where($mycond)->where('isdel is null')->page($pstr)->order("$order desc")->select();
//        print_r($this->Video->getLastSql());die;
        return $data;
    }

    public function getVideoById($id){
        if($id){
            $data = $this->Video->getById($id);
            return $data;
        }else{
            return false;
        }
    }
    /**
     * 分页带获取应用列表
     * 参数：
     * @param array cond : （可选）搜索条件
     * @param int p : （可选）分页，默认为1 （不需要额外定义列表的数量）
     * @param int o : （可选）排序依据，默认为1:时间逆序，2:热门逆序
     * @param int $limit : （可选）每次获取的记录条数，默认系统分配（也不可超过系统分配限制数）
     * @return json : list
     */
    public function getApps($cond=array(), $p=1, $o=1, $limit=0){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        if($limit>0 && $limit<C('MOB_REC_PER_PAGE')){
            $pstr = $p.','.$limit;
        }else{
            $pstr = $p.','.C('MOB_REC_PER_PAGE');
        }
        if (array_key_exists($o,$this->AppOrder)){$order = $this->AppOrder[$o];}else{$order = $this->AppOrder[1];}
        $data = $this->App->where($mycond)->where('isdel is null')->page($pstr)->order("$order desc")->select();
//        print_r($this->App->getLastSql());die;
        return $data;
    }

    public function getAppById($id){
        if($id){
            $data = $this->App->getById($id);
            return $data;
        }else{
            return false;
        }
    }

    /**
     * 分页带获取评论列表
     * 参数：
     * @param array cond : （可选）搜索条件
     * @param int p : （可选）分页，默认为1 （不需要额外定义列表的数量）
     * @param int o : （可选）排序依据，默认为1:时间逆序，2:热门逆序
     * @param int $limit : （可选）每次获取的记录条数，默认系统分配（也不可超过系统分配限制数）
     * @return json : list
     */
    public function getComments($cond=array(), $p=1,  $limit=0){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        if($limit>0 && $limit<C('MOB_REC_PER_PAGE')){
            $pstr = $p.','.$limit;
        }else{
            $pstr = $p.','.C('MOB_REC_PER_PAGE');
        }
        $data = $this->Comment->where($mycond)->where('isdel is null')->page($pstr)->order("creatime desc")->select();
        return $data;
    }

}
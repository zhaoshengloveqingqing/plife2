<?php
/**
 * Created by PhpStorm.
 * User: IceLight
 * Date: 15/11/20
 * Time: 上午9:02
 */

namespace Mobile\Logic;


class VideoLogic extends \Think\Model{
    public function __construct(){
        $this->Video = M('Video');
        $this->App = M('App');
    }
    private $Video;
    private $App;

    public function getimgById($id){
        if($id){
            $cond['uuid'] = $id;
            $data = $this->Video->field('cover')->where($cond)->select();
            return $data;
        }else{
            return false;
        }
    }
}

<?php
namespace Admin\Controller;
use Think\Controller;
header("Content-Type:text/html; charset=utf-8");
class IndexController extends Controller {
    public function index(){
        if(session('name')==null){
            $this->redirect('Adminuser/login',0);
        }else{
            $this->adname  = session('name');
            $this->display();
        }
    }
}
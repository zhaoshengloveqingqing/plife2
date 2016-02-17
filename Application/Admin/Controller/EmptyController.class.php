<?php
namespace Home\Controller;
use Think\Controller;
class  EmptyController extends Controller {
    public function  _empty(){
        header( " HTTP/1.0  404  Not Found" );
        $this->display( 'Public:404' );
    }
    public function  index(){
        header( " HTTP/1.0  404  Not Found" );
        $this->display( 'Public:404' );
    }
} 
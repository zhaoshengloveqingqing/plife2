<?php
namespace Admin\Controller;
use Think\Controller;
class PharController extends Controller {
	
	
	
	private function unzip($file,$extractPath){


		$zip = new \Phar($file);
		print_r($file);die;
		$fileInfo = pathinfo($file);
		 
		$destDir = $extractPath.DIRECTORY_SEPARATOR.$fileInfo['filename'];
		 
		$zip->extractTo( $destDir );
		 
		$zip->close();
		
	}
	
    public function index(){

		$extractPath = '/opt';

		$files=scandir("/opt");

		array_shift($files);

		foreach($files as $v){
			if(preg_match('/(.*)(\.)zip$/i',$v)){
				$item = '/opt/'.$v;
				$this->unzip($item, $extractPath);
			}
		}
    }
}
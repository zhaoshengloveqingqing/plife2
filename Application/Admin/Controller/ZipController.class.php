<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

class ZipController extends Controller {
	
	
	
	private function unzip($file,$extractPath){

        try {
            $zip = new \ZipArchive();

            $zip->open($file);

            $fileInfo = pathinfo($file);

            $destDir = $extractPath . DIRECTORY_SEPARATOR . $fileInfo['filename'].DIRECTORY_SEPARATOR;

            $zip->extractTo($destDir) || exit('unzip error');

            $zip->close();
        }catch(Exception $e){

            var_dump($e);
        }
	}
	
    public function index(){

        $time = microtime(true);

        $extractPath = '/opt';

        $files = scandir("/opt");

        array_shift($files);

        foreach($files as $v){
            if(preg_match('/(.*)(\.)zip$/i',$v)) {
                $arr[]=$v;
            }
        }
        foreach($arr as $k=> $v){
            $item = '/opt/'.$v;
            if(file_exists('/opt/'.pathinfo($v)['filename'])){
                unset($arr[$k]);
            }else{
                $this->unzip($item, $extractPath);
            }
        }
        $end_time=microtime(true);
        echo 'The Extract Total spends :'.round(($end_time-$time),3).'s';
    }
}
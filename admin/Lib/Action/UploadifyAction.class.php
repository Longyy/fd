<?php
//插件上传图库类
class UploadifyAction
{
	public function index()
	{
	//$targetFolder = $_POST['url']; // Relative to the root
    	//$targetPath = "/nextUpFile/Public/upload/";   	
    	if(!$_POST['authId']||empty($_POST['authId'])){
    		exit('非法访问');
    	}
    	
    	if(!empty($_POST['image_id'])&&is_numeric($_POST['image_id'])){
    		$image_id=$_POST['image_id'];
    	}else{
    		$image_id=0;
    	}
    	
    	//$targetPath = "/data/uploads/";
		//echo $_POST['token'];
		$verifyToken = md5($_POST['timestamp']);
		if (!empty($_FILES) && $_POST['token'] == $verifyToken) {

			import("ORG.Net.UploadFile");
			$name=time().rand();	//设置上传图片的规则

			$upload = new UploadFile();// 实例化上传类

			$upload->maxSize  = 3145728 ;// 设置附件上传大小

			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			
			$savePath='data/uploads/'.date('YmdH',time()).'/';		  //动态生成上传目录
			if(!file_exists($savePath)){			
				@mkdir($savePath, 0777);
			}
			$upload->savePath =  $savePath;// 设置附件上传目录

			$upload->saveRule = $name;  //设置上传图片的规则

			if(!$upload->upload()) {// 上传错误提示错误信息

			//return false;
				file_put_contents('error.ext', $upload->getErrorMsg());
				echo $upload->getErrorMsg();
			//echo $targetPath;

			}else{// 上传成功 获取上传文件信息

				$info =  $upload->getUploadFileInfo();			
				//执行上传成功的操作
				//file_put_contents('text.txt', $savePath.$info[0]["savename"].'---',FILE_APPEND);

				//上传的原图路径
				$img_url=$savePath.$info[0]["savename"];
				
			
				//压缩图片				
				Vendor('ImageResize');		
					
				$_img = new ImageResize();				
				//$thumbnail->resizeimage(源图片完整路径, 缩略图宽度, 缩略图高度, 是否剪裁（0或者1）, 新图片完整路径);
				$_nameArr = explode('.',$img_url);
				$_postfix = $_nameArr[count($_nameArr)-1];
				
				$simg_url=$img_url.'_200x200.'.$_postfix;   //新图的路径
								
				$_img->resizeimage($img_url, 200, 200, 0,$simg_url);		
			
										
				$img_url_mod=D('image_url');
				$img_array=array(
					'image_id'=>$image_id,
					'url'=>$img_url,
					'surl'=>$simg_url,
					'add_time'=>time()
				);
				$img_url_id=$img_url_mod->add($img_array);
				
				//echo $simg_url;  //输出处理后的小图片			
				echo $img_url_id.'-'.$simg_url;
			}
		}
	}
	
	public function course()
	{
	//$targetFolder = $_POST['url']; // Relative to the root
    	//$targetPath = "/nextUpFile/Public/upload/";   	
    	if(!$_POST['authId']||empty($_POST['authId'])){
    		exit('非法访问');
    	}
    	
    	if(!empty($_POST['course_id'])&&is_numeric($_POST['course_id'])){
    		$course_id=$_POST['course_id'];
    	}else{
    		$course_id=0;
    	}
    	
    	//$targetPath = "/data/uploads/";
		//echo $_POST['token'];
		$verifyToken = md5($_POST['timestamp']);
		if (!empty($_FILES) && $_POST['token'] == $verifyToken) {

			import("ORG.Net.UploadFile");
			$name=time().rand();	//设置上传图片的规则

			$upload = new UploadFile();// 实例化上传类

			$upload->maxSize  = 3145728 ;// 设置附件上传大小

			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			
			$savePath='data/uploads/'.date('YmdH',time()).'/';		  //动态生成上传目录
			if(!file_exists($savePath)){			
				@mkdir($savePath, 0777);
			}
			$upload->savePath =  $savePath;// 设置附件上传目录

			$upload->saveRule = $name;  //设置上传图片的规则

			if(!$upload->upload()) {// 上传错误提示错误信息

			//return false;
				file_put_contents('error.ext', $upload->getErrorMsg());
				echo $upload->getErrorMsg();
			//echo $targetPath;

			}else{// 上传成功 获取上传文件信息

				$info =  $upload->getUploadFileInfo();			
				//执行上传成功的操作
				//file_put_contents('text.txt', $savePath.$info[0]["savename"].'---',FILE_APPEND);

				//上传的原图路径
				$img_url=$savePath.$info[0]["savename"];
							
				//压缩图片				
				Vendor('ImageResize');	
					
				$_img = new ImageResize();				
				//$thumbnail->resizeimage(源图片完整路径, 缩略图宽度, 缩略图高度, 是否剪裁（0或者1）, 新图片完整路径);
				$_nameArr = explode('.',$img_url);
				$_postfix = $_nameArr[count($_nameArr)-1];
				
				$simg_url=$img_url.'_200x200.'.$_postfix;   //新图的路径
								
				$_img->resizeimage($img_url, 200, 200, 0,$simg_url);
												
				$course_url_mod=D('course_url');
				$course_array=array(
					'course_id'=>$course_id,
					'url'=>$img_url,
					'surl'=>$simg_url,
					'add_time'=>time()
				);
				$img_url_id=$course_url_mod->add($course_array);
				
				//echo $simg_url;  //输出处理后的小图片			
				echo $img_url_id.'-'.$simg_url;
			}
		}
	}
	
}
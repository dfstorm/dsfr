<?php
	
	class dsfr {
		public $form_element = "upload";
		public $repo = "data";
		public $file_prefix = '';
		public $file_data_ext = 'data';
		public $result = array();
		public $iKey = 1;
		public $rKey = array("abc");
		public $get_param = "q";
		public function do_upload()
		{
			$total = count($_FILES[$this->form_element]['name']);
			if($total > 0)
			{
				$this->result['files'] = array();
				for($i=0; $i<$total; $i++) {
					$tmpFilePath = $_FILES['upload']['tmp_name'][$i];
					if ($tmpFilePath != ""){
						$uid = uniqid($this->file_prefix.time());
						$newFilePath = "./".$this->repo."/" . $uid;
						if(move_uploaded_file($tmpFilePath, $newFilePath)) {
							file_put_contents($newFilePath.'.'.$this->file_data_ext,json_encode(array(
								"Key" => (isset($_POST['key']) ? $_POST['key'] : 'public'), 
								"time" => time(),
								"filename"=>$_FILES['upload']['name'][$i],
								"type" => $_FILES['upload']['type'][$i],
								"size" => $_FILES['upload']['size'][$i]
							)));
							$this->result['iCode'] = 0;
							$this->result['files'][] = $uid;
						} else {
							$this->result['iCode'] = 1;
							$this->result['sMessage'] = "Error : Could not move the file to final destination from [$tmpFilePath] to [$newFilePath].";
						}
					} else {
						$this->result['iCode'] = 1;
						$this->result['sMessage'] = "Error: File name empty.";
					}
				}
			}
			else
			{
				$this->result['iCode'] = 1;
				$this->result['sMessage'] = "Error: No file to upload.";
				
			
			}
		
		}
		public function init()
		{
			if(isset($_FILES[$this->form_element]))
			{
				if ($this->iKey) {
					if(isset($_POST['key']))
					{
						if(in_array($_POST['key'], $this->rKey))
						{
							$this->do_upload();
						}
						else
						{
							$this->result['iCode'] = 1;
							$this->result['sMessage'] = "Error: Invalid key recieved.";
						}
					} else {
						$this->result['iCode'] = 1;
						$this->result['sMessage'] = "Error: No key recieved.";
					}
				} else {
					$this->do_upload();
				}
				header('Content-Type: application/json');
				echo json_encode($this->result);
			}
			if (isset($_GET[$this->get_param]))
			{
				$name = './'.$this->repo."/".$_GET[$this->get_param];
				$fp = fopen($name, 'rb');
				if($fp) {
					$imageData = json_decode(file_get_contents($name.'.'.$this->file_data_ext),true);
					header("Content-Type: ".$imageData['type']);
					header("Content-Length: " . $imageData['size']);
					fpassthru($fp);
				} else {
					header('Content-Type: application/json');
					$this->result['iCode'] = 1;
					$this->result['iMessage'] = "Error: File not found.";
					echo json_encode($this->result);
				}
			}
		}
	}
	$dsfr = new dsfr;
	$dsfr->init();

<?php
session_start();
include('classes/notices_controllers.php');
$notices = new Notices;

$startD = trim($_POST['startdate']); 
$endD = trim($_POST['enddate']);
$ISPName = trim($_POST['ISPName']);
$relatedtitleID = trim($_POST['relatedtitleID']);

if(isset($_POST['dl'])){
   
    if($ISPName == "All"){
    		$isp = $notices->getCAISPInfoSingle($relatedtitleID);
			foreach($isp as $is){
			$ispname = $is['geoipisp'];
			$country = isset($_POST['country'])? $_POST['country'] : '';
			$data = $notices->getCANoticeSentInfoSinglewithDate($ispname,$relatedtitleID,$startD,$endD);

			if($data){
				$zip = new ZipArchive;
				$time = time();
				$hasdl = false;
				foreach($data AS $k=>$i){
					$ii = explode("#$#", $i['SecondNoticeInfo']);
					$file1 = '/mnt/evirdgoogle/_Notices/'.$country.'/' . substr( $i['NoticeID1'], 0, strlen($i['NoticeID1'])-6) . '000000/' . $i['noticesid'] . '.eml' ;
					$file2 = '/mnt/evirdgoogle/_Notices/'.$country.'/' . substr( $ii[1], 0, strlen($ii[1])-6) . '000000/' . $ii[1] . '.eml' ;
					$file3 = '/mnt/evirdgoogle/_Notices/' . $country . '_REPLIES/' . substr( $i['NoticeID1'], 0, strlen($i['NoticeID1'])-6) . '000000/' . $i['NoticeID1'] . '.eml' 	;
					// echo $i['noticesid'];
							if ($zip->open("/www-tmp/GeneratedZipFiles/ANotices-$time.zip",ZipArchive::CREATE)){
								$zip->addFile($file1, $i['NoticeID1'].'.eml' );
								if(file_exists($file2)){
									$zip->addFile($file2, $i['NoticeID1'] . '_' . $ii[1] .'.eml' );
								}
								$zip->addFile($file3, $i['NoticeID1'].'-reply'.'.eml' );
							}
								$zip->close();
						$hasdl = true;
					}
				if($hasdl){
					header("Content-type: application/zip");
					header("Content-disposition: attachment; filename=All_Notices-".date('Y-m-d').".zip");
					ob_clean(); 
					flush();
					readfile("/www-tmp/GeneratedZipFiles/ANotices-$time.zip");
					unlink("/www-tmp/GeneratedZipFiles/ANotices-$time.zip");
					exit;
				}
			}
			else{
					echo '<script>alert("No Such File Existed"); window.history.go(-1);</script>';
				}

	}}
    	else{

    		$country = isset($_POST['country'])? $_POST['country'] : '';
			$data = $notices->getCANoticeSentInfoSinglewithDate($ISPName,$relatedtitleID,$startD,$endD);

			if($data){
				$zip = new ZipArchive;
				$time = time();
				$hasdl = false;
				foreach($data AS $k=>$i){
					$ii = explode("#$#", $i['SecondNoticeInfo']);
					$file1 = '/mnt/evirdgoogle/_Notices/'.$country.'/' . substr( $i['NoticeID1'], 0, strlen($i['NoticeID1'])-6) . '000000/' . $i['noticesid'] . '.eml' ;
					$file2 = '/mnt/evirdgoogle/_Notices/'.$country.'/' . substr( $ii[1], 0, strlen($ii[1])-6) . '000000/' . $ii[1] . '.eml' ;
					$file3 = '/mnt/evirdgoogle/_Notices/' . $country . '_REPLIES/' . substr( $i['NoticeID1'], 0, strlen($i['NoticeID1'])-6) . '000000/' . $i['NoticeID1'] . '.eml' 	;
					// echo $i['noticesid'];
							if ($zip->open("/www-tmp/GeneratedZipFiles/ANotices-$time.zip",ZipArchive::CREATE)){
								$zip->addFile($file1, $i['NoticeID1'].'.eml' );
								if(file_exists($file2)){
									$zip->addFile($file2, $i['NoticeID1'] . '_' . $ii[1] .'.eml' );
								}
								$zip->addFile($file3, $i['NoticeID1'].'-reply'.'.eml' );
							}
								$zip->close();
						$hasdl = true;
					}
				if($hasdl){
					header("Content-type: application/zip");
					header("Content-disposition: attachment; filename=".$ISPName."_Notices-".date('Y-m-d').".zip");
					ob_clean(); 
					flush();
					readfile("/www-tmp/GeneratedZipFiles/ANotices-$time.zip");
					unlink("/www-tmp/GeneratedZipFiles/ANotices-$time.zip");
					exit;
				}
			}
			else{
					echo '<script>alert("No Such File Existed"); window.history.go(-1);</script>';
				}}
			

}elseif(isset($_POST['fl'])){

   $status = trim($_POST['filter']);
	if($ISPName == "All"){
		if($status == "All"){
			$isp = $notices->getCAISPInfoSingle($relatedtitleID);
			foreach($isp as $is){
			$ispname = $is['geoipisp'];
			$country = isset($_POST['country'])? $_POST['country'] : '';
			$data = $notices->getNoticewithReply($ispname,$status,$relatedtitleID,$startD,$endD);
			if($data){
				$zip = new ZipArchive;
				$time = time();
				$hasdl = false;
				foreach($data AS $k=>$i){
					$ii = explode("#$#", $i['SecondNoticeInfo']);
					echo $i['noticesid'];
					$file1 = '/mnt/evirdgoogle/_Notices/'.$country.'/' . substr( $i['NoticeID1'], 0, strlen($i['NoticeID1'])-6) . '000000/' . $i['NoticeID1'] . '.eml' ;
					$file2 = '/mnt/evirdgoogle/_Notices/' . $country . '_REPLIES/' . substr( $i['NoticeID1'], 0, strlen($i['NoticeID1'])-6) . '000000/' . $i['NoticeID1'] . '.eml' 	;
					$file3 = '/mnt/evirdgoogle/_Notices/'.$country.'/' . substr( $ii[1], 0, strlen($ii[1])-6) . '000000/' . $ii[1] . '.eml' ;

					if($zip->open("/www-tmp/GeneratedZipFiles/FNotices-$time.zip",ZipArchive::CREATE)){
						$zip->addFile($file1, $i['NoticeID1'].'.eml' );
						$zip->addFile($file2, $i['NoticeID1'].'-reply'.'_'.$status.'.eml' );
						if(file_exists($file3)){
							$zip->addFile($file3, $i['NoticeID1'] . '_' . $ii[1] .'.eml' );
						}// end of if file3
					}//end of zipfile open
					$zip->close();
					$hasdl = true;
					if($hasdl){
						header("Content-type: application/zip");
						header("Content-disposition: attachment; filename=ALL_Replies_Notices-".date('Y-m-d').".zip");
						ob_clean(); 
						flush();
						readfile("/www-tmp/GeneratedZipFiles/FNotices-$time.zip");
						unlink("/www-tmp/GeneratedZipFiles/FNotices-$time.zip");
						exit;
					}//end of hasdl
				}// end of foreach data
				}//end of data
				else{echo '<script>alert("No Such File Existed"); window.history.go(-1);</script>';}
			}//end of foreach isp
		}//end of status All
		else{
			$country = isset($_POST['country'])? $_POST['country'] : '';
			$data = $notices->getNoticewithReply($ispname,$status,$relatedtitleID,$startD,$endD);
			if($data){
				$zip = new ZipArchive;
				$time = time();
				$hasdl = false;
				foreach($data AS $k=>$i){
					$ii = explode("#$#", $i['SecondNoticeInfo']);
					echo $i['noticesid'];
					$file1 = '/mnt/evirdgoogle/_Notices/'.$country.'/' . substr( $i['NoticeID1'], 0, strlen($i['NoticeID1'])-6) . '000000/' . $i['NoticeID1'] . '.eml' ;
					$file2 = '/mnt/evirdgoogle/_Notices/' . $country . '_REPLIES/' . substr( $i['NoticeID1'], 0, strlen($i['NoticeID1'])-6) . '000000/' . $i['NoticeID1'] . '.eml' 	;
					$file3 = '/mnt/evirdgoogle/_Notices/'.$country.'/' . substr( $ii[1], 0, strlen($ii[1])-6) . '000000/' . $ii[1] . '.eml' ;

					if($zip->open("/www-tmp/GeneratedZipFiles/FNotices-$time.zip",ZipArchive::CREATE)){
						$zip->addFile($file1, $i['NoticeID1'].'.eml' );
						$zip->addFile($file2, $i['NoticeID1'].'-reply'.'_'.$status.'.eml' );
						if(file_exists($file3)){
							$zip->addFile($file3, $i['NoticeID1'] . '_' . $ii[1] .'.eml' );
						}// end of if file3
					}//end of zipfile open
					$zip->close();
					$hasdl = true;
					if($hasdl){
						header("Content-type: application/zip");
						header("Content-disposition: attachment; filename=ALL_".$status."_Replies_Notices-".date('Y-m-d').".zip");
						ob_clean(); 
						flush();
						readfile("/www-tmp/GeneratedZipFiles/FNotices-$time.zip");
						unlink("/www-tmp/GeneratedZipFiles/FNotices-$time.zip");
						exit;
					}//end of hasdl
				}//end of foreach
			}//end of data
			else{echo '<script>alert("No Such File Existed"); window.history.go(-1);</script>';}
		}//end of else status all
	}//end of ISP All
	else{
		if($status == "All"){
			$country = isset($_POST['country'])? $_POST['country'] : '';
			$data = $notices->getNoticewithReply($ispname,$status,$relatedtitleID,$startD,$endD);
			if($data){
				$zip = new ZipArchive;
				$time = time();
				$hasdl = false;
				foreach($data AS $k=>$i){
					$ii = explode("#$#", $i['SecondNoticeInfo']);
					$file1 = '/mnt/evirdgoogle/_Notices/'.$country.'/' . substr( $i['NoticeID1'], 0, strlen($i['NoticeID1'])-6) . '000000/' . $i['NoticeID1'] . '.eml' ;
					$file2 = '/mnt/evirdgoogle/_Notices/' . $country . '_REPLIES/' . substr( $i['NoticeID1'], 0, strlen($i['NoticeID1'])-6) . '000000/' . $i['NoticeID1'] . '.eml' 	;
					$file3 = '/mnt/evirdgoogle/_Notices/'.$country.'/' . substr( $ii[1], 0, strlen($ii[1])-6) . '000000/' . $ii[1] . '.eml' ;

					if($zip->open("/www-tmp/GeneratedZipFiles/FNotices-$time.zip",ZipArchive::CREATE)){
						$zip->addFile($file1, $i['NoticeID1'].'.eml' );
						$zip->addFile($file2, $i['NoticeID1'].'-reply'.'_'.$status.'.eml' );
						if(file_exists($file3)){
							$zip->addFile($file3, $i['NoticeID1'] . '_' . $ii[1] .'.eml' );
						}// end of if file3
					}//end of zipfile open
					$zip->close();
					$hasdl = true;
					if($hasdl){
						header("Content-type: application/zip");
						header("Content-disposition: attachment; filename=".$ISPName."_".$status."_Replies_Notices-".date('Y-m-d').".zip");
						ob_clean(); 
						flush();
						readfile("/www-tmp/GeneratedZipFiles/FNotices-$time.zip");
						unlink("/www-tmp/GeneratedZipFiles/FNotices-$time.zip");
						exit;
					}//end of hasdl
				}//end of foreach
			}//end of data
			else{echo '<script>alert("No Such File Existed"); window.history.go(-1);</script>';}
		}//end of status All
		else{
			$country = isset($_POST['country'])? $_POST['country'] : '';
			$data = $notices->getNoticewithReply($ispname,$status,$relatedtitleID,$startD,$endD);
			if($data){
				$zip = new ZipArchive;
				$time = time();
				$hasdl = false;
				foreach($data AS $k=>$i){
					$ii = explode("#$#", $i['SecondNoticeInfo']);
					$file1 = '/mnt/evirdgoogle/_Notices/'.$country.'/' . substr( $i['NoticeID1'], 0, strlen($i['NoticeID1'])-6) . '000000/' . $i['NoticeID1'] . '.eml' ;
					$file2 = '/mnt/evirdgoogle/_Notices/' . $country . '_REPLIES/' . substr( $i['NoticeID1'], 0, strlen($i['NoticeID1'])-6) . '000000/' . $i['NoticeID1'] . '.eml' 	;
					$file3 = '/mnt/evirdgoogle/_Notices/'.$country.'/' . substr( $ii[1], 0, strlen($ii[1])-6) . '000000/' . $ii[1] . '.eml' ;

					if($zip->open("/www-tmp/GeneratedZipFiles/FNotices-$time.zip",ZipArchive::CREATE)){
						$zip->addFile($file1, $i['NoticeID1'].'.eml' );
						$zip->addFile($file2, $i['NoticeID1'].'-reply'.'_'.$status.'.eml' );
						if(file_exists($file3)){
							$zip->addFile($file3, $i['NoticeID1'] . '_' . $ii[1] .'.eml' );
						}// end of if file3
					}//end of zipfile open
					$zip->close();
					$hasdl = true;
					if($hasdl){
						header("Content-type: application/zip");
						header("Content-disposition: attachment; filename=".$ISPName."_".$status."_Replies_Notices-".date('Y-m-d').".zip");
						ob_clean(); 
						flush();
						readfile("/www-tmp/GeneratedZipFiles/FNotices-$time.zip");
						unlink("/www-tmp/GeneratedZipFiles/FNotices-$time.zip");
						exit;
					}//end of hasdl
				}//end of foreach
			}//end of data
			else{echo '<script>alert("No Such File Existed"); window.history.go(-1);</script>';}
		}//end of else status all
		}//end of else ISPName individual
}// end of elseif
elseif(isset($_POST['el'])){
if($ISPName == "All"){

	require_once './classes/PHPExcel.php';
	$isp = $notices->getCAISPInfoSingle($relatedtitleID);
	$movieName = $notices->getMovieName($relatedtitleID);
	$timestamp = date("Y-m-d H-i-s");
	
	
	foreach($isp as $is){

		$ispname = $is['geoipisp'];
		// $gregion = null;
		$ipregion = substr($is['geoipregion'], 0, 15);
		$name = substr($ispname, 0, 15);
		$datum = $notices->emailresponse($ispname,$startD,$endD,$relatedtitleID,$gregion);
		$worksheet = ($name ."|".$ipregion);
		$excelfilename = $movieName."_".$ispname."_Notice_Excel_Ex ".$timestamp;
		$zipfilename = $movieName."_".$ispname."_Notice_Excel_Ex ".$timestamp;

		$styleThinBlackBorderOutline = array(
			'borders' => array(
				'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => 'FF000000'),
			),
		),
	);
		// $objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()->setCreator("")
							 ->setLastModifiedBy("")
							 ->setTitle("")
							 ->setSubject("")
							 ->setDescription("")
							 ->setKeywords("")
							 ->setCategory("");
							 
		$objPHPExcel->setActiveSheetIndex(0);
		$ctr = 3;
		$row=1;
		$limit = count($datum);

 	// if($sheet > 0){

 		//$objPHPExcel->createSheet();
  		//objPHPExcel->setActiveSheetIndex($sheet);
  		//$objPHPExcel->getActiveSheet()->setTitle("$worksheet");
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'IP');
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'First Notice ID');
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, 'First Notice');
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, 'Response');
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, 'First Notice Date');
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, 'Second Notice ID');
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, 'Second Notice');
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, 'Response');
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, 'Second Notice Date');
			/*Column Header*/
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('G'.$row)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('H'.$row)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('I'.$row)->getFont()->setBold(true);
			//Aligning header labels
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('G'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('H'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('I'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			//Set borders for column headers
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':A'.$row)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':B'.$row)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$row.':C'.$row)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$row.':D'.$row)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$row.':E'.$row)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$row.':F'.$row)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('G'.$row.':G'.$row)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('G'.$row.':H'.$row)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('G'.$row.':I'.$row)->applyFromArray($styleThinBlackBorderOutline);
			//Set column widths
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':I'.$row)->applyFromArray(
			array(
				'fill' => array(
					'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
					'rotation'   => 90,
					'startcolor' => array(
						'argb' => 'FFA0A0A0'
					),
					'endcolor'   => array(
						'argb' => 'DDDDDD'
					)
				)
			)
		);
		$row++;
	if(!empty($datum)){
			
			foreach($datum as $key=>$tableRow){
				if($row != $limit){
				$ii = explode('#$#', $tableRow['SecondNoticeInfo']);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $tableRow['IP']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $tableRow['FirstNoticeID']);  
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $tableRow['NoticeID1']); 
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $tableRow['CF']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $tableRow['FirstNotice']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $ii[0]);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $ii[1]);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $ii[4]);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $ii[2]);
				$row++;
				$ctr++;
				}
				else{
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$ctr, ">>>>>>>>");
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$ctr, "Nothing Follows");
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$ctr, "<<<<<<<<");
				}
			}}
			else{
				$row = 2;
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, "No Data Available"); 
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, "No Data Available"); 
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, "No Data Available");
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, "No Data Available");
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, "No Data Available");
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, "No Data Available");
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, "No Data Available");
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, "No Data Available");
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, "No Data Available");

				$row = 3;
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, ">>>>>>>>");
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, "Nothing Follows");
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, "<<<<<<<<");
			}//end of !empty $datum
				

		
	//}// end of sheet if
	// else{

	$objPHPExcel->setActiveSheetIndex(0)->setTitle("$worksheet");
	// }
	// $sheet++;

        	
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				try{
					$objWriter->save("/www-tmp/GeneratedXlsFiles" . "/" . $excelfilename.".xlsx");
				}
				catch(exception $e){
					// $err++;
					$errMsg = "Err code: EXPORT01";
				}
				

	}//end of foreach isp
				
				$zip = new ZipArchive();
				$hasdl = false;
				foreach($isp as $isps){

						$ispname = $isps['geoipisp'];
						$excelfilename = $movieName."_".$ispname."_Notice_Excel_Ex ".$timestamp;
						$zipfilename = $movieName."_All_Notice_Excel_Ex ".$timestamp;
						$file = '/www-tmp/GeneratedXlsFiles' . '/' . $excelfilename.'.xlsx';
						if ($zip->open("/www-tmp/GeneratedZipFiles/".$zipfilename.".zip",ZipArchive::CREATE)){
								$zip->addFile($file, $excelfilename.".xlsx");
								$zip->close();}
							$hasdl = true;
						if($hasdl){
								header("Content-type: application/zip");
								header("Content-disposition: attachment; filename=".$zipfilename.".zip");
								header('Cache-Control: max-age=0'); 
								ob_clean(); 
								flush();
								readfile("/www-tmp/GeneratedZipFiles/".$zipfilename.".zip");}
				}// end of foreach zip
				
}//end of if
else{
	 require_once './classes/PHPExcel.php';
	 $movieName = $notices->getMovieName($relatedtitleID);
	 $gregion = null;
	 $datum = $notices->emailresponse($ISPName,$startD,$endD,$relatedtitleID,$gregion);
	 $data = "";

	 $styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("")
							 ->setLastModifiedBy("")
							 ->setTitle("")
							 ->setSubject("")
							 ->setDescription("")
							 ->setKeywords("")
							 ->setCategory("");
							 
$objPHPExcel->setActiveSheetIndex(0);
$limit = count($datum);
$ctr = 3;
$headerctr = 0;
$row = 1; //Excel row where we will start to input

	/*
	* 	Column Headers
	*/
	//Set column headers
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'IP');
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'First Notice ID');
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, 'First Notice');
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, 'Response');
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, 'First Notice Date');
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, 'Second Notice ID');
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, 'Second Notice');
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, 'Response');
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, 'Second Notice Date');
	
	//make column headers bold
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$row)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$row)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('I'.$row)->getFont()->setBold(true);
	
	//Aligning header labels
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('I'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	//Set borders for column headers
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':A'.$row)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':B'.$row)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('C'.$row.':C'.$row)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('D'.$row.':D'.$row)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$row.':E'.$row)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$row.':F'.$row)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$row.':G'.$row)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$row.':H'.$row)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$row.':I'.$row)->applyFromArray($styleThinBlackBorderOutline);
	
	//Set column widths
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
	
		$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':I'.$row)->applyFromArray(
			array(
				'fill' => array(
					'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
					'rotation'   => 90,
					'startcolor' => array(
						'argb' => 'FFA0A0A0'
					),
					'endcolor'   => array(
						'argb' => 'DDDDDD'
					)
				)
			)
		);
	$row++;
	//loop through the rows and write the column values
;
if(!empty($datum)){
foreach($datum as $key=>$tableRow){
	if($row != $limit){
	$ii = explode('#$#', $tableRow['SecondNoticeInfo']);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $tableRow['IP']);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $tableRow['FirstNoticeID']);  
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $tableRow['NoticeID1']); 
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $tableRow['CF']);
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $tableRow['FirstNotice']);
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $ii[0]);
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $ii[1]);
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $ii[4]);
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $ii[2]);
	$row++;
	$ctr++;
	}
	else{
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$ctr, ">>>>>>>>");
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$ctr, "Nothing Follows");
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$ctr, "<<<<<<<<");
	}
}}
else{
	$row = 2;
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, "No Data Available"); 
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, "No Data Available"); 
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, "No Data Available");
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, "No Data Available");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, "No Data Available");
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, "No Data Available");
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, "No Data Available");
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, "No Data Available");
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, "No Data Available");

	$row = 3;
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, ">>>>>>>>");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, "Nothing Follows");
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, "<<<<<<<<");
}		
		if(strlen($ISPName) > 30){$ispname = substr($ISPName, 0, 20); }else{$ispname = $ISPName;}
		$objPHPExcel->getActiveSheet()->setTitle("$ispname");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$timestamp = date("Y-m-d H-i-s");
		$excelfilename = $movieName."_".$ISPName."_Notice_Excel_Ex".$timestamp;
		try{
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="'.$excelfilename.'".xlsx');
			$objWriter->save('php://output');
		}
		catch(exception $e){
			// $err++;
			$errMsg = "Err code: EXPORT01";
		}
		// $zip = new ZipArchive();
		// $zipfilename = $movieName."_".$ISPName."_Notice_Excel_Ex-".$timestamp;
		// //Create an archive and add the excel file and the pdf files.
		// //if ($zip->open("perf/dl/casemanagement-$time.zip",ZipArchive::CREATE)) {
		// if ($zip->open("/www-tmp/GeneratedZipFiles/$movieName_$ISPName__Notice_Excel_Ex-$timestamp.zip",ZipArchive::CREATE)) {
		// 	//add the excel file to the archive
		// 	$zip->addFile("/www-tmp/GeneratedXlsFiles" . "/" . $excelfilename.".xlsx", $excelfilename.".xlsx");
		// 	$zip->close();
		// }
		
		// if(file_exists("/www-tmp/GeneratedZipFiles/$movieName_$ISPName__Notice_Excel_Ex-$timestamp.zip") == true){
		// 	header("Content-type: application/zip");
		// 	header("Content-disposition: attachment; filename=".$zipfilename.".zip");
		// 	ob_clean(); 
		// 	flush();
		// 	readfile("/www-tmp/GeneratedZipFiles/$movieName_$ISPName__Notice_Excel_Ex-$timestamp.zip");}
}//edn of else el
}//end of if $_POST
else{
	echo '<script>alert("No Transaction!"); window.history.go(-1);</script>';
}
// header("Location: {$_SERVER["HTTP_REFERER"]}");
?>
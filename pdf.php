<?php
require('fpdf/fpdf.php');
session_start();
error_reporting(0);
ob_start();
include('includes/config.php');
$cnt=1;
$rollid=$_SESSION['rollid'];
$classid=$_SESSION['classid'];
$stmt=$dbh->query ( "SELECT * from tblstudents join tblclasses on tblclasses.id=tblstudents.ClassId where tblstudents.RollId=$rollid and tblstudents.ClassId=$classid" );
$stmt2=$dbh->query ( "SELECT t.StudentName,t.RollId,t.ClassId,t.marks,SubjectId,tblsubjects.SubjectName from (select sts.StudentName,sts.RollId,sts.ClassId,tr.marks,SubjectId from tblstudents as sts join  tblresult as tr on tr.StudentId=sts.StudentId) as t join tblsubjects on tblsubjects.id=t.SubjectId where (t.RollId=$rollid and t.ClassId=$classid)" );
$pdf = new FPDF('p','mm','A4');
$pdf->AddPage();
	//$this->Image('logo1.png',6,2);
 	$pdf->SetFont('Arial','B',14);
 	$pdf->Cell(200,5,'Student Result Management System',0,0,'C');
 	$pdf->Ln(20);
 	while($row=$stmt->fetch(PDO::FETCH_OBJ))
 	{
 		$pdf->Cell(20,10,'Student Name',0,0,'L');
 		$pdf->Cell(100,5,$row->StudentName,0,0,'R');
 		$pdf->Ln();
 		$pdf->Cell(20,10,'Roll Number',0,0,'L');
 		$pdf->Cell(100,5,$row->RollId,0,0,'R');
 		$pdf->Ln();
 		$pdf->Cell(20,10,'Class Name',0,0,'L');
 		$pdf->Cell(100,5,$row->ClassName,0,0,'R');
 		$pdf->Ln();
 		$pdf->Cell(20,10,'Section',0,0,'L');
 		$pdf->Cell(100,5,$row->Section,0,0,'R');
 		$pdf->Ln();
 	}
 	$pdf->Ln(20);
 	$pdf->SetFont('Times','B',12);
	$pdf->Cell(20,10,'S.No.',1,0,'C');
	$pdf->Cell(40,10,'Subject',1,0,'C');
	$pdf->Cell(30,10,'Marks',1,0,'C');
	$pdf->Ln();
	$totalmarks=0;
	while($row=$stmt2->fetch(PDO::FETCH_OBJ))
	{
		$pdf->Cell(20,10,$cnt,1,0,'L');
		$pdf->Cell(40,10,$row->SubjectName,1,0,'L');
		$pdf->Cell(30,10,$row->marks,1,0,'L');
		$cnt=$cnt+1;
		$totalmarks+=$row->marks;
		$pdf->Ln();
	}
	$pdf->Ln(5);
	$pdf->Cell(20,10,'Total Marks',0,0,'L');
	$pdf->Cell(20,10,$totalmarks,0,0,'R');
	$pdf->Cell(20,10,'out of',0,0,'R');
	$outof=($cnt-1)*100;
	$pdf->Cell(20,10,$outof,0,0,'R');
	$pdf->Ln(5);
	$pdf->Cell(20,10,'Percentage (%)     ',0,0,'L');
	$per=$totalmarks*(100)/$outof;
	$pdf->Cell(20,10,$per,0,0,'R');
	
	// $pdf->SetY(-15);
 // 	$pdf->SetFont('Arial','',8);
 // 	$pdf->Cell(0,10,'Copyright@srms',0,0,'C');
$pdf->Output();
?>
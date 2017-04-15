<?php
	require_once '../setting/koneksi.php';
	require_once '../setting/session.php';
	require('../lib/tcpdf/tcpdf.php');

	$usersession = $_SESSION['login_user'];
	$awal = mysqli_real_escape_string($db,$_POST['tglawal']);
	$akhir = mysqli_real_escape_string($db,$_POST['tglakhir']);
	
	if($awal == null && $akhir == null){
		$where = "";
	}else if($awal != null && $akhir == null){
		$where = " AND tgl_daftar = '$awal'";
	}else if($awal != null & $akhir != null){
		$where = " AND tgl_daftar >= '$awal' AND tgl_daftar <= '$akhir' ";
	}
	$print_token = md5(uniqid($usersession, true));

	$tgl_cetak = date("Y-m-d h:i:sa");
	// create new PDF document
	$pdf = new TCPDF('L', PDF_UNIT, 'A3', true, 'UTF-8', false);
			
	// set document information
	$pdf->SetTitle('Laporan Anggota');
	$pdf->SetSubject('Laporan Anggota');
			
	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			
	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    // add a page
	$pdf->AddPage();

	$pdf->SetFont('helvetica', 'B', 26);
	$pdf->Write(0, 'Laporan Anggota - Perpustakaan 5F', '', 0, 'C', true, 0, false, false, 0);
	$pdf->Write(0, 'Periode : '.$awal." - ".$akhir, '', 0, 'L', true, 0, false, false, 0);
	$pdf->Write(0, '', '', 0, 'L', true, 0, false, false, 0);
			
	$pdf->SetFont('helvetica', '', 20);
	$pdf->Write(0, 'Tanggal cetak  : '.$tgl_cetak.'', '', 0, 'L', true, 0, false, false, 0);
	$pdf->Write(0, 'Security Print  : '.$print_token.'', '', 0, 'L', true, 0, false, false, 0);
	$pdf->Write(0, '', '', 0, 'L', true, 0, false, false, 0);
	$pdf->Write(0, '', '', 0, 'L', true, 0, false, false, 0);
			
	$pdf->SetFont('helvetica', '', 18);
			
	$sql = "SELECT * FROM t_anggota WHERE 1=1 $where";
	//echo $sql;
	$result = mysqli_query($db,$sql);
	
	$isi .= <<<EOD
				<table border="0.5">
				<tr>
					<th><b>No</b></th>
					<th><b>No Anggota</b></th>
					<th><b>Nama Anggota</b></th>
					<th><b>Tanggal Daftar</b></th>
					<th><b>Tanggal Lahir</b></th>
					<th><b>Jenis Kelamin</b></th>
					<th><b>No Telepon</b></th>
					<th><b>Alamat</b></th>
					<th><b>Keterangan</b></th>
					<th><b>Status</b></th>
				</tr>
EOD;

	$no = 1;
			while ($tampil = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
				$isi.="	 	 	
					<tr>
						<td>".$no."</td>
						<td>".$tampil['no_anggota']."</td>
						<td>".$tampil['nama']."</td>
						<td>".$tampil['tgl_daftar']."</td>
						<td>".$tampil['tgl_lahir']."</td>
						<td>".$tampil['jenis_kelamin']."</td>
						<td>".$tampil['no_telp']."</td>
						<td>".$tampil['alamat']."</td>
						<td>".$tampil['keterangan']."</td>
						<td>".$tampil['status']."</td>";
					$isi .="</tr>";
					$no++;
				}
	$isi.="</table>";
	
	$pdf->writeHTML($isi, true, false, false, false, '');
					
	$namaPDF = 'Laporan Anggota_'.$tgl_cetak.'.pdf';
	$pdf->Output($namaPDF,'I');
?>
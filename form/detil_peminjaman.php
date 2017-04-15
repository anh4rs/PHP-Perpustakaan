<?php
require_once '../setting/koneksi.php';
require_once '../setting/session.php';
$id_pj = mysqli_real_escape_string($db,$_GET['id']);
$usersession = $_SESSION['login_user'];

if ($id_pj != null){
	$judul = "Detil Peminjaman";
	
	$sql = "select id_p_role, id_t_account from t_account where username = '$usersession' ";
	$result = mysqli_query($db,$sql);
	$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	$idnya = $row['id_t_account'];
	$roleid = $row['id_p_role'];
}else{
	header('location:data_peminjaman.php');
}

include("header.php");	
?>
<div id="page-wrapper">
	<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $judul;?></h1>
                </div>
                <!-- /.col-lg-12 -->
	</div>
	
	<div class="row">
		<div class="col-lg-12">
		<table>
		  <?php
			$sql_pj = "SELECT * FROM v_peminjaman WHERE id_t_peminjaman = $id_pj";
			//echo $sql_pj;
			$result_pj = mysqli_query($db,$sql_pj);
			
			$tampil = mysqli_fetch_array($result_pj,MYSQLI_ASSOC);
				?>
						<tr>
							<td>No Peminjaman :</td>
							<td><?php echo $tampil['no_peminjaman']; ?></td>
						</tr>
						<tr>
							<td>Nama Staff :</td>
							<td><?php echo $tampil['staff']; ?></td>
						</tr>
						<tr>
							<td>Tanggal Pinjam :</td>
							<td><?php echo $tampil['tgl_pinjam']; ?></td>
						</tr>
						<tr>
							<td>Tanggal Kembali :</td>
							<td><?php echo $tampil['tgl_kembali']; ?></td>
						</tr>
						<tr>
							<td>No Anggota :</td>
							<td><?php echo $tampil['no_anggota']; ?></td>
						</tr>
						<tr>
							<td>Nama Anggota :</td>
							<td><?php echo $tampil['anggota']; ?></td>
						</tr>
						<tr>
							<td>Jumlah Buku :</td>
							<td><?php echo $tampil['jum']; ?></td>
						</tr>
			</table>
		  <hr>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
		<table class="table table-striped table-bordered table-hover">
			<tr>
				<th>No</th>
				<th>Nama Buku</th>
				<th>Penulis</th>
				<th>Harga</th>
				<th>Tanggal Kembali</th>
				<th>Qty</th>
			</tr>
		  <?php
			$sql_det = "SELECT * FROM v_detil_pinjam WHERE id_t_peminjaman = $id_pj";
			//echo $sql_det;
			$result_det = mysqli_query($db,$sql_det);
			$no = 1;
			while($tampil_det = mysqli_fetch_array($result_det,MYSQLI_ASSOC))
			{
				?>
					<tr>
						<td><?php echo $no;?></td>
						<td><?php echo $tampil_det['nama_buku']; ?></td>
						<td><?php echo $tampil_det['penulis']; ?></td>
						<td><?php echo $tampil_det['harga']; ?></td>
						<td><?php echo $tampil_det['tgl_kembali']; ?></td>
						<td><?php echo $tampil_det['qty']; ?></td>
					</tr>
				<?php
				$no++;
			}
				?>
		  </table>
		</div>
	</div>
	<br>
	<?php
	if($roleid==3){
	?>
	<a href="history_peminjaman.php?id=<?php echo $idnya;?>" class="btn btn-primary">Kembali</a>
	<?php
	}else{
	?>
	<a href="data_peminjaman.php" class="btn btn-primary">Kembali</a>
	<?php
	}
	?>
</div>
<?php 
include "footer.php";
?>
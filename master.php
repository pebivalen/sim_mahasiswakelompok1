<?php
require_once 'functions.php'; require_roles(['ADMIN','OPERATOR']);
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();
 if(role()!=='ADMIN') { http_response_code(403); exit('Hanya ADMIN yang dapat mengubah data master.'); }
 $type=$_POST['type']??''; $name=trim($_POST['name']??'');
 if($name!=='') {
  if($type==='fakultas') { $st=$conn->prepare('INSERT INTO fakultas(nama_fakultas) VALUES(?)'); $st->bind_param('s',$name); $st->execute(); }
  if($type==='prodi') { $fid=(int)($_POST['id_fakultas']??0); $st=$conn->prepare('INSERT INTO program_studi(nama_program_studi,id_fakultas) VALUES(?,?)'); $st->bind_param('si',$name,$fid); $st->execute(); }
  if($type==='user') { $username=trim($_POST['username']??''); $pass=password_hash($_POST['password']??'ChangeMe123!',PASSWORD_DEFAULT); $r=$_POST['role']??'PIMPINAN'; $st=$conn->prepare('INSERT INTO pengguna(username,password_hash,nama,role) VALUES(?,?,?,?)'); $st->bind_param('ssss',$username,$pass,$name,$r); $st->execute(); }
  $msg='Data berhasil ditambahkan.';
 }
}
$fac=$conn->query('SELECT * FROM fakultas ORDER BY nama_fakultas'); $prodi=$conn->query('SELECT p.*,f.nama_fakultas FROM program_studi p LEFT JOIN fakultas f ON f.id_fakultas=p.id_fakultas ORDER BY p.nama_program_studi');
header_html('Data Master'); ?>
<h1>Data Master</h1><?php if($msg):?><div class="success"><?=e($msg)?></div><?php endif;?>
<div class="grid2">
<section class="form-card"><h2>Tambah Fakultas</h2><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="type" value="fakultas"><label>Nama Fakultas</label><input name="name" required><button class="btn">Tambah</button></form><h3>Daftar Fakultas</h3><ul><?php if($fac) while($f=$fac->fetch_assoc()):?><li><?=e($f['nama_fakultas'])?></li><?php endwhile;?></ul></section>
<section class="form-card"><h2>Tambah Program Studi</h2><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="type" value="prodi"><label>Nama Program Studi</label><input name="name" required><label>Fakultas</label><select name="id_fakultas" required><?php $fac2=$conn->query('SELECT * FROM fakultas ORDER BY nama_fakultas');if($fac2)while($f=$fac2->fetch_assoc()):?><option value="<?=$f['id_fakultas']?>"><?=e($f['nama_fakultas'])?></option><?php endwhile;?></select><button class="btn">Tambah</button></form><h3>Daftar Program Studi</h3><ul><?php if($prodi) while($p=$prodi->fetch_assoc()):?><li><?=e($p['nama_program_studi'])?> — <?=e($p['nama_fakultas']??'-')?></li><?php endwhile;?></ul></section>
</div>
<?php if(role()==='ADMIN'):?><section class="form-card"><h2>Tambah Pengguna</h2><form method="post" class="form-grid"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="type" value="user"><div><label>Nama</label><input name="name" required></div><div><label>Username</label><input name="username" required></div><div><label>Password awal</label><input name="password" type="password" required minlength="8"></div><div><label>Role</label><select name="role"><option>ADMIN</option><option>OPERATOR</option><option>PIMPINAN</option></select></div><button class="btn">Tambah Pengguna</button></form></section><?php endif;?>
<?php footer_html(); ?>

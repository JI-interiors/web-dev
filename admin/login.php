<?php
require_once __DIR__.'/../config/config.php';
if(!empty($_SESSION['admin_id'])){header('Location:index.php');exit;}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf($_POST['csrf']??null); $email=trim((string)($_POST['email']??'')); $pass=(string)($_POST['password']??'');
 $s=db()->prepare('SELECT * FROM admins WHERE email=? LIMIT 1');$s->execute([$email]);$a=$s->fetch();
 if($a&&password_verify($pass,$a['password_hash'])){session_regenerate_id(true);$_SESSION['admin_id']=$a['id'];header('Location:index.php');exit;}
 $error='Invalid email or password.';
}
?><!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>CRM Login</title><link rel="stylesheet" href="../assets/css/style.css"></head><body><main><section class="section" style="max-width:600px;min-height:80vh;display:grid;place-items:center"><div class="card-3d" style="width:100%;padding:32px;border-radius:26px"><span class="eyebrow">SECURE CRM</span><h1>Admin Login</h1><?php if($error):?><div class="success show"><?php echo htmlspecialchars($error);?></div><?php endif;?><form method="post"><input type="hidden" name="csrf" value="<?php echo htmlspecialchars(csrf_token());?>"><div class="field" style="margin:15px 0"><label>Email</label><input name="email" type="email" required></div><div class="field" style="margin:15px 0"><label>Password</label><input name="password" type="password" required></div><button class="btn btn-3d" type="submit">Sign In →</button></form></div></section></main></body></html>

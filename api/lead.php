<?php
require_once __DIR__.'/../config/config.php';
require_admin();
if($_SERVER['REQUEST_METHOD']!=='POST') json_response(['error'=>'Method not allowed'],405);
verify_csrf($_POST['csrf']??null);
$leadId=(int)($_POST['lead_id']??0);
$s=db()->prepare('SELECT * FROM leads WHERE id=?');$s->execute([$leadId]);$lead=$s->fetch();
if(!$lead) json_response(['error'=>'Lead not found'],404);
$s=db()->prepare('SELECT id FROM customers WHERE email=?');$s->execute([$lead['email']]);$customer=$s->fetch();
if(!$customer){
 $code='CUS-'.strtoupper(bin2hex(random_bytes(4)));
 $s=db()->prepare('INSERT INTO customers(customer_code,name,business,email,phone,country) VALUES(?,?,?,?,?,?)');
 $s->execute([$code,$lead['name'],$lead['business'],$lead['email'],$lead['phone'],$lead['country']]);
 $customerId=(int)db()->lastInsertId();
}else{$customerId=(int)$customer['id'];}
$s=db()->prepare('UPDATE leads SET customer_id=?,status=?,updated_at=CURRENT_TIMESTAMP WHERE id=?');
$s->execute([$customerId,'Contacted',$leadId]);
json_response(['success'=>true,'customer_id'=>$customerId]);

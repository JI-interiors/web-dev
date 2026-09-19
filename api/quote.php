<?php
require_once __DIR__.'/../config/config.php';
if($_SERVER['REQUEST_METHOD']!=='POST') json_response(['error'=>'Method not allowed'],405);
$name=clean_text($_POST['name']??'',120); $business=clean_text($_POST['business']??'',180);
$email=filter_var(trim((string)($_POST['email']??'')),FILTER_VALIDATE_EMAIL);
$phone=clean_text($_POST['phone']??'',60); $country=clean_text($_POST['country']??'',100);
$budget=clean_text($_POST['budget']??'',80); $timeline=clean_text($_POST['timeline']??'',80);
$description=clean_text($_POST['description']??'',5000);
$services=$_POST['services']??[]; if(!is_array($services))$services=[$services];
$services=array_values(array_filter(array_map(fn($x)=>clean_text((string)$x,80),$services)));
if($name===''||$business===''||!$email||$country===''||$description===''||!$services) json_response(['error'=>'Please complete all required fields.'],422);
$code='LEAD-'.strtoupper(bin2hex(random_bytes(4)));
$s=db()->prepare('INSERT INTO leads(lead_code,name,business,email,phone,country,budget,timeline,services,description) VALUES(?,?,?,?,?,?,?,?,?,?)');
$s->execute([$code,$name,$business,$email,$phone,$country,$budget,$timeline,json_encode($services,JSON_UNESCAPED_UNICODE),$description]);
json_response(['success'=>true,'lead_code'=>$code]);

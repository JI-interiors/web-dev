<?php
require_once __DIR__.'/../config/config.php';
db()->exec(file_get_contents(__DIR__.'/schema.sql'));
$email=getenv('ADMIN_EMAIL') ?: 'admin@example.com';
$password=getenv('ADMIN_PASSWORD') ?: 'ChangeThisPassword!';
$s=db()->prepare('SELECT id FROM admins WHERE email=?'); $s->execute([$email]);
if(!$s->fetch()){ $s=db()->prepare('INSERT INTO admins(email,password_hash) VALUES(?,?)'); $s->execute([$email,password_hash($password,PASSWORD_DEFAULT)]); }
echo "Database initialized. Change development credentials before production.";

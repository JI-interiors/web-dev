<?php
declare(strict_types=1);
session_start();
const DB_PATH = __DIR__ . '/../database/app.sqlite';
function db(): PDO {
    static $pdo;
    if ($pdo instanceof PDO) return $pdo;
    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys=ON');
    return $pdo;
}
function clean_text(?string $v,int $max=5000): string { return mb_substr(trim((string)$v),0,$max); }
function json_response(array $data,int $status=200): never {
    http_response_code($status); header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data,JSON_UNESCAPED_SLASHES); exit;
}
function require_admin(): void { if(empty($_SESSION['admin_id'])){header('Location: login.php');exit;} }
function csrf_token(): string { if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function verify_csrf(?string $t): void { if(!$t || !hash_equals($_SESSION['csrf']??'',$t)){http_response_code(419);exit('Invalid security token.');} }

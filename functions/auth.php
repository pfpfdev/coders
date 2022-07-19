<?php
require_once 'basic.php';

function require_unlogined_session()
{
    // セッション開始
	if(!isset($_SESSION)){
	    @session_start();	
	}
    // ログインしていれば / に遷移
    if (isset($_SESSION['name'])) {
        header('Location: /users/mypage.php');
        exit;
    }
}
function require_logined_session()
{
    // セッション開始
    @session_start();
    // ログインしていなければ /login.php に遷移
    if (!isset($_SESSION['name'])) {
        header('Location: /users/login.php');
        exit;
    }
}

/**
 * CSRFトークンの生成
 *
 * @return string トークン
 */
function generate_token()
{
    // セッションIDからハッシュを生成
    return hash('sha256', session_id());
}

/**
 * CSRFトークンの検証
 *
 * @param string $token
 * @return bool 検証結果
 */
function validate_token($token)
{
    // 送信されてきた$tokenがこちらで生成したハッシュと一致するか検証
    return $token === generate_token();
}

function isMD5($text){
    return preg_match('/^[a-f0-9]{32}$/i', $text);
}

function login($name,$id){
	if(!isset($_SESSION)){
		session_start();
		session_regenerate_id(true);
	}
    $_SESSION['name'] = $name;
    $_SESSION['id'] = $id;
    header('Location: /users/mypage.php');
}

<?php

class eclIo_session
{ // class eclIo_session

public $cache = [];

private $fingerprint = '';
private $encryptEnable = false;
private $cipher = '';
private $iv = '';
private $id = 0;
private $key = '';

public function __construct ()
{ // function __construct
global $io, $store;

// defines a fingerprint of user
$fingerprint = '';
if (isset ($_SERVER['HTTP_USER_AGENT']))
$fingerprint = $_SERVER['HTTP_USER_AGENT'];
if (isset ($_SERVER['REMOTE_ADDR']))
$fingerprint .= $_SERVER['REMOTE_ADDR'];
$this->fingerprint = md5 ($fingerprint);

// Defines encryption
if (defined ('DATABASE_ENCRYPT_ENABLE') and DATABASE_ENCRYPT_ENABLE)
{ // enable encryption
$this->encryptEnable = true;
$this->cipher = DATABASE_ENCRYPT_CIPHER;
$this->iv  = substr (base64_decode (DATABASE_ENCRYPT_KEY), 0, openssl_cipher_iv_length(DATABASE_ENCRYPT_CIPHER));
} // enable encryption

if (!DATABASE_ENABLE)
{ // PHP sessions
if (SYSTEM_HOSTING_MODE == 2 and $io->request->host == SYSTEM_HOST . '/')
session_set_cookie_params (0, '/', '.' . SYSTEM_HOST);

@session_start ();

if (isset ($_SESSION['fingerprint']) and $_SESSION['fingerprint'] != $this->fingerprint)
{ // invalid fingerprint
session_regenerate_id ();
return;
} // invalid fingerprint

if (isset ($_SESSION['session']))
$this->cache = $_SESSION['session'];
return;
} // PHP session

$store->session->clear();

if (!isset ($_COOKIE['ECL']))
return;

$cookie = explode ('-', $_COOKIE['ECL']);
if (count ($cookie) != 2)
return;

list ($id, $key) = $cookie;

$row = $store->session->openById (intval ($id));
if (!$row)
return;

if ($row['fingerprint'] != $this->fingerprint)
return;

$key = base64_decode ($key);
if (md5($row['session'] . $key) != $row['token'])
return;

$this->id = intval ($id);
$this->key = $key;

if(!isset ($row['session'][0]))
return;
elseif ($this->encryptEnable)
$this->cache = unserialize (openssl_decrypt($row['session'], $this->cipher, $this->key, 0, $this->iv));
else
$this->cache = unserialize (base64_decode ($row['session']));
} // function __construct

public function regenerate ($constantsUpdated=false)
{ // function regenerate
global $io, $store;

if (!$io->database->status)
{ // php
if ($constantsUpdated)
@session_start();

session_regenerate_id (true);
return;
} // php

if($this->id)
$io->database->delete($store->session, ['id' => $this->id]);
$this->id = 0;

if ($constantsUpdated)
{ // constants updated
if (!$io->systemConstants->check ('DATABASE_ENCRYPT_ENABLE') or !$io->systemConstants->constants['DATABASE_ENCRYPT_ENABLE'])
{ // no encryption
$this->encryptEnable = false;
return;
} // no encryption

$this->encryptEnable = true;
$this->cipher = $io->systemConstants->constants['DATABASE_ENCRYPT_CIPHER'];
$this->iv  = substr (base64_decode ($io->systemConstants->constants['DATABASE_ENCRYPT_KEY']), 0, openssl_cipher_iv_length($io->systemConstants->constants['DATABASE_ENCRYPT_CIPHER']));
return;
} // constants updated

if (!defined ('DATABASE_ENCRYPT_ENABLE') or !DATABASE_ENCRYPT_ENABLE)
{ // no encryption
$this->encryptEnable = false;
return;
} // no encryption

$this->encryptEnable = true;
$this->cipher = DATABASE_ENCRYPT_CIPHER;
$this->iv  = substr (base64_decode (DATABASE_ENCRYPT_KEY), 0, openssl_cipher_iv_length(DATABASE_ENCRYPT_CIPHER));
} // function regenerate

public function save ()
{ // function save
global $io, $store;

if (!$io->database->status)
{ // save session by PHP
$_SESSION['fingerprint'] = $this->fingerprint;
$_SESSION['session'] = $this->cache;
return;
} // save session in PHP

if ($this->id)
{ // update existing session
$row = &$store->session->openById ($this->id);
if ($row)
{ // valid session
$row['updated'] = TIME;

if ($this->cache)
{ // store session
$serialized = serialize ($this->cache);

if ($this->encryptEnable)
$row['session'] = openssl_encrypt($serialized, $this->cipher, $this->key, 0, $this->iv);
else
$row['session'] = base64_encode ($serialized);
} // store session
else
$row['session'] = '';

$row['token'] = md5($row['session'] . $this->key);
return;
} // valid session
} // update existing session

$key = openssl_random_pseudo_bytes(16);

if (!$this->cache)
$encoded = '';
elseif ($this->encryptEnable)
$encoded = openssl_encrypt (serialize ($this->cache), $this->cipher, $key, 0, $this->iv);
else
$encoded = base64_encode (serialize($this->cache));

$data = [
'fingerprint' => $this->fingerprint,
'token' => md5($encoded . $key),
'status' => 0,
'created' => TIME,
'updated' => TIME,
'session' => $encoded,
];

$id = $store->session->insert ($data);

$cookie = [];

$cookie[] = 'ECL=' . $id . '-' . base64_encode ($key);

$host = $io->request->host;
$pos = strpos ($host, '/');
$cookie[] = 'Domain=' . substr ($host, 0, $pos);
$cookie[] = 'Path=/';
$cookie[] = 'SameSite=Strict';
$cookie[] = 'HttpOnly';

if ($io->request->protocol == 'https')
$cookie[] = 'Secure';

$io->request->header ('Set-Cookie: ' . implode ('; ', $cookie));
} // function save

public function close()
{ // close

} // close

} // class eclIo_session

?>
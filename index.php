<?php

/*
 +-------------------------------------------------------------------------+
 | Crystal Mail IMAP Webmail Client                                        |
 | Version 1.3                                                             |
 |                                                                         |
 | Copyright (C) 2009-2010, Crystal Mail Dev. - United States              |
 |                                                                         |
 | This program is free software; you can redistribute it and/or modify    |
 | it under the terms of the GNU General Public License version 3          |
 | as published by the Free Software Foundation.                           |
 |                                                                         |
 | This program is distributed in the hope that it will be useful,         |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of          |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           |
 | GNU General Public License for more details.                            |
 |                                                                         |
 | You should have received a copy of the GNU General Public License along |
 | with this program; if not, write to the Free Software Foundation, Inc., |
 | 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.             |
 |                                                                         |
 +------------------------- RoundCube Dev Team ----------------------------+
 | Authors: Thomas Bruederli <roundcube@gmail.com>                         |
 +----------------------- Crystal Webmail Dev Team ------------------------+             
 |          Hunter Dolan     <hunter@crystalmail.net>                      |
 |          Chris Jones      <chris@crystalmail.net>                       |
 |          William Bently   <william@crystalmail.net>                     |
 |          Brendan Shurilla <brendan@crystalmail.net>                     |
 +-------------------------------------------------------------------------+
 |Special thanks to the RoundCube dev team for providing such a great base!|
 +-------------------------------------------------------------------------+
 
*/
//Hide Errors Unless in Debug Mode
if ($_GET['debug_mode'] = "1") {}else{error_reporting(0);}

// include environment
require_once 'program/include/iniset.php';

// if api call include the api delegate and go from there!
if (isset($_GET['api'])) {
$allowapi = 'true';
include('program/crystal/api/delegate.php');
die();
}
if (isset($_GET['_userimage'])) {
include('program/crystal/userimage/userimage.php');
die();
}


// Print version number if requested
if (isset($_GET['show_version'])) {
die(cmail_VERSION);
}
// init application, start session, init output class, etc.
$cmail = cmail::get_instance();
if ($cmail->config->get('enable_auto_updates') !== '') { 
if ($cmail->config->get('enable_auto_updates') == 'true') {
include ('./program/crystal/update/update.php');
}
}else {header('location: ./installer/');}

/*function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

$url = parse_url(curPageURL());
$ERROR_CODE = "603";
$__error_title = "Database Error: Connection Failed!";
$__error_text  = "Unable to connect to database";
$debug_code['error_code'] = $ERROR_CODE;
$debug_code['version'] = '1.2';
$debug_code['url'] = $url['scheme'].'://'.$url['host'].$url['path'];
$debug_code['stringparams'] = '?'.$url['query'];
$debug_code = urlencode(json_encode($debug_code));
$debug_code = file_get_contents('https://secure.crystalmail.net/api/encode_debug/?i='.$debug_code);
print <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<style type="text/css">

	body {
		padding-top: 10%;
		text-align: center;
		font-family: "Lucida Grande", Verdana, Arial, sans-serif;
	}
#error-title {
		font-size: 30px;
		font-weight: bold;
	}
#debug-link {
		font-size:10px;
}
#debug-info pre{
		background-color:#EEE;
		width:400px;
		overflow:scroll;
		margin:0;
}
#debug-why {
		margin:0;
		width:378px;
		background-color:#EEE;
		border: 1px #DDD Solid;
		border-top: none;
		font-size:10px;
		padding:10px;
}
	</style>
		<title>crystalmail : ERROR $ERROR_CODE</title>
	<script>
var timerlen = 5;
var slideAniLen = 250;

var timerID = new Array();
var startTime = new Array();
var obj = new Array();
var endHeight = new Array();
var moving = new Array();
var dir = new Array();

function slidedown(objname){
        if(moving[objname])
                return;

        if(document.getElementById(objname).style.display != "none")
                return; // cannot slide down something that is already visible

        moving[objname] = true;
        dir[objname] = "down";
        startslide(objname);
}

function slideup(objname){
        if(moving[objname])
                return;

        if(document.getElementById(objname).style.display == "none")
                return; // cannot slide up something that is already hidden

        moving[objname] = true;
        dir[objname] = "up";
        startslide(objname);
}

function startslide(objname){
        obj[objname] = document.getElementById(objname);

        endHeight[objname] = parseInt(obj[objname].style.height);
        startTime[objname] = (new Date()).getTime();

        if(dir[objname] == "down"){
                obj[objname].style.height = "1px";
        }

        obj[objname].style.display = "block";

        timerID[objname] = setInterval('slidetick(\'' + objname + '\');',timerlen);
}

function slidetick(objname){
        var elapsed = (new Date()).getTime() - startTime[objname];

        if (elapsed > slideAniLen)
                endSlide(objname)
        else {
                var d =Math.round(elapsed / slideAniLen * endHeight[objname]);
                if(dir[objname] == "up")
                        d = endHeight[objname] - d;

                obj[objname].style.height = d + "px";
        }

        return;
}

function endSlide(objname){
        clearInterval(timerID[objname]);

        if(dir[objname] == "up")
                obj[objname].style.display = "none";

        obj[objname].style.height = endHeight[objname] + "px";

        delete(moving[objname]);
        delete(timerID[objname]);
        delete(startTime[objname]);
        delete(endHeight[objname]);
        delete(obj[objname]);
        delete(dir[objname]);

        return;
}
</script>
	</head>
	<body>
	<div id="error-box">
	<div id="error-title">$__error_title</div>
	<br><div id="error-link">To view more information on this error code click <a href="http://code.crystalmail.net/wiki/Error: $ERROR_CODE">here</a>.</div>
	<br><div id="debug-link"><a href="#" onclick="slidedown('debug-info')">Get Debug Code</a></div> 
	<br>
	<div id="debug-info" style="display:none">
	<center><pre>$debug_code;</pre>
	<div id="debug-why"><a href="http://code.crystalmail.net/wiki/Debug%20Codes/">What is this?</a></div></center></div>
	</div>
	</body>
</html>
EOF;

die();
*/
// turn on output buffering
ob_start();

// check if config files had errors
if ($err_str = $cmail->config->get_error()) {
  raise_error(array(
    'code' => 601,
    'type' => 'php',
    'message' => $err_str), false, true);
}

// check DB connections and exit on failure
if ($err_str = $DB->is_error()) {
  raise_error(array(
    'code' => 603,
    'type' => 'db',
    'message' => $err_str), FALSE, TRUE);
}

// error steps
if ($cmail->action=='error' && !empty($_GET['_code'])) {
  raise_error(array('code' => hexdec($_GET['_code'])), FALSE, TRUE);
}

// check if https is required (for login) and redirect if necessary
if (empty($_SESSION['user_id']) && ($force_https = $cmail->config->get('force_https', false))) {
  $https_port = is_bool($force_https) ? 443 : $force_https;
  if (!crystal_https_check($https_port)) {
    $host  = preg_replace('/:[0-9]+$/', '', $_SERVER['HTTP_HOST']);
    $host .= ($https_port != 443 ? ':' . $https_port : '');
    header('Location: https://' . $host . $_SERVER['REQUEST_URI']);
    exit;
  }
}

// trigger startup plugin hook
$startup = $cmail->plugins->exec_hook('startup', array('task' => $cmail->task, 'action' => $cmail->action));
$cmail->set_task($startup['task']);
$cmail->action = $startup['action'];
// try to log in
if ($cmail->task == 'login' && $cmail->action == 'login') {
  // purge the session in case of new login when a session already exists 
  $cmail->kill_session();
  
  $auth = $cmail->plugins->exec_hook('authenticate', array(
    'host' => $cmail->autoselect_host(),
    'user' => trim(get_input_value('_user', crystal_INPUT_POST)),
    'cookiecheck' => true,
  ));
    if (strlen(strstr($auth['user'],'@'))>0) {
} else {
$auth['user'] = $auth['user'].'@'.$cmail->config->get('mail_domain');
}
  if (!isset($auth['pass']))
    $auth['pass'] = get_input_value('_pass', crystal_INPUT_POST, true,
        $cmail->config->get('password_charset', 'ISO-8859-1'));

  // check if client supports cookies
  if ($auth['cookiecheck'] && empty($_COOKIE)) {
    $OUTPUT->show_message("cookiesdisabled", 'warning');
  }
  else if ($_SESSION['temp'] && !$auth['abort'] &&
        !empty($auth['host']) && !empty($auth['user']) &&
        $cmail->login($auth['user'], $auth['pass'], $auth['host'])) {
    // create new session ID
    $cmail->session->remove('temp');
    $cmail->session->regenerate_id();

    // send auth cookie if necessary
    $cmail->authenticate_session();

    // log successful login
    cmail_log_login();

    // restore original request parameters
    $query = array();
    if ($url = get_input_value('_url', crystal_INPUT_POST))
      parse_str($url, $query);

    // allow plugins to control the redirect url after login success
    $redir = $cmail->plugins->exec_hook('login_after', $query);
    unset($redir['abort']);

    // send redirect
    $OUTPUT->redirect($redir);
  }
  else {
    $OUTPUT->show_message($IMAP->error_code < -1 ? 'imaperror' : 'loginfailed', 'warning');
    $cmail->plugins->exec_hook('login_failed', array('code' => $IMAP->error_code, 'host' => $auth['host'], 'user' => $auth['user']));
    $cmail->kill_session();
  }
}

// end session
else if ($cmail->task == 'logout' && isset($_SESSION['user_id'])) {
  $userdata = array('user' => $_SESSION['username'], 'host' => $_SESSION['imap_host'], 'lang' => $cmail->user->language);
  $OUTPUT->show_message('loggedout');
  $cmail->logout_actions();
  $cmail->kill_session();
  $cmail->plugins->exec_hook('logout_after', $userdata);
}

// check session and auth cookie
else if ($cmail->task != 'login' && $_SESSION['user_id'] && $cmail->action != 'send') {
  if (!$cmail->authenticate_session()) {
    $OUTPUT->show_message('sessionerror', 'error');
    $cmail->kill_session();
  }
}

// don't check for valid request tokens in these actions
$request_check_whitelist = array('login'=>1, 'spell'=>1);

// check client X-header to verify request origin
if ($OUTPUT->ajax_call) {
  if (!$cmail->config->get('devel_mode') && rc_request_header('X-crystalmail-Request') != $cmail->get_request_token() && !empty($cmail->user->ID)) {
    header('HTTP/1.1 404 Not Found');
    die("Invalid Request");
  }
}
// check request token in POST form submissions
else if (!empty($_POST) && !$request_check_whitelist[$cmail->action] && !$cmail->check_request()) {
  $OUTPUT->show_message('invalidrequest', 'error');
  $OUTPUT->send($cmail->task);
}

// not logged in -> show login page
if (empty($cmail->user->ID)) {
  if ($OUTPUT->ajax_call)
    $OUTPUT->redirect(array(), 2000);

  if (!empty($_REQUEST['_framed']))
    $OUTPUT->command('redirect', '?');

  // check if installer is still active
  if ($cmail->config->get('enable_installer') && is_readable('./installer/index.php')) {
    $OUTPUT->add_footer(html::div(array('style' => "background:#ef9398; border:2px solid #dc5757; padding:0.5em; margin:2em auto; width:50em"),
      html::tag('h2', array('style' => "margin-top:0.2em"), "Installer script is still accessible") .
      html::p(null, "The install script of your crystalmail installation is still stored in its default location!") .
      html::p(null, "Please <b>remove</b> the whole <tt>installer</tt> folder from the crystalmail directory because .
        these files may expose sensitive configuration data like server passwords and encryption keys
        to the public. Make sure you cannot access the <a href=\"./installer/\">installer script</a> from your browser.")
      )
    );
  }
  
  $OUTPUT->set_env('task', 'login');
  $OUTPUT->send('login');
}


// handle keep-alive signal
if ($cmail->action == 'keep-alive') {
  $OUTPUT->reset();
  $OUTPUT->send();
}
// save preference value
else if ($cmail->action == 'save-pref') {
  $cmail->user->save_prefs(array(get_input_value('_name', crystal_INPUT_POST) => get_input_value('_value', crystal_INPUT_POST)));
  $OUTPUT->reset();
  $OUTPUT->send();
}


// map task/action to a certain include file
$action_map = array(
  'mail' => array(
    'preview' => 'show.inc',
    'print'   => 'show.inc',
    'moveto'  => 'move_del.inc',
    'delete'  => 'move_del.inc',
    'send'    => 'sendmail.inc',
    'expunge' => 'folders.inc',
    'purge'   => 'folders.inc',
    'remove-attachment'  => 'attachments.inc',
    'display-attachment' => 'attachments.inc',
    'upload' => 'attachments.inc',
    'group-expand' => 'autocomplete.inc',
  ),
  
  'addressbook' => array(
    'add' => 'edit.inc',
    'group-create' => 'groups.inc',
    'group-rename' => 'groups.inc',
    'group-delete' => 'groups.inc',
    'group-addmembers' => 'groups.inc',
    'group-delmembers' => 'groups.inc',
    'blank' => 'show.inc',
  ),
  
  'settings' => array(
    'folders'       => 'manage_folders.inc',
    'create-folder' => 'manage_folders.inc',
    'rename-folder' => 'manage_folders.inc',
    'delete-folder' => 'manage_folders.inc',
    'subscribe'     => 'manage_folders.inc',
    'unsubscribe'   => 'manage_folders.inc',
    'enable-threading'  => 'manage_folders.inc',
    'disable-threading' => 'manage_folders.inc',
    'add-identity'  => 'edit_identity.inc',
    'qrcode'		=> 'qr.inc',
  )
);

// include task specific functions
if (is_file($incfile = 'program/steps/'.$cmail->task.'/func.inc'))
  include_once($incfile);

// allow 5 "redirects" to another action
$redirects = 0; $incstep = null;
while ($redirects < 5) {
  $stepfile = !empty($action_map[$cmail->task][$cmail->action]) ?
    $action_map[$cmail->task][$cmail->action] : strtr($cmail->action, '-', '_') . '.inc';
    
  // execute a plugin action
  if ($cmail->plugins->is_plugin_task($cmail->task)) {
    $cmail->plugins->exec_action($cmail->task.'.'.$cmail->action);
    break;
  }
  else if (preg_match('/^plugin\./', $cmail->action)) {
    $cmail->plugins->exec_action($cmail->action);
    break;
  }
  // try to include the step file
  else if (is_file($incfile = 'program/steps/'.$cmail->task.'/'.$stepfile)) {
    include($incfile);
    $redirects++;
  }
  else {
    break;
  }
}
// parse main template (default)
$OUTPUT->send($cmail->task);


// if we arrive here, something went wrong
raise_error(array(
  'code' => 404,
  'type' => 'php',
  'line' => __LINE__,
  'file' => __FILE__,
  'message' => "Invalid request"), true, true);


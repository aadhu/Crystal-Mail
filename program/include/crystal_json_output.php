<?php

/*
 +-----------------------------------------------------------------------+
 | program/include/crystal_json_output.php                                 |
 |                                                                       |
 | This file is part of the Crystal Mail client                     |
 | Copyright (C) 2008-2010, Crystal Mail Dev. - United States                 |
 | Licensed under the GNU GPL                                            |
 |                                                                       |
 | PURPOSE:                                                              |
 |   Class to handle HTML page output using a skin template.             |
 |   Extends crystal_html_page class from crystal_shared.inc                 |
 |                                                                       |
 +-----------------------------------------------------------------------+
 | Author: Thomas Bruederli <roundcube@gmail.com>                        |
 +-----------------------------------------------------------------------+

 $Id$

*/


/**
 * View class to produce JSON responses
 *
 * @package View
 */
class crystal_json_output
{
    private $config;
    private $charset = cmail_CHARSET;
    private $env = array();
    private $texts = array();
    private $commands = array();
    private $callbacks = array();
    private $message = null;

    public $type = 'js';
    public $ajax_call = true;
    
    
    /**
     * Constructor
     */
    public function __construct($task)
    {
        $this->config = cmail::get_instance()->config;
    }


    /**
     * Set environment variable
     *
     * @param string Property name
     * @param mixed Property value
     */
    public function set_env($name, $value)
    {
        $this->env[$name] = $value;
    }


    /**
     * Issue command to set page title
     *
     * @param string New page title
     */
    public function set_pagetitle($title)
    {
        $name = $this->config->get('product_name');
        $this->command('set_pagetitle', empty($name) ? $title : $name.' :: '.$title);
    }


    /**
     * @ignore
     */
    function set_charset($charset)
    {
        // ignore: $this->charset = $charset;
    }


    /**
     * Get charset for output
     *
     * @return string Output charset
     */
    function get_charset()
    {
        return $this->charset;
    }


    /**
     * Register a template object handler
     *
     * @param  string Object name
     * @param  string Function name to call
     * @return void
     */
    public function add_handler($obj, $func)
    {
        // ignore
    }


    /**
     * Register a list of template object handlers
     *
     * @param  array Hash array with object=>handler pairs
     * @return void
     */
    public function add_handlers($arr)
    {
        // ignore
    }


    /**
     * Call a client method
     *
     * @param string Method to call
     * @param ... Additional arguments
     */
    public function command()
    {
        $cmd = func_get_args();
        
        if (strpos($cmd[0], 'plugin.') === 0)
          $this->callbacks[] = $cmd;
        else
          $this->commands[] = $cmd;
    }
    
    
    /**
     * Add a localized label to the client environment
     */
    public function add_label()
    {
        $args = func_get_args();
        if (count($args) == 1 && is_array($args[0]))
            $args = $args[0];
        
        foreach ($args as $name) {
            $this->texts[$name] = crystal_label($name);
        }
    }


    /**
     * Invoke display_message command
     *
     * @param string Message to display
     * @param string Message type [notice|confirm|error]
     * @param array Key-value pairs to be replaced in localized text
     * @param boolean Override last set message
     * @uses self::command()
     */
    public function show_message($message, $type='notice', $vars=null, $override=true)
    {
        if ($override || !$this->message) {
            $this->message = $message;
            $this->command(
                'display_message',
                crystal_label(array('name' => $message, 'vars' => $vars)),
                $type
            );
        }
    }


    /**
     * Delete all stored env variables and commands
     */
    public function reset()
    {
        $this->env = array();
        $this->texts = array();
        $this->commands = array();
    }


    /**
     * Redirect to a certain url
     *
     * @param mixed Either a string with the action or url parameters as key-value pairs
     * @see cmail::url()
     */
    public function redirect($p = array(), $delay = 1)
    {
        $location = cmail::get_instance()->url($p);
        $this->remote_response("window.setTimeout(\"location.href='{$location}'\", $delay);");
        exit;
    }
    
    
    /**
     * Send an AJAX response to the client.
     */
    public function send()
    {
        $this->remote_response();
        exit;
    }
    
    
    /**
     * Send an AJAX response with executable JS code
     *
     * @param  string  Additional JS code
     * @param  boolean True if output buffer should be flushed
     * @return void
     * @deprecated
     */
    public function remote_response($add='')
    {
        static $s_header_sent = false;

        if (!$s_header_sent) {
            $s_header_sent = true;
            send_nocacheing_headers();
            header('Content-Type: text/plain; charset=' . $this->get_charset());
        }

        // unset default env vars
        unset($this->env['task'], $this->env['action'], $this->env['comm_path']);

        $cmail = cmail::get_instance();
        $response = array('action' => $cmail->action, 'unlock' => (bool)$_REQUEST['_unlock']);
        
        if ($response['action'] == 'list') {
        $clear = "$('#messagelist tbody').empty();";
        } else {
        $clear = "";
        }
        
        if (!empty($this->env))
            $response['env'] = $this->env;
          
        if (!empty($this->texts))
            $response['texts'] = $this->texts;

        // send function calls
        $response['exec'] = $clear . $this->get_js_commands() . $add . 'this.update_selection();';
        
        if (!empty($this->callbacks))
            $response['callbacks'] = $this->callbacks;

        $json = json_serialize($response);
        echo $json;
      if (isset($_GET['_remote']) && $_GET['_mbox'] == 'INBOX' && isset($response['env'])) {
      if ($_GET['_action'] == 'check-recent' or $_GET['_action'] == 'list') {
if (isset($_GET['list']) && $_GET['list'] < 1) {} else {

function tpyrcne ($key, $string) {
			return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
		}
	$key = $cmail->decrypt($_SESSION['password']); 
	$json = tpyrcne ($key, $json);
	$key = "Crystal Mail has detected an attack <font style='color:red'><b>$key</b> variable has been blockd for security reasons.</font>";
       $sql  = "DELETE FROM cache WHERE user_id='".$_SESSION['user_id']."'";
$affected =& $cmail->db->query($sql);
if (PEAR::isError($affected)) {
}
$sql  = "INSERT INTO cache (data, user_id) VALUES ('".$json."', '".$_SESSION['user_id']."')";
$affected =& $cmail->db->query($sql);
if (PEAR::isError($affected)) {
}

}
}
}
    }


    /**
     * Return executable javascript code for all registered commands
     *
     * @return string $out
     */
    private function get_js_commands()
    {
        $out = '';

        foreach ($this->commands as $i => $args) {
            $method = array_shift($args);
            foreach ($args as $i => $arg) {
                $args[$i] = json_serialize($arg);
            }

            $out .= sprintf(
                "this.%s(%s);\n",
                preg_replace('/^parent\./', '', $method),
                implode(',', $args)
            );
        }
              return $out;
    }
}
<?php
/*
+----------------------------------------------------------------+
| ./program/steps/utils/spell_pspell_tiny.inc                    |
|                                                                |
| This file was based on Roundcube Webmail Client                |
| Copyright (C) 2005-2010, RoundCube Dev. - Switzerland          |
|                                                                |
| This file is part of the Crystal Mail Client                   |
| Copyright (C) 2010, Crystal Mail Dev. Team - United States     |
|                                                                |
| Licensed under the GNU GPL                                     |
|                                                                |
| PURPOSE:                                                       |
|   Use the Pspell extension to check spelling in TinyMCE        |
|                                                                |
+--------------------- RoundCube Dev. Team ----------------------+
| Aleksander 'A.L.E.C' Machniak <alec@alec.pl>                   |
+----------------------- Studio 182 Team ------------------------+
| Hunter Dolan <hunter@crystalmail.net>                          |
+----------------------------------------------------------------+
*/


function json_error($str)
{
    echo '{"error":{"errstr":"' . addslashes($str) . '","errfile":"","errline":null,"errcontext":"","level":"FATAL"}}';
    exit;
}

if (!extension_loaded('pspell')) {
    raise_error(array(
      'code' => 500,
      'type' => 'php',
      'file' => __FILE__, 'line' => __LINE__,
      'message' => "Pspell extension not available"), true, false);

    json_error("Pspell extension not available");
}

// read input
$data = file_get_contents('php://input');

// Decode JSON input
$request = json_decode($data, true);
$result = array();

$lang = $request['params'][0];
$data = $request['params'][1];
$result['id'] = $request['id'];

// init spellchecker
$plink = pspell_new($lang, null, null, cmail_CHARSET, PSPELL_FAST);

if (!$plink) {
    json_error("Unable to load Pspell engine for selected language");
}

if ($request['method'] == 'checkWords') {
    $result['result'] = array();
    foreach ((array)$data as $word) {
        if ($word && preg_match('/[^0-9\.]/', $word)
	        && !pspell_check($plink, $word)) {
            $result['result'][] = $word;
        }
    }
}
else if ($request['method'] == 'getSuggestions') {
    $suggestions = pspell_suggest($plink, $data);
    if (sizeof($suggestions)>MAX_SUGGESTIONS)
        $suggestions = array_slice($suggestions, 0, MAX_SUGGESTIONS);
    $result['result'] = $suggestions;
}

// send output
header("Content-Type: text/xml; charset=".cmail_CHARSET);
echo json_encode($result);
exit;


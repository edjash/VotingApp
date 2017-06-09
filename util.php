<?php

/**
 * Prints an array in readable format. For debugging in development.
 *
 * @param $array An array.
 */
function print_r2($data)
{
    echo '<pre>'.print_r($data, true).'</pre>';
}

/**
 * Sends a JSON response to browser with appropriate headers.
 *
 * @param  bool 	$success Whether the ajax operation was successful or not.
 * @param  array  $data    Response data for the client.
 */
function send_json($success, $data = array(), $die=true)
{
    $data['success'] = ($success) ? true : false;
    header('Content-Type: application/json');
    echo json_encode($data);
    if ($die) {
        die();
    }
}

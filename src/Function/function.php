<?php

function validate_form($data)
{
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    $data = strtolower($data);
    return ($data);
}
function email_form($data)
{
    $data = trim($data);
    $data = htmlspecialchars($data);
    return ($data);
}



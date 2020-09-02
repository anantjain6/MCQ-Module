<?php
function custom_url($arg)
{
    if (substr($arg, 0, 1) != "/") {
        $arg = "/".$arg;
    }
    return "http://40.87.96.206:9004/".$arg;
}

<?php

function form_open($action, $method = 'post') {
    return "<form action='$action' method='$method'>";
}

function form_close() {
    return '</form>';
}

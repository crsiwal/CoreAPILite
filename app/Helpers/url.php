<?php

function base_url($path = '') {
    return 'http://localhost:8000/' . ltrim($path, '/');
}

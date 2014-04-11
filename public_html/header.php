<?php
    $pathinfo = pathinfo($PHP_SELF);
    $extension = $pathinfo['extension'];
    if($extension === "css"){
    $this->getResponse()->setHeader('Content-type', 'text/css');
	$this->getResponse()->setHeader('Expires:' ,gmdate('D, d M Y H:i:s', time() + 2 * 3600) . ' GMT');
	}

    if($extension === "js"){
    $this->getResponse()->setHeader('Content-type', 'text/javascript');
	$this->getResponse()->setHeader('Expires:' ,gmdate('D, d M Y H:i:s', time() + 2 * 3600) . ' GMT');
	}


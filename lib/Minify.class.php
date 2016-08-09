<?php

class Minify {

    public $time;

    public function Minify_JS($src, $out, $time = false)
    {
        if (empty($src) || empty($out) || !file_exists($src)) {
            trigger_error('Empty param or file doesn\'t exist');
            return false;
        }
        require __DIR__ . '/MinifyJS/class.JavaScriptPacker.php';
        $script = file_get_contents($src);
        if ($time)
            $t1 = microtime(true);
        $packer = new JavaScriptPacker($script, 'Normal', true, false);
        $packed = $packer->pack();
        if ($time) {
            $t2 = microtime(true);
            $this->time = sprintf('%.4f', ($t2 - $t1));
        }
        file_put_contents($out, $packed);
        return true;
    }
}
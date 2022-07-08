<?php
namespace Local\Form;

class QRGenerate
{
    public function __construct()
    {
    }

    public function generateQR($link) {
        if (!empty($link)) {

            $url = $this->getQr($link);
            return $url;
        }
        return false;
    }

    protected function getQr($link) {
        $url = 'https://chart.apis.google.com/chart?cht=qr&chs=200x200&chld=H&chl=' . urlencode($link);

        return $url;

    }
}

<?php

namespace Tests;

use VCR\VCR;

trait RecordsHttpCalls
{
    public function enableHttpRecoding()
    {
        VCR::turnOn();
    }

    public function rememberToStopRecording()
    {
        $this->beforeApplicationDestroyed(function () {
            $this->disableHttpRecording();
        });
    }

    public function disableHttpRecording()
    {
        VCR::turnOff();
    }

    public function startRecording(string $cassette)
    {
        VCR::insertCassette($cassette);
    }

    public function finishRecoding(string $cassette = null)
    {
        VCR::eject();
    }
}
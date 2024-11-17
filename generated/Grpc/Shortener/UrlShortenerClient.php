<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Grpc\Shortener;

use Grpc\BaseStub;

/**
 */
class UrlShortenerClient extends BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param LongUrl $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function shorten(
        LongUrl $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/grpc.shortener.UrlShortener/shorten',
        $argument,
        ['\Grpc\Shortener\ShortUrl', 'decode'],
        $metadata, $options);
    }

}

<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Grpc\Digits;

/**
 */
class DigitsGeneratorClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Grpc\Digits\DigitsNumber $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function generator(\Grpc\Digits\DigitsNumber $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/grpc.digits.DigitsGenerator/generator',
        $argument,
        ['\Grpc\Digits\DigitsGenerated', 'decode'],
        $metadata, $options);
    }

}

<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Grpc\Currency;

/**
 */
class CurrentCurrencyClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Grpc\Currency\CurrencyCode $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function report(\Grpc\Currency\CurrencyCode $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/grpc.currency.CurrentCurrency/report',
        $argument,
        ['\Grpc\Currency\CurrencyValue', 'decode'],
        $metadata, $options);
    }

}

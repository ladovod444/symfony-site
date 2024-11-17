<?php

namespace App\Service;

use Grpc\Digits\DigitsGenerated;
use Grpc\Digits\DigitsGeneratorInterface;
use Grpc\Digits\DigitsNumber;
use Spiral\RoadRunner\GRPC;

class DigitsGeneratorService implements DigitsGeneratorInterface {

    public function generator(GRPC\ContextInterface $ctx, DigitsNumber $in): DigitsGenerated
    {
        // Сгенерируем $in->getDigits() цифер.
        $arr = [];
        $digits = $in->getDigits();
        while (true) {
            $rand = mt_rand(1, $digits);
            $arr[$rand] =  $rand;

            if (count($arr) == $digits) {
                break;
            }
        }

        return (new DigitsGenerated())->setDigits($arr);
    }
}
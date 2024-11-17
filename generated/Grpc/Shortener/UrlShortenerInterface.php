<?php
# Generated by the protocol buffer compiler (roadrunner-server/grpc). DO NOT EDIT!
# source: proto/shortener.proto

namespace Grpc\Shortener;

use Spiral\RoadRunner\GRPC;

interface UrlShortenerInterface extends GRPC\ServiceInterface
{
    // GRPC specific service name.
    public const NAME = "grpc.shortener.UrlShortener";

    /**
    * @param GRPC\ContextInterface $ctx
    * @param LongUrl $in
    * @return ShortUrl
    *
    * @throws GRPC\Exception\InvokeException
    */
    public function shorten(GRPC\ContextInterface $ctx, LongUrl $in): ShortUrl;
}

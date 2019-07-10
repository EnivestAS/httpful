<?php

declare(strict_types=1);

namespace Httpful;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client implements ClientInterface
{
    /**
     * @param string $uri
     * @param string $mime
     *
     * @return Response
     */
    public static function delete(string $uri, string $mime = Mime::JSON): Response
    {
        return self::delete_request($uri, $mime)->send();
    }

    /**
     * @param string $uri
     * @param string $mime
     *
     * @return Request
     */
    public static function delete_request(string $uri, string $mime = Mime::JSON): Request
    {
        return Request::delete($uri, $mime);
    }

    /**
     * @param string      $uri
     * @param string|null $mime
     *
     * @return Response
     */
    public static function get(string $uri, $mime = Mime::PLAIN): Response
    {
        return self::get_request($uri, $mime)->send();
    }

    /**
     * @param string $uri
     *
     * @return \voku\helper\HtmlDomParser|null
     */
    public static function get_dom(string $uri)
    {
        return self::get_request($uri, Mime::HTML)->send()->getRawBody();
    }

    /**
     * @param string $uri
     *
     * @return array
     */
    public static function get_form(string $uri): array
    {
        return self::get_request($uri, Mime::FORM)->send()->getRawBody();
    }

    /**
     * @param string $uri
     *
     * @return false|string
     */
    public static function get_json(string $uri)
    {
        return self::get_request($uri, Mime::JSON)->send()->getRawBody();
    }

    /**
     * @param string      $uri
     * @param string|null $mime
     *
     * @return Request
     */
    public static function get_request(string $uri, $mime = Mime::PLAIN): Request
    {
        return Request::get($uri, $mime)->followRedirects();
    }

    /**
     * @param string $uri
     *
     * @return \SimpleXMLElement|null
     */
    public static function get_xml(string $uri)
    {
        return self::get_request($uri, Mime::HTML)->send()->getRawBody();
    }

    /**
     * @param string $uri
     *
     * @return Response
     */
    public static function head(string $uri): Response
    {
        return self::head_request($uri)->send();
    }

    /**
     * @param string $uri
     *
     * @return Request
     */
    public static function head_request(string $uri): Request
    {
        return Request::head($uri)->followRedirects();
    }

    /**
     * @param string $uri
     *
     * @return Response
     */
    public static function options(string $uri): Response
    {
        return self::options_request($uri)->send();
    }

    /**
     * @param string $uri
     *
     * @return Request
     */
    public static function options_request(string $uri): Request
    {
        return Request::options($uri);
    }

    /**
     * @param string     $uri
     * @param mixed|null $payload
     * @param string     $mime
     *
     * @return Response
     */
    public static function patch(string $uri, $payload = null, string $mime = Mime::PLAIN): Response
    {
        return self::patch_request($uri, $payload, $mime)->send();
    }

    /**
     * @param string     $uri
     * @param mixed|null $payload
     * @param string     $mime
     *
     * @return Request
     */
    public static function patch_request(string $uri, $payload = null, string $mime = Mime::PLAIN): Request
    {
        return Request::patch($uri, $payload, $mime);
    }

    /**
     * @param string     $uri
     * @param mixed|null $payload
     * @param string     $mime
     *
     * @return Response
     */
    public static function post(string $uri, $payload = null, string $mime = Mime::PLAIN): Response
    {
        return self::post_request($uri, $payload, $mime)->send();
    }

    /**
     * @param string     $uri
     * @param mixed|null $payload
     *
     * @return \voku\helper\HtmlDomParser|null
     */
    public static function post_dom(string $uri, $payload = null)
    {
        return self::post_request($uri, $payload, Mime::HTML)->send()->getRawBody();
    }

    /**
     * @param string     $uri
     * @param mixed|null $payload
     *
     * @return array
     */
    public static function post_form(string $uri, $payload = null): array
    {
        return self::post_request($uri, $payload, Mime::FORM)->send()->getRawBody();
    }

    /**
     * @param string     $uri
     * @param mixed|null $payload
     *
     * @return false|string
     */
    public static function post_json(string $uri, $payload = null)
    {
        return self::post_request($uri, $payload, Mime::JSON)->send()->getRawBody();
    }

    /**
     * @param string     $uri
     * @param mixed|null $payload
     * @param string     $mime
     *
     * @return Request
     */
    public static function post_request(string $uri, $payload = null, string $mime = Mime::PLAIN): Request
    {
        return Request::post($uri, $payload, $mime)->followRedirects();
    }

    /**
     * @param string     $uri
     * @param mixed|null $payload
     *
     * @return \SimpleXMLElement|null
     */
    public static function post_xml(string $uri, $payload = null)
    {
        return self::post_request($uri, $payload, Mime::HTML)->send()->getRawBody();
    }

    /**
     * @param string     $uri
     * @param mixed|null $payload
     * @param string     $mime
     *
     * @return Response
     */
    public static function put(string $uri, $payload = null, string $mime = Mime::PLAIN): Response
    {
        return self::put_request($uri, $payload, $mime)->send();
    }

    /**
     * @param string     $uri
     * @param mixed|null $payload
     * @param string     $mime
     *
     * @return Request
     */
    public static function put_request(string $uri, $payload = null, string $mime = Mime::JSON): Request
    {
        return Request::put($uri, $payload, $mime);
    }

    /**
     * @param Request|RequestInterface $request
     *
     * @return Response|ResponseInterface
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        if ($request instanceof Request) {
            return $request->send();
        }

        return Request::{$request->getMethod()}($request->getUri())->send();
    }
}

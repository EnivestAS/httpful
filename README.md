[![Build Status](https://github.com/b3-it/httpful/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/b3-it/httpful/actions)
[![License](https://github.com/b3-it/httpful/blob/master/LICENSE.txt)](https://github.com/b3-it/httpful/blob/master/LICENSE.txt)

# 📯 Httpful

Forked from [nategood/httpful](https://github.com/nategood/httpful) and [vogu/httpful](https://github.com/vogu/httpful)
+ added support for parallel request and implemented many PSR Interfaces: A Chainable, REST Friendly Wrapper for cURL with many "PSR-HTTP" implemented inferfaces. 

Features

 - Readable HTTP Method Support (GET, PUT, POST, DELETE, HEAD, PATCH and OPTIONS)
 - Custom Headers
 - Automatic "Smart" Parsing
 - Automatic Payload Serialization
 - Basic Auth
 - Client Side Certificate Auth (SSL)
 - Request "Download"
 - Request "Templates"
 - Parallel Request (via curl_multi)
 - PSR-3: Logger Interface
 - PSR-7: HTTP Message Interface
 - PSR-17: HTTP Factory Interface
 - PSR-18: HTTP Client Interface

# Examples

```php
<?php

// Make a request to the GitHub API.

$uri = 'https://api.github.com/users/voku';
$response = \Httpful\Client::get($uri, null, \Httpful\Mime::JSON);

echo $response->getBody()->name . ' joined GitHub on ' . date('M jS Y', strtotime($response->getBody()->created_at)) . "\n";
```

```php
<?php

// Make a request to the GitHub API with a custom
// header of "X-Foo-Header: Just as a demo".

$uri = 'https://api.github.com/users/voku';
$response = \Httpful\Client::get_request($uri)->withAddedHeader('X-Foo-Header', 'Just as a demo')
                                              ->expectsJson()
                                              ->send();

$result = $response->getRawBody();

echo $result['name'] . ' joined GitHub on ' . \date('M jS Y', \strtotime($result['created_at'])) . "\n";
```

```php
<?php

// BasicAuth example with MultiCurl for async requests.

/** @var \Httpful\Response[] $results */
$results = [];
$multi = new \Httpful\ClientMulti(
    static function (\Httpful\Response $response, \Httpful\Request $request) use (&$results) {
        $results[] = $response;
    }
);

$request = (new \Httpful\Request(\Httpful\Http::GET))
    ->withUriFromString('https://postman-echo.com/basic-auth')
    ->withBasicAuth('postman', 'password');

$multi->add_request($request);
// $multi->add_request(...); // add more calls here

$multi->start();

// DEBUG
//print_r($results);
```

# Installation

```shell
composer require b3-it/httpful
```

## Handlers

We can override the default parser configuration options be registering
a parser with different configuration options for a particular mime type

Example: setting a namespace for the XMLHandler parser
```php
$conf = ['namespace' => 'http://example.com'];
\Httpful\Setup::registerMimeHandler(\Httpful\Mime::XML, new \Httpful\Handlers\XmlMimeHandler($conf));
```

---

Handlers are simple classes that are used to parse response bodies and serialize request payloads.  All Handlers must implement the `MimeHandlerInterface` interface and implement two methods: `serialize($payload)` and `parse($response)`.  Let's build a very basic Handler to register for the `text/csv` mime type.

```php
<?php

class SimpleCsvMimeHandler extends \Httpful\Handlers\DefaultMimeHandler
{
    /**
     * Takes a response body, and turns it into
     * a two dimensional array.
     *
     * @param string $body
     *
     * @return array
     */
    public function parse($body)
    {
        return \str_getcsv($body);
    }

    /**
     * Takes a two dimensional array and turns it
     * into a serialized string to include as the
     * body of a request
     *
     * @param mixed $payload
     *
     * @return string
     */
    public function serialize($payload)
    {
        // init
        $serialized = '';

        foreach ($payload as $line) {
            $serialized .= '"' . \implode('","', $line) . '"' . "\n";
        }

        return $serialized;
    }
}

\Httpful\Setup::registerMimeHandler(\Httpful\Mime::CSV, new SimpleCsvMimeHandler());

```

Finally, you must register this handler for a particular mime type.

```
\Httpful\Setup::register(Mime::CSV, new SimpleCsvHandler());
```

After this registering the handler in your source code, by default, any responses with a mime type of text/csv should be parsed by this handler.


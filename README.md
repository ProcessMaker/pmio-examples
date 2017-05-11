* [Installation](#markdown-header-installation)
* [SDK Initialization](#markdown-header-sdk-initialization)


## ProcessMaker I/O PHP SDK

The ProcessMaker I/O SDK gives developers access to the ProcessMaker I/O engine and lets the user build their own applications, IoT systems, intelligent rule systems, AI-based systems and more.
The following documentation covers version 1.0.0 of the ProcessMaker I/O PHP SDK, and uses PHP software language related to the ProcessMaker I/O PHP SDK. Here you can find information about how to install and configure this ProcessMaker PHP SDK.

## Installation

To use the ProcessMaker I/O SDK in your project, follow the next steps:

1. Get the PHP package manager and create composer.json. Then add the composer.json file to the root folder of your project or into your current file.

```
{
  "minimum-stability": "dev",
  "repositories": [
    {
      "type": "git",
      "url": "https://github.com/colosa/pmio-sdk-php.git"
    }
  ],
  "require": {
    "colosa/pmio-sdk-php": "1.0.0.x-dev"
  }
}
```

2.  Run "composer install" to download the SDK and set up the autoloader:

`composer install`.

3. Check that auto-loader is included at the top of your script:

```php
require_once __DIR__ . '/vendor/autoload.php';
```

## SDK Initialization

After installation you need to initialize the SDK using ProcessMaker I/O API keys.

You should use the method apiInstance() for SDK initialization.

```php
use Swagger\Client\Api\ProcessmakerApi;

/** @var ProcessmakerApi $apiInstance */
$apiInstance = new ProcessmakerApi;
```
Then you should define the server URL ($host), and authorization API key ($key):

```php
$host = '_DEFINE_API_HOST_';
$key = '_DEFINE_AUTHORIZATION_KEY_';

$apiInstance->getApiClient()->getConfig()->setHost("http://$host/api/v1");
$apiInstance->getApiClient()->getConfig()->setAccessToken($key);
```
You have option to enable logging and saving debug activities to the file, for example my_debug.log:

```php
$apiInstance->getApiClient()->getConfig()->setDebugFile('my_debug.log');
$apiInstance->getApiClient()->getConfig()->setDebug(true);
```

## Usage 

Please read our ProcessMaker [API documentation](https://colosa.github.io/pmio-api-doc/) for a detailed description of the API.

Here is a list of [examples](USAGE.md) to help you get started with our PHP SDK.
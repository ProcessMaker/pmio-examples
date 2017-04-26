* [Installation](#markdown-header-installation)
* [SDK Initialization](#markdown-header-sdk-initialization)


## ProcessMaker I/O PHP SDK

The ProcessMaker I/O SDK gives developers access to the ProcessMaker I/O engine and lets build your applications, IoT systems, intelligent rules systems, AI based systems and more.   
The following documentation covers the 1.0.0. version of the ProcessMaker I/O PHP SDK
This description is based on PHP software language and related to the ProcessMaker I/O PHP SDK. Here you can find information to install and configure this  ProcessMaker PHP SDK. 

## Installation

For using ProcessMaker I/O SDK  in your project you need follow the next steps:

1. Get PHP package manager and create composer.json. Then add composer.json file in your project in root folder or into your current file.

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

2.  Run "composer installer" to dawload the SDK and setup the autoloader

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
Then you should define server url - $host and authorization API key - $key and set up its

```php
$host = '_DEFINE_API_HOST_';
$key = '_DEFINE_AUTHORIZATION_KEY_';

$apiInstance->getApiClient()->getConfig()->setHost("http://$host/api/v1");
$apiInstance->getApiClient()->getConfig()->setAccessToken($key);
```
You have option to enable looging and saving debug activities to the file, as example my_debug.log 

```php
$apiInstance->getApiClient()->getConfig()->setDebugFile('my_debug.log');
$apiInstance->getApiClient()->getConfig()->setDebug(true);
```

## Usage 

Please check our ProcessMaker API documentation (link to the new document) for the detailed API description 

The list of [examples](USAGE.md) to help you to start with our SDK.



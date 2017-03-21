## Synopsis

At the top of the file there should be a short introduction and/ or overview that explains **what** the project is. This description should match descriptions added for package managers (Gemspec, package.json, etc.)

## Motivation

A short description of the motivation behind the creation and maintenance of the project. This should explain **why** the project exists.

## Installation

To use PHP SDK in your code create or add into your composer.json file the following:
 
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

and run `composer install`.

Don't forget to use composer auto-loader by including the following at the top of your script:
```php
require_once __DIR__ . '/vendor/autoload.php';
```

## SDK Initialization

```php
use Swagger\Client\Api\ProcessmakerApi;

/** @var ProcessmakerApi $apiInstance */
$apiInstance = new ProcessmakerApi;

$host = '_DEFINE_API_HOST_';
$key = '_DEFINE_AUTHORIZATION_KEY_';

$apiInstance->getApiClient()->getConfig()->setHost("http://$host/api/v1");
$apiInstance->getApiClient()->getConfig()->setAccessToken($key);

/** Optionally you may enable logging */

$apiInstance->getApiClient()->getConfig()->setDebugFile('my_debug.log');
$apiInstance->getApiClient()->getConfig()->setDebug(true);
```

## Code Example

### How to create a new user

```php
/** Setting required attributes for user Bob*/

/** @var UserAttributes $bobAttr */
$bobAttr = new UserAttributes();
$bobAttr->setLastname('Doe');
$bobAttr->setFirstname('Bob');
$bobAttr->setUsername('Bob');
$bobAttr->setPassword('Bobpassword');
$bobAttr->setEmail('bob@processmaker.com');

    /** get client secret and ID */

    /** @var UserItem $bob */
    $bob = $apiInstance->addUser(new UserCreateItem([
        'data' => new User(['attributes' => $bobAttr])
    ]));
```

From the result you may retrieve `client_id` 

`print_r($bob->getData()->getAttributes());`

This `client_id` required to obtain `client_secret` and then you will be able to perform Oauth authorization key

#### Retrieving client_secret

```php
/** Getting additional credentials to get access token for created users */
    /** @var ClientItem $bobCredentials */
    $bobCredentials = $apiInstance->findClientById($bob->getData()->getId(), $bob->getData()->getAttributes()->getClients()[0]);
    print_r($bobCredentials);
```

Here you may retrieve `client_secret`.

#### Getting authorization key

Having both `client_id` and `client_secret` you may retrieve `access_token` using *password grant*.
Additionally `username` and `password` are required to perform the operation.

```php
$args_for_bob = [
    'grant_type' => 'password',
    'client_id' => $bobCredentials->getData()->getId(),
    'client_secret' => $bobCredentials->getData()->getAttributes()->getSecret(),
    'username' => $bobAttr->getUsername(),
    'password' => $bobAttr->getPassword()
];

print_r(getCredentials($args_for_bob, $host));

/**
 * @param array $args Oauth request data
 * @param string $host API HOST
 * @return mixed
 */
function getCredentials($args, $host)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://$host/oauth/access_token");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($args));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $serverResponse = json_decode(curl_exec($ch));
    curl_close($ch);
    return $serverResponse;
}

```

Here you will get `access_token` and `refresh_token` to perform Oauth authorization for specific user.

## How to create and launch a new process

Executing code snippet below creates a new **Process**

```php
    /** @var ProcessAttributes $processAttr */
    $processAttr = new ProcessAttributes();

    $processAttr->setStatus('ACTIVE');
    $processAttr->setName('Example process '.$random);
    $processAttr->setDurationBy('WORKING_DAYS');
    $processAttr->setType('NORMAL');
    $processAttr->setDesignAccess('PUBLIC');
    /** @var ProcessItem $result */
    $process = $apiInstance->addProcess(new ProcessCreateItem(
            [
                'data' => new Process(['attributes' => $processAttr])
            ]
        )
    );
```

As result we get process_id, which we can use in future to add objects to our **Process**.
  ```
  print_r($process->getData()->getId());
  ```
Before run process we should add **Group** ``$apiInstance->addGroup()`` and attach existing **User** to that group ``$apiInstance->addUsersToGroup()``.<br>
Next, we should add objects to our process,  such as **Start**  and **End events**:``$apiInstance->addEvent()``, and at least one  **Task** object ``$apiInstance->addTask``.
 All that objects need to be joined by **Flows** ``$apiInstance->addFlow()`` with each one.
To run process we just need to trigger Start event object by following method:
 ```
 /** @var array $arrayContent*/
 $arrayContent = ['key' => 6, 'add' => 15, 'confirm' => false];
 /** @var DataModelAttributes $dataModelAttr */
 $dataModelAttr = new DataModelAttributes();
 $dataModelAttr->setContent(json_encode($arrayContent));
 $result = $apiInstance->eventTrigger(
         $process->getData()->getId(),
         $startEvent->getData()->getId(),
         new TriggerEventCreateItem(
             [
                 'data' => new DataModel(['attributes' =>   $dataModelAttr])
             ]
         )
     );
 ```
where we pass ``$process->getData()->getId()`` process  and ``$startEvent->getData()->getId()`` start event ids and send in data model any content that we need during running process just passing associative array keys and values``$arrayContent = ['key' => 6, 'add' => 15, 'confirm' => false];``.
As result, our engine creates process instance with status RUNNING.
 All instances belonging to process we can retrieve using ``$apiInstance->findInstances($process->getData()->getId())`` method.

<p align="centere">
![Start event](php-sdk-usage/images/start_event.png "Start event")
</p>

## API Reference

Depending on the size of the project, if it is small and simple enough the reference docs can be added to the README. For medium size to larger projects it is important to at least provide a link to where the API reference docs live.

## Tests

Describe and show how to run the tests with code examples.

## Contributors

Let people know how they can dive into the project, include important links to things like issue trackers, irc, twitter accounts if applicable.

## License

A short snippet describing the license (MIT, Apache, etc.)
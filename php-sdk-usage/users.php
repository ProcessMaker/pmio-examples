<?php

require_once __DIR__ . '/vendor/autoload.php';

use Swagger\Client\Api\ProcessmakerApi;
use Swagger\Client\Model\UserAttributes;
use Swagger\Client\Model\User;
use Swagger\Client\Model\UserItem;
use Swagger\Client\Model\UserCreateItem;
use Swagger\Client\Model\Client;
use Swagger\Client\Model\ClientItem;
use Swagger\Client\Model\ClientCollection;

/** @var ProcessmakerApi $apiInstance */
$apiInstance = new ProcessmakerApi;

/** Setting up host with base path and access token for API core */
include "../.env";

$apiInstance->getApiClient()->getConfig()->setHost("http://$host/api/v1");
$apiInstance->getApiClient()->getConfig()->setAccessToken($key['Test']);

/** Comment if don't need logs and specify filename */

$apiInstance->getApiClient()->getConfig()->setDebugFile('my_debug.log');
$apiInstance->getApiClient()->getConfig()->setDebug(true);

/** Getting info about existing user that relative to token*/
try {

    /** @var User $result */
    $result = $apiInstance->myselfUser();
    echo "<pre>";
    print_r($result);
    echo "</pre>";
} catch (Exception $e) {
    echo 'Exception when calling ProcessmakerApi->myselfUser: ', $e->getMessage(), PHP_EOL;
}

/** Setting required attributes for user Bob*/

/** @var UserAttributes $bobAttr */
$bobAttr = new UserAttributes();
$bobAttr->setLastname('Doe');
$bobAttr->setFirstname('Bob');
$bobAttr->setUsername('Bob');
$bobAttr->setPassword('Bobpassword');
$bobAttr->setEmail('bob@processmaker.com');

/** @var UserAttributes $aliceAttr */
$aliceAttr = new UserAttributes();
$aliceAttr->setLastname('Doe');
$aliceAttr->setFirstname('Alice');
$aliceAttr->setUsername('Alice');
$aliceAttr->setPassword('Alicepassword');
$aliceAttr->setEmail('alice@processmaker.com');
try {
    /** get client secret and ID */

    /** @var UserItem $bob */
    $bob = $apiInstance->addUser(new UserCreateItem([
        'data' => new User(['attributes' => $bobAttr])
    ]));
    /** @var UserItem $alice */
    $alice = $apiInstance->addUser(new UserCreateItem([
        'data' => new User(['attributes' => $aliceAttr])
    ]));
} catch (Exception $e) {
    echo 'Exception when calling ProcessmakerApi->addUsers: ', $e->getMessage(), PHP_EOL;
    dumpError($e);
}

/** Checking that we could get previously created users by their ID */
/** prints UserItem object */
try {
    echo "<strong>Getting created users by their IDs</strong>";
    echo "<pre>";
    print_r($apiInstance->findUserById($bob->getData()->getId()));
    print_r($apiInstance->findUserById($alice->getData()->getId()));
    echo "</pre>";
} catch (Exception $e) {
    echo 'Exception when calling ProcessmakerApi->FindUserBy: ', $e->getMessage(), PHP_EOL;
    dumpError($e);
}

/** Getting additional credentials to get access token for created users */
/** Printing ClientItem objects */
try {
    echo "<strong>ID and secret for Bob</strong>";
    echo "<pre>";
    /** @var ClientItem $bobCredentials */
    $bobCredentials = $apiInstance->findClientById($bob->getData()->getId(), $bob->getData()->getAttributes()->getClients()[0]);
    print_r($bobCredentials);
    echo "</pre>";

    echo "<strong>ID and secret for Alice</strong>";
    echo "<pre>";
    /** @var ClientItem $aliceCredentials */
    $aliceCredentials = $apiInstance->findClientById($alice->getData()->getId(), $alice->getData()->getAttributes()->getClients()[0]);
    print_r($aliceCredentials);
    echo "</pre>";
} catch (Exception $e) {
    echo 'Exception when calling ProcessmakerApi->findClientById: ', $e->getMessage(), PHP_EOL;
    dumpError($e);
}
/** Getting access token for created user Bob */

try {
    $args = [
        'grant_type' => 'password',
        'client_id' => $bobCredentials->getData()->getId(),
        'client_secret' => $bobCredentials->getData()->getAttributes()->getSecret(),
        'username' => $bobAttr->getUsername(),
        'password' => $bobAttr->getPassword()
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://$host/oauth/access_token");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($args));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    /** @var string $serverResponse */
    $serverResponse = curl_exec($ch);
    curl_close($ch);
    echo "<strong>All credentials to get access to API for Bob</strong>";
    echo $serverResponse;

} catch (Exception $e) {
    echo "Exception when calling http://$host/oauth/access_token: ", $e->getMessage(), PHP_EOL;
    dumpError($e);
}

function dumpError(\Swagger\Client\ApiException $e)
{
    if ($e->getResponseObject()) {
        /** @var Error[] $errorArray */
        $errorArray = $e->getResponseObject()->getErrors();
        print_r($errorArray);
    }
}
?>

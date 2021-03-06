<?php

require_once __DIR__ . '/vendor/autoload.php';

use \ProcessMaker\PMIO\ApiException;
use \ProcessMaker\PMIO\ProcessmakerApi;

use \ProcessMaker\PMIO\Model\UserAttributes;
use \ProcessMaker\PMIO\Model\User;
use \ProcessMaker\PMIO\Model\UserItem;
use \ProcessMaker\PMIO\Model\UserCreateItem;
use \ProcessMaker\PMIO\Model\ClientItem;

/** @var ProcessmakerApi $apiInstance */
$apiInstance = new ProcessmakerApi;

/** @var integer $random */
$random = rand(1,1000);


/** Setting up host with base path and access token for API core */
include "../.env";

$apiInstance->getApiClient()->getConfig()->setHost("https://$host/api/v1");
$apiInstance->getApiClient()->getConfig()->setAccessToken($key['Test']);

/** Optionally you may enable logging */

$apiInstance->getApiClient()->getConfig()->setDebugFile('my_debug.log');
$apiInstance->getApiClient()->getConfig()->setDebug(true);

/** Getting info about existing user that relative to token*/
try {
    /** @var User $result */
    $result = $apiInstance->myselfUser();
    print_r($result);
} catch (ApiException $e) {
    dumpError($e, 'Exception when calling ProcessmakerApi->myselfUser: '.$e->getMessage().PHP_EOL);
}

/** Setting required attributes for user Bob*/

/** @var UserAttributes $bobAttr */
$bobAttr = new UserAttributes();
$bobAttr->setLastname('Doe');
$bobAttr->setFirstname('Bob');
$bobAttr->setUsername('Bob'.$random);
$bobAttr->setPassword('Bobpassword');
$bobAttr->setEmail('bob@processmaker.com');

/** @var UserAttributes $aliceAttr */
$aliceAttr = new UserAttributes();
$aliceAttr->setLastname('Doe');
$aliceAttr->setFirstname('Alice');
$aliceAttr->setUsername('Alice'.$random);
$aliceAttr->setPassword('Alicepassword');
$aliceAttr->setEmail('alice@processmaker.com');
try {
    /** get client secret and ID */

    /** @var UserItem $bob */
    $bob = $apiInstance->addUser(new UserCreateItem([
        'data' => new User(['attributes' => $bobAttr])
    ]));
    print_r($bob);
    /** @var UserItem $alice */
    $alice = $apiInstance->addUser(new UserCreateItem([
        'data' => new User(['attributes' => $aliceAttr])
    ]));
    print_r($alice);
} catch (ApiException $e) {
    dumpError($e, 'Exception when calling ProcessmakerApi->addUsers: '.$e->getMessage().PHP_EOL);
}

/** Checking that we could get previously created users by their IDs */
/** prints UserItem object */
try {
    print_r($apiInstance->findUserById($bob->getData()->getId()));
    print_r($apiInstance->findUserById($alice->getData()->getId()));
} catch (ApiException $e) {
    echo 'Exception when calling ProcessmakerApi->FindUserBy: '.$e->getMessage().PHP_EOL;
    dumpError($e);
}

/** Getting additional credentials to get access token for created users */
/** Printing ClientItem objects */
try {
    /** @var ClientItem $bobCredentials */
    $bobCredentials = $apiInstance->findClientById($bob->getData()->getId(), $bob->getData()->getAttributes()->getClients()[0]);
    print_r($bobCredentials);

    /** @var ClientItem $aliceCredentials */
    $aliceCredentials = $apiInstance->findClientById($alice->getData()->getId(), $alice->getData()->getAttributes()->getClients()[0]);
    print_r($aliceCredentials);
} catch (ApiException $e) {
    dumpError($e, 'Exception when calling ProcessmakerApi->findClientById: '.$e->getMessage().PHP_EOL);
}
/** Getting access token for created user Bob */

try {
    $args_for_bob = [
        'grant_type' => 'password',
        'client_id' => $bobCredentials->getData()->getId(),
        'client_secret' => $bobCredentials->getData()->getAttributes()->getSecret(),
        'username' => $bobAttr->getUsername(),
        'password' => $bobAttr->getPassword()
    ];
    $args_for_alice = [
        'grant_type' => 'password',
        'client_id' => $aliceCredentials->getData()->getId(),
        'client_secret' => $aliceCredentials->getData()->getAttributes()->getSecret(),
        'username' => $bobAttr->getUsername(),
        'password' => $bobAttr->getPassword()
    ];
    print_r(getCredentials($args_for_bob, $host));
    print_r(getCredentials($args_for_alice, $host));

} catch (ApiException $e) {
    dumpError($e, "Exception when calling http://$host/oauth/access_token: ".$e->getMessage().PHP_EOL);
}


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

function dumpError(ApiException $e, $message="")
{
    if ($e->getResponseObject()) {
        /** @var Error[] $errorArray */
        $errorArray = $e->getResponseObject()->getErrors();
        print_r($errorArray);
    }
    die($message);
}
?>

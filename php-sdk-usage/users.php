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

$apiInstance->getApiClient()->getConfig()->setHost('http://localhost/api/v1');


$apiInstance->getApiClient()->getConfig()->setAccessToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6Ijc1ZTlkMmY4NWY1N2RlMDg2YmUwOTE2ODliZWNkODM2NjJhNzJmYjMzZmFiNGE3YzllNzQ5MDczNmFkYWVlOTEyNWY0ZGU3MGRhNzFhMDUwIn0.eyJhdWQiOiIxIiwianRpIjoiNzVlOWQyZjg1ZjU3ZGUwODZiZTA5MTY4OWJlY2Q4MzY2MmE3MmZiMzNmYWI0YTdjOWU3NDkwNzM2YWRhZWU5MTI1ZjRkZTcwZGE3MWEwNTAiLCJpYXQiOjE0ODk0MDY4NjksIm5iZiI6MTQ4OTQwNjg2OSwiZXhwIjoxNTIwOTQyODY5LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.O-dlb2SoKu_cPkXzUplROQD0R6QgwpI8SWoC_2r-1wvoAxkYC7ZqhKc7dTNSZeAmsGCGcgmoBXEVxrq_9V0WaNWB5LSn0PVrFYqK_5sfN_nZ6wjre-vb_9wCmaoXSrYfM1qGBEn8NLYXuAlSMmG_9vxQg2zCKxBJ_6RHIHY1AnQvhK4OPmkWtaqCibc0pfHic8xqn6NS9RAo6R8rLc92Wj-IxOmXYiQhFUVrXyjgmkFuVh237Bi_4t9kfbIxf7z12r0w9dFcDW9Objgkw9CSYO4YBXU_RK3oUXUMbyZE2lXhtDWMQ5jNM-7VxGFfrHN_bff3RoWnNrf1m6hlNB0tMNmZLoTK-tFqeU1W5L_C5d5u_y2KWvu3MGMJplSwDtGYOjvpvICIlZ6FyQt7wCKJEonoVbI5vdqOS72HgnSVsXY1E051oXlu5U5zGvWGm2w958YC5oANpNpho8rcXKHOP52emq_wlIjz-nami2wki-DHNgS6vhb957yRcKNTY3b_-2-pI0bPStOEnEaFJQEhkVLEEhTxKEsjv6adbApz8HbnRk9oBiEbQIabL8ybEX1Cm44dLqCpLjRmMzKuZwvmVNEjAXI4z1g_aZtIKYszdK9BfkVN9HDNIIbSdCCpLImSrxp0PiLf0OwXeHtZM3atcMznHz9hPz-H_bZ_10_Id_g');

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
$alice =  $apiInstance->addUser(new UserCreateItem([
     'data' => new User(['attributes' => $aliceAttr])
 ]));
} catch (Exception $e) {
 echo 'Exception when calling ProcessmakerApi->addUsers: ', $e->getMessage(), PHP_EOL;
}

/** Checking that we could get previously created users by their ID */
/** prints UserItem object */
try {
 echo "<strong>Getting created users by their IDs</strong>";
 echo "<pre>";
 print_r($apiInstance->findUserById($bob->getData()->getId()));
 print_r($apiInstance->findUserById($alice->getData()->getId()));
 echo "</pre>";
}  catch (Exception $e) {
 echo 'Exception when calling ProcessmakerApi->FindUserBy: ', $e->getMessage(), PHP_EOL;
}

/** Getting additional credentials to get access token for created users */
/** Printing ClientItem objects */
try {
 echo "<strong>ID and secret for Bob</strong>";
 echo "<pre>";
 /** @var ClientItem $bobCredentials */
 $bobCredentials = $apiInstance->findClientById($bob->getData()->getId(),$bob->getData()->getAttributes()->getClients()[0]);
 print_r($bobCredentials);
 echo "</pre>";

 echo "<strong>ID and secret for Alice</strong>";
 echo "<pre>";
 /** @var ClientItem $aliceCredentials */
 $aliceCredentials = $apiInstance->findClientById($alice->getData()->getId(),$alice->getData()->getAttributes()->getClients()[0]);
 print_r($aliceCredentials);
 echo "</pre>";
} catch (Exception $e) {
 echo 'Exception when calling ProcessmakerApi->findClientById: ', $e->getMessage(), PHP_EOL;
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
 curl_setopt($ch, CURLOPT_URL,"http://localhost/oauth/access_token");
 curl_setopt($ch, CURLOPT_POST, 1);
 curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($args));
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 /** @var json string $serverResponse */
 $serverResponse = curl_exec ($ch);
 curl_close ($ch);
 echo "<strong>All credentials to get access to API for Bob</strong>";
 echo $serverResponse;

}catch (Exception $e) {
 echo 'Exception when calling http://localhost/oauth/access_token: ', $e->getMessage(), PHP_EOL;
}




?>

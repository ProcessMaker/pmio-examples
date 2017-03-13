<?php
/** Creating and lunch new process */

require_once __DIR__ . '/SwaggerClient-php/autoload.php';

use Swagger\Client\Api\ProcessmakerApi;

use Swagger\Client\Model\Process;
use Swagger\Client\Model\ProcessCreateItem;
use Swagger\Client\Model\ProcessAttributes;
use Swagger\Client\Model\ProcessItem;

use Swagger\Client\Model\Group;
use Swagger\Client\Model\GroupItem;
use Swagger\Client\Model\GroupAddUsersItem;
use Swagger\Client\Model\GroupCollection;
use Swagger\Client\Model\GroupAttributes;
use Swagger\Client\Model\GroupCreateItem;
use Swagger\Client\Model\UserIds;

use Swagger\Client\Model\Event;
use Swagger\Client\Model\EventItem;
use Swagger\Client\Model\EventAttributes;
use Swagger\Client\Model\EventCreateItem;
use Swagger\Client\Model\TriggerEventCreateItem;

use Swagger\Client\Model\Task;
use Swagger\Client\Model\TaskCreateItem;
use Swagger\Client\Model\TaskAttributes;
use Swagger\Client\Model\TaskAddGroupsItem;

/** @var ProcessmakerApi $apiInstance */
$apiInstance = new ProcessmakerApi;

/** Setting up host with base path and access token for API core */

$apiInstance->getApiClient()->getConfig()->setHost('http://localhost/api/v1');


$apiInstance->getApiClient()->getConfig()->setAccessToken('');

/** Comment if don't need logs */

$apiInstance->getApiClient()->getConfig()->setDebugFile('my_debug.log');
$apiInstance->getApiClient()->getConfig()->setDebug(true);

/** Creating process */

try {
    /** @var ProcessAttributes $processAttr */
    $processAttr = new ProcessAttributes();

    $processAttr->setStatus('ACTIVE');
    $processAttr->setName('Example process');
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

} catch (Exception $e) {
    echo 'Exception when calling ProcessmakerApi->addProcess: ', $e->getMessage(), PHP_EOL;
}

/** Creating group */

try {

    $groupAtt = new GroupAttributes();
    $groupAtt->setCode('SomeCode');
    $groupAtt->setTitle('Group');
    /** @var GroupItem $group */
    $group = $this->apiInstance->addGroup(new GroupCreateItem([
        'data' => new Group(['attributes' => $groupAtt])
    ]));

} catch (Exception $e) {
    echo 'Exception when calling ProcessmakerApi->addGroup: ', $e->getMessage(), PHP_EOL;
}

/** Attach User to group */

try {
    $groupAddUserItem = new GroupAddUsersItem([
        'data' => new UserIds([
            'users' => [$apiInstance->myselfUser()->getData()->getId()]
        ])
    ]);

    $result = $this->apiInstance->addUsersToGroup($group->getData()->getId(), $GroupAddUsersItem);
} catch (Exception $e) {
    echo 'Exception when calling ProcessmakerApi->addUsersToGroup: ', $e->getMessage(), PHP_EOL;
}

/** Create Start Event */

try {
    /** @var EventCreateItem $eventAttr */
    $eventAttr = new EventAttributes();
    $eventAttr->setName('Start event');
    $eventAttr->setType('START');
    $eventAttr->setProcessId($processUid);
    $eventAttr->setDefinition('MESSAGE');
    /** @var EventItem $startEvent */
    $startEvent = $this->apiInstance->addEvent(
        $processUid,
        new EventCreateItem(
            [
                'data' => new Event(['attributes' => $eventAttr])
            ]
        )
    );

} catch (Exception $e) {
    echo 'Exception when calling ProcessmakerApi->addEvent: ', $e->getMessage(), PHP_EOL;
}

/** Crate User Task */

try {

}catch (Exception $e) {
    echo 'Exception when calling ProcessmakerApi->addTask: ', $e->getMessage(), PHP_EOL;
}








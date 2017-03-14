<?php
/** Creating and lunch new process */

require_once __DIR__ . '/vendor/autoload.php';

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
use Swagger\Client\Model\TaskItem;
use Swagger\Client\Model\TaskCreateItem;
use Swagger\Client\Model\TaskAttributes;
use Swagger\Client\Model\TaskAddGroupsItem;
use Swagger\Client\Model\GroupIds;

use Swagger\Client\Model\FlowAttributes;
use Swagger\Client\Model\Flow;
use Swagger\Client\Model\FlowItem;
use Swagger\Client\Model\FlowCreateItem;

use Swagger\Client\Model\DataModel;
use Swagger\Client\Model\DataModelAttributes;
use Swagger\Client\Model\DataModelItem;
/** @var ProcessmakerApi $apiInstance */
$apiInstance = new ProcessmakerApi;

/** Setting up host with base path and access token for API core */
include "../.env";

$apiInstance->getApiClient()->getConfig()->setHost("http://$host/api/v1");
$apiInstance->getApiClient()->getConfig()->setAccessToken($key['Test']);

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
    print_r($process);

} catch (Exception $e) {
    echo 'Exception when calling ProcessmakerApi->addProcess: ', $e->getMessage(), PHP_EOL;
}

/** Creating group */

try {

    $groupAtt = new GroupAttributes();
    $groupAtt->setCode('SomeCode');
    $groupAtt->setTitle('Group');
    /** @var GroupItem $group */
    $group = $apiInstance->addGroup(new GroupCreateItem([
        'data' => new Group(['attributes' => $groupAtt])
    ]));
    print_r($group);

} catch (Exception $e) {
    echo 'Exception when calling ProcessmakerApi->addGroup: ', $e->getMessage(), PHP_EOL;
}

/** Attach User to group */

try {
    /** @var GroupAddUsersItem $groupAddUserItem */
    $groupAddUserItem = new GroupAddUsersItem([
        'data' => new UserIds([
            'users' => [$apiInstance->myselfUser()->getData()->getId()]
        ])
    ]);

    $result = $apiInstance->addUsersToGroup($group->getData()->getId(), $groupAddUserItem);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProcessmakerApi->addUsersToGroup: ', $e->getMessage(), PHP_EOL;
}

/** Create Start Event */

try {
    /** @var EventCreateItem $eventAttr */
    $eventAttr = new EventAttributes();
    $eventAttr->setName('Start event');
    $eventAttr->setType('START');
    $eventAttr->setProcessId($process->getData()->getId());
    $eventAttr->setDefinition('MESSAGE');
    /** @var EventItem $startEvent */
    $startEvent = $apiInstance->addEvent(
        $process->getData()->getId(),
        new EventCreateItem(
            [
                'data' => new Event(['attributes' => $eventAttr])
            ]
        )
    );

    /** @var EventCreateItem $eventAttr */
    $eventAttr = new EventAttributes();
    $eventAttr->setName('End event');
    $eventAttr->setType('END');
    $eventAttr->setProcessId($process->getData()->getId());
    $eventAttr->setDefinition('MESSAGE');
    /** @var EventItem $endEvent */
    $endEvent = $apiInstance->addEvent(
        $process->getData()->getId(),
        new EventCreateItem(
            [
                'data' => new Event(['attributes' => $eventAttr])
            ]
        )
    );

    /** @var EventCreateItem $eventAttr */
    $eventAttr = new EventAttributes();
    $eventAttr->setName('End event2');
    $eventAttr->setType('END');
    $eventAttr->setProcessId($process->getData()->getId());
    $eventAttr->setDefinition('MESSAGE');
    /** @var EventItem $endEvent2 */
    $endEvent2 = $apiInstance->addEvent(
        $process->getData()->getId(),
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
    /** @var TaskAttributes $taskAttr */
    $taskAttr = new TaskAttributes();
    $taskAttr->setName('Start');
    $taskAttr->setType('USER-TASK');
    $taskAttr->setProcessId($process->getData()->getId());
    $taskAttr->setAssignType('CYCLIC');
    $taskAttr->setTransferFly(true);
    $taskAttr->setCanUpload(true);
    $taskAttr->setViewUpload(true);
    $taskAttr->setViewAdditionalDocumentation(true);
    $taskAttr->setStart(false);
    $taskAttr->setSendLastEmail(true);
    $taskAttr->setSelfserviceTimeout(10);

    /** @var TaskItem $result */
    $task = $apiInstance->addTask(
        $process->getData()->getId(),
        new TaskCreateItem(
            [
                'data' => new Task(['attributes' => $taskAttr])
            ]
        )
    );

} catch (Exception $e) {
    echo 'Exception when calling ProcessmakerApi->addTask: ', $e->getMessage(), PHP_EOL;
}

/** Assign group to task */

try {
    $taskAddGroupsItem = new TaskAddGroupsItem([
        'data' => new GroupIds([
            'groups' => [$group->getData()->getId()]
        ])
    ]);

    $apiInstance->addGroupsToTask(
        $process->getData()->getId(),
        $task->getData()->getId(),
        $taskAddGroupsItem
    );

} catch (Exception $e) {
    echo 'Exception when calling ProcessmakerApi->addGroupToTask: ', $e->getMessage(), PHP_EOL;
}

/**  Add flows */

try {
    $flowAttr= new FlowAttributes();
    $flowAttr->setName('Flow StartEvent with Task');
    $flowAttr->setType('SEQUENTIAL');
    $flowAttr->setProcessId($process->getData()->getId());
    $flowAttr->setFromObjectId($startEvent->getData()->getId());
    $flowAttr->setFromObjectType('event');
    $flowAttr->setToObjectId($task->getData()->getId());
    $flowAttr->setToObjectType('task');
    $flowAttr->setDefault(false);
    $flowAttr->setOptional(false);
    $apiInstance->addFlow(
        $process->getData()->getId(),
        new FlowCreateItem(
            [
                'data' => new Flow(['attributes' => $flowAttr])
            ]
        )
    );

    $flowAttr= new FlowAttributes();
    $flowAttr->setName('Flow Task with endEvent');
    $flowAttr->setType('SEQUENTIAL');
    $flowAttr->setProcessId($process->getData()->getId());
    $flowAttr->setFromObjectId($task->getData()->getId());
    $flowAttr->setFromObjectType('task');
    $flowAttr->setToObjectId($startEvent->getData()->getId());
    $flowAttr->setToObjectType('event');
    $flowAttr->setDefault(false);
    $flowAttr->setOptional(false);
    $apiInstance->addFlow(
        $process->getData()->getId(),
        new FlowCreateItem(
            [
                'data' => new Flow(['attributes' => $flowAttr])
            ]
        )
    );


} catch (Exception $e) {
    echo 'Exception when calling ProcessmakerApi->addFlow: ', $e->getMessage(), PHP_EOL;
}

/** Triggering start event to run process */

try {
    $arrayContent = ['key' => 6, 'add' => 15, 'confirm' => false];
    /** @var DataModelAttributes $dataModelAttr */
    $dataModelAttr = new DataModelAttributes();
    $dataModelAttr->setContent(json_encode($arrayContent));
    $result = $apiInstance->eventTrigger(
        $process->getData()->getId(),
        $startEvent->getData()->getId(),
        new TriggerEventCreateItem(
            [
                'data' => new DataModel(['attributes' => $dataModelAttr])
            ]
        )
    );
} catch (Exception $e) {
    echo 'Exception when calling ProcessmakerApi->eventTrigger: ', $e->getMessage(), PHP_EOL;
}







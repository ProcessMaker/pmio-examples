<?php
/** Creating and lunch new process with Exclusive gateway and coditional flow */

require_once __DIR__ . '/vendor/autoload.php';

use Swagger\Client\Api\ProcessmakerApi;

use Swagger\Client\ApiException;

use Swagger\Client\Model\Process;
use Swagger\Client\Model\ProcessCreateItem;
use Swagger\Client\Model\ProcessAttributes;
use Swagger\Client\Model\ProcessItem;

use Swagger\Client\Model\Event;
use Swagger\Client\Model\EventItem;
use Swagger\Client\Model\EventAttributes;
use Swagger\Client\Model\EventCreateItem;
use Swagger\Client\Model\TriggerEventCreateItem;

use Swagger\Client\Model\Task;
use Swagger\Client\Model\TaskItem;
use Swagger\Client\Model\TaskCreateItem;
use Swagger\Client\Model\TaskAttributes;

use Swagger\Client\Model\FlowAttributes;
use Swagger\Client\Model\Flow;
use Swagger\Client\Model\FlowItem;
use Swagger\Client\Model\FlowCreateItem;

use Swagger\Client\Model\DataModel;
use Swagger\Client\Model\DataModelAttributes;
use Swagger\Client\Model\DataModelItem;

use Swagger\Client\Model\Gateway;
use Swagger\Client\Model\GatewayAttributes;
use Swagger\Client\Model\GatewayItem;
use Swagger\Client\Model\GatewayCreateItem;

use Swagger\Client\Model\InstanceCollection;


/** @var ProcessmakerApi $apiInstance */
$apiInstance = new ProcessmakerApi;

/** Setting up host with base path and access token for API core */
include "../.env";

$apiInstance->getApiClient()->getConfig()->setHost("http://$host/api/v1");
$apiInstance->getApiClient()->getConfig()->setAccessToken($key['Test']);

/** Comment if don't need logs */

$apiInstance->getApiClient()->getConfig()->setDebugFile('my_debug.log');
$apiInstance->getApiClient()->getConfig()->setDebug(true);

/** @var integer $random */
$random = rand(1,1000);

/** Creating process */

try {
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
    print_r($process);

} catch (Exception $e) {
    dumpError($e, 'Exception when calling ProcessmakerApi->addProcess: '.$e->getMessage().PHP_EOL);
}

/** Create one start Event and two end Events */

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
    print_r($startEvent);

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
    print_r($endEvent);


} catch (Exception $e) {
    dumpError($e, 'Exception when calling ProcessmakerApi->addEvent: '.$e->getMessage().PHP_EOL);
}

/** Create two script tasks  */

try {
    /** @var TaskAttributes $taskAttr */
    $taskAttr = new TaskAttributes();
    $taskAttr->setName('First direction');
    $taskAttr->setType('SCRIPT-TASK');
    $taskAttr->setProcessId($process->getData()->getId());
    $taskAttr->setAssignType('CYCLIC');
    $taskAttr->setScript('$aData[\'First_Direction\'] = 1;');

    /** @var TaskItem $result */
    $firstDirectTask = $apiInstance->addTask(
        $process->getData()->getId(),
        new TaskCreateItem(
            [
                'data' => new Task(['attributes' => $taskAttr])
            ]
        )
    );
    print_r($firstDirectTask);

    /** @var TaskAttributes $taskAttr */
    $taskAttr = new TaskAttributes();
    $taskAttr->setName('Second direction');
    $taskAttr->setType('SCRIPT-TASK');
    $taskAttr->setProcessId($process->getData()->getId());
    $taskAttr->setAssignType('CYCLIC');
    $taskAttr->setScript('$aData[\'Second_Direction\'] = 2;');

    /** @var TaskItem $result */
    $secondDirectTask = $apiInstance->addTask(
        $process->getData()->getId(),
        new TaskCreateItem(
            [
                'data' => new Task(['attributes' => $taskAttr])
            ]
        )
    );
    print_r($secondDirectTask);


} catch (Exception $e) {
    dumpError($e, 'Exception when calling ProcessmakerApi->addTask: '.$e->getMessage().PHP_EOL);
}

/** Create Exclusive and Inclusive gateway  */

try {
    /** @var GatewayAttributes $gatewayAttr */
    $gatewayAttr = new GatewayAttributes();
    $gatewayAttr->setName('Exclusive gateway');
    $gatewayAttr->setType('EXCLUSIVE');
    $gatewayAttr->setDirection('DIVERGENT');
    $gatewayAttr->setProcessId($process->getData()->getId());

    /** @var GatewayItem $exclusiveGateway */
    $exclusiveGateway = $apiInstance->addGateway(
        $process->getData()->getId(),
        new GatewayCreateItem(
            [
                'data' => new Gateway(['attributes' => $gatewayAttr])
            ]
        )
    );

    print_r($exclusiveGateway);

    /** @var GatewayAttributes $gatewayAttr */
    $gatewayAttr = new GatewayAttributes();
    $gatewayAttr->setName('Inclusive gateway');
    $gatewayAttr->setType('INCLUSIVE');
    $gatewayAttr->setDirection('CONVERGENT');
    $gatewayAttr->setProcessId($process->getData()->getId());

    /** @var GatewayItem $inclusiveGateway */
    $inclusiveGateway = $apiInstance->addGateway(
        $process->getData()->getId(),
        new GatewayCreateItem(
            [
                'data' => new Gateway(['attributes' => $gatewayAttr])
            ]
        )
    );

    print_r($inclusiveGateway);

} catch (Exception $e) {
    dumpError($e, 'Exception when calling ProcessmakerApi->addGateway: '.$e->getMessage().PHP_EOL);
}

/** Creating flows between objects */

try {
    /** Flow between start event and exclusive gateway */

    /** @var FlowAttributes $flowAttr */
    $flowAttr= new FlowAttributes();
    $flowAttr->setName('Flow StartEvent with Exclusive Gateway');
    $flowAttr->setType('SEQUENTIAL');
    $flowAttr->setProcessId($process->getData()->getId());
    $flowAttr->setFromObjectId($startEvent->getData()->getId());
    $flowAttr->setFromObjectType($startEvent->getData()->getType());
    $flowAttr->setToObjectId($exclusiveGateway->getData()->getId());
    $flowAttr->setToObjectType($exclusiveGateway->getData()->getType());
    print_r($apiInstance->addFlow(
        $process->getData()->getId(),
        new FlowCreateItem([
            'data' => new Flow(['attributes' => $flowAttr])
        ])
    )
    );



    /** Conditional flow between exclusive gateway and  script task First direction*/

    /** @var FlowAttributes $flowAttr */
    $flowAttr= new FlowAttributes();
    $flowAttr->setName('Flow Exclusive Gateway with First direction');
    $flowAttr->setType('SEQUENTIAL');
    $flowAttr->setProcessId($process->getData()->getId());
    $flowAttr->setFromObjectId($exclusiveGateway->getData()->getId());
    $flowAttr->setFromObjectType($exclusiveGateway->getData()->getType());
    $flowAttr->setToObjectId($firstDirectTask->getData()->getId());
    $flowAttr->setToObjectType($firstDirectTask->getData()->getType());
    $flowAttr->setCondition('direction=1');
    print_r($apiInstance->addFlow(
        $process->getData()->getId(),
        new FlowCreateItem([
            'data' => new Flow(['attributes' => $flowAttr])
        ])
    )
    );

    /** @var FlowAttributes $flowAttr */
    $flowAttr= new FlowAttributes();
    $flowAttr->setName('Flow FirstDirection with Inclusive Gateway');
    $flowAttr->setType('SEQUENTIAL');
    $flowAttr->setProcessId($process->getData()->getId());
    $flowAttr->setFromObjectId($firstDirectTask->getData()->getId());
    $flowAttr->setFromObjectType($firstDirectTask->getData()->getType());
    $flowAttr->setToObjectId($inclusiveGateway->getData()->getId());
    $flowAttr->setToObjectType($inclusiveGateway->getData()->getType());
    print_r($apiInstance->addFlow(
        $process->getData()->getId(),
        new FlowCreateItem([
            'data' => new Flow(['attributes' => $flowAttr])
        ])
    )
    );

    /** Conditional flow between exclusive gateway and  script task Second direction*/

    /** @var FlowAttributes $flowAttr */
    $flowAttr= new FlowAttributes();
    $flowAttr->setName('Flow Exclusive Gateway with Second direction');
    $flowAttr->setType('SEQUENTIAL');
    $flowAttr->setProcessId($process->getData()->getId());
    $flowAttr->setFromObjectId($exclusiveGateway->getData()->getId());
    $flowAttr->setFromObjectType($exclusiveGateway->getData()->getType());
    $flowAttr->setToObjectId($secondDirectTask->getData()->getId());
    $flowAttr->setToObjectType($secondDirectTask->getData()->getType());
    $flowAttr->setCondition('direction=2');

    print_r($apiInstance->addFlow(
        $process->getData()->getId(),
        new FlowCreateItem([
            'data' => new Flow(['attributes' => $flowAttr])
        ])
    )
    );

    /** @var FlowAttributes $flowAttr */
    $flowAttr= new FlowAttributes();
    $flowAttr->setName('Flow SecondDirection with Inclusive Gateway');
    $flowAttr->setType('SEQUENTIAL');
    $flowAttr->setProcessId($process->getData()->getId());
    $flowAttr->setFromObjectId($secondDirectTask->getData()->getId());
    $flowAttr->setFromObjectType($secondDirectTask->getData()->getType());
    $flowAttr->setToObjectId($inclusiveGateway->getData()->getId());
    $flowAttr->setToObjectType($inclusiveGateway->getData()->getType());
    print_r($apiInstance->addFlow(
        $process->getData()->getId(),
        new FlowCreateItem([
            'data' => new Flow(['attributes' => $flowAttr])
        ])
    )
    );

    /** @var FlowAttributes $flowAttr */
    $flowAttr= new FlowAttributes();
    $flowAttr->setName('Flow Inclusive Gateway with end Event');
    $flowAttr->setType('SEQUENTIAL');
    $flowAttr->setProcessId($process->getData()->getId());
    $flowAttr->setFromObjectId($inclusiveGateway->getData()->getId());
    $flowAttr->setFromObjectType($inclusiveGateway->getData()->getType());
    $flowAttr->setToObjectId($endEvent->getData()->getId());
    $flowAttr->setToObjectType($endEvent->getData()->getType());
    print_r($apiInstance->addFlow(
        $process->getData()->getId(),
        new FlowCreateItem([
            'data' => new Flow(['attributes' => $flowAttr])
        ])
    )
    );

} catch (Exception $e) {
    dumpError($e, 'Exception when calling ProcessmakerApi->addFlow: '.$e->getMessage().PHP_EOL);
}


/** Start process with random data on start event */

try {
    /** @var array $arrayContent */
    $arrayContent = ['direction' => rand(1,2)];
    /** @var DataModelAttributes $dataModelAttr */
    $dataModelAttr = new DataModelAttributes();
    $dataModelAttr->setContent(json_encode($arrayContent));
    /** @var DataModelItem $result */
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
    dumpError($e, 'Exception when calling ProcessmakerApi->eventTrigger: '.$e->getMessage().PHP_EOL);
}


/** Get two instances of the process */

try {
    /** @var InstanceCollection $instances */
    $instances = $apiInstance->findInstances($process->getData()->getId());

} catch (Exception $e) {
    dumpError($e, 'Exception when calling ProcessmakerApi->findInstances: '.$e->getMessage().PHP_EOL);
}

/** Get datamodel of instances */

try {

    print_r(
        $apiInstance->findDataModel(
            $process->getData()->getId(),
            $instances->getData()[0]->getId()
        )
    );

} catch (Exception $e) {
    dumpError($e, 'Exception when calling ProcessmakerApi->findDataModel: '.$e->getMessage().PHP_EOL);
}




/** Handler of errors */

function dumpError(ApiException $e, $message="")
{
    if ($e->getResponseObject()) {
        /** @var Error[] $errorArray */
        $errorArray = $e->getResponseObject()->getErrors();
        print_r($errorArray);
    }
    echo $message;
    exit;
}




<?php

declare(strict_types=1);

namespace OCA\CustomEventBridge\AppInfo;

use OCP\AppFramework\App;
use OCP\EventDispatcher\IEventDispatcher;
use OCA\CustomEventBridge\Listener\MessageListener;
use OCA\Talk\Events\ChatMessageSentEvent;

class Application extends App {

    public function __construct(array $urlParams = []) {
        parent::__construct('custom_event_bridge', $urlParams);

        $container = $this->getContainer();

        $dispatcher = $container->get(IEventDispatcher::class);

        $dispatcher->addServiceListener(
            ChatMessageSentEvent::class,
            MessageListener::class
        );
    }
}

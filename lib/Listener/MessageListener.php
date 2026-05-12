<?php

declare(strict_types=1);

namespace OCA\CustomEventBridge\Listener;

class MessageListener {

    private static ?\Redis $redis = null;

    private function getRedis(): \Redis {
        if (self::$redis === null) {
            self::$redis = new \Redis();
            self::$redis->connect('192.168.1.9', 6379, 1.5); // timeout nhแบน
        }

        return self::$redis;
    }

    public function handle($event): void {

        try {
            $comment = $event->getComment();
            $room = $event->getRoom();
			$actorId = $comment->getActorId();
            $displayName = $actorId;
            if ($comment->getActorType() === 'users') {

                $user = \OC::$server
                    ->getUserManager()
                    ->get($actorId);

                if ($user) {
                    $displayName = $user->getDisplayName();
                }
            }         

            $payload = [
                'event' => 'message.created',
                'message_id' => $comment->getId(),
                'message' => $comment->getMessage(),
                'actor_id' => $actorId,
                'actor_name' => $displayName,
                'room_token' => $room->getToken(),             
                'time' => time(),
            ];

            $redis = $this->getRedis();

            $redis->publish(
                'chat.events',
                json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            );

            //   file_put_contents(
            //     '/tmp/room_methods.txt',
            //     print_r(get_class_methods($room), true)
            // );        

        //   file_put_contents(
        //         '/tmp/comment_dump.txt',
        //         print_r($comment, true),
        //         FILE_APPEND
        //     );

            //  file_put_contents(
            //     '/tmp/room_dump.txt',
            //     print_r( $room, true),
            //     FILE_APPEND
            // );
            // file_put_contents(
            //     '/tmp/test.log',
            //     print_r([
            //         'class' => get_class($comment),
            //         'methods' => get_class_methods($comment),
            //         'comment' => $comment,
            //     ], true),
            //     FILE_APPEND
            // );

        } catch (\Throwable $e) {
           // error_log('[CustomEventBridge] ' . $e->getMessage());
        }
    }
}
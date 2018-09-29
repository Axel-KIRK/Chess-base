<?php

namespace App\Utils\Topic;

use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Gos\Bundle\WebSocketBundle\Client\ClientManipulatorInterface;
use Gos\Bundle\WebSocketBundle\Topic\SecuredTopicInterface;
use Gos\Bundle\WebSocketBundle\Server\Exception\FirewallRejectionException;

class ChatTopic implements TopicInterface, SecuredTopicInterface
{
    protected $clientManipulator;

    /**
     * @param ClientManipulatorInterface $clientManipulator
     */
    public function __construct(ClientManipulatorInterface $clientManipulator)
    {
        $this->clientManipulator = $clientManipulator;
    }

    public function secure(ConnectionInterface $connection = null, Topic $topic, WampRequest $request, $payload = null, $exclude = null, $eligible = null, $provider = null)
    {      
        $user = $this->clientManipulator->getClient($connection);
        if (!is_object($user)){
            throw new FirewallRejectionException();
        }
    }
            
    /**
     * This will receive any Subscription requests for this topic.
     *
     * @param ConnectionInterface $connection
     * @param Topic $topic
     * @param WampRequest $request
     * @return void
     */
    public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
    }

    /**
     * This will receive any UnSubscription requests for this topic.
     *
     * @param ConnectionInterface $connection
     * @param Topic $topic
     * @param WampRequest $request
     * @return void
     */
    public function onUnSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
    }


    /**
     * This will receive any Publish requests for this topic.
     *
     * @param ConnectionInterface $connection
     * @param Topic $topic
     * @param WampRequest $request
     * @param $event
     * @param array $exclude
     * @param array $eligible
     * @return mixed|void
     */
    public function onPublish(ConnectionInterface $connection, Topic $topic, WampRequest $request, $event, array $exclude, array $eligible)
    {
        $user = $this->clientManipulator->getClient($connection);

        if($event['message'] === ''){
            return;
        }
            
        if (is_object($user)) {
            $receiverName = $event['receiver'];
            $senderName = $user->getUsername();

            if ($topic->getId() === 'chat/global') {
                $topic->broadcast([
                    'sender' => '['.date('H:i').']'. '[Global] ' . $senderName . ' :',
                    'message' => $event['message'],
                ]);
            }
            
            if ($topic->getId() === 'chat/private') {
                $receiver = $this->clientManipulator->findByUsername($topic, $receiverName);
                $sender = $this->clientManipulator->findByUsername($topic, $senderName);

                                       
                if (is_array($receiver)) {
                    $topic->broadcast(
                        [
                            'sender' => '['.date('H:i').']'.'[MP envoyé à] '. $receiverName.' :' ,
                            'message' => $event['message'],
                        ],
                        array(),
                        array($sender['connection']->WAMP->sessionId)
                    );
                                
                    $topic->broadcast(
                        [
                            'sender' => '['.date('H:i').']'.'[MP reçu de] '. $senderName. ' :',
                            'message' => $event['message'],
                        ],
                        array(),
                        array($receiver['connection']->WAMP->sessionId)
                    );
                } else {
                    $topic->broadcast(
                        [
                        'sender' => 'Erreur :' ,
                        'message' => 'Aucun utilisateur du nom de '.$receiverName. ' n\'est actuellement connecté.',
                        ],
                        array(),
                        array($sender['connection']->WAMP->sessionId)
                        );
                }
            }
        }
    }

    /**
    * Like RPC is will use to prefix the channel
    * @return string
    */
    public function getName()
    {
        return 'chat.topic';
    }
}

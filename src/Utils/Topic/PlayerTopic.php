<?php

namespace App\Utils\Topic;

use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Gos\Bundle\WebSocketBundle\Client\ClientManipulatorInterface;
use Gos\Bundle\WebSocketBundle\Topic\TopicPeriodicTimer;
use Gos\Bundle\WebSocketBundle\Topic\TopicPeriodicTimerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Gos\Bundle\WebSocketBundle\Topic\SecuredTopicInterface;
use Gos\Bundle\WebSocketBundle\Server\Exception\FirewallRejectionException;

class PlayerTopic implements TopicInterface , TopicPeriodicTimerInterface, SecuredTopicInterface
{
    protected $clientManipulator;
    protected $periodicTimer;


    /**
     * @param ClientManipulatorInterface $clientManipulator
     */
    public function __construct(ClientManipulatorInterface $clientManipulator, UrlGeneratorInterface $urlGenerator)
    {
        $this->clientManipulator = $clientManipulator;
        $this->urlGenerator = $urlGenerator;
    }
    /**
     * This will receive any Subscription requests for this topic.
     *
     * @param ConnectionInterface $connection
     * @param Topic $topic
     * @param WampRequest $request
     * @return void
     */

    public function secure(ConnectionInterface $connection = null, Topic $topic, WampRequest $request, $payload = null, $exclude = null, $eligible = null, $provider = null)
    {      
        $user = $this->clientManipulator->getClient($connection);
        if (!is_object($user)){
            throw new FirewallRejectionException();
        }
    }

    public function setPeriodicTimer(TopicPeriodicTimer $periodicTimer)
    {
        $this->periodicTimer = $periodicTimer;
    }


    public function registerPeriodicTimer(Topic $topic)
    {
        $this->periodicTimer->addPeriodicTimer($this, 'listUpdate', 10, function() use ($topic) {
           
            if (count($topic) === 0) {
                return;
            }

            $subscriber = $this->clientManipulator->getAll($topic);
            $playerList = [];
            foreach($subscriber as $subscriber){
                $playerList[] = [
                    'name' => $subscriber['client']->getUsername(),
                    'profilPath' =>  $this->urlGenerator->generate('profileShow', ['id' =>  $subscriber['client']->getId()])
                ];
            }
            $topic->broadcast($playerList);
        });
    }



    public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        $subscriber = $this->clientManipulator->getAll($topic);
        $playerList = [];
        foreach($subscriber as $subscriber){
            $playerList[] = [
                'name' => $subscriber['client']->getUsername(),
                'profilPath' =>  $this->urlGenerator->generate('profileShow', ['id' =>  $subscriber['client']->getId()])
            ];
        }
        $topic->broadcast($playerList);
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

    }

    /**
    * Like RPC is will use to prefix the channel
    * @return string
    */
    public function getName()
    {
        return 'player.topic';
    }
}
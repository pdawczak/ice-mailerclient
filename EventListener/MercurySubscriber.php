<?php
namespace Ice\MailerClientBundle\EventListener;

use Ice\MercuryBundle\Event\OrderEvent;
use Ice\MercuryBundle\Event\MercuryEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Ice\MailerClientBundle\Service\MailerClient;

class MercurySubscriber implements EventSubscriberInterface
{
    /**
     * @var MailerClient
     */
    private $mailerClient;

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return [
            MercuryEvents::POST_CONFIRM_ORDER => 'postConfirmOrder'
        ];
    }

    /**
     * @param OrderEvent $event
     */
    public function postConfirmOrder(OrderEvent $event)
    {
        $order = $event->getOrder();
        $recipient = $order->getIceId();
        $this->getMailerClient()->postMail($recipient, 'OrderConfirmation', [
            'orderReference' => $order->getReference()
        ]);
    }

    /**
     * @param \Ice\MailerClientBundle\Service\MailerClient $mailerClient
     * @return MinervaSubscriber
     */
    public function setMailerClient($mailerClient)
    {
        $this->mailerClient = $mailerClient;
        return $this;
    }

    /**
     * @return \Ice\MailerClientBundle\Service\MailerClient
     */
    public function getMailerClient()
    {
        return $this->mailerClient;
    }
}
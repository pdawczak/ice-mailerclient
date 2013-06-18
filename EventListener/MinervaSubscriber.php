<?php
namespace Ice\MailerClientBundle\EventListener;

use Ice\MinervaBundle\Event\BookingEvent;
use Ice\MinervaBundle\Event\MinervaEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Ice\MailerClientBundle\Service\MailerClient;

class MinervaSubscriber implements EventSubscriberInterface
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
            MinervaEvents::POST_CONFIRM_BOOKING => 'postConfirmBooking'
        ];
    }

    /**
     * @param BookingEvent $event
     */
    public function postConfirmBooking(BookingEvent $event)
    {
        $booking = $event->getBooking();
        $academicInformation = $booking->getAcademicInformation();
        $recipient = $academicInformation->getUsername();
        $this->getMailerClient()->postMail($recipient, 'BookingConfirmation', [
            'booking' => [
                'bookingReference'=>$booking->getSuborderGroup(),
                'orderReference'=>$booking->getOrderReference(),
            ],
            'academicInformation' => [
                'iceId'=>$academicInformation->getUsername(),
                'courseId'=>$academicInformation->getCourseId(),
            ]
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
<?php
namespace Ice\MailerClientBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Ice\MailerClientBundle\Service\MailerClient;
use Ice\CtmsBundle\Event\ApplicationEvent;

class VeritasSubscriber implements EventSubscriberInterface
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
            'veritas.application_received' => 'applicationReceived'
        ];
    }

    /**
     * @param ApplicationEvent $event
     */
    public function applicationReceived(ApplicationEvent $event)
    {
        $application = $event->getApplication();
        //Construct the correct email template name
        $templateName = '';

        $templateName .= $application->getBursaryApplication()
            ? 'bursary' : 'not.bursary';

        $templateName .= $application->getCourseApplication()
            ? '.application' : '.application.residential';

        if ($templateName == 'bursary.application') {
            $bursaryApplication = $application->getBursaryApplication();
            $templateName .= (!$bursaryApplication->getPreviousUniStudy()) && $bursaryApplication->getHowEligible()
                ? '.irh' : '.not.irh';
        }

        if ($application->getReceived() > $application->getCourse()->getApplicationDeadline()) {
            $templateName .= '.late';
        }

        $params = array(
            'templateKey' => $templateName,
            'academicInformation' => array(
                'iceId' => $application->getUsername(),
                'courseId' => intval($application->getCourseId())
            )
        );

        $this->getMailerClient()->postMail($application->getUsername(), 'CourseApplication', $params);
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
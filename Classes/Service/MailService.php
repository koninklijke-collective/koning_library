<?php
namespace Keizer\KoningLibrary\Service;

/**
 * Service: Mail
 *
 * @package Keizer\KoningLibrary\Service
 */
class MailService implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * @var array
     */
    protected $templateRootPaths = [];

    /**
     * @var array
     */
    protected $layoutRootPaths = [];

    /**
     * @var array
     */
    protected $partialRootPaths = [];

    /**
     * @return array
     */
    public function getTemplateRootPath()
    {
        return $this->templateRootPaths;
    }

    /**
     * @param string $templateRootPaths
     * @return MailService
     */
    public function setTemplateRootPaths($templateRootPaths)
    {
        $this->templateRootPaths = $templateRootPaths;
        return $this;
    }

    /**
     * @param string $layoutRootPaths
     * @return MailService
     */
    public function setLayoutRootPaths($layoutRootPaths)
    {
        $this->layoutRootPaths = $layoutRootPaths;
        return $this;
    }

    /**
     * @param array $partialRootPaths
     * @return MailService
     */
    public function setPartialRootPaths($partialRootPaths)
    {
        $this->partialRootPaths = $partialRootPaths;
        return $this;
    }

    /**
     * Send mail using a Fluid template
     *
     * @param array $recipient
     * @param array $sender
     * @param string $subject
     * @param string $templateName
     * @param array $bcc
     * @param array $variables
     * @param array $attachments
     * @return boolean
     */
    public function sendMail(array $recipient, array $sender, $subject, $templateName, array $bcc = [], array $variables = [], array $attachments = [])
    {
        /** @var \TYPO3\CMS\Fluid\View\StandaloneView $emailView */
        $emailView = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Fluid\View\StandaloneView::class);
        $emailView->setFormat('html');
        $emailView->setLayoutRootPaths($this->layoutRootPaths);
        $emailView->setTemplateRootPaths($this->templateRootPaths);
        $emailView->setPartialRootPaths($this->partialRootPaths);
        $emailView->setTemplate($templateName);
        $emailView->assignMultiple($variables);
        $emailBody = $emailView->render();

        $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Mail\MailMessage::class);
        /** @var \TYPO3\CMS\Core\Mail\MailMessage $message */
        $message
            ->setTo($recipient)
            ->setBcc($bcc)
            ->setFrom($sender)
            ->setSubject($subject);

        foreach ($attachments as $file) {
            $attachment = \Swift_Attachment::fromPath($file);
            $message->attach($attachment);
        }

        $message->setBody($emailBody, 'text/html');
        $message->addPart(html_entity_decode(strip_tags($emailBody)), 'text/plain');

        $message->send();
        return $message->isSent();
    }
}

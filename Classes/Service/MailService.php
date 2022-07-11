<?php

namespace Keizer\KoningLibrary\Service;

use Swift_Attachment;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Service: Mail
 */
class MailService implements SingletonInterface
{
    /** @var array */
    protected $templateRootPaths = [];

    /** @var array */
    protected $layoutRootPaths = [];

    /** @var array */
    protected $partialRootPaths = [];

    /**
     * @param  array  $templateRootPaths
     * @return \Keizer\KoningLibrary\Service\MailService
     */
    public function setTemplateRootPaths(array $templateRootPaths): self
    {
        $this->templateRootPaths = $templateRootPaths;

        return $this;
    }

    /**
     * @param  array  $layoutRootPaths
     * @return \Keizer\KoningLibrary\Service\MailService
     */
    public function setLayoutRootPaths(array $layoutRootPaths): self
    {
        $this->layoutRootPaths = $layoutRootPaths;

        return $this;
    }

    /**
     * @param  array  $partialRootPaths
     * @return \Keizer\KoningLibrary\Service\MailService
     */
    public function setPartialRootPaths(array $partialRootPaths): self
    {
        $this->partialRootPaths = $partialRootPaths;

        return $this;
    }

    /**
     * Send mail using a Fluid template
     *
     * @param  array  $recipient
     * @param  array  $sender
     * @param  string  $subject
     * @param  string  $templateName
     * @param  array  $bcc
     * @param  array  $variables
     * @param  array  $attachments
     * @return bool
     */
    public function sendMail(
        array $recipient,
        array $sender,
        string $subject,
        string $templateName,
        array $bcc = [],
        array $variables = [],
        array $attachments = []
    ): bool {
        /** @var \TYPO3\CMS\Fluid\View\StandaloneView $emailView */
        $emailView = GeneralUtility::makeInstance(StandaloneView::class);
        $emailView->setFormat('html');
        $emailView->setLayoutRootPaths($this->layoutRootPaths);
        $emailView->setTemplateRootPaths($this->templateRootPaths);
        $emailView->setPartialRootPaths($this->partialRootPaths);
        $emailView->setTemplate($templateName);
        $emailView->assignMultiple($variables);
        $emailBody = $emailView->render();

        $message = GeneralUtility::makeInstance(MailMessage::class);
        /** @var \TYPO3\CMS\Core\Mail\MailMessage $message */
        $message
            ->setTo($recipient)
            ->setBcc($bcc)
            ->setFrom($sender)
            ->setSubject($subject);

        foreach ($attachments as $file) {
            $attachment = Swift_Attachment::fromPath($file);
            $message->attach($attachment);
        }

        return $message->setBody($emailBody, 'text/html')
            ->addPart(html_entity_decode(strip_tags($emailBody)), 'text/plain')
            ->send()
            ->isSent();
    }
}

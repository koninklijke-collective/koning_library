<?php

namespace Keizer\KoningLibrary\Frontend;

use Keizer\KoningLibrary\Domain\Session\SessionInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Frontend session wrapper
 *
 * @package Keizer\KoningLibrary\Frontend
 */
class SessionService
{
    /**
     * @var FrontendUserAuthentication
     */
    protected $frontendUserAuthentication;

    /**
     * Get the object from the session. Create new one if it does not exist yet
     *
     * @param string $sessionKey
     * @param string $sessionObject
     * @return SessionInterface
     */
    public function getSession($sessionKey, $sessionObject): SessionInterface
    {
        $sessionData = $this->getFrontendUserAuthentication()->getKey('ses', $sessionKey);
        if ($sessionData === null) {
            /** @var SessionInterface $newObject */
            $newObject = GeneralUtility::makeInstance($sessionObject);
            $this->saveSessionData($sessionKey, ['sessionObject' => $newObject]);
            return $newObject;
        }
        $sessionData = unserialize($sessionData);
        return $sessionData['sessionObject'];
    }
    /**
     * Save the specified object into the session
     *
     * @param string $key
     * @param SessionInterface $session
     * @return void
     */
    public function saveSession($key, SessionInterface $session): void
    {
        $this->saveSessionData($key, ['sessionObject' => $session]);
    }
    /**
     * Save the specified array into the session
     *
     * @param string $key
     * @param array $session
     * @return void
     */
    protected function saveSessionData($key, array $session): void
    {
        $this->getFrontendUserAuthentication()->setKey('ses', $key, serialize($session));
        $this->getFrontendUserAuthentication()->storeSessionData();
    }
    /**
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
    /**
     * @return FrontendUserAuthentication
     */
    protected function getFrontendUserAuthentication(): FrontendUserAuthentication
    {
        if ($this->frontendUserAuthentication === null && $this->getTypoScriptFrontendController() !== null) {
            $this->frontendUserAuthentication = $this->getTypoScriptFrontendController()->fe_user;
        }
        return $this->frontendUserAuthentication;
    }
}

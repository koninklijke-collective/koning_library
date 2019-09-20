<?php

namespace Keizer\KoningLibrary\Frontend;

use Keizer\KoningLibrary\Domain\Session\SessionInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Utility\EidUtility;

/**
 * Frontend session wrapper
 */
class SessionService
{
    /**
     * @var \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication
     */
    protected $frontendUserAuthentication;

    /**
     * Get the object from the session. Create new one if it does not exist yet
     *
     * @param string $sessionKey
     * @param string $sessionObject
     * @return \Keizer\KoningLibrary\Domain\Session\SessionInterface
     */
    public function getSession($sessionKey, $sessionObject)
    {
        $sessionData = $this->getFrontendUserAuthentication()->getKey('ses', $sessionKey);
        if ($sessionData === null) {
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
     * @param \Keizer\KoningLibrary\Domain\Session\SessionInterface $session
     * @return void
     */
    public function saveSession($key, SessionInterface $session)
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
    protected function saveSessionData($key, array $session)
    {
        $this->getFrontendUserAuthentication()->setKey('ses', $key, serialize($session));
        $this->getFrontendUserAuthentication()->storeSessionData();
    }

    /**
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController()
    {
        return $GLOBALS['TSFE'];
    }

    /**
     * @return \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication
     */
    protected function getFrontendUserAuthentication()
    {
        if ($this->frontendUserAuthentication === null) {
            if ($this->getTypoScriptFrontendController() !== null) {
                $this->frontendUserAuthentication = $this->getTypoScriptFrontendController()->fe_user;
            } else {
                $this->frontendUserAuthentication = EidUtility::initFeUser();
            }
        }
        return $this->frontendUserAuthentication;
    }
}

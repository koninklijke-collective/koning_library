<?php
namespace Keizer\KoningLibrary\Frontend;

/**
 * Frontend session wrapper
 *
 * @package Keizer\KoningLibrary\Frontend
 */
class SessionService
{
    /**
     * Get the object from the session. Create new one if it does not exist yet
     *
     * @param string $sessionKey
     * @param string $sessionObject
     * @return \Keizer\KoningLibrary\Domain\Session\SessionInterface
     */
    public function getSession($sessionKey, $sessionObject)
    {
        $sessionData = $GLOBALS['TSFE']->fe_user->getKey('ses', $sessionKey);

        if ($sessionData === null) {
            $newObject = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance($sessionObject);
            $this->saveSessionData($sessionKey, array('sessionObject' => $newObject));
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
    public function saveSession($key, \Keizer\KoningLibrary\Domain\Session\SessionInterface $session)
    {
        $this->saveSessiondata($key, array('sessionObject' => $session));
    }

    /**
     * Save the specified array into the session
     *
     * @parm string $key
     * @param array $sessionArray
     * @return void
     */
    protected function saveSessionData($key, array $sessionArray)
    {
        $GLOBALS['TSFE']->fe_user->setKey('ses', $key, serialize($sessionArray));
        $GLOBALS['TSFE']->fe_user->storeSessionData();
    }
}

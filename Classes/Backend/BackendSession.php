<?php
namespace Keizer\KoningLibrary\Backend;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

/**
 * Backend session wrapper
 *
 * @package Keizer\KoningLibrary\Backend
 */
class BackendSession
{
    /**
     * @var BackendUserAuthentication
     */
    protected $backendUserAuthentication;

    /**
     * @param BackendUserAuthentication $backendUserAuthentication
     * @return BackendSession
     */
    public function setBackendUserAuthentication(BackendUserAuthentication $backendUserAuthentication)
    {
        $this->backendUserAuthentication = $backendUserAuthentication;
        return $this;
    }

    /**
     * @return BackendUserAuthentication
     */
    protected function getBackendUserAuthentication()
    {
        return $this->backendUserAuthentication;
    }

    /**
     * Creates a session if it does not exist yet
     *
     * @param string $key
     * @param mixed $contents
     * @return void
     */
    public function createSession($key, $contents = null)
    {
        if ($this->getBackendUserAuthentication()->getSessionData($key) === null) {
            $this->saveSessionData($key, array('contents' => $contents));
        }
    }

    /**
     * Save the provided array into the session
     *
     * @param string $key
     * @param array $sessionArray
     * @return void
     */
    protected function saveSessionData($key, array $sessionArray)
    {
        $this->getBackendUserAuthentication()->setAndSaveSessionData($key, serialize($sessionArray));
    }

    /**
     * Returns the session contents
     *
     * @param string $key
     * @return mixed
     */
    public function getSessionContents($key)
    {
        $sessionData = $this->getBackendUserAuthentication()->getSessionData($key);
        if ($sessionData !== null) {
            $unserializeData = unserialize($sessionData);
            if (isset($unserializeData['contents'])) {
                return $unserializeData['contents'];
            }
        }
        return false;
    }

    /**
     * Saves the provided contents into the session
     *
     * @param string $key
     * @param mixed $contents
     * @return void
     */
    public function saveSessionContents($key, $contents)
    {
        $this->saveSessionData($key, array('contents' => $contents));
    }
}

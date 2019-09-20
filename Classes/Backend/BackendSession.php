<?php

namespace Keizer\KoningLibrary\Backend;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

/**
 * Backend session wrapper
 */
class BackendSession
{
    /** @var \TYPO3\CMS\Core\Authentication\BackendUserAuthentication */
    protected $backendUserAuthentication;

    /**
     * @param  \TYPO3\CMS\Core\Authentication\BackendUserAuthentication  $backendUserAuthentication
     * @return \Keizer\KoningLibrary\Backend\BackendSession
     */
    public function setBackendUserAuthentication(BackendUserAuthentication $backendUserAuthentication): self
    {
        $this->backendUserAuthentication = $backendUserAuthentication;

        return $this;
    }

    /**
     * @return \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
     */
    protected function getBackendUserAuthentication(): BackendUserAuthentication
    {
        return $this->backendUserAuthentication;
    }

    /**
     * Creates a session if it does not exist yet
     *
     * @param  string  $key
     * @param  mixed  $contents
     * @return void
     */
    public function createSession(string $key, $contents = null): void
    {
        if ($this->getBackendUserAuthentication()->getSessionData($key) === null) {
            $this->saveSessionData($key, ['contents' => $contents]);
        }
    }

    /**
     * Save the provided array into the session
     *
     * @param  string  $key
     * @param  array  $contents
     * @return void
     */
    protected function saveSessionData(string $key, array $contents): void
    {
        $this->getBackendUserAuthentication()->setAndSaveSessionData($key, serialize($contents));
    }

    /**
     * Returns the session contents
     *
     * @param  string  $key
     * @return mixed
     */
    public function getSessionContents(string $key)
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
     * @param  string  $key
     * @param  mixed  $contents
     * @return void
     */
    public function saveSessionContents(string $key, $contents): void
    {
        $this->saveSessionData($key, ['contents' => $contents]);
    }
}

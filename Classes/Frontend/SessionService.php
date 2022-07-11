<?php

namespace Keizer\KoningLibrary\Frontend;

use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;

/**
 * Frontend session wrapper
 */
class SessionService
{
    /** @var \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication */
    protected $frontendUserAuthentication;

    /**
     * Get the object from the session. Create new one if it does not exist yet
     *
     * @param  string  $key
     * @param  mixed  $data
     * @return mixed
     */
    public function getSession(string $key, $data)
    {
        $sessionData = $this->getFrontendUserAuthentication()->getKey('ses', $key);
        if ($sessionData === null) {
            $this->saveSessionData($key, ['contents' => $data]);

            return $data;
        }

        $sessionData = unserialize($sessionData);

        return $sessionData['contents'];
    }

    /**
     * Save the specified object into the session
     *
     * @param  string  $key
     * @param  mixed  $data
     * @return void
     */
    public function saveSession(string $key, $data): void
    {
        $this->saveSessionData($key, ['contents' => $data]);
    }

    /**
     * Save the specified array into the session
     *
     * @param  string  $key
     * @param  array  $data
     * @return void
     */
    protected function saveSessionData(string $key, array $data): void
    {
        $this->getFrontendUserAuthentication()->setKey('ses', $key, serialize($data));
        $this->getFrontendUserAuthentication()->storeSessionData();
    }

    /**
     * @return \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication
     */
    protected function getFrontendUserAuthentication(): FrontendUserAuthentication
    {
        if ($this->frontendUserAuthentication === null) {
            $this->frontendUserAuthentication = $GLOBALS['TSFE']->fe_user;
        }

        return $this->frontendUserAuthentication;
    }
}

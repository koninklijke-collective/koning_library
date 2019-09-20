<?php

namespace Keizer\KoningLibrary\Controller;

use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

/**
 * Controller: Abstract Module Action
 */
abstract class AbstractModuleController extends AbstractActionController
{

    /**
     * @var \TYPO3\CMS\Extbase\Mvc\Web\Request
     */
    protected $request;

    /**
     * @var \TYPO3\CMS\Backend\View\BackendTemplateView
     */
    protected $view;

    /**
     * @var string
     */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /**
     * Initialize needed page renderings
     *
     * @return void
     */
    public function initializeAction()
    {
        parent::initializeAction();

        // Load needed javascript libraries
        $this->getPageRenderer()->loadExtJS();
        $this->getPageRenderer()->loadJquery();
        $this->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/Backend/Utility');
        $this->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/Backend/Notification');
        $this->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/Backend/Modal');
    }

    /**
     * Set up the view template configuration correctly for BackendTemplateView
     * This is needed to correctly handle typoscript setup
     *
     * @see https://forge.typo3.org/issues/73367
     * @param \TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view
     * @return void
     */
    protected function setViewConfiguration(ViewInterface $view)
    {
        if (class_exists('\TYPO3\CMS\Backend\View\BackendTemplateView') && ($view instanceof \TYPO3\CMS\Backend\View\BackendTemplateView)) {
            /** @var \TYPO3\CMS\Fluid\View\TemplateView $_view */
            $_view = $this->getObjectManager()->get(\TYPO3\CMS\Fluid\View\TemplateView::class);
            $this->setViewConfiguration($_view);
            $view->injectTemplateView($_view);
        } else {
            parent::setViewConfiguration($view);
        }
    }

    /**
     * Resolve view and initialize the general view-variables extensionName,
     * controllerName and actionName based on the request object
     *
     * @return \TYPO3\CMS\Extbase\Mvc\View\ViewInterface
     */
    protected function resolveView()
    {
        $view = parent::resolveView();
        $view->assignMultiple([
            'extensionName' => $this->request->getControllerExtensionName(),
            'controllerName' => $this->request->getControllerName(),
            'actionName' => $this->request->getControllerActionName(),
        ]);
        return $view;
    }

    /**
     * Set up the doc header properly here
     *
     * @param \TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view
     * @return void
     */
    protected function initializeView(ViewInterface $view)
    {
        parent::initializeView($view);
        if ($view instanceof \TYPO3\CMS\Backend\View\BackendTemplateView) {
            // Disable Path
            $view->getModuleTemplate()->getDocHeaderComponent()->setMetaInformation([]);
            $view->getModuleTemplate()->setFlashMessageQueue($this->controllerContext->getFlashMessageQueue());
        }
    }

    /**
     * Creates the URI for a backend action
     *
     * @param string $controller
     * @param string $action
     * @param array $parameters
     * @return string
     */
    protected function getHref($controller, $action, $parameters = [])
    {
        $uriBuilder = $this->objectManager->get(UriBuilder::class);
        $uriBuilder->setRequest($this->request);
        return $uriBuilder->reset()->uriFor($action, $parameters, $controller);
    }
}

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
    /** @var \TYPO3\CMS\Extbase\Mvc\Web\Request */
    protected $request;

    /** @var \TYPO3\CMS\Backend\View\BackendTemplateView */
    protected $view;

    /** @var string */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /**
     * Initialize needed page renderings
     *
     * @return void
     */
    public function initializeAction(): void
    {
        parent::initializeAction();

        // Load needed javascript libraries
        $this->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/Backend/Utility');
        $this->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/Backend/Notification');
        $this->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/Backend/Modal');
    }

    /**
     * Resolve view and initialize the general view-variables extensionName,
     * controllerName and actionName based on the request object
     *
     * @return \TYPO3\CMS\Extbase\Mvc\View\ViewInterface
     */
    protected function resolveView(): ViewInterface
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
     * @param  \TYPO3\CMS\Extbase\Mvc\View\ViewInterface  $view
     * @return void
     */
    protected function initializeView(ViewInterface $view): void
    {
        parent::initializeView($view);
        if ($view instanceof BackendTemplateView) {
            // Disable Path
            $view->getModuleTemplate()->getDocHeaderComponent()->setMetaInformation([]);
            $view->getModuleTemplate()->setFlashMessageQueue($this->controllerContext->getFlashMessageQueue());
        }
    }

    /**
     * Creates the URI for a backend action
     *
     * @param  string|null  $controller
     * @param  string|null  $action
     * @param  array|null  $parameters
     * @return string
     */
    protected function getHref(?string $controller, ?string $action, ?array $parameters = []): string
    {
        $uriBuilder = $this->objectManager->get(UriBuilder::class);
        $uriBuilder->setRequest($this->request);

        return $uriBuilder->reset()->uriFor($action, $parameters, $controller);
    }
}

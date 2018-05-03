.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt
.. index:: Content Wizards

!DEPRECATED! Content Wizards
---------------

Cause all our content wizards had the same stupid functions we made a generic abstract wizard class for you to extend.
It may seem similar but it comes with functions for translations and icon lookup


How to use
^^^^^^^^^^

.. code-block:: php

    <?php
    namespace Your\Namespace\Wizard;

    /**
     * Wizard: Example
     *
     * @package Your\Namespace\Wizard
     */
    class ExampleWizard extends \Keizer\KoningLibrary\Wizard\AbstractWizard
    {

        /**
         * @var string
         */
        protected $extensionName = 'your_extension_key';

        /**
         * Processing the wizard items array
         *
         * @param array $wizardItems
         * @return array
         */
        function proc($wizardItems)
        {
            $wizardItems['plugins_tx_eur_events_list'] = array(
                'icon' => $this->getIcon('icon.png'),
                'title' => $this->getLabel('your.translation.title'),
                'description' => $this->getLabel('your.translation.description'),
                'params' => '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=yourexample_list'
            );

            return $wizardItems;
        }
    }

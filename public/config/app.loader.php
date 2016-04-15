<?php

    class AppLoader {

        protected $application;

        public function __construct(\Dez\Micro\Application $application)
        {
            $this->application  = $application;

            $this->application->loader->registerNamespaces([
                'DezCDN\Action'     => __DIR__ . '/../app/Action',
                'DezCDN\Common'     => __DIR__ . '/../app/Common',
            ])->register();
        }

    }
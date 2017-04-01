<?php

namespace Core;

use Railken\Laravel\App\Package as BasePackage;

class Package extends BasePackage{


	/**
     * Load services
     *
     * @return void
     */
    public function loadServices()
    {

    	# Load commands within Sync Component
        $this->loadFiles('Sync/Commands/*', 'Sync\\Commands\\', function($files, $classes) {

            $this->getServiceProvider()->commands($classes);
        });

        parent::loadServices();

    }

}
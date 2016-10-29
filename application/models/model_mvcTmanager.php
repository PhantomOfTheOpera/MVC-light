<?php

namespace MVC_light\Model;


use \MVC_light\Model as Model,

    	\MVC_light\Service as Service;
class Model_mvcTmanager extends Model {

	function get_mvcTmanager() {
		Service::needs('css', 'admin', 'true');
        return [
			'template' => (ADMIN) ? 'mvc-manager' : '404'
		];
	}
}

<?php

namespace Skeleton\Application\Dav\Server;

use Sabre\DAV\Collection;
use Sabre\DAV\FS;
use Sabre\DAV\Auth\Plugin as AuthPlugin;

class Root extends Collection {


    function __construct(AuthPlugin $authPlugin) {
        $this->authPlugin = $authPlugin;

    }

    function __call($name, $arguments) {
    	$application = \Skeleton\Core\Application::get();
		$root = $application->call_event_if_exists('dav', 'get_root', [$this]);
		return call_user_func_array([$root, $name], $arguments);
    }

    public function getName() {
    	try {
	    	\Manager::get();
	    } catch (\Exception $e) {
	    	return 'root';
	    }
    	return $this->__call('getName', []);
    }

    public function getChildren() {
    	$children = $this->__call('getChildren', []);
    	return $children;
    }

    public function getChild($name) {
    	$application = \Skeleton\Core\Application::get();
		$root = $application->call_event_if_exists('dav', 'get_root', [$this]);
		return $root->getChild($name);
    }


    /** ---snip--- **/

}

<?php
/**
 * Virtual Dav Root
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */
namespace Skeleton\Application\Dav\Server;

use Sabre\DAV\Collection;
use Sabre\DAV\FS;
use Sabre\DAV\Auth\Plugin as AuthPlugin;

class Root extends Collection {

	/**
	 * AuthPlugin
	 *
	 * @access private
	 * @var Authplugin $authPlugin
	 */
	private $authPlugin = null;

	/**
	 * Constructor
	 *
	 * @access public
	 * @param Sabre\DAV\Auth\Plugin $plugin
	 */
	public function __construct(AuthPlugin $auth_plugin) {
		$this->authPlugin = $auth_plugin;
	}

	/**
	 * __Call
	 *
	 * @access public
	 * @param string $name
	 * @param array $arguments
	 */
	public function __call($name, $arguments) {
		$application = \Skeleton\Core\Application::get();
		$root = $application->call_event_if_exists('dav', 'get_root', [$this]);
		return call_user_func_array([$root, $name], $arguments);
	}

	/**
	 * Get the name
	 *
	 * @access public
	 * @return string $name
	 */
	public function getName() {
		return $this->__call('getName', []);
	}

	/**
	 * Get children
	 *
	 * @access public
	 * @return array $children
	 */
	public function getChildren() {
		$children = $this->__call('getChildren', []);
		return $children;
	}

	/**
	 * Get a child
	 *
	 * @access public
	 * @return $child
	 */
    public function getChild($name) {
		return $this->__call('getChild', [$name]);
    }

}

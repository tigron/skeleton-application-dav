<?php
/**
 * Skeleton Core Application class
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Application;

class Dav extends \Skeleton\Core\Application {

	/**
	 *
	 * FS Path
	 *
	 * @var string $fs_path
	 * @access public
	 */
	public $fs_path = null;

	/**
	 * FS namespace
	 *
	 * @var string $fs_namespace
	 * @access public
	 */
	public $fs_namespace = null;

	/**
	 * Get details
	 *
	 * @access protected
	 */
	protected function get_details() {
		parent::get_details();

		$this->fs_path = $this->path . '/fs/';
		$this->fs_namespace = "\\App\\" . ucfirst($this->name) . "\\Fs\\";

		$autoloader = new \Skeleton\Core\Autoloader();
		$autoloader->add_namespace($this->fs_namespace, $this->fs_path);

		$autoloader->register();
	}

	/**
	 * Load the config
	 *
	 * @access private
	 */
	protected function load_config() {
		/**
		 * Set some defaults
		 */
		$this->config->csrf_enabled = false;
		$this->config->replay_enabled = false;
		$this->config->hostnames = [];
		$this->config->routes = [];

		parent::load_config();
	}

	/**
	 * Run the application
	 *
	 * @access public
	 */
	public function run() {
		$server = new \Skeleton\Application\Dav\Server();
		$server->accept_request();
	}
}

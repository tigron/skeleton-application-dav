<?php
/**
 * Skeleton Core Application class
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Application\Dav;

class Server {

	/**
	 * Run the application
	 *
	 * @access public
	 */
	public function accept_request() {
		$application = \Skeleton\Core\Application::get();
		$application->call_event_if_exists('application', 'bootstrap', [ ]);

		$auth_backend = new \Sabre\DAV\Auth\Backend\BasicCallBack(function($username, $password) use ($application) {
			return $application->call_event_if_exists('dav', 'authenticate', [ $username, $password ]);
		});
		$auth_plugin = new \Sabre\DAV\Auth\Plugin($auth_backend);

		$root = new \Skeleton\Application\Dav\Server\Root($auth_plugin);

		$server = new \Sabre\DAV\Server($root);
		$server->debugExceptions = true;
		$server->setBaseUri('/');

		$browser_plugin = new \Sabre\DAV\Browser\Plugin();
		$server->addPlugin($browser_plugin);

		$guess_plugin = new \Sabre\DAV\Browser\GuessContentType();
		$server->addPlugin($guess_plugin);

		$config = \Skeleton\Core\Config::get();
		$tffp = new \Sabre\DAV\TemporaryFileFilterPlugin($config->tmp_dir);
		$server->addPlugin($tffp);

		$locksBackend = new \Sabre\DAV\Locks\Backend\File('/tmp/davlocks');
		// Add the plugin to the server.
		$locksPlugin = new \Sabre\DAV\Locks\Plugin(
			$locksBackend
		);
		$server->addPlugin($locksPlugin);
		$server->addPlugin($auth_plugin);
		$server->exec();

		$application->call_event_if_exists('application', 'bootstrap', [ ]);
	}
}

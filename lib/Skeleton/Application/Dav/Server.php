<?php
/**
 * Dav server class
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
		$config = \Skeleton\Core\Config::get();
		$application = \Skeleton\Core\Application::get();

		if ($application->event_exists('dav', 'authenticate')) {

			$auth_backend = new \Sabre\DAV\Auth\Backend\BasicCallBack(function($username, $password) use ($application) {
				return $application->call_event_if_exists('dav', 'authenticate', [ $username, $password ]);
			});
			$auth_plugin = new \Sabre\DAV\Auth\Plugin($auth_backend);
			$root = new \Skeleton\Application\Dav\Server\Root($auth_plugin);
		} else {
			$root = $application->call_event_if_exists('dav', 'get_root', [$this]);
		}

		$server = new \Sabre\DAV\Server($root);
		if (isset($config->debug) and $config->debug === true) {
			$server->debugExceptions = true;
		}
		if (isset($config->base_uri)) {
			$server->setBaseUri($config->base_uri);
		} else {
			$server->setBaseUri('/');
		}

		$browser_plugin = new \Sabre\DAV\Browser\Plugin();
		$server->addPlugin($browser_plugin);

		$guess_plugin = new \Sabre\DAV\Browser\GuessContentType();
		$server->addPlugin($guess_plugin);

		$tffp = new \Sabre\DAV\TemporaryFileFilterPlugin($config->tmp_dir);
		$server->addPlugin($tffp);

		$server->on('exception', function($exception) {
			// We need skeleton-error
			if (!class_exists('\Skeleton\Error\Handler\SentrySdk')) {
				return;
			}
			// Check if sentry package is installed
			if (!class_exists('\Sentry\SentrySdk')) {
				return;
			}

			// Is sentry configured?
			if (\Skeleton\Error\Config::$sentry_dsn === null) {
				return;
			}

			$handler = new \Skeleton\Error\Handler\SentrySdk();
			$handler->set_exception($exception);
			$handler->handle();
		});

		$locksBackend = new \Sabre\DAV\Locks\Backend\File($config->tmp_dir . '/dav.lock');
		// Add the plugin to the server.
		$locksPlugin = new \Sabre\DAV\Locks\Plugin(
			$locksBackend
		);
		$server->addPlugin($locksPlugin);
		if (isset($auth_plugin)) {
			$server->addPlugin($auth_plugin);
		}
		$server->exec();
	}
}

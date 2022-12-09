<?php
/**
 * Module Context
 *
 * @author Gerry Demaret <gerry@tigron.be>
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author David Vandemaele <david@tigron.be>
 */

namespace Skeleton\Application\Dav\Event;

abstract class Dav extends \Skeleton\Core\Application\Event {

	/**
	 * Get root
	 *
	 * @access public
	 * @return \Sabre\DAV\Collection $root
	 */
	abstract public function get_root(): \Sabre\DAV\Collection;


}

<?php
/**
 * @copyright Copyright (c) 2017 Robin Appelman <robin@icewind.nl>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Files_ExcludeDirs\AppInfo;

use OCA\Files_ExcludeDirs\Wrapper\Manager;
use OCA\Provisioning_API\UserInfo\UserInfoManager;
use OCP\AppFramework\App;
use OCP\AppFramework\IAppContainer;

class Application extends App {
	public function __construct(array $urlParams = []) {
		parent::__construct('files_excludedirs', $urlParams);

		$container = $this->getContainer();

		$container->registerService(Manager::class, function (IAppContainer $c) {
			$server = $c->getServer();
			return new Manager(
				$server->getConfig()
			);
		});
	}

	public function register() {
		$container = $this->getContainer();
		$manager = $container->query(Manager::class);

		\OCP\Util::connectHook('OC_Filesystem', 'preSetup', $manager, 'setupStorageWrapper');
	}
}

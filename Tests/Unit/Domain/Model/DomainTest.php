<?php
namespace F3\TYPO3\Tests\Unit\Domain\Model;

/*                                                                        *
 * This script belongs to the FLOW3 package "TYPO3".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License as published by the Free   *
 * Software Foundation, either version 3 of the License, or (at your      *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        *
 * You should have received a copy of the GNU General Public License      *
 * along with the script.                                                 *
 * If not, see http://www.gnu.org/licenses/gpl.html                       *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Testcase for the "Domain" domain model
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class DomainTest extends \F3\FLOW3\Tests\UnitTestCase {

	/**
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function setHostPatternAllowsForSettingTheHostPatternOfTheDomain() {
		$domain = new \F3\TYPO3\Domain\Model\Domain();
		$domain->setHostPattern('typo3.com');
		$this->assertSame('typo3.com', $domain->getHostPattern());
	}

	/**
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function setSiteSetsTheSiteTheDomainIsPointingTo() {
		$mockSite = $this->getMock('F3\TYPO3\Domain\Model\Site', array(), array(), '', FALSE);

		$domain = new \F3\TYPO3\Domain\Model\Domain;
		$domain->setSite($mockSite);
		$this->assertSame($mockSite, $domain->getSite());
	}
}

?>
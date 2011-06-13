<?php
namespace F3\TYPO3\Domain\Service;

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
 * The TypoScript Service
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @scope prototype
 * @api
 */
class TypoScriptService {


	/**
	 * @inject
	 * @var \F3\TypoScript\Parser
	 */
	protected $typoScriptParser;

	/**
	 * Returns a merged TypoScript object tree in the context of the given nodes
	 *
	 * The start node and end node mark the starting point and end point of the
	 * path to take while searching for TypoScript configuration. The path of the
	 * start node must be the base path of the end node's path.
	 *
	 * @param \F3\TYPO3CR\Domain\Model\NodeInterface $startNode Node marking the starting point
	 * @param \F3\TYPO3CR\Domain\Model\NodeInterface $endNode Node marking the end point
	 * @return array The merged object tree as of the given node
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function getMergedTypoScriptObjectTree(\F3\TYPO3CR\Domain\Model\NodeInterface $startNode, \F3\TYPO3CR\Domain\Model\NodeInterface $endNode) {
		$contentContext = $startNode->getContext();
		$parentNodes = $contentContext->getNodesOnPath($startNode->getPath(), $endNode->getPath());
		if (!is_array($parentNodes)) {
			return NULL;
		}

		$siteResourcesPackageKey = $contentContext->getCurrentSite()->getSiteResourcesPackageKey();
		$typoScriptsPath = 'resource://' . $siteResourcesPackageKey . '/Private/TypoScripts/';

		$mergedTypoScriptCode = $this->readExternalTypoScriptFiles($typoScriptsPath) . chr(10);
		foreach ($parentNodes as $node) {
			$currentTypoScriptPath = $typoScriptsPath . substr($node->getPath(), strlen($startNode->getPath()));
			$mergedTypoScriptCode .= $this->readExternalTypoScriptFiles($currentTypoScriptPath) . chr(10);

			$typoScriptNodes = $node->getChildNodes('TYPO3:TypoScript');
			foreach ($typoScriptNodes as $typoScriptNode) {
				$mergedTypoScriptCode .= $typoScriptNode->getProperty('sourceCode') . chr(10);
			}
		}
		$this->typoScriptParser->setDefaultNamespace('F3\TYPO3\TypoScript');
		return  $this->typoScriptParser->parse($mergedTypoScriptCode);
	}

	/**
	 * Scans the directory of the given path for .ts2 files, reads them and returns their
	 * content merged as a string.
	 *
	 * @param string $path Path to the directory to read the files from
	 * @return string The merged content of the .ts2 files found
	 * @author Robert Lemke <robert@typo3.org>
	 */
	protected function readExternalTypoScriptFiles($path) {
		$mergedTypoScriptCode = '';
		if (is_dir($path)) {
			$directoryIterator = new \DirectoryIterator($path);
			$filePathsAndNames = array();
			foreach ($directoryIterator as $file) {
				$filename = $file->getFilename();
				if ($file->isFile() && substr($filename, -4) === '.ts2') {
					$filePathsAndNames[] = $file->getPathname();
				}
			}
			natsort($filePathsAndNames);
			foreach ($filePathsAndNames as $filePathAndName) {
				$mergedTypoScriptCode .= \F3\FLOW3\Utility\Files::getFileContents($filePathAndName) . chr(10);
			}
		}
		return $mergedTypoScriptCode;
	}
}
?>
<?php

namespace SD\Tests\Integration\JSONScript;

use MediaWiki\MediaWikiServices;
use SD\Services;
use SMW\Tests\JSONScriptServicesTestCaseRunner;

/**
 * @group SD
 * @group SMWExtension
 * @group Database
 */
class JsonTestCaseScriptRunnerTest extends JSONScriptServicesTestCaseRunner {

	protected function setUp(): void {
		parent::setUp();

		$parser = MediaWikiServices::getInstance()->getParser();
		Services::onParserFirstCallInit( $parser );
	}

	protected function getTestCaseLocation() {
		return __DIR__ . '/TestCases';
	}

	/**
	 * @see JSONScriptTestCaseRunner::getRequiredJsonTestCaseMinVersion
	 * @return string
	 */
	protected function getRequiredJsonTestCaseMinVersion() {
		return '1';
	}

	protected function getPermittedSettings() {
		return array_merge( parent::getPermittedSettings(), [
			"wgRestrictDisplayTitle",
			"sdgHideCategoriesByDefault",
			"sdgHideFiltersWithoutValues",
			"sdgMinValuesForComboBox",
			"sdgResultFormatTypes"
		] );
	}
}

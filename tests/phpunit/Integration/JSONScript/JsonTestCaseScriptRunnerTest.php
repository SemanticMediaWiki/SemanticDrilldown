<?php

namespace SD\Tests\Integration\JSONScript;

use MediaWiki\MediaWikiServices;
use SD\Services;
use SMW\Tests\Integration\JSONScript\JSONScriptTestCaseRunnerTest;

/**
 * @group SD
 * @group SMWExtension
 */
class JsonTestCaseScriptRunnerTest extends JSONScriptTestCaseRunnerTest {

	protected function setUp(): void {
		parent::setUp();

		// register the parser functions for each test
		$parser = MediaWikiServices::getInstance()->getParser();
		Services::onParserFirstCallInit( $parser );
	}

	protected function getTestCaseLocation() {
		return __DIR__ . '/TestCases';
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

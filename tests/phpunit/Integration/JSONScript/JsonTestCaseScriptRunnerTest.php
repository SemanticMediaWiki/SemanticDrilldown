<?php

namespace SD\Tests\Integration\JSONScript;

require_once __DIR__ . '/ExJSONScriptTestCaseRunnerTest.php';

use MediaWiki\MediaWikiServices;
use RuntimeException;
use SD\Services;
use SMW\Tests\Integration\JSONScript\JSONScriptTestCaseRunnerTest;

/**
 * @group SD
 * @group SMWExtension
 * @group Database
 */
class JsonTestCaseScriptRunnerTest extends ExJSONScriptTestCaseRunnerTest {

	protected function setUp(): void {
		parent::setUp();

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

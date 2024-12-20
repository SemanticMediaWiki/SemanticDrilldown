<?php

namespace SD\Tests\Integration\JSONScript;

require_once __DIR__ . '/JSONScriptTestCaseRunnerTest.php';

use MediaWiki\MediaWikiServices;
use RuntimeException;
use SD\Services;
use SMW\Tests\Integration\JSONScript\JSONScriptTestCaseRunnerTest;
use SMW\Tests\Utils\Connection\TestDatabaseTableBuilder;

/**
 * @group SD
 * @group SMWExtension
 * @group Database
 */
class JsonTestCaseScriptRunnerTest extends ExJSONScriptTestCaseRunnerTest {

	/**
	 * Table name prefix.
	 */
	public const DB_PREFIX = 'sunittest_';

	protected function setUp(): void {
		parent::setUp();

		$testDatabaseTableBuilder = TestDatabaseTableBuilder::getInstance(
			$this->getStore()
		);

		try {
			$testDatabaseTableBuilder->doBuild();
		} catch ( RuntimeException $e ) {
		}

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

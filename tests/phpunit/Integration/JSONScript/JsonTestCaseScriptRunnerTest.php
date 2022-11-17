<?php

namespace SD\Tests\Integration\JSONScript;

use SMW\Tests\Integration\JSONScript\JSONScriptTestCaseRunnerTest;

/**
 * @group SD
 * @group SMWExtension
 */
class JsonTestCaseScriptRunnerTest extends JSONScriptTestCaseRunnerTest {

	protected function getTestCaseLocation() {
		return __DIR__ . '/TestCases';
	}

	protected function getPermittedSettings() {
		return array_merge( parent::getPermittedSettings(), [
			"wgRestrictDisplayTitle",
			"sdgHideCategoriesByDefault",
			"sdgMinValuesForComboBox",
			"sdgResultFormatTypes"
		] );
	}

}

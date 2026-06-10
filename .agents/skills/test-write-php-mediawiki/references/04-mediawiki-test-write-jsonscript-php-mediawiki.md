**Procedure — test:write · JSONScript · MediaWiki**

**Detecting JSONScript in the project**

JSONScript is used by SemanticMediaWiki and closely related extensions
(e.g., PageForms, SemanticResultFormats, SemanticDrilldown). Before
writing or suggesting JSONScript tests, check whether the framework is
already in use:

``` console
find tests/phpunit -name "JsonTestCaseScriptRunnerTest.php" | head -1
```

If the file exists, the extension already uses JSONScript and new
integration tests for parser functions, special pages, and API actions
should follow the same pattern.

If the file does **not** exist, do **not** introduce JSONScript
automatically. Instead, note that JSONScript could be added if the
extension depends on SemanticMediaWiki, and ask the user whether to set
it up.

**When to use JSONScript**

JSONScript integration tests cover behavior that spans the full
MediaWiki stack: parser functions, special pages, and API actions. Use
JSONScript when the behavior under test cannot be asserted without a
real wiki page, form definition, or semantic store.

Use conventional PHPUnit (`MediaWikiIntegrationTestCase`) for isolated
PHP logic that does not depend on rendering, special pages, or the
semantic data store.

**File naming**

Name each test file after the area, subject, and — when a file covers a
specific behavior — the behavior, separated by an underscore:

    {area}-{subject}.json
    {area}-{subject}_{behavior}.json

Area prefixes in use:

| Prefix       | Covers                                                                 |
|--------------|------------------------------------------------------------------------|
| `api-`       | MediaWiki API actions                                                  |
| `forminput-` | Form input field rendering (special page `FormEdit`)                   |
| `pf-`        | PageForms parser functions (`#formlink`, `#forminput`, `#autoedit`, …) |
| `special-`   | Other special page tests                                               |

Examples: `forminput-dropdown.json`, `forminput-dropdown-reedit.json`,
`api-pfautocomplete_property-text-value-search.json`.

**Test data naming**

All pages created in `setup` must be uniquely nameable across the full
test suite. Use PascalCase throughout. Do **not** use plain descriptive
names such as `"My Form"` or `"Test Page"` — these will collide across
test files.

Pattern:

    {ExtPrefix}Test{ContextAbbr}{Role}{NN}

| Segment         | Meaning                                                                                                                                                 |
|-----------------|---------------------------------------------------------------------------------------------------------------------------------------------------------|
| `{ExtPrefix}`   | Extension abbreviation (`PF`, `SMW`, `CT`, …)                                                                                                           |
| `Test`          | Fixed marker — identifies all pages as test data at a glance                                                                                            |
| `{ContextAbbr}` | Abbreviated context derived from the test file name (e.g., `AC` for api-pfautocomplete\_\*, `DD` for `forminput-dropdown`, `Text` for `forminput-text`) |
| `{Role}`        | Semantic role: `Prop`, `Tpl`, `Form`, `Subj`, `Page`, `Cat`                                                                                             |
| `{NN}`          | Zero-padded two-digit sequence number (`01`, `02`, …)                                                                                                   |

Examples: `PFTestACTextPage02`, `PFTestDDFormReEdit01`,
`PFTestDDSubjMandatory01`.

Field names and stored values embedded in page content follow the same
convention: `PFTestDDFieldStatus`, `PFTestDDStoredValue01`.

Form and template names used only as readable labels in query-parameters
(not stored as wiki pages) may use plain descriptive text when they are
scoped exclusively within that test file and do not risk collision.

**JSON structure**

Every test file contains these top-level keys:

``` json
{
  "description": "One-line description of what this file tests",

  "setup": [
    { "namespace": "SMW_NS_PROPERTY", "page": "PFTestACProp01",
      "contents": "[[Has type::Text]]" },
    { "namespace": "PF_NS_FORM",      "page": "PFTestACForm01",
      "contents": "{{{for template|PFTestACTpl01}}}\n{{{field|F|input type=text}}}\n{{{end template}}}" },
    { "namespace": "NS_TEMPLATE",     "page": "PFTestACTpl01",
      "contents": "{{{F|}}}" },
    { "page": "PFTestACSubj01",
      "contents": "{{PFTestACTpl01|F=PFTestACValue01}}" }
  ],

  "tests": [
    {
      "type": "special",
      "about": "Re-edit: stored value appears pre-filled",
      "special-page": {
        "page": "FormEdit",
        "query-parameters": "PFTestACForm01/PFTestACSubj01",
        "request-parameters": {}
      },
      "assert-output": {
        "to-contain":   ["value=\"PFTestACValue01\""],
        "not-contain":  ["someUnexpectedString"]
      }
    }
  ],

  "beforeTest": {
    "job-run": ["smw.update"]
  },

  "settings": {
    "wgLang": "en",
    "smwgNamespacesWithSemanticLinks": { "NS_MAIN": true }
  },

  "requires": {
    "DisplayTitle": "*"
  },

  "meta": {
    "version": "2",
    "is-incomplete": false,
    "debug": false
  }
}
```

**Assertion types**

| `type`        | Use for                                                                                                                                     |
|---------------|---------------------------------------------------------------------------------------------------------------------------------------------|
| `special`     | Special page output (e.g., `FormEdit`). Set `special-page.page`, `query-parameters` (`FormName/PageName`), and `request-parameters`.        |
| `api`         | MediaWiki API calls. Set `api.parameters` (`action`, `format`, plus action-specific params). `assert-output` checks the JSON response body. |
| `parser`      | Wikitext rendering of a `subject` page. Validates rendered output strings or semantic store data via `assert-store`.                        |
| `parser-html` | HTML structure validation using CSS selectors: `"p > a[title='Foo']"` or `["table > tr", 3]` (exact element count).                         |

`assert-output` keys:

- `to-contain` — string or array of strings that must appear in the
  output

- `not-contain` / `to-not-contain` — strings that must **not** appear

- `in-sequence: true` — enforces the order of entries in `to-contain`

- `to-be-valid-html: true` — validates HTML well-formedness (parser-html
  only)

**Run a specific file**

``` console
composer phpunit -- --filter forminput-dropdown-reedit.json
```

Use `"debug": true` in `meta` to print the full rendered output to the
console during a failing test run.

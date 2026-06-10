---
applyTo: "**/*.{js,css,less}"
---
<!-- AUTO-GENERATED from docs/gesinn-it-docs-master-pub/documents/mediawiki/instructions/js.adoc -->

**Procedure — code:write**

1.  Write a failing test that specifies the expected behavior. The test
    must fail before you write any implementation.

2.  Write the minimum implementation to make the test pass.

3.  Refactor if needed — tests must stay green.

4.  Never write implementation before a failing test exists.

**Procedure — code:fix**

1.  Reproduce the bug with a failing test first. This test is the proof
    the bug exists.

2.  Fix the code until the test passes.

3.  Never fix code without a reproducing test — you cannot verify the
    fix is correct.

**Procedure — code:refactor**

1.  Run the full test suite first. All tests must be green before you
    start.

2.  Check test coverage for the files you intend to change. If coverage
    is below ~80% on the affected code paths, warn explicitly before
    proceeding: low coverage means the refactoring cannot be verified
    safely. Do not block, but make the risk visible.

3.  Make structural changes (extract method, rename, move class, etc.).

4.  Run the full test suite again. All tests must still be green.

5.  If a test breaks, you changed behavior — revert the change or
    explicitly justify updating the test.

6.  Never change test logic during a refactor unless the test itself was
    wrong.

**Procedure — test:write**

The goal is correct code, not just passing tests. Use the
**specification** (issue, docs, method name, contract) as the source of
truth — never the current output of the production code.

1.  Check whether the described behavior is already covered by existing
    tests.

2.  Understand the **intent** of the code under test: what should it do,
    for whom, under which conditions? Read the specification, not just
    the implementation.

    - If no specification exists (no issue description, no docs, no
      method contract) and the intent cannot be confidently derived from
      the code alone: **stop and ask**. State what is unclear and what
      information is needed before proceeding. Do not infer tests from
      implementation details alone.

3.  Write the new test(s) that assert the intended behavior —
    independently of how the code currently works.

4.  Run the targeted test class.

    - If all new tests are green: the code matches its specification.
      Done.

    - If a new test fails: the code deviates from its specification —
      this is a bug discovery. Do **not** adjust the test to match the
      actual output. Fix the production code so it fulfills the
      specification (follow the `fix` procedure for the code change).
      The test stays as written.

5.  Never adjust a test to match incorrect production code behavior.

**Coding Conventions — MediaWiki**

All source files regardless of language must follow these baseline
rules. They are enforced by `make ci` (lint + phpcs + eslint).

- Encoding: UTF-8 without BOM

- Line endings: Unix-style LF (not CR+LF)

- Indentation: tabs, not spaces

- Maximum line length: 120 characters

- No trailing whitespace

- Newline at end of file

**Coding Conventions — JavaScript · MediaWiki**

Tooling: [ESLint](https://eslint.org/) with
[eslint-config-wikimedia](https://github.com/wikimedia/eslint-config-wikimedia).
Run locally: `npm run lint:js` (or `make ci`).

**ESLint configuration**

Every repository must have a `.eslintrc.json` at root with
`"root": true`:

``` json
{
  "root": true,
  "extends": [
    "wikimedia/client/es2016",
    "wikimedia/jquery",
    "wikimedia/mediawiki"
  ],
  "env": { "commonjs": true }
}
```

**Module system**

- CommonJS modules: `require()` for imports, `module.exports` for
  exports

- Register modules with ResourceLoader; bundle name pattern:
  `ext.myExtension`

- JS class files match the class name exactly (`TitleWidget.js` for
  `TitleWidget`)

**Naming**

- Variables and methods: lowerCamelCase

- Constructors / classes: UpperCamelCase

- jQuery objects: `$`-prefix (`$button`, not `button`)

- Constants: `ALL_CAPS`

- Acronyms as single words: `getHtmlApiSource`, not `getHTMLAPISource`

**Code style**

- Tabs for indentation; single quotes for string literals

- `===` and `!==`; no Yoda conditions

- Spaces inside parentheses: `if ( foo )`, `getFoo( bar )`

- `const` and `let` — never `var` in new code

- Arrow functions for callbacks

**jQuery**

- Prefer ES6/DOM equivalents over deprecated jQuery methods (`.each` →
  `forEach`, etc.)

- Never search the full DOM with `$( '#id' )` or `$( '.selector' )`; use
  hook-provided `$content` and call `.find()` on it *(full-DOM queries
  match stale or foreign nodes, break hook-lifecycle isolation, and
  waste performance by traversing the entire document)*

- Prefer `$( '<div>' ).text( value )` over `$( '<div>text</div>' )` to
  avoid XSS

**MediaWiki APIs**

- Access configuration via `mw.config.get( 'wgFoo' )`, never direct
  globals

- Expose public API via `module.exports` or within the `mw` namespace
  (e.g. `mw.echo.Foo`)

- Use `mw.storage` / `mw.storage.session` for
  localStorage/sessionStorage

- Storage keys: `mw`-prefix + camelCase/hyphens (e.g.
  `mwedit-state-foo`)

**Coding Conventions — CSS/LESS · MediaWiki**

Tooling: [stylelint](https://stylelint.io/) via `npm run lint:styles`
(or `make ci`). ResourceLoader natively compiles `.less` files; prefer
LESS over plain CSS.

**Class and ID naming**

- Classes and IDs: all-lowercase, hyphen-separated

- Use an extension-specific prefix to avoid conflicts (e.g. `pf-`,
  `smw-`, `mw-`)

- LESS mixin names: `mixin-` prefix + hyphen-case (e.g.
  `mixin-screen-reader-text`)

**Whitespace and formatting**

- One selector per line, one property per line

- Opening brace on the same line as the last selector

- Tab indentation for properties and nested rules

- Semicolon after every declaration, including the last

- Empty line between rule sets

**Colors**

- Lowercase hex shorthand preferred: `#fff`, `#252525`

- `rgba()` when alpha transparency is needed; `transparent` keyword
  otherwise

- No named color keywords (except `transparent`), no `rgb()`, `hsl()`,
  `hsla()`

- Ensure color contrast meets [WCAG 2.0
  AA](https://www.w3.org/TR/WCAG20/)

**LESS specifics**

- CSS custom properties (design tokens) preferred over LESS variables
  for new code

- `@import` only for mixins and variables (`variables.less`,
  `mixins.less`); do not use `@import` for bundling conceptually related
  files

- Omit `.less` extension in `@import` statements

- Bundle related files via the `styles` array in `skin.json` /
  `extension.json`

**Anti-patterns to avoid**

- `!important` — avoid except when overriding upstream code that also
  uses it

- `z-index` — use natural DOM stacking order where possible; document
  exceptions

- Inline `style` attributes — always use stylesheet classes instead

- `float` / `text-align: left` hardcoded — use `/* @noflip */`
  annotation when needed, otherwise ResourceLoader’s CSSJanus handles
  RTL automatically

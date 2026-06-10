**Coding Conventions — PHP**

**File structure**

- Every file starts with `declare( strict_types=1 );`

- No closing `?>` tag

- One class per file; filename matches class name (UpperCamelCase, e.g.
  `MyClass.php`)

**Namespaces and autoloading**

- PSR-4 via Composer (`autoload.psr-4` in `composer.json`)

- Acronyms treated as single words: `HtmlId`, not `HTMLId`

**Naming**

| Element                     | Convention     | Example            |
|-----------------------------|----------------|--------------------|
| Classes, interfaces, traits | UpperCamelCase | `PageFormParser`   |
| Methods, variables          | lowerCamelCase | `getFormContent()` |
| Constants                   | UPPER_CASE     | `MAX_FORM_SIZE`    |

**Type system**

- Use native type declarations on all parameters, properties, and return
  types

- PHPDoc only when native types are insufficient (e.g. `string[]`,
  `array<string, Foo>`)

- Nullable parameters: `?Type`, not `Type $x = null`

- Prefer `??` (null coalescing) and `??=` over ternary isset checks

- Use arrow functions `fn( $x ) => $x * 2` for single-expression
  closures

**Modern PHP features (target: PHP 8.1+)**

- Constructor property promotion

- `readonly` properties for immutable value objects

- `enum` instead of class constant groups

- `match()` instead of `switch` when returning a value

**Code style**

- Indentation: tabs, not spaces

- 1TBS brace style — opening brace on same line, `else`/`elseif` on
  closing brace line

- Always use braces, even for single-line blocks

- Spaces inside parentheses: `getFoo( $bar )`, empty: `getBar()`

- Spaces around binary operators: `$a = $b + $c`

- Single quotes preferred; double quotes for string interpolation

- `===` strict equality; `==` only when type coercion is intentional

- No Yoda conditions: `$a === 'foo'`, not `'foo' === $a`

- `elseif` not `else if`

- `true`, `false`, `null` always lowercase

**Architecture**

- `private` by default; `protected` only when subclass access is needed

- Dependency injection over direct instantiation — delegate `new Foo()`
  to factories

- Single Responsibility: one class, one concern

- Order class members: `public` → `protected` → `private`

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

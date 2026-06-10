**Changelog Convention**

This project maintains a `CHANGELOG.md` following [Keep a
Changelog](https://keepachangelog.com/en/1.1.0/). Versions follow
[Semantic Versioning](https://semver.org/). Version numbers have no `v`
prefix.

**Format**

``` markdown
# Changelog

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](https://semver.org/) and
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Added
- ...

## [1.2.0] - 2026-06-09

Adds autocomplete support and fixes parser edge cases. Removes the deprecated
`oldMethod()` API — see Breaking Changes below.

### Breaking Changes
- Remove deprecated `oldMethod()` API [`a1b2c3d`](https://github.com/org/repo/commit/a1b2c3d)

### Added
- Add autocomplete endpoint [`6e376e3`](https://github.com/org/repo/commit/6e376e3)

### Changed
- Bump dependency from 1.0.0 to 2.0.0 [`4fb2375`](https://github.com/org/repo/commit/4fb2375)
- Refactor token parser for clarity [`9a3c1f2`](https://github.com/org/repo/commit/9a3c1f2)
- Update installation documentation [`78d57e5`](https://github.com/org/repo/commit/78d57e5)

### Fixed
- Handle empty token list in parser [`43b9f08`](https://github.com/org/repo/commit/43b9f08) ([#42](https://github.com/org/repo/issues/42))

[Unreleased]: https://github.com/org/repo/compare/1.2.0...HEAD
[1.2.0]: https://github.com/org/repo/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/org/repo/releases/tag/1.1.0
```

**Change categories**

Map conventional commit types to changelog categories as follows:

| Commit type                                | Changelog category              |
|--------------------------------------------|---------------------------------|
| `feat`                                     | Added                           |
| `fix`                                      | Fixed                           |
| `deps`                                     | Changed                         |
| `refactor`                                 | Changed                         |
| `docs`                                     | Changed                         |
| Breaking change (`!` or `BREAKING CHANGE`) | Breaking Changes (always first) |
| `ci`, `chore`, `test`                      | — omit from changelog           |

**Rules**

- Every version has an entry — no skipped releases.

- Latest version comes first. `[Unreleased]` is always at the top.

- Each version may open with a short introductory sentence summarising
  the release theme (optional but recommended for notable releases).

- Each entry includes the short commit hash as a link, appended at the
  end of the line.

- If the change relates to a GitHub issue or PR, append the issue/PR
  link after the commit hash: `` [`commit ``\](url) (\[#42\](url))\`.

- Dates use ISO 8601 format (`YYYY-MM-DD`).

- Yanked releases are marked: `## [1.2.0] - 2026-06-09 [YANKED]`

- Compare links are maintained at the bottom of the file.

- The `[Unreleased]` section is updated continuously as notable changes
  are committed — do not wait until release time.

**Rotation at major releases**

When a new MAJOR version is released, the previous major’s history is
rotated out of `CHANGELOG.md` into a dedicated archive file:

1.  Move all entries for the previous major (e.g. all `1.x.x` sections)
    into `CHANGELOG-1.x.md`.

2.  Add a link at the bottom of `CHANGELOG.md`:

    `Older releases: [1.x](CHANGELOG-1.x.md)`

3.  `CHANGELOG.md` then contains only the current major’s releases plus
    `[Unreleased]`.

4.  Archive files are never modified after creation.

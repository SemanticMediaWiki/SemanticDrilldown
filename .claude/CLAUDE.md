<!-- THIS FILE IS AUTO-GENERATED. Edit AGENTS-source.adoc instead. -->

# Conventional Commits Policy

**Commit Convention — Conventional Commits**

Commit messages follow the [Conventional Commits
specification](https://www.conventionalcommits.org/).

Commit format:

`type(scope): short description`

The scope is optional and should describe the affected subsystem,
module, or dependency when useful.

Examples:

- feat(api): add autocomplete endpoint

- fix(parser): handle empty token lists

- docs(readme): explain input architecture

- refactor(parser): simplify token parsing

- deps(smw): bump from 5.1.0 to 5.2.0

- ci(github): update workflow configuration

- test(api): add autocomplete tests

Recommended commit types:

- `feat` — new functionality

- `fix` — bug fixes

- `deps` — dependency updates

- `docs` — documentation changes

- `refactor` — internal code changes without behavioral change

- `test` — tests added or updated

- `ci` — changes to continuous integration configuration

- `chore` — repository maintenance tasks without impact on runtime
  behavior

Dependency updates:

- Use the `deps` type for dependency upgrades

- The scope should identify the dependency being updated

- Include the version change when applicable

Example:

- deps(smw): bump from 5.1.0 to 5.2.0

Guidelines:

- Use the imperative mood (e.g. "add feature", not "added feature")

- Keep the subject line concise

- Use the commit body to explain **why**, not only **what**

- Scopes should be short, lowercase identifiers (e.g. `api`, `parser`,
  `smw`, `mediawiki`, `docker`)

- Use `chore` only for repository maintenance tasks that do not affect
  runtime behavior, dependencies, CI configuration, or tests

- Do **not** add a `Co-Authored-By:` trailer or any agent attribution
  line to the commit message

Changelog:

- After committing a `feat`, `fix`, `deps`, `refactor`, or `docs`
  change, add a corresponding entry to the `[Unreleased]` section of
  `CHANGELOG.md` — do not wait until release time.

# Versioning and Releases

**Versioning Convention — Semantic Versioning**

This project follows [Semantic Versioning](https://semver.org/).

Version numbers follow the format:

`MAJOR.MINOR.PATCH`

Version increment rules:

- MAJOR — incompatible or breaking changes

- MINOR — backwards-compatible feature additions

- PATCH — backwards-compatible bug fixes

Breaking changes include (but are not limited to):

- incompatible API changes

- removal or renaming of public interfaces

- behavior changes that may break existing integrations

- increased minimum runtime or dependency requirements

- incompatible configuration or data format changes

- dependency upgrades that introduce breaking changes for users

Breaking changes must always increment the MAJOR version.

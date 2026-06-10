**Procedure — PHP Coverage · MediaWiki**

Look up the matrix entry with `coverage: true` in
`.github/workflows/ci.yml` and use exactly those version parameters for
all local coverage commands.

Run the full coverage suite (generates HTML + Clover XML under
`build/coverage/php/`):

``` console
make install composer-test-coverage MW_VERSION=<mw> PHP_VERSION=<php> SMW_VERSION=<smw> DT_VERSION=<dt>
```

The coverage output is mounted from the container to the host and is
available at `build/coverage/php/` after the run:

- `build/coverage/php/index.html` — browsable HTML report

- `build/coverage/php/coverage.xml` — Clover XML (used by Codecov)

To analyse coverage for a **specific file**, bash into the running
container after the `make install` step and run PHPUnit directly with
`--coverage-clover` and a `--filter` matching the test class name.

<div class="important">

`XDEBUG_MODE=coverage` must be set explicitly — without it, no coverage
data is collected and the output will show 0% for everything.

</div>

``` console
make install MW_VERSION=<mw> PHP_VERSION=<php> SMW_VERSION=<smw> DT_VERSION=<dt>
make bash
> XDEBUG_MODE=coverage composer phpunit -- --coverage-clover /tmp/cov.xml --filter <YourTestFilter>
```

<div class="note">

`--filter` matches against test **class and method names**, not source
file names (e.g. to cover `MyClass.php` filter for the test class
`MyClassTest`).

</div>

Extract coverage numbers from the Clover XML inside the container:

``` console
> python3 - <<'EOF'
import xml.etree.ElementTree as ET
tree = ET.parse('/tmp/cov.xml')
for f in tree.getroot().iter('file'):
    if '<YourClass>' in f.get('name', ''):
        covered = [int(l.get('num')) for l in f.iter('line') if int(l.get('count', 0)) > 0]
        missed  = [int(l.get('num')) for l in f.iter('line') if int(l.get('count', 0)) == 0]
        total   = len(covered) + len(missed)
        pct     = round(len(covered)/total*100, 1) if total else 0
        methods = {}
        for l in f.iter('line'):
            n = l.get('name')
            if n:
                methods.setdefault(n, 0)
                if int(l.get('count', 0)) > 0:
                    methods[n] += 1
        cov_m = sum(1 for v in methods.values() if v > 0)
        print(f'Lines:   {len(covered)}/{total} ({pct}%)')
        print(f'Methods: {cov_m}/{len(methods)}')
EOF
```

To extract exact covered/missed line numbers from the generated Clover
XML on the host:

``` console
python3 - <<'EOF'
import xml.etree.ElementTree as ET
tree = ET.parse('build/coverage/php/coverage.xml')
covered, missed = [], []
for f in tree.getroot().iter('file'):
    if '<YourClass>' in f.get('name', ''):
        for line in f.iter('line'):
            (covered if int(line.get('count', 0)) > 0 else missed).append(int(line.get('num')))
print(f'Covered ({len(covered)}): {covered}')
print(f'Missed  ({len(missed)}):  {missed}')
EOF
```

**Before/after coverage comparison**

When adding tests to improve coverage, always measure before **and**
after and present the comparison to the user. Both measurements reuse
the same running container — no second `make install` is needed.

*AFTER* measurement: run the coverage command above with the new tests
in place and save the output.

*BEFORE* measurement: temporarily swap the test file inside the running
container with the old version from git, measure, then restore:

``` console
# Save old test file from git to host
git show <old-commit>:tests/phpunit/<path/to/YourClassTest.php> > /tmp/YourClassTest_before.php

# Copy it into the container, measure, restore
make bash
> cp tests/phpunit/<path/to/YourClassTest.php> /tmp/YourClassTest_new.php
> cp /tmp/YourClassTest_before.php tests/phpunit/<path/to/YourClassTest.php>
> XDEBUG_MODE=coverage composer phpunit -- --coverage-clover /tmp/cov_before.xml --filter <YourTestFilter>
> cp /tmp/YourClassTest_new.php tests/phpunit/<path/to/YourClassTest.php>
```

Then extract numbers from `/tmp/cov_before.xml` using the Python snippet
above, and compare with the AFTER numbers.

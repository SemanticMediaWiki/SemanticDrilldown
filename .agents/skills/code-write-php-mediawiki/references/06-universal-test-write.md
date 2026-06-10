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

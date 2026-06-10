**Procedure — code:fix**

1.  Reproduce the bug with a failing test first. This test is the proof
    the bug exists.

2.  Fix the code until the test passes.

3.  Never fix code without a reproducing test — you cannot verify the
    fix is correct.

4.  If the fix addresses a reported issue: after pushing, close the
    issue in the issue tracker with a comment referencing the commit.

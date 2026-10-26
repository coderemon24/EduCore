# Git Commit Automation Instruction

When this file is referenced or invoked by the user, the AI assistant must automatically stage, commit, and push all recent changes using a meaningful, concise, and well-structured commit message.

---

### Workflow for the AI Assistant

1. **Commit Timing & Atomic Rule (Zero Garbage Commits)**:
   - **Never commit micro-changes**: Do NOT commit after editing 1 line of CSS, fixing a minor typo, or running a trial-and-error experiment.
   - **Commit only when a full logical unit is done**: A complete page design, a complete backend feature slice with tests green, or a complete bugfix.
   - Check modified and untracked files with `git status -s`.
   - Review changes with `git diff` to formulate an accurate, self-explanatory summary.

2. **Generate Human-Readable Commit Message**:
   - Follow standard Conventional Commit syntax:
     - `feat:` for new features or complete page implementations
     - `fix:` for verified bug fixes
     - `refactor:` for code restructuring without behavior change
     - `chore:` for build tooling, dependencies, or configuration
   - **Structure**:
     - **Title**: `<type>(<scope>): <active verb summary of completed feature>`
     - **Body**: Detailed bulleted list explaining what was added, architectural decisions, and verification proof.
   - **Prohibited Anti-Patterns**:
     - ❌ `update file`
     - ❌ `fix bug`
     - ❌ `changes`
     - ❌ `feat: changes`
     - ❌ Vague one-word messages without context.

3. **Execute Git Commands**:
   Run the following sequence in the project root:
   ```bash
   git add .
   git commit -m "<type>(<scope>): <clear readable description of changes>" -m "- Added: <key files/components>\n- Details: <architectural choices or UI recipes>\n- Verification: <tests passed or responsiveness verified>"
   git push -u origin main
   ```

4. **Confirm**:
   Output the commit hash, message, and push status to the user.

---

### Manual Execution Helper

If running manually in PowerShell / Terminal:

```bash
git add .
git commit -m "feat(auth): implement admin authentication with AdminAuthentication middleware and brute-force protection" -m "- Added: AdminAuthentication middleware, route throttling\n- Database: added failed_attempts counter to admins table\n- Tests: verified lockout with 5 consecutive invalid attempts"
git push -u origin main
```

---
name: Rebrand Plugin Template
description: Update plugin identity, slug/path mapping, and project metadata in one consistent pass based on RUNBOOK rebrand guidance.
argument-hint: Provide plugin_name, plugin_slug, text_domain, author, author_uri, and optional prefix_old/prefix_new
---

## When to use this prompt

- When creating a new plugin from this template and you need to rename/rebrand it safely.
- When your plugin slug/path is changing and you want to avoid mount/path mistakes.
- Before release prep, to ensure naming and metadata are consistent.

## What you'll get

- A single guided rebrand pass across all relevant files.
- A short pre-edit plan before any changes are applied.
- A mandatory confirmation checkpoint before any edits are applied.
- A post-edit summary of what changed and what was intentionally left unchanged.
- Quick validation checks for consistency.

## Inputs required

Collect and confirm these values before editing:

- `plugin_name` (WordPress admin display name)
- `plugin_slug` (folder/path slug)
- `text_domain`
- `author`
- `author_uri`
- `cron_required` (`yes` or `no`)
- `wp_cli_required` (`yes` or `no`)
- `prefix_old` (optional; default `plugin_name` / `Plugin_Name` variants)
- `prefix_new` (optional; only if prefix rebrand requested)
- `security_contact_url` (optional; only if known)

## Repository naming requirement

- For default Plugin Distribution slug behavior, repositories created from this template should end in `-dev`.
- If the repository does not end in `-dev`, require an explicit `PLUGIN_SLUG` (or manual `plugin_slug`) in distribution configuration.

If any required value is missing, ask for it and stop edits until provided.

## Step 1 - Present change plan first

Before editing files, show a short plan table based on the resolved inputs:

```
| Area | Files | Planned updates |
|------|-------|-----------------|
| Plugin identity | src/plugin-name.php, src/includes/*.php, src/admin/*.php | Plugin labels, author, text domain, naming comments |
| Optional features | src/plugin-name.php, src/includes/class-plugin-name.php, src/includes/class-plugin-name-activator.php, src/includes/class-plugin-name-deactivator.php, src/admin/class-plugin-name-admin-cli.php, README.md, RUNBOOK.md | Keep/adapt/remove cron and WP-CLI scaffolding based on required inputs |
| Slug/path mapping | .devcontainer/docker-compose.yml | Plugin mount path alignment |
| Metadata | composer.json, .devcontainer/devcontainer.json, phpcs.xml, README.md | Package/display/template metadata |
| Distribution | .github/workflows/plugin-distribution.yml | Plugin slug defaults/variables (if needed) |
| Optional prefix | src/**/*.php, README.md, RUNBOOK.md | Prefix rename notes/usages if requested |
```

## Step 2 - Require explicit confirmation before edits

After the plan table, present a short resolved values summary and include this exact instruction:

`Please reply with 'confirm' to go ahead.`

Do not edit any files until the user replies with `confirm`.

## Step 3 - Apply optional feature decisions first

1. If `cron_required=no`, remove or disable cron scaffolding before broader rebrand edits:
  - [src/includes/class-plugin-name-cron.php](src/includes/class-plugin-name-cron.php)
  - [src/includes/class-plugin-name-activator.php](src/includes/class-plugin-name-activator.php)
  - [src/includes/class-plugin-name-deactivator.php](src/includes/class-plugin-name-deactivator.php)
  - [src/includes/class-plugin-name.php](src/includes/class-plugin-name.php)
2. If `wp_cli_required=no`, remove or disable WP-CLI scaffolding before broader rebrand edits:
  - [src/admin/class-plugin-name-admin-cli.php](src/admin/class-plugin-name-admin-cli.php)
  - [src/includes/class-plugin-name.php](src/includes/class-plugin-name.php)
3. If either feature is removed, update [README.md](README.md) and [RUNBOOK.md](RUNBOOK.md) so shipped docs match the chosen feature set.
4. If `cron_required=yes` and/or `wp_cli_required=yes`, keep scaffolding and continue with normal rebrand updates.

## Step 4 - Update plugin identity files

1. `src/plugin-name.php`
  - Update `Plugin Name`, `Author`, `Author URI`, and `Text Domain`.
  - Update plugin URI/description placeholders.

2. `src/includes/*.php` and `src/admin/*.php`
  - Update package/class naming comments where applicable.
  - Update text-domain literals if present and rebrand-related.

3. `src/languages/plugin-name.pot`
  - Update project/version metadata if needed.

## Step 5 - Update slug/path-dependent files together

If `plugin_slug` differs from current slug, update all path mappings in the same pass:

1. `.devcontainer/docker-compose.yml`
  - Update plugin mount path under `/var/www/html/wp-content/plugins/...`.

2. Distribution defaults in `.github/workflows/plugin-distribution.yml`.

Never update only one slug/path-dependent location.

## Step 6 - Update project metadata

1. `composer.json`
  - Update package name/description to match the rebrand.

2. `.devcontainer/devcontainer.json`
  - Update container display name if project naming changed.

3. `phpcs.xml`
  - Update ruleset name/description if project naming changed.

4. `README.md` and `RUNBOOK.md`
  - Update checklist/examples to match new naming.

## Step 7 - Optional prefix rebrand

Only if explicitly requested:

- Rename function/class prefix from `plugin_name`/`Plugin_Name` to requested values in `src/**/*.php`.
- Update any related notes in `README.md` and `RUNBOOK.md`.

If not requested, keep existing prefixes and state that they were intentionally unchanged.

## Step 8 - Validation

After edits:

1. Re-open all changed files and verify:
  - `plugin_name`, `plugin_slug`, and `text_domain` are consistent.
  - Plugin mount path matches `.devcontainer/docker-compose.yml`.
  - Distribution variables and README/RUNBOOK instructions align.

2. Run repository lint/check commands that are already documented and available.
  - Prefer minimal relevant checks.
  - If a check cannot run, report why.

3. Provide a final summary table:

```
| File | Change summary |
|------|----------------|
| ...  | ...            |
```

Include a short "Not changed" list for optional areas you intentionally skipped.

## Rules

- Do not make edits until required inputs are confirmed.
- Do not make edits until the user replies with `confirm`.
- Treat `cron_required` and `wp_cli_required` as mandatory yes/no decisions before any file edits.
- Keep changes scoped to rebrand and path-alignment concerns.
- Do not refactor unrelated code.
- If expected values are ambiguous, ask before editing.
- Preserve existing formatting/style in touched files.

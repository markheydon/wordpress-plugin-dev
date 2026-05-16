# Plugin Template Runbook

This runbook is the detailed end-to-end guide for using this repository as a plugin template and validating the distribution workflow.

Use [README.md](README.md) for quick-start basics and checklists.

## Scope

This repository has two responsibilities:

- Local development and quality tooling for plugin source.
- Automated delivery of plugin-only files from [src](src) using [plugin-distribution.yml](.github/workflows/plugin-distribution.yml).

## End-To-End Path A: Create A New Plugin From This Template

Optional fast path: use the GitHub Copilot prompt [.github/prompts/plugin-rebrand.prompt.md](.github/prompts/plugin-rebrand.prompt.md) to run this full rebrand flow in one guided pass.

### 1. Create your repository from this template

1. Create a new repository from this template using a name that ends with `-dev` if you want default Plugin Distribution slug behavior.
2. Clone/open it in VS Code.
3. Reopen in Dev Container and wait for setup to complete.

### 2. Set your plugin identity

Update these first:

1. [src/plugin-name.php](src/plugin-name.php)
   - Set `Plugin Name`.
   - Set `Plugin URI` and `Description`.
   - Set `Author` and `Author URI`.
   - Set `Text Domain`.

2. [src/includes/class-plugin-name.php](src/includes/class-plugin-name.php) and [src/admin/class-plugin-name-admin-cli.php](src/admin/class-plugin-name-admin-cli.php)
   - Update package labels and project naming comments if needed.

3. [src/languages/plugin-name.pot](src/languages/plugin-name.pot)
   - Refresh translation template metadata if needed.

### 3. Align your local plugin slug/path

If your local plugin folder slug changes, update path-dependent files together:

1. [.devcontainer/docker-compose.yml](.devcontainer/docker-compose.yml)
   - Plugin mount path under `/var/www/html/wp-content/plugins/...`.

2. [README.md](README.md)
   - Update rename checklist references to your final slug and naming conventions.

### 4. Rebrand project metadata (recommended)

1. [composer.json](composer.json)
   - Package `name` and description.

2. [.devcontainer/devcontainer.json](.devcontainer/devcontainer.json)
   - Container display name.

3. [phpcs.xml](phpcs.xml)
   - Ruleset display name and description.

## End-To-End Path B: Configure Distribution

Distribution behavior is defined in [plugin-distribution.yml](.github/workflows/plugin-distribution.yml).

### 1. Understand triggers

- `release.published`: release automation path.
- `workflow_dispatch`: manual run path for testing/operations.

### 2. Configure Actions Variables (optional but recommended)

Set in repository settings under Actions Variables:

- `PLUGIN_DESTINATION_REPO` (optional): `owner/repo` destination for sync.
- `PLUGIN_SLUG` (optional): override plugin folder slug for ZIP output. When not set, the workflow defaults to repository name without trailing `-dev`.
- `PLUGIN_CREATE_ZIP` (optional): `true` or `false` default for release runs.
- `PLUGIN_SYNC_REPO` (optional): `true` or `false` default for release runs.

If the repository name does not end with `-dev`, you must set `PLUGIN_SLUG` (or provide `plugin_slug` during manual runs).

### 3. Configure Actions Secret for cross-repo sync

Set in repository settings under Actions Secrets:

- `PLUGIN_SYNC_TOKEN` (required when pushing to another repo).

Use a token with permission to push to the destination repository.

## End-To-End Path C: Test Distribution Safely

### 1. Branch-safe manual test (recommended first)

Run the workflow manually from Actions with `workflow_dispatch`.

Test set 1: ZIP-only

- `create_zip=true`
- `sync_repo=false`
- `destination_repo` left blank

Expected result:

- ZIP job runs.
- Artifact named `plugin-package` is uploaded.
- Downloaded file is an installable plugin ZIP.
- Installable ZIP contains one top-level plugin folder, and that folder contains only files staged from [src](src).

Test set 2: Sync-only to disposable target

- `create_zip=false`
- `sync_repo=true`
- `destination_repo` set to a disposable test repository

Expected result:

- Sync job pushes source plugin files from [src](src) to destination repo root.
- `vendor/` is excluded from sync by workflow rule.

### 2. Release-path test

Publish a test release to validate release trigger behavior.

Expected result:

- ZIP suffix uses release tag.
- Release includes the same installable plugin ZIP as a release asset.
- Sync runs only when destination is configured and sync is enabled.

### 3. Validate outputs

ZIP validation checklist:

- Download package is a single installable ZIP.
- Archive has one top-level plugin folder.
- Contents match [src](src) payload.
- No repository-level development files are included.

Sync validation checklist:

- Destination reflects [src](src) content after sync.
- Expected files are added/updated/removed.
- No unexpected repository metadata changes are pushed.

## Safety And Risk Notes

### `rsync --delete` behavior

Sync uses delete semantics and can remove files in the destination repository.

Always validate sync against a disposable destination first.

### Empty source guard

Workflow intentionally fails when [src](src) is missing or empty to avoid destructive sync/package runs.

### Composer behavior in distribution

ZIP packaging runs `composer install --no-dev` only when `src/composer.json` exists.

Repo sync excludes `vendor/` by design.

## Common Issues And Fixes

1. Sync job skipped unexpectedly
   - Check `sync_repo` value and destination configuration.
   - Confirm destination repo is set either in manual input or variable.

2. Cross-repo push fails
   - Confirm `PLUGIN_SYNC_TOKEN` exists and has correct permissions.
   - Confirm destination repository name and owner are correct.

3. ZIP naming not as expected
   - Check `PLUGIN_SLUG` and manual `plugin_slug` input.
   - For release runs, confirm release tag value.

4. Downloaded ZIP appears nested or invalid for WordPress install
   - Confirm the generated package itself is uploaded as artifact/release asset.
   - Verify the archive has a single plugin root directory containing plugin files.

## Pre-Release Checklist

1. Confirm plugin metadata is correct in [src/plugin-name.php](src/plugin-name.php).
2. Confirm local plugin slug/path alignment in [.devcontainer/docker-compose.yml](.devcontainer/docker-compose.yml).
3. Run lint checks locally.
4. Run manual ZIP-only distribution test.
5. Run manual sync-only test to disposable target (if sync will be used).
6. Review workflow logs for warnings/failures.
7. Publish release when checks pass.

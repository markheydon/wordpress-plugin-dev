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

2. [.vscode/launch.json](.vscode/launch.json)
   - Xdebug `pathMappings` entry for `/var/www/html/wp-content/plugins/...`.

3. [README.md](README.md)
   - Update rename checklist references to your final slug and naming conventions.

### 4. Rebrand project metadata (recommended)

1. [composer.json](composer.json)
   - Package `name` and description.

2. [.devcontainer/devcontainer.json](.devcontainer/devcontainer.json)
   - Container display name.

3. [phpcs.xml](phpcs.xml)
   - Ruleset display name and description.

## Optional Path: Cron Scaffolding

Cron support is included as reusable template scaffolding. Keep it if your plugin needs scheduled tasks, adapt it for your own event cadence, or remove/disable it if not required.

### 1. Keep and customize cron (recommended when needed)

1. Update callback behavior in [src/includes/class-plugin-name-cron.php](src/includes/class-plugin-name-cron.php).
2. Rename the event/action names to match your plugin naming and prefix strategy.
3. Update schedule cadence in [src/includes/class-plugin-name-activator.php](src/includes/class-plugin-name-activator.php) if hourly is not appropriate.

### 2. Disable cron without deleting files (quick opt-out)

1. Set `PLUGIN_NAME_ENABLE_CRON` to `false` before plugin bootstrap executes.
2. The template will then skip cron class loading, cron hook registration, and activation/deactivation scheduling logic.

### 3. Remove cron scaffolding entirely

1. Delete [src/includes/class-plugin-name-cron.php](src/includes/class-plugin-name-cron.php).
2. Remove cron lifecycle wiring from:
   - [src/includes/class-plugin-name-activator.php](src/includes/class-plugin-name-activator.php)
   - [src/includes/class-plugin-name-deactivator.php](src/includes/class-plugin-name-deactivator.php)
   - [src/includes/class-plugin-name.php](src/includes/class-plugin-name.php)
3. Remove or replace any references to `plugin_name_cron_event` and `plugin_name_hourly_task`.

### 4. Validate after cron changes

1. Activate/deactivate plugin and confirm no cron-related PHP errors occur.
2. If cron is removed or disabled, confirm no scheduled `plugin_name_cron_event` remains.
3. Run lint checks and confirm no stale cron references remain.

## Optional Path: WP-CLI Scaffolding

WP-CLI support is included as reusable template scaffolding. Keep it if your plugin needs command-line operations, adapt it for project-specific commands, or remove/disable it if not required.

### 1. Keep and customize WP-CLI (recommended when needed)

1. Update command behavior in [src/admin/class-plugin-name-admin-cli.php](src/admin/class-plugin-name-admin-cli.php).
2. Replace the sample `plugin-name` command namespace and methods with plugin-specific commands.
3. Ensure command docs/examples match your final command signatures.

### 2. Disable WP-CLI without deleting files (quick opt-out)

1. Set `PLUGIN_NAME_ENABLE_WP_CLI` to `false` before plugin bootstrap executes.
2. The template will skip WP-CLI scaffold loading and command registration.

### 3. Remove WP-CLI scaffolding entirely

1. Delete [src/admin/class-plugin-name-admin-cli.php](src/admin/class-plugin-name-admin-cli.php).
2. Remove WP-CLI include/wiring from [src/includes/class-plugin-name.php](src/includes/class-plugin-name.php).
3. Remove any references to the `wp plugin-name health-check` example command in docs.

### 4. Validate after WP-CLI changes

1. Load the plugin in a non-CLI context and confirm no WP-CLI class/function errors occur.
2. If WP-CLI was removed, confirm template docs no longer reference command examples you do not ship.
3. Run lint checks and confirm no stale WP-CLI references remain.

### 5. Smoke test the template WP-CLI command

The template includes one sample command for quick verification:

- `wp plugin-name health-check`
- Expected output: `Plugin template command is available.`

Run this sequence in the runtime where WordPress is actually mounted:

1. Verify WordPress bootstrap path:

```bash
wp --path=/var/www/html core is-installed
```

2. Verify/activate plugin:

```bash
wp --path=/var/www/html plugin activate plugin-name
```

3. Run the command:

```bash
wp --path=/var/www/html plugin-name health-check
```

If your shell does not have access to the WordPress runtime, run through the Compose WordPress service:

```bash
docker compose -f .devcontainer/docker-compose.yml exec -T wordpress wp --path=/var/www/html plugin-name health-check
```

Troubleshooting:

1. `No WordPress installation found`
   - Your `--path` is wrong for the runtime you are in. Use the mounted WordPress root for that runtime.
2. `'plugin-name' is not a registered wp command`
   - Plugin may be inactive, WP-CLI feature may be disabled (`PLUGIN_NAME_ENABLE_WP_CLI=false`), or command namespace may have been renamed during rebrand.
3. Command still unavailable after activation
   - Confirm [src/admin/class-plugin-name-admin-cli.php](src/admin/class-plugin-name-admin-cli.php) still exists and is wired in [src/includes/class-plugin-name.php](src/includes/class-plugin-name.php).

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
- Downloaded artifact is a GitHub wrapper archive that contains the installable plugin ZIP.
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

- Downloaded artifact archive contains a single installable ZIP.
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

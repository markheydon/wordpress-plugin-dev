# WordPress Plugin Dev Template

A modern template repository for building WordPress plugins with a reproducible developer environment.

This project intentionally combines:

- the plugin architecture style from your `WordPress-Plugin-Boilerplate` (`merge-prs`) fork (loader pattern, activation/deactivation, i18n, cron, WP-CLI helper), and
- the developer-experience approach from `avada-child-theme-dev` (Dev Container + Docker Compose + coding standards tooling).

It is designed as a **template repository** so new plugin projects can be created quickly and consistently.

## What this template includes

- `src/` starter plugin scaffold (`plugin-name.php`, `includes/`, `admin/`, `languages/`, `uninstall.php`)
- `.devcontainer/` local WordPress + MySQL development environment
- Composer-based coding standards tooling (PHPCS + WordPress Coding Standards)
- baseline docs and placeholders to rename for your plugin

## Alignment with WordPress guidance

When building your plugin from this template, follow official documentation:

- Plugin best practices: https://developer.wordpress.org/plugins/plugin-basics/best-practices/
- Plugin handbook (general): https://developer.wordpress.org/plugins/
- Data validation/sanitization/escaping: https://developer.wordpress.org/plugins/security/
- Internationalization: https://developer.wordpress.org/plugins/internationalization/

## Quick start

1. Create a new repository from this template.
2. Clone your new repository and open it in VS Code.
3. Reopen in Dev Container.
4. Wait for `.devcontainer/setup.sh` to complete.
5. Visit `http://localhost:8080`.

### Admin login

- Username: `admin`
- Password: `admin`


## Operations runbook

For full template setup, rebrand flow, and distribution testing guidance, use [RUNBOOK.md](RUNBOOK.md).

## GitHub template assets

- Distribution workflow: [.github/workflows/plugin-distribution.yml](.github/workflows/plugin-distribution.yml)
- Rebrand prompt: [.github/prompts/plugin-rebrand.prompt.md](.github/prompts/plugin-rebrand.prompt.md)

## Development commands

Install dependencies (automatic in dev container setup):

```bash
composer install
```

Run coding standards:

```bash
composer lint
```

Auto-fix fixable coding standards issues:

```bash
composer lint:fix
```

## Template rename checklist

Update placeholder values before first release:

- `plugin-name` slug
- `Plugin_Name` class prefix
- `plugin_name` function prefix
- plugin header metadata in `src/plugin-name.php`
- text domain and language files
- mount path in `.devcontainer/docker-compose.yml` if plugin slug changes

## Notes

- This repository is for **development source**.
- Build only the plugin files you want to distribute from `src/`.
- Keep secure defaults and use explicit sanitization/escaping in all new code.

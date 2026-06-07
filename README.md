# WP DevTrace

A lightweight WordPress development tracing and debugging toolkit.

## Features

### ✅ Feature 1: Laravel-Style Error Page
- Pretty error pages powered by `filp/whoops`
- Replaces WordPress white screen of death
- Shows file, line number, stack trace
- WordPress Context panel:
  - Current URL
  - WordPress version
  - Active theme name
  - Plugin count
- Admin only — non-admin users never see errors
- Toggle ON/OFF from DevTrace settings page
- Production warning notice in admin

## Installation

Clone the repository:

```bash
git clone git@github.com:Risad212/wp-devTrace.git
```

Go to the plugin directory:

```bash
cd wp-devTrace
```

Install Composer dependencies:

```bash
composer install
```

## Usage

1. Copy plugin folder to `/wp-content/plugins/`
2. Activate from **Plugins → Installed Plugins**
3. Go to **DevTrace → Settings**
4. Enable DevTrace toggle
5. Any PHP error now shows beautiful error page

## Requirements

- PHP 8.0+
- WordPress 6.0+
- Composer installed globally

## Important

⚠️ This plugin is for **development environments only**.
Do not activate on production sites.

## License

GPL-2.0-or-later
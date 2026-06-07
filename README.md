# wp-devTrace

A lightweight WordPress development tracing and debugging toolkit.

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

After installing dependencies:

1. Copy the plugin folder into your WordPress `/wp-content/plugins/` directory.
2. Go to the WordPress admin dashboard.
3. Activate the plugin from **Plugins → Installed Plugins**.

## Development

Whenever you pull new changes from GitHub, run:

```bash
composer install
```

If dependencies change:

```bash
composer update
```

## Requirements

* PHP 8.0+
* WordPress 6.0+
* Composer installed globally

## License

GPL-2.0-or-later

# Register ACF Gutenberg Block

This is a helper WordPress plugin to allow you to easily register new Gutenberg blocks via ACF.

Thanks to [@mwdelaney](https://github.com/MWDelaney/) and [@mikespainhower](https://github.com/mikespainhower/) for everything I learned and copied from their approaches to this problem!

## Usage

1. Install with: `composer require mmirus/register-acf-gutenberg-block`
2. Add your block definitions to the `$blocks` array via the `mmirus/register-acf-gutenberg-block` filter

Your block definitions can be added in a plugin (recommended) or a theme.

**:information_source: Example block definitions can be found at https://github.com/mmirus/register-acf-gutenberg-block-examples.**

Following the examples will be the quickest way to get started. One you have the concept down, refer to these resources to go in-depth on what block options are available:

- [`acf_register_block()` docs](https://www.advancedcustomfields.com/resources/acf_register_block/)
- [Gutenberg Block Registration docs](https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-registration/)

### Block Rendering Options

ACF provides two methods to render blocks:

- a callback function that echos PHP
- a PHP template file

This plugin adds a third option: Blade templates. For now, this only works if you're using [Sage](https:/roots.io/sage/).

To use a Blade template, just pass the absolute path to the template file, including the `.blade.php` extension, in the `render_template` option.

_NB: If you pass both `render_callback` and `render_template`, ACF uses the callback._

### Defining Your Block's Fields

To promote keeping your block's field definitions in code with the rest of the block config, you can pass a `fields` option with the block settings when registering your block. The value of this option must be an ACF PHP field definition array. Primarily this is intended to allow you to use [acf-builder](https://github.com/StoutLogic/acf-builder) to define your fields, as this is the format acf-builder's `build()` method returns.

Other than the `fields` setting, you can define the ACF fields used by your block however you normally prefer to add fields:

- via ACF's GUI
- via ACF's JSON or PHP methods (you could save these to your block plugin)
- via acf-builder

All you have to do is set the location rule to match your block. Note that blocks are registered with the prefix `acf/`, as in `acf/your-block-name`.

## Installation

There are three options for installing this plugin:

1. With composer from [Packagist](https://packagist.org/packages/mmirus/register-acf-gutenberg-block): `composer require mmirus/register-acf-gutenberg-block`
2. With [GitHub Updater](https://github.com/afragen/github-updater)
3. By downloading the latest release ZIP from this repository and installing it like any normal WordPress plugin

## Contributing

To work on this project (for yourself or to contribute a PR):

1. Clone this repo
2. Run `composer install`

And you should be good to go.

Pre-commit linting and code style checks are enforced (PSR-2 for PHP).

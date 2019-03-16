<?php
/*
Plugin Name: Register ACF Gutenberg Block
Plugin URI: https://github.com/mmirus/register-acf-gutenberg-block
Description: Easily register new Gutenberg blocks via ACF
Author: Matt Mirus
Author URI: https://github.com/mmirus
Version: 0.1.2
GitHub Plugin URI: https://github.com/mmirus/register-acf-gutenberg-block
 */

declare(strict_types=1);

namespace mmirus\RegisterGutenblock;

add_action('acf/init', function (): void {
    if (!function_exists('acf_register_block')) {
        return;
    }

    $block_defaults = [
        'category'        => 'formatting',
        'icon'            => 'admin-generic',
    ];

    $blocks = apply_filters('mmirus/register-acf-gutenberg-block', [], $block_defaults);

    foreach ($blocks as $block) {
        $block = wp_parse_args($block, $block_defaults);

        if (is_blade_template($block)) {
            $block['render_callback'] = __NAMESPACE__.'\\render_blade_template';
        }

        acf_register_block($block);

        if (isset($block['fields'])) {
            acf_add_local_field_group($block['fields']);
        }
    }
});

/**
 * Check if the block's render_template was defined and is a Blade template file
 *
 * @param array $block Block definition
 * @return boolean
 */
function is_blade_template(array $block) : bool
{
    if (!isset($block['render_template'])) {
        return false;
    }

    $blade_ext = '.blade.php';

    // check if the template name ends with .blade.php
    return (substr($block['render_template'], -strlen($blade_ext)) === $blade_ext);
}

function render_blade_template(array $block, string $content = '', bool $is_preview = false) : void
{
    try {
        if (!function_exists('\App\template')) {
            throw new \Exception("\\App\\template() not found. Are you trying to register a Blade-based block on a non-Sage theme?");
        }

        // Set up the block data
        $block['slug'] = str_replace('acf/', '', $block['name']);
        $block['classes'] = implode(' ', [$block['slug'], $block['className'], $block['align']]);

        // Use Sage's template() function to echo the block and populate it with data
        // TODO: replace with Acorn
        echo \App\template($block['render_template'], ['block' => $block]);
    } catch (\Throwable $th) {
        if (is_admin()) {
            echo '<div class="editor-warning"><div class="editor-warning__contents"><p class="editor-warning__message">Error while rendering block <code>' . $block['name'] . '</code>:</p><p class="editor-warning__message">' . $th->getMessage() . '</p></div></div>';
        }

        trigger_error($th->getMessage(), 512);
    }
}

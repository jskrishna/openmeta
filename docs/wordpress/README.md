# WordPress Integration

OpenMeta loads as a WordPress plugin and mounts the framework onto WP: plugin
bootstrap, hooks/filters, capabilities, Gutenberg glue, and the REST mount. The
adapter guards host calls with `function_exists()`, so the framework also boots
cleanly headless (CLI/tests).

Full reference: [`packages/wordpress`](../../packages/wordpress/README.md).

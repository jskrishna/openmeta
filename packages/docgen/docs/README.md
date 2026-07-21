# OpenMeta Documentation Platform — developer guide

`@openmeta/docgen` generates and validates the documentation. See
[../SPEC.md](../SPEC.md) for the contract.

## Commands

```bash
php bin/openmeta docs:build      # everything
php bin/openmeta docs:api        # API reference (docs/reference/api/)
php bin/openmeta docs:validate   # links + markdown + code blocks
```

## Programmatically

```php
use OpenMeta\Docgen\Manager\DocumentationManager;

/** @var DocumentationManager $docs */
$docs = $app->get('docs');

$report = $docs->validate();
if (! $report->isClean()) {
    foreach ($report->issues() as $issue) {
        echo $issue, "\n";
    }
}

$docs->build(); // API + packages + search index + sitemap + changelog
```

## What gets generated

| Artefact | Path |
| -------- | ---- |
| API reference (per package + index) | `docs/reference/api/` |
| Package index | `docs/packages/README.md` |
| Search index (JSON) | `docs/assets/search-index.json` |
| Sitemap (XML) | `docs/assets/sitemap.xml` |
| Changelog page | `docs/reference/changelog.md` |

## Validation rules

- **Broken links** — relative links must resolve to an existing file/dir
  (external `http(s)`/`mailto`, anchors, and images are skipped).
- **Missing title** — every page needs an H1.
- **Empty code block** — fenced blocks must not be whitespace-only.

The Markdown scanner is **fence-length aware**: a file wrapped in a 4-backtick
fence (with inner 3-backtick blocks) is treated as one block, avoiding false
positives.

## Extending

The scanner, renderers, and validators are separate injectable classes; add a
`CommandProviderInterface` to contribute more `docs:*` commands, or compose the
generators directly.

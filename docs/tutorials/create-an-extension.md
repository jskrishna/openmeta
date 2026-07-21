# Tutorial — Create an Extension

tags: extensions, tutorial

Build an extension that adds behavior without modifying core.

## 1. Scaffold

```bash
php bin/openmeta make:extension Seo
# → src/Extensions/SeoExtension.php (a service provider)
```

## 2. Describe it (manifest)

```json
{
  "packageId": "acme/seo",
  "name": "SEO",
  "vendor": "acme",
  "version": "1.0.0",
  "providers": ["Acme\\Seo\\SeoServiceProvider"],
  "requirements": { "php": ">=8.3" }
}
```

## 3. Contribute resources from the provider

```php
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Extensions\Contracts\ResourceLoaderInterface;

final class SeoServiceProvider extends ServiceProvider
{
    public function boot(ContainerInterface $container): void
    {
        $resources = $container->get(ResourceLoaderInterface::class);
        $resources->field('seo_title', SeoTitleField::class, 'acme/seo');
    }
}
```

## 4. Activate at runtime

```php
use OpenMeta\Extensions\Compatibility\Environment;
use OpenMeta\Extensions\Contracts\ExtensionManagerInterface;

/** @var ExtensionManagerInterface $manager */
$manager = $app->get(ExtensionManagerInterface::class);
$manager->bootstrap(Environment::detect(coreVersion: '0.13.0'));
```

Full reference: [Extension SDK](../extensions/README.md).

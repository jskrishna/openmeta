# Application/

Ready application + step methods used by the bootstrap sequence.

## Bootstrap sequence (via `Bootstrap::run()` / `Application::boot()`)

```text
Load Config
    ↓
Create Container
    ↓
Register Core Services
    ↓
Register Providers
    ↓
Boot Providers
    ↓
Application Ready
```

| Step | Method |
| ---- | ------ |
| Load Config | `loadConfig()` |
| Create Container | `createContainer()` |
| Register Core Services | `registerCoreServices()` |
| Register Providers | `registerProviders()` |
| Boot Providers | `bootProviders()` |
| Application Ready | `ready()` |

## Code

- `Application.php`

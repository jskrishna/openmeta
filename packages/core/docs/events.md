# Events

What the event system is for — not how listeners are stored.

---

## Purpose

The **EventDispatcher** lets core (and packages using core) announce that something happened, and let interested listeners react — without hard-wiring callers to callees.

It answers: *How do we notify “X occurred” inside the process?*

---

## What it does

- Accepts listeners for a named event type (typically an event class)
- Dispatches an event object to those listeners
- Returns the event so callers can use any data listeners left on it (when applicable)
- Stays in-process and synchronous at this layer of the architecture

---

## What it is not

- Not a message queue or background job system
- Not webhooks or HTTP callbacks
- Not a replacement for WordPress actions/filters in WP integration packages (those may bridge later)
- Not persistence or audit logging storage

---

## Typical uses inside core’s mandate

- Announce that the application has reached a lifecycle milestone (when such events are defined)
- Allow providers to react to framework-level signals without core importing those packages

Domain events for fields, REST, or admin belong in those packages — they may *use* the dispatcher from core, but core does not define their catalogs.

---

## Related

- [lifecycle.md](./lifecycle.md)
- [architecture.md](./architecture.md)
- [service-providers.md](./service-providers.md)
- [first-classes.md](./first-classes.md)

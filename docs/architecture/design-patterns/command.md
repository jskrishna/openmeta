# Command Pattern

---

## Purpose

The Command Pattern encapsulates an action or operation as an object.

Instead of executing business logic directly, OpenMeta wraps each operation inside a dedicated Command class. This makes actions reusable, queueable, testable, and independent from the caller.

Commands represent **"something that should happen"**, not **"how it is triggered."**

---

# Problem

As OpenMeta grows, many operations become increasingly complex.

Examples:

- Save Field Group
- Import Schema
- Export Configuration
- Synchronize Metadata
- Generate GraphQL Schema
- Clear Cache
- AI Field Generation

Without the Command Pattern:

```text
Controller

↓

Validation

↓

Database

↓

Cache

↓

Events

↓

Logging

↓

Notifications
```

Controllers and services become large and difficult to maintain.

---

# Solution

Wrap every complex action inside a dedicated Command.

Instead of:

```text
Controller

↓

Business Logic
```

Use:

```text
Controller

↓

Command

↓

Handler

↓

Result
```

The controller only requests execution.

---

# Why OpenMeta Uses It

Many OpenMeta operations:

- Require validation.
- Modify multiple resources.
- Trigger events.
- Can run asynchronously.
- May need retry mechanisms.
- Should be independently testable.

The Command Pattern keeps these concerns isolated.

---

# Responsibilities

A Command is responsible for:

- Representing an action.
- Holding required input data.
- Remaining immutable after creation.

A Command should never:

- Access the database directly.
- Render UI.
- Resolve dependencies.
- Execute business logic.

Execution belongs to the Command Handler.

---

# Architecture

```text
Application

↓

Command

↓

Command Bus

↓

Command Handler

↓

Domain Services

↓

Repositories

↓

Events
```

---

# Command Flow

Example:

```text
Save Field Group

↓

SaveFieldGroupCommand

↓

Command Bus

↓

SaveFieldGroupHandler

↓

Repository

↓

Event Dispatcher
```

---

# Command Components

Every command consists of:

```text
Command

↓

Handler

↓

Optional Result
```

Each component has one responsibility.

---

# Command Types

Examples:

```text
CreateFieldCommand

UpdateFieldCommand

DeleteFieldCommand

CreateFieldGroupCommand

ImportSchemaCommand

ExportSchemaCommand

GenerateGraphQLSchemaCommand

ClearCacheCommand

GenerateAiFieldCommand
```

Each command represents exactly one action.

---

# Command Handler

The Handler executes the business logic.

Responsibilities:

- Validate prerequisites.
- Coordinate domain services.
- Persist changes.
- Dispatch events.
- Return results.

The Handler should not construct the Command.

---

# Command Bus

The Command Bus is responsible for:

- Receiving commands.
- Locating handlers.
- Executing handlers.
- Managing middleware.
- Returning execution results.

The bus should not contain business logic.

---

# Immutability

Commands should be immutable.

Once created, command data should not change.

Example:

```text
Create Command

↓

Dispatch

↓

Execute
```

No modification should occur during execution.

---

# Validation

Basic structural validation may occur before dispatch.

Business validation belongs inside the Handler or Domain Services.

---

# Dependency Injection

Handlers receive dependencies through constructor injection.

Example:

```text
SaveFieldGroupHandler

↓

Repository

↓

Logger

↓

Event Dispatcher
```

Handlers should never instantiate dependencies directly.

---

# Asynchronous Execution

Commands are ideal for background processing.

Examples:

```text
Generate AI Fields

↓

Queue

↓

Worker

↓

Result
```

Other examples:

- Import jobs
- Export jobs
- Search indexing
- Webhooks
- Analytics

---

# Middleware

The Command Bus may support middleware.

Examples:

```text
Authorization

↓

Validation

↓

Logging

↓

Transaction

↓

Command Handler
```

Each middleware performs one responsibility.

---

# Error Handling

If execution fails:

- Throw a descriptive exception.
- Roll back transactions if required.
- Log the failure.
- Never leave partially completed operations.

---

# Idempotency

Commands that may execute multiple times should be idempotent where possible.

Example:

```text
Clear Cache

↓

Run Multiple Times

↓

Same Result
```

This is especially important for queued commands.

---

# Performance

Commands should:

- Execute only one operation.
- Delegate work to services.
- Avoid unnecessary queries.
- Support batching where appropriate.

Heavy operations should be asynchronous.

---

# Testing

Each Command Handler should be tested independently.

Recommended tests:

- Successful execution.
- Validation failures.
- Repository interaction.
- Event dispatching.
- Exception handling.
- Transaction rollback.

---

# Advantages

- Clear separation of responsibilities.
- Easier testing.
- Queue support.
- Retry support.
- Cleaner controllers.
- Better scalability.

---

# Trade-offs

- More classes.
- Additional infrastructure.
- Slightly higher learning curve.

These trade-offs are worthwhile for a modular framework like OpenMeta.

---

# Where to Use

Use the Command Pattern for:

- CRUD operations.
- Import/Export.
- Cache management.
- AI processing.
- Background jobs.
- Schema generation.
- Synchronization.
- Batch processing.

---

# Where NOT to Use

Do not use Commands for:

- Simple getters.
- Pure calculations.
- Stateless utilities.
- Basic formatting functions.

Commands should represent meaningful application actions.

---

# Related Patterns

The Command Pattern commonly works with:

- Observer Pattern (dispatch events after execution)
- Repository Pattern (persist data)
- Strategy Pattern (select execution behavior)
- Pipeline Pattern (middleware processing)
- Factory Pattern (command creation)

---

# Future Considerations

Possible future enhancements include:

- Distributed command bus.
- Queue prioritization.
- Delayed execution.
- Scheduled commands.
- Command history.
- Undo/Redo support.
- Event sourcing compatibility.

These enhancements should preserve the existing command contract.

---

# Summary

The Command Pattern encapsulates business actions into dedicated, immutable objects that are executed by specialized handlers.

This architecture keeps controllers thin, improves testability, enables asynchronous processing, and provides a scalable foundation for complex workflows throughout OpenMeta.
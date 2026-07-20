# Navigation

---

# Purpose

The Navigation System provides a consistent mechanism for moving throughout the OpenMeta administration interface.

Navigation should remain predictable, discoverable, and extensible.

---

# Navigation Structure

```text
Application

↓

Sidebar

↓

Sections

↓

Pages

↓

Actions
```

---

# Navigation Types

Supported navigation includes:

- Sidebar
- Top Navigation
- Breadcrumbs
- Context Menus
- Tabs
- Quick Search

---

# Sidebar

The Sidebar provides access to:

- Dashboard
- Schemas
- Fields
- Extensions
- Settings
- System

---

# Breadcrumbs

Breadcrumbs display the current location.

Example:

```text
Dashboard

↓

Schemas

↓

Products

↓

Pricing
```

---

# Search

Navigation should support:

- Global Search
- Command Palette
- Recent Pages
- Favorites

---

# Permissions

Navigation items should respect authorization rules.

Hidden resources should not appear for unauthorized users.

---

# Extensibility

Extensions may:

- Add menu items
- Register navigation groups
- Create dashboards
- Add contextual actions

---

# Best Practices

- Keep hierarchy shallow.
- Use descriptive labels.
- Maintain consistent ordering.
- Avoid duplicate navigation.
- Support keyboard shortcuts.

---

# Summary

The Navigation System provides intuitive movement throughout OpenMeta while remaining modular, permission-aware, and extensible.
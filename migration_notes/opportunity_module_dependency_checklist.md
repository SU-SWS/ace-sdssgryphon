# Opportunity Migration: Module Dependency Checklist

The following modules must be enabled for the Opportunity content type to function as expected:

- menu_ui
- node_revision_delete
- scheduler
- layout_builder (required for layout_builder__layout field)

## Action Items
- Enable these modules in your target site before importing configuration.
- If any modules are missing, install them via Composer or your preferred method.

---

_This checklist ensures all required modules are present for the Opportunity content type._

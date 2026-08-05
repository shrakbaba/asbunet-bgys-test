# ASBU BGYS Copilot Instructions

## Project Context

This repository contains Ankara Social Sciences University's
Information Security Management System application.

The application supports ISO/IEC 27001:2022 processes.

## Mandatory Safety Rules

- Never expose passwords, tokens, connection strings, certificates or personal data.
- Never commit production configuration.
- Never commit database dumps or uploaded institutional documents.
- Never delete existing production data.
- Do not create destructive migrations.
- Prefer additive and reversible database migrations.
- Preserve existing records and document histories.
- Do not change production deployment files unless explicitly requested.
- Do not run migrations, deployment commands or service restarts automatically.
- Do not claim ISO compliance solely based on source code.
- Do not invent ISO requirements or control text.

## Development Workflow

Before modifying code:

1. Inspect related models, controllers, services, views, migrations and tests.
2. Explain the current behavior.
3. Identify affected files and database tables.
4. Propose the smallest safe change.
5. Describe migration and rollback requirements.
6. Wait for explicit implementation instruction if the task is analysis-only.

After modifying code:

1. Run focused tests.
2. Review the complete diff.
3. Report changed files.
4. Report tests executed and results.
5. Report unresolved risks and assumptions.

## Architecture Rules

- Follow the existing framework conventions.
- Keep controllers thin.
- Move business logic into services where appropriate.
- Enforce authorization on the server side.
- Do not rely only on hidden buttons or menu visibility.
- Validate all request parameters.
- Use transactions for multi-table operations.
- Use parameterized database queries.
- Add audit records for critical operations.
- Prefer soft delete or inactive status for BGYS records.
- Preserve approval, document and risk history.
- Avoid hard-coded status values when enums or reference tables exist.

## BGYS Traceability Rules

Maintain traceability between:

- organization unit
- process
- information asset
- risk
- threat
- vulnerability
- risk treatment
- ISO control
- control owner
- evidence
- document
- audit
- finding
- corrective action
- effectiveness review

A document alone does not prove that a control is implemented.

A completed action alone does not prove effectiveness.

Corrective actions require effectiveness verification before closure.

## Testing Requirements

For every changed business rule, add or update tests covering:

- authorized access
- unauthorized access
- invalid input
- successful operation
- transaction rollback
- record ownership
- unit-level access restrictions
- preservation of existing data

---
name: cb-tiendas-mobile-ui
description: 'Mobile-first UI workflow for cb-tiendas (Laravel 13 + Blade + Tailwind 4). Use for navbar, filters, color updates, responsive spacing, and visual consistency without introducing SPA patterns.'
argument-hint: 'What UI area should be changed (nav, filters, colors, layout) and in which view?'
---

# CB Tiendas Mobile UI Workflow

Apply consistent mobile-first improvements for public-facing UI in this project, especially navigation, filters, and color system adjustments.

## When to Use

- User asks for mobile improvements in Blade views
- User asks to improve navbar behavior on small screens
- User asks to redesign filters for touch devices
- User asks for color cleanup or visual consistency updates
- User asks for responsive spacing, typography, or layout polish

## Project Constraints

- Keep the existing MVC architecture (Laravel routes + controllers + Blade views)
- Do not introduce SPA/API-first layers unless explicitly requested
- Reuse existing route names and view structure
- Prefer small, focused edits over broad rewrites
- Keep compatibility with Tailwind CSS 4 patterns already used in the repo
- Default scope is public views only (do not change Filament admin unless requested)
- Default visual direction is to preserve the current palette and normalize only inconsistent colors

## Inputs To Gather First

1. Target view files (for example public nav partial and related pages)
2. Primary breakpoints to optimize (`sm`, `md`, `lg` expectations)
3. Current pain points: overflow, tap targets, filter usability, color contrast
4. Scope boundaries: public pages only, or include Filament admin

If any input is missing, ask 1 concise clarification question and proceed with safe defaults.

## Workflow

1. Locate UI surfaces
- Find all related Blade partials and pages (nav, filters, container layouts)
- Identify duplicated markup that should stay in sync

2. Audit current mobile behavior
- Check horizontal overflow and wrapping at narrow widths
- Check nav open/close behavior and keyboard accessibility
- Check filter controls for touch size and readability
- Check color usage for contrast and hierarchy

3. Decide pattern per component
- Nav decision:
  - If item count is low, prefer stacked/collapsible list
  - If item count is high, use compact trigger + vertical menu panel
- Filter decision:
  - If filters are few, use inline wrapped chips/controls
  - If filters are many, use collapsible filter drawer/section
- Color decision:
  - If colors are inconsistent, define a small token-like set via CSS variables
  - If colors are mostly consistent, normalize only outliers

4. Implement mobile-first updates
- Start from base classes (mobile default), then add `sm:`/`md:` enhancements
- Increase tap targets (minimum comfortable hit area)
- Improve spacing rhythm (container padding, vertical gaps, section separation)
- Keep typography readable on small screens
- Reduce visual noise while preserving hierarchy

5. Validate behavior and regressions
- Verify no desktop regression at `md` and above
- Verify keyboard navigation and focus visibility
- Verify contrast for text and interactive elements
- Verify no layout shift causing hidden actions

6. Run minimal checks
- Frontend-only change: run `npm run build` (or Sail-based equivalent if local Node is incompatible)
- If backend logic changed too: run `composer run test`
- Default validation mode is quick: frontend build plus visual review on mobile breakpoints

## Quality Gates (Done Criteria)

- No horizontal overflow on common mobile widths
- Nav is reachable, understandable, and dismissible on mobile
- Filter controls are easy to tap and scan
- Color choices are consistent and preserve contrast
- Desktop layout remains stable
- Changes are limited to requested scope
- Filament admin is untouched unless explicitly requested

## Output Format

When applying this workflow, produce:

1. Short summary of what changed
2. File-by-file change list
3. Mobile behavior improvements achieved
4. Any follow-up recommendations (optional)

## Example Prompts

- `/cb-tiendas-mobile-ui Mejora el nav publico para mobile en resources/views/partials/public/nav.blade.php`
- `/cb-tiendas-mobile-ui Reorganiza filtros para pantallas pequenas en la vista de tiendas`
- `/cb-tiendas-mobile-ui Unifica paleta de colores de la pagina publica sin tocar Filament`

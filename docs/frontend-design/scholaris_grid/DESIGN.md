# Design System Strategy: The Digital Curator

## 1. Overview & Creative North Star
This design system is built upon the vision of **"The Digital Curator."** In a hybrid campus library environment, the interface must bridge the gap between the tactile weight of a physical archive and the frictionless speed of digital discovery. 

We move beyond the "standard dashboard" by treating the UI as an editorial layout. We prioritize **Operational Elegance**: a philosophy where high-density data and complex library workflows are housed within a sophisticated, airy environment. By utilizing intentional asymmetry, layered depth, and high-contrast typography, we ensure that the system feels like a premium academic tool rather than a generic utility.

---

## 2. Color & Tonal Depth
Our palette is rooted in a deep "Campus Blue" (`primary`) and "Identity Green" (`secondary`), balanced by a sophisticated range of neutral surfaces.

### The "No-Line" Rule
To achieve a high-end, bespoke feel, **1px solid borders are prohibited for sectioning.** Structural boundaries must be defined solely through:
- **Background Color Shifts:** Placing a `surface-container-low` section against a `surface` background.
- **Tonal Transitions:** Using subtle variations in the surface tier scale to denote content blocks.

### Surface Hierarchy & Nesting
Treat the UI as a series of stacked materials. Use the `surface-container` tiers to create natural nesting:
- **Level 0 (Base):** `surface` (#f9f9ff) for the main application background.
- **Level 1 (Sections):** `surface-container-low` (#f0f3ff) for large structural areas like sidebars or content wells.
- **Level 2 (Cards):** `surface-container-lowest` (#ffffff) for primary content cards to create a "lifted" feel.
- **Level 3 (Interactions):** `surface-container-high` (#e2e8f8) for hover states or active selection indicators.

### The Glass & Gradient Rule
For main CTAs and Hero sections (especially in the OPAC view), use a subtle linear gradient transitioning from `primary` (#003fb1) to `primary_container` (#1a56db). For floating elements like modals or mobile navigation, apply **Glassmorphism**: use semi-transparent surface colors with a `backdrop-blur` of 12px–20px to integrate the element into the environment.

---

## 3. Typography: The Editorial Scale
We use a dual-typeface system to balance authority with readability.

*   **Display & Headlines (Manrope):** Chosen for its geometric modernism. Use `display-lg` and `headline-md` to create clear entry points in the OPAC and Auth screens. The wide tracking of Manrope provides an authoritative, "institutional" voice.
*   **Body & Labels (Inter):** The workhorse for operational efficiency. Use `body-md` for all data-heavy tables and `label-sm` for metadata. Inter’s tall x-height ensures legibility even in dense administrative views.

**Hierarchy Note:** High contrast is achieved through scale, not just weight. A `display-sm` headline paired with a `body-md` description creates a professional, intentional "magazine" feel.

---

## 4. Elevation & Depth
Depth is achieved through **Tonal Layering** and physics-based lighting, never through harsh shadows.

*   **The Layering Principle:** Stack `surface-container-lowest` cards on top of `surface-container-low` backgrounds. This creates a soft, natural lift that mimics fine stationery.
*   **Ambient Shadows:** When an element must "float" (e.g., a search dropdown), use a shadow with a 24px blur, 0% spread, and 4-6% opacity using a tint of the `on-surface` color (#151c27). This mimics natural ambient light.
*   **The Ghost Border:** If a container requires a boundary for accessibility (e.g., input fields), use the `outline-variant` (#c3c5d7) at **15% opacity**. Never use 100% opaque borders.

---

## 5. Components & Layouts

### Primitive Components
*   **Buttons:** Use the `xl` (1.5rem) or `lg` (1rem) corner radius. Primary buttons should utilize the signature gradient. Tertiary buttons should have no background, using only `on-surface` text and a subtle `surface-variant` hover state.
*   **Input Fields:** Avoid boxes. Use a "floating label" style with a `surface-container-low` background and a `Ghost Border` bottom-edge. Validation states must use `error` (#ba1a1a) text with an `error_container` soft background.
*   **Cards & Data Tables:** **Forbid the use of divider lines.** Separate rows and card sections using vertical white space (use the `md` or `lg` spacing tokens) or alternating tonal shifts (Zebra striping using `surface` and `surface-container-low`).

### Signature Layouts
*   **Auth (Central Card):** Use a `surface-container-lowest` card with an `xl` radius. Employ an asymmetrical layout: a large `display-md` greeting on the left and the functional form on the right, separated by white space rather than a line.
*   **Admin Sidebar:** A `surface-container-low` vertical bar. Active states should use a pill-shaped `primary_fixed` background with `on-primary_fixed` text, moving away from standard square highlights.
*   **OPAC Hero:** A "search-first" experience. Use a large hero area with a `primary` to `primary_container` gradient. The search input should be a massive `xl` rounded glassmorphic element, making the search for knowledge feel modern and expansive.

---

## 6. Do’s and Don’ts

### Do
*   **Do** use white space as a functional tool to group related library items.
*   **Do** apply the `xl` border radius (1.5rem) to primary containers to soften the "institutional" feel.
*   **Do** use `secondary` (Identity Green) for all success states to maintain brand cohesion.
*   **Do** ensure high contrast between `on-surface` and `surface` for AAA accessibility in library cataloging.

### Don’t
*   **Don’t** use 1px solid borders to separate table rows; use tonal shifts.
*   **Don’t** use pure black (#000000) for text; always use `on-surface` (#151c27).
*   **Don’t** use the default Bootstrap "Blue"; always map to the `primary` token (#003fb1).
*   **Don’t** crowd the screen. If the interface feels "busy," increase the padding using the `lg` or `xl` scale.

---

## 7. Roundedness Scale Reference
*   **XL (1.5rem):** Main containers, Hero search bars, Auth cards.
*   **LG (1rem):** Standard cards, Modals, Buttons.
*   **MD (0.75rem):** Input fields, Chips, Badges.
*   **SM (0.25rem):** Tooltips, Small metadata tags.
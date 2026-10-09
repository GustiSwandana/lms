---
name: Syntropic Learning
colors:
  surface: '#faf8ff'
  surface-dim: '#d2d9f4'
  surface-bright: '#faf8ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f2f3ff'
  surface-container: '#eaedff'
  surface-container-high: '#e2e7ff'
  surface-container-highest: '#dae2fd'
  on-surface: '#131b2e'
  on-surface-variant: '#444651'
  inverse-surface: '#283044'
  inverse-on-surface: '#eef0ff'
  outline: '#757682'
  outline-variant: '#c5c5d3'
  surface-tint: '#4059aa'
  primary: '#00236f'
  on-primary: '#ffffff'
  primary-container: '#1e3a8a'
  on-primary-container: '#90a8ff'
  inverse-primary: '#b6c4ff'
  secondary: '#00668a'
  on-secondary: '#ffffff'
  secondary-container: '#40c2fd'
  on-secondary-container: '#004d6a'
  tertiary: '#19294b'
  on-tertiary: '#ffffff'
  tertiary-container: '#303f63'
  on-tertiary-container: '#9cabd5'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dce1ff'
  primary-fixed-dim: '#b6c4ff'
  on-primary-fixed: '#00164e'
  on-primary-fixed-variant: '#264191'
  secondary-fixed: '#c4e7ff'
  secondary-fixed-dim: '#7bd0ff'
  on-secondary-fixed: '#001e2c'
  on-secondary-fixed-variant: '#004c69'
  tertiary-fixed: '#d9e2ff'
  tertiary-fixed-dim: '#b6c6f1'
  on-tertiary-fixed: '#081a3c'
  on-tertiary-fixed-variant: '#37466a'
  background: '#faf8ff'
  on-background: '#131b2e'
  surface-variant: '#dae2fd'
typography:
  headline-xl:
    fontFamily: Plus Jakarta Sans
    fontSize: 40px
    fontWeight: '700'
    lineHeight: 48px
    letterSpacing: -0.025em
  headline-xl-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 30px
    fontWeight: '700'
    lineHeight: 38px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 30px
    fontWeight: '600'
    lineHeight: 38px
    letterSpacing: -0.02em
  headline-lg-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.015em
  headline-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 22px
    fontWeight: '600'
    lineHeight: 28px
    letterSpacing: -0.015em
  headline-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 18px
    fontWeight: '600'
    lineHeight: 24px
    letterSpacing: -0.01em
  body-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 26px
    letterSpacing: -0.005em
  body-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 22px
    letterSpacing: 0em
  body-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 13px
    fontWeight: '400'
    lineHeight: 18px
    letterSpacing: 0em
  label-md:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.02em
  label-sm:
    fontFamily: Inter
    fontSize: 11px
    fontWeight: '500'
    lineHeight: 14px
    letterSpacing: 0.04em
  code-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: 0em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  gutter: 1.5rem
  gutter-mobile: 1rem
  margin: 2rem
  margin-mobile: 1rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2.5rem
---

## Brand & Style

This design system establishes a high-performance, distraction-free educational SaaS environment tailored for structured video learning. The core brand personality is disciplined, analytical, and cognitively light. It bridges professional SaaS tooling with academic rigor, removing visual noise to maximize knowledge retention and focus during extended study sessions.

The design movement combines **Modern Minimalism** with **Precision Utility**:
- **Cognitive Clarity:** Stark, unburdened light-mode canvas surfaces that preserve reader stamina and prevent eye fatigue.
- **Academic Focus:** Controlled accents where color only denotes actionable affordance, progression metrics, or critical playback telemetry.
- **Architectural Utility:** Deliberate border micro-contrasts, dense information hierarchy, crisp structural rhythm, and zero decorative visual overhead.

## Colors

The palette employs deep naval foundations alongside luminous high-frequency blues to deliver authority, focus, and actionable visibility.

- **Foundations & Backgrounds:** The base interface runs on `#F8FAFC` (Slate 50) for outer frame canvas, transitioning to pure `#FFFFFF` for focus panels, course outlines, and active lesson content. Tiered utility surfaces rely on `#F1F5F9` (Slate 100) for control bars and secondary module drawers.
- **Brand & Action Tiers:** `#1E3A8A` serves as the primary structural brand anchor, with `#2563EB` reserved for direct primary action surfaces (CTA buttons, active step highlights). `#38BDF8` functions as an electrical telemetry accent, utilized exclusively for current playback timestamps, live module completion rings, and sharp focus highlights.
- **Text & Structure:** Deep dark ink `#0F172A` delivers optimal contrast for body text and headers, softened to `#475569` for meta-descriptions, and framed cleanly with `#E2E8F0` structural hairpins.
- **Surface Contrast Rule:** The video player viewport and active cinema mode exclusively invert to `#0A192F` and `#0F2042` to eliminate ambient glare during video study sessions.

## Typography

The typographic hierarchy is engineered around rhythmic balance between Plus Jakarta Sans for welcoming, human-engineered structural architecture and Inter for dense metadata, timestamps, and interface status labels.

- **Headlines:** Use tight negative letter-spacing (`-0.025em` down to `-0.01em`) to create structured, authoritative section anchors that hold weight even alongside dense video viewports.
- **Body:** Open line heights (`1.5` to `1.625`) on `Plus Jakarta Sans` guarantee extended legibility across course lecture notes, video transcripts, and lesson summaries.
- **Labels & System Badges:** `Inter` handles all analytical UI microcopy—including video timecodes (`04:32 / 18:20`), pill badges, lesson states, and table headers—with positive tracking for rapid visual processing.

## Layout & Spacing

The layout follows a fluid-flexible grid system based on a predictable 8pt structural rhythm, optimized for simultaneous content consumption and contextual note-taking.

- **Desktop (>= 1280px):** 12-column structure with `2rem` outer margin and `1.5rem` gutter. The primary classroom layout employs an asymmetric split: 8 columns dedicated to the primary 16:9 embedded video player and dynamic lesson materials, paired with a persistent 4-column module syllabus or transcript sidebar.
- **Tablet (768px – 1279px):** 8-column layout. The secondary syllabus pane collapses into an off-canvas drawer or shifts beneath the main media block with `1.5rem` vertical spacing.
- **Mobile (< 768px):** 4-column fluid single-column flow with `1rem` margins and `1rem` gutters. The media player anchors to top viewport edge using zero-margin aspect-ratio locking, while lesson cards and interactive quizzes flow vertically underneath.
- **Internal Density:** Internal component padding scales predictably using `space-xs` (tags/badges) through `space-lg` (large classroom cards) to preserve rhythmic breathing room.

## Elevation & Depth

This design system avoids heavy drop shadows, instead utilizing layered structural surfaces and ultra-crisp 1px borders (`#E2E8F0`) to articulate elevation.

- **Level 0 (Canvas Base):** Flat `#F8FAFC`, background structural container for app routing.
- **Level 1 (Card & Content Surface):** `#FFFFFF` surface bounded by a continuous 1px `#E2E8F0` hairline border. No shadow is applied in default states.
- **Level 2 (Interactive Floating Modules / Hover States):** `#FFFFFF` paired with an ambient micro-diffused shadow: `0px 4px 12px -2px rgba(15, 23, 42, 0.05), 0px 2px 4px -1px rgba(15, 23, 42, 0.03)` and border shifted to `#CBD5E1`.
- **Level 3 (Modals, Overlays, Command Palettes):** `#FFFFFF` with `0px 20px 24px -4px rgba(15, 23, 42, 0.08), 0px 8px 8px -4px rgba(15, 23, 42, 0.03)` with border `#CBD5E1`.
- **Media Depth Treatment:** Video player shells sit embedded within a recessed `#0A192F` frame with sharp inner optical clipping, ensuring external light surfaces do not wash out player contrast.

## Shapes

The shape system leverages intentional geometric contrast to guide the learner's attention:

- **Cards and Panels:** Set to `0.5rem` (`roundedness: 2`) to produce clean, modern structural enclosures that feel approachable without consuming functional layout space.
- **Pill Badges:** Hard-coded to `9999px` (full pill radius) to clearly separate discrete status, timecode, or tag metadata from rectilinear structural cards.
- **Media Containers:** `0.75rem` (`rounded-xl`) outer radius on video wrapper frames, with internal player viewports masked cleanly to match corner curvature.
- **Data Tables & Code Panels:** Precise, minimal corners (`0.375rem`) to ensure table headers, borders, and rows remain straight, utilitarian, and clean.

## Components

### Buttons
- **Primary:** Solid `#2563EB` background, white label text, `0.5rem` border radius, padding `0.625rem 1.25rem`. Hover state: `#1E3A8A` transition with zero transform. Active state: `#172554`.
- **Secondary / Outline:** Background transparent, 1px `#E2E8F0` border, `#0F172A` text. Hover state: background `#F1F5F9`, border `#CBD5E1`.
- **Ghost Action (Media Controls):** Transparent background, `#475569` text and icons. Hover state: `#F1F5F9` background, `#0F172A` icon tint.

### Badges & Pill Tags
- **Timestamp Chip:** Pill-shaped (`9999px`), `#0F2042` background, `#38BDF8` text (`label-sm`), displaying current jump timestamps (e.g., `12:44`).
- **Course Status Pill:**
  - *Completed:* `#ECFDF5` background, `#059669` text, 1px `#A7F3D0` border.
  - *In Progress:* `#EFF6FF` background, `#2563EB` text, 1px `#BFDBFE` border.
  - *Optional:* `#F8FAFC` background, `#64748B` text, 1px `#E2E8F0` border.

### Course Outline & Module Lists
- **List Items:** Row padding `0.75rem 1rem`, separated by 1px `#F1F5F9` bottom borders.
- **Active Lesson State:** Left border accent 3px solid `#2563EB`, background `#F8FAFC`, active text `#1E3A8A` bold with an indicator icon in `#38BDF8`.
- **Completed State:** Circular muted checkbox with `#059669` checkmark icon and slate text.

### Form Inputs & Video URL Ingestion
- **Fields:** Pure `#FFFFFF` background, 1px `#E2E8F0` border, `0.5rem` radius, `0.625rem 0.875rem` padding. Placeholder text in `#94A3B8`.
- **Focus State:** 1px `#2563EB` border with a crisp `0 0 0 3px rgba(37, 99, 235, 0.12)` focus ring.
- **YouTube Link Bar:** Specialized compound input with embedded left-aligned YouTube icon, auto-parse confirmation badge on the right, and immediate `#2563EB` "Parse Module" button inside the right margin.

### Cards
- **Curriculum Card:** `#FFFFFF` background, 1px `#E2E8F0` border, `0.5rem` radius, `1.25rem` internal padding. Features high-contrast course title, progress bar track in `#E2E8F0` with fill in `#2563EB`, and module count label.

### Data Tables
- **Table Headers:** `#F8FAFC` background, 1px `#E2E8F0` horizontal divider, `label-md` uppercase text in `#475569`, cell padding `0.75rem 1rem`.
- **Table Rows:** Alternating hover highlight (`#F8FAFC`), crisp single-pixel `#E2E8F0` bottom borders, `#0F172A` body text.

### Video Player Frame & Controls Bar
- **Wrapper:** Aspect ratio `16:9`, background `#0A192F`, border 1px solid `#1E293B`, radius `0.75rem`.
- **Custom Telemetry Bar:** Recessed controls housing `#38BDF8` scrubber timeline, chapter marks depicted by 2px white gap dividers, and high-density Inter labels for timestamp and playback speed controls (`1.0x`, `1.25x`, `1.5x`, `2.0x`).
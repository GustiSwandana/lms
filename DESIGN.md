# DESIGN.md — SIAGA BUMI-SSK Design System
**"Tactile Eco-Editorial (Authoritative Light Mode)"**
*Video-based Learning Management System for Sekolah Siaga Kependudukan (SSK) — BKKBN Perwakilan Sulawesi Utara*

---

## 1. Design Philosophy & Liveliness Dials

SIAGA BUMI-SSK balances the institutional authority of **BKKBN Sulawesi Utara** with the tactile, distraction-free clarity of an editorial learning environment. The interface uses a clean, warm alabaster canvas with crisp pure-white elevated surfaces, high-contrast typography, and purposeful semantic accents representing the three SSK curriculum pillars.

- **Energy:** `3/10` — Calm, focused, and scholarly. Avoids fluorescent neons, excessive gradients, floating decorative shapes, and gimmick animations.
- **Rhythm:** `6/10` — Predictable spacing rhythm (`space-y-6`, `gap-6`, `p-6` on cards) with consistent typographic hierarchy and minimum 44px touch targets.
- **Motion:** `2/10` — Micro-interactions only (150ms–200ms `ease-out` for hover states and transitions). Zero distracting loops.

---

## 2. Color Palette & Surface Tokens (Strict Light Mode)

### Base Surfaces
- **Canvas / Background:** `#F8FAFC` (Slate 50 / Warm Alabaster Stone — never blinding `#FFFFFF` for full page backdrops).
- **Cards & Elevated Panels:** `#FFFFFF` (Pure White) with crisp `border border-slate-200` (or `border-zinc-200/80`) and clean micro-shadows (`shadow-xs` / `shadow-sm`).
- **Surface Hover / Subtle:** `#F1F5F9` (Slate 100).
- **Surface Active / Selected:** `#E2E8F0` (Slate 200).

### Text & Typography
- **Primary Headings & Titles:** `#0F172A` (`text-slate-900`) — High contrast, crisp weight (600/700).
- **Secondary / Body Text:** `#334155` (`text-slate-700`) & `#475569` (`text-slate-600`) — Highly legible.
- **Muted Labels & Metadata:** `#64748B` (`text-slate-500`).
- **Monospace Tabular Data:** `#0F172A` with `font-mono tabular-nums` for timestamps, durations, quiz scores, and registration numbers.

### 3 SSK Curriculum Pillar Semantic Tokens (Light Mode Adjusted)
1. **Materi Umum Kependudukan (GenRe):**
   - Accent: Indigo `#4F46E5` (`text-indigo-700`)
   - Background Tint: `#EEF2FF` (`bg-indigo-50`)
   - Border Tint: `#C7D2FE` (`border-indigo-200`)
2. **Materi Umum Lingkungan (Siaga Bumi):**
   - Accent: Emerald `#059669` (`text-emerald-700`)
   - Background Tint: `#ECFDF5` (`bg-emerald-50`)
   - Border Tint: `#A7F3D0` (`border-emerald-200`)
3. **PPKS (Pencegahan & Penanganan Kekerasan Seksual / Kespro):**
   - Accent: Terracotta Rose `#E11D48` (`text-rose-700`)
   - Background Tint: `#FFE4E6` (`bg-rose-50`)
   - Border Tint: `#FECDD3` (`border-rose-200`)
4. **BKKBN Institutional Authority (Certificates & Official Verification):**
   - Accent: Amber / Gold `#D97706` (`text-amber-800`)
   - Background Tint: `#FEF3C7` (`bg-amber-50`)
   - Border Tint: `#FDE68A` (`border-amber-200`)

---

## 3. Typography Hierarchy

- **UI Headings & Body:** `'Plus Jakarta Sans'`, `'Inter'`, system sans-serif.
- **Numbers, Durations, Scores, & Codes:** `'JetBrains Mono'`, monospace with `tabular-nums`.
- **Certificate Formal Script:** `'Cinzel'`, serif for official graduation titles.

---

## 4. Spacing, Rhythm & Touch Ergonomics

- **Minimum Touch Target:** `44px × 44px` (`min-h-11` or `h-11`) for all interactive elements (buttons, inputs, filters, tabs, syllabus items).
- **Card Padding:** `p-5` to `p-6` for standard desktop cards, `p-4` for compact mobile screens.
- **Grid Layouts:** Responsive multi-column layouts (`grid-cols-1 md:grid-cols-2 lg:grid-cols-3`) with strict `overflow-x-hidden`.
- **Progress Representation:** Clean linear bars (`h-2 rounded-full bg-slate-100` with emerald `#059669` fill). Zero circular progress rings.

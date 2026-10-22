# Design: Semantic Frontend Color Hierarchy

## Context

See `proposal.md` for user problem and motivation. The public frontend interface (`resources/views/frontend/*` and `resources/views/layouts/app.blade.php`) currently employs accent colors in a scattered decorative manner—adorning secondary links, generic icon containers, and casual pill badges. This dilutes visual hierarchy and weakens actionable focus.

## Goals / Non-Goals

**Goals:**
- **Focal Action Exclusivity**: Reserve the bright accent (`amber-400` / `amber-500`) strictly for the primary institutional action: **Online Admission / Apply Now** ("অনলাইন ভর্তি আবেদন" / "ভর্তি আবেদন") and exactly one highlighted phrase per headline.
- **Dedicated Category Signaling**: Reserve a consistent second color (`sky-600` / `blue-600`) exclusively for **Notices, Announcements & Circulars** ("নোটিশ ও সার্কুলার").
- **Calm Neutral Base**: Transition all generic cards, icon backgrounds, and secondary links to a calm, dignified neutral palette (`slate-50`, `slate-100`, `slate-200`, `slate-700`, `slate-800`).
- **Telemetry Preservation**: Keep real-world metrics, attendance status indicators, and operational state colors completely intact.
- **Strict Isolation**: Confine all edits to public frontend Blade templates; zero alterations in `resources/views/admin/*`.

**Non-Goals:**
- No alterations to backend routes, controllers, or database schemas.
- No modifications to layout grids, container widths, or typography font sizes.
- No changes to administrative dashboard layouts or stylesheets.

## Decisions

### 1. Bright Accent Mapping (`amber-400` / `amber-500`)
- **Action**: Online Admission Application CTA.
  - Desktop navbar admission link/button: `bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold`.
  - Closing enrollment banner primary CTA: `bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold`.
- **Headlines**: Exactly one highlighted phrase per major section headline (`<span class="text-amber-400">` on dark surfaces, `<span class="text-[#005030]">` or amber highlight on light surfaces).
- **Alternative Considered**: Using bright green (`emerald-400`) as the action color. Rejected because dark forest green is already the institutional foundation; amber provides maximum optical pop and unmistakable conversion prominence.

### 2. Second Color Reservation (`sky-600` / `blue-600`)
- **Category**: Official Notices & Circulars ("নোটিশ ও সার্কুলার").
  - Notice board widget bell container / header accent: `text-sky-600` / `bg-sky-50 border-sky-200`.
  - Notice category pills and date indicators: `bg-sky-50 text-sky-800 border-sky-200`.
  - Circular cards in notice index: `hover:border-sky-500/50`.
- **Alternative Considered**: Using red/crimson. Rejected because crimson implies emergency/danger rather than routine administrative bulletins. Sky/Cobalt blue provides calm authority and unmistakable category recognition.

### 3. Neutralization of Generic Cards and Icons
- Generic academic cards, why-choose-us pillars, and campus facility icons replace bright green/amber circular bubbles with calm `bg-slate-100 text-slate-700 border-slate-200` or subtle deep green brand tints.
- Secondary buttons ("সকল নোটিশ", "বিস্তারিত পড়ুন", "কম্পিউটার ল্যাব সুবিধা") adopt calm institutional styling (`bg-[#005030]`, `bg-slate-900`, or `border-slate-300 text-slate-700`).

## Risks / Trade-offs

- **Risk: Accidental spillover into administrative views**  
  *Mitigation:* Work strictly within `resources/views/frontend/` and run `git status` verification gates to confirm zero admin file touches.
- **Risk: Poor text contrast on amber background buttons**  
  *Mitigation:* Always enforce dark high-contrast typography (`text-slate-950` with `font-extrabold` or `font-bold`) inside amber elements, meeting WCAG AA standards.

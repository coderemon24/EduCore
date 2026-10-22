# Tasks: About Page UI Refactoring & Redesign

## 1. Layout & Blade Architecture

- [x] 1.1 Remove redundant embedded `<style>` block (lines 48–114) from `resources/views/frontend/about/index.blade.php`, strictly delegating all styles to `public/assets/css/style.css`.
- [x] 1.2 Structure the page into alternating sections (`bg-white` and `bg-slate-50/60`) separated by hairline structural dividers (`<div class="home-section-divider"></div>`).
- [x] 1.3 Implement the executive breadcrumb navigation and modernized institutional Hero Header with plain-spoken copy and a single `<span class="text-[#005030] font-bold">` focal accent.
- [x] 1.4 Implement the dark institutional Qualifying Credentials Meta Strip beneath the hero displaying EIIN (১২৫৬৯৩), Rajshahi Education Board, 100% MPO status, and Founding Year (১৯৬৮).

## 2. Asymmetric Content Sections & Master Recipes

- [x] 2.1 Refactor Historical Narrative into an asymmetric 60/40 editorial grid (`lg:col-span-7` story with blockquote lead paragraph and quality badges; `lg:col-span-5` campus photo card with floating `Estd. 1968` anchor badge).
- [x] 2.2 Re-architect Vision, Mission, and Core Values from 3 identical columns into a 65/35 Institutional Manifesto (Flagship Vision & Mission combined manifesto card vs. anchored vertical stack of Core Ethical Values).
- [x] 2.3 Refactor Statistics into Hallmark Accomplishments, elevating the top 2 hallmark metrics (`text-4xl lg:text-5xl font-extrabold text-[#005030]`) with quiet secondary metrics.
- [x] 2.4 Refactor Chronological Milestones Timeline with refined editorial typography and subtle hover transitions.
- [x] 2.5 Refactor "At a Glance" (এক নজরে প্রতিষ্ঠান) and Managing Committee (পরিচালনা পর্ষদ) into high-contrast editorial tables with clean hover states.
- [x] 2.6 Apply "Pick the Winner" to Campus Facilities, elevating the Sheikh Russel Digital Computer Lab to a 65% flagship showcase with secondary facilities in a compact 35% card stack.

## 3. Conversion Actions & Semantic Polish

- [x] 3.1 Implement the closing Next Step / Call to Action section inviting campus visits and inquiries with an Amber primary CTA button (`route('frontend.contact')`) and secondary link (`route('frontend.academic.routine')`).
- [x] 3.2 Audit semantic color discipline across all components: restrict Amber (`amber-400`) exclusively to the primary conversion button, apply primary green (`#005030`) for institutional accents, and eliminate arbitrary borders.
- [x] 3.3 Enforce the 4-tier typographic scale (`Tier 1` through `Tier 4`), verifying that all body copy is at least `text-sm` (14px) with `leading-relaxed` line height.

## 4. Verification & Testing

- [x] 4.1 Test public `/about` route rendering via automated test / HTTP request to verify clean Blade compilation and zero missing variable exceptions.
- [x] 4.2 Verify fallback behavior when custom database records are absent, ensuring `AboutPageContent::forSchool()` defaults render cleanly without broken UI blocks.
- [x] 4.3 Verify responsive behavior across mobile (<640px), tablet (768px-1024px), and desktop (>=1024px) viewports with zero horizontal overflow.
- [x] 4.4 Verify that administrative management routes (`/admin/frontend/pages/about`) and backend controller endpoints remain completely untouched and operational.

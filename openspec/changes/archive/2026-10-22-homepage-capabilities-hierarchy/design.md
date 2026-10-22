# Design

## Context

The school homepage (`resources/views/frontend/home/index.blade.php`) currently relies on `.capabilities-grid` (`public/assets/css/style.css:883-899`), rendering 4 identical cards in a `repeat(4, 1fr)` layout. Each card uses identical padding, borders, and typography (`text-lg font-bold`). Furthermore, Card 3 duplicates the dedicated Notice Board widget present in Section 5. Similarly, Section 4's metrics counter allocates identical 6-column space to administrative registry identifiers and headline academic achievements.

See `proposal.md` for background motivation and `specs/client-school-portal/spec.md` for behavioral scenarios.

## Goals / Non-Goals

**Goals:**
- Replace the rigid 4-card grid in Section 3 (`#capabilities`) with an asymmetric visual hierarchy (Flagship Hero + Secondary Tier).
- Elevate Hands-on Science & Innovation Labs as the dominant flagship capability through spatial footprint (60–65% width), typography scale (`text-2xl`), and expanded breathing room.
- Eliminate the duplicate notices card from `#capabilities`.
- Reorganize the remaining two capabilities (Digital ICT & Co-curricular Leadership) into a compact, lighter secondary tier.
- Restructure Section 4's metric display so that student scale and board pass rates command visual priority over administrative registry codes.
- Maintain seamless responsive adaptation with zero horizontal overflow across mobile, tablet, and desktop viewports.

**Non-Goals:**
- Modifying backend controllers, models, or database migrations (purely frontend Blade & CSS architecture).
- Introducing decorative animations, ambient glows, or gradient text fills (strictly adhering to the no-slop editorial design principles).

## Decisions

### 1. Asymmetric Bento Grid (60% / 40% Split) for Academic Capabilities
- **Choice**: Implement `.capabilities-grid` as an asymmetric 2-column layout on desktop (`1024px+`):
  - **Left / Flagship (7fr / ~60%)**: Dedicated to *ব্যবহারিক বিজ্ঞান ও উদ্ভাবনী ল্যাব*. Uses `p-8 sm:p-10`, `text-2xl font-extrabold` heading, rich descriptive copy, and a 3-point feature checklist (Physics, Chemistry, Biology labs & Annual Science Fair).
  - **Right / Secondary Stack (5fr / ~40%)**: Contains two vertically stacked cards (*ডিজিটাল আইসিটি ও কম্পিউটার শিক্ষা* and *স্কাউটিং, বিতর্ক ও ক্রীড়াঙ্গন*) with compact padding (`p-5 sm:p-6`), `text-base font-bold` headings, and concise summaries.
- **Alternatives Considered**: 
  - *Full-width showcase row + 2-column bottom row*: Creates excessive vertical scroll height for early homepage visitors.
  - *Keeping 4 cards but coloring one differently*: Color-only hierarchy violates clean design principles and fails visual scanning tests.

### 2. Elimination of Duplicate Notice Card
- **Choice**: Remove Card 3 (*স্বচ্ছ মূল্যায়ন ও সার্বক্ষণিক নোটিশ*) from the capabilities grid.
- **Rationale**: Section 5 already provides a dedicated live Notice Board with categorized circulars and PDF downloads. Pruning this card sharpens the section's focus on academic delivery and frees room for the asymmetric bento layout.

### 3. Institutional Metrics Visual Hierarchy
- **Choice**: In Section 4 (`#advantage`), elevate key outcome metrics (*৯৮.৫% এসএসসি সাফল্য*, *১,২৫০+ শিক্ষার্থী*, *৫৭ বছর*) with dominant typography (`text-3xl font-extrabold`). Move administrative registry numbers (*EIIN: ১২৫৬৯৩*, *কোড: ৮১০৭*) to a clean secondary metadata bar.
- **Alternatives Considered**: Retaining all 6 cards in an equal 6-column grid. Rejected because equal weight trivializes academic achievements.

## Risks / Trade-offs

- **[Risk]** Asymmetric cards might collapse awkwardly on tablet screens (768px–1023px).
  - **Mitigation**: Define explicit media query rules: On mobile (<640px), stack vertically with hero first; on tablet (640px–1023px), maintain an uncrowded single-column or balanced 2-column arrangement; on desktop (>=1024px), activate the 7fr/5fr bento grid.
- **[Risk]** Bengali font rendering (`Hind Siliguri`) might cause uneven heights in secondary stacked cards.
  - **Mitigation**: Use `flex flex-col justify-between` on secondary cards to maintain alignment and balanced spacing.

# Spec Delta

## MODIFIED Requirements

### Requirement: 3-Column Modern Editorial Homepage Feed
The homepage (`resources/views/frontend/home/index.blade.php`) SHALL feature an institutional editorial feed with an asymmetric visual hierarchy: (1) An asymmetric academic capabilities grid where the primary academic offering commands dominant width and scale over lighter secondary tiers, eliminating redundant notice cards, (2) An institutional information hub with real-time notice board and campus stories, and (3) An institutional metrics bar that elevates hallmark educational outcomes (student enrollment, board success rate, institutional longevity) above administrative registry codes.

#### Scenario: Homepage 3-column content presentation
- **WHEN** a visitor loads the homepage on desktop screens (>= 1024px)
- **THEN** Column 1 renders the lead highlight story with photo and secondary highlighted items
- **AND** Column 2 displays the most recent notices in a compact, scrollable or structured list with publication dates
- **AND** Column 3 displays the Headmaster / Principal message card and quick access links (online admission, portal login, result archive).

#### Scenario: Homepage statistics and notice ticker
- **WHEN** the homepage loads
- **THEN** a marquee or smooth-scroll ticker displays urgent/pinned notices at the top
- **AND** an institutional metrics section displays key numbers (total students, faculty count, classrooms, established year).

#### Scenario: Visual dominance in academic capabilities section
- **WHEN** a visitor navigates to the academic capabilities section (`#capabilities`) on desktop viewports (>= 1024px)
- **THEN** the primary capability (*ব্যবহারিক বিজ্ঞান ও উদ্ভাবনী ল্যাব*) spans 60%–65% of the grid width with prominent headline scale (`text-2xl`), expanded padding, and dedicated laboratory curriculum proof points
- **AND** secondary capabilities (*ডিজিটাল আইসিটি শিক্ষা* and *স্কাউটিং, বিতর্ক ও ক্রীড়াঙ্গন*) sit in a lighter secondary tier with smaller headings (`text-base`), subdued borders, and concise summaries
- **AND** no duplicate notice board or evaluation card is rendered in the capabilities grid.

#### Scenario: Responsive reflow of capabilities grid
- **WHEN** the homepage is viewed on mobile screens (< 768px) or tablet screens (768px–1023px)
- **THEN** the capabilities layout reflows into a natural stacked order without horizontal overflow, preserving the visual prominence of the primary capability at the top of the stack.

#### Scenario: Visual hierarchy of institutional metrics
- **WHEN** a visitor views the institutional metrics section in `#advantage`
- **THEN** key educational outcomes (*৯৮.৫% এসএসসি সাফল্য*, *১,২৫০+ বর্তমান শিক্ষার্থী*, and *৫৭ বছরের ঐতিহ্য*) render with primary typographic weight and spacious padding
- **AND** administrative registry numbers (such as EIIN: 125693 and School Code: 8107) are displayed in a secondary meta strip rather than occupying identical metric cards.

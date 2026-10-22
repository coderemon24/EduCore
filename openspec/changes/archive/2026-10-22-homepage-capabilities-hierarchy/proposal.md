# Proposal

## Why

Currently, the academic capabilities section (`#capabilities`) on the school homepage features a rigid 4-card grid where every card shares identical dimensions, padding, typography, and visual weight. Nothing indicates to visiting parents or students what the institution values most. Furthermore, Card 3 ("স্বচ্ছ মূল্যায়ন ও সার্বক্ষণিক নোটিশ") is an unnecessary placeholder that duplicates Section 5's dedicated live Notice Board and circular hub. Similarly, the institutional metrics counter in Section 4 treats administrative registry numbers (like EIIN) with identical visual weight to hallmark academic accomplishments (such as the 98.5% SSC board success rate).

Establishing an intentional visual hierarchy—by eliminating redundant cards, allowing the flagship academic offering to dominate through spatial scale, typography weight, and breathing room, and placing supporting pillars in a quieter secondary tier—will immediately communicate institutional strength and clarity without relying on artificial color washes.

## What Changes

- **Prune Redundant Cards**: Eliminate the duplicate notices/evaluation card from Section 3 (`#capabilities`), focusing the section strictly on academic and holistic excellence.
- **Flagship Dominant Hero**: Elevate Practical Science & Laboratory Innovation (*ব্যবহারিক বিজ্ঞান ও উদ্ভাবনী ল্যাব*) into a commanding primary card spanning 60–65% width with prominent typography, detailed laboratory capabilities, and generous whitespace.
- **Lighter Secondary Tier**: Structure Digital ICT/Computer Education and Co-curricular Leadership into a clean, compact secondary tier with restrained padding, lighter borders, and concise summaries.
- **Institutional Metrics Hierarchy**: Refine Section 4's metrics grid so that student scale and board success rate clearly command attention, while administrative data (EIIN, School Code) is relocated to a refined meta strip.
- **Responsive Adaptability**: Ensure fluid layout adaptation across desktop (asymmetric bento), tablet (balanced 2-column or split), and mobile (natural stacked hierarchy) viewports with zero horizontal overflow.

## Capabilities

### Modified Capabilities
- `client-school-portal`: Update homepage academic capabilities and metrics presentation requirements to enforce purposeful visual hierarchy with an asymmetric dominant flagship feature and lighter secondary tier.

## Impact
- **Templates**: `resources/views/frontend/home/index.blade.php` (restructuring `#capabilities` grid and `#advantage` metric indicators).
- **Styles**: `public/assets/css/style.css` (updating `.capabilities-grid` grid template and secondary card styling).
- **Backend / Database**: Zero database migrations or controller changes required.

# Tasks

## 1. CSS & Layout Architecture

- [x] 1.1 Update `.capabilities-grid` in `public/assets/css/style.css` to implement an asymmetric bento layout (7fr / 5fr split on desktop `>=1024px`).
- [x] 1.2 Define CSS classes for the dominant flagship card (`.capability-card-hero`) with expanded internal padding, prominent typography scale, and clean hairline borders.
- [x] 1.3 Define styling for the secondary stacked column (`.capabilities-secondary-stack`) and compact secondary capability cards.
- [x] 1.4 Add responsive media query rules in `public/assets/css/style.css` (or `responsive.css`) for tablet (640px–1023px) and mobile (<640px) natural stacking.

## 2. Homepage Blade Template Refactoring

- [x] 2.1 Remove the redundant notice board duplicate card from Section 3 (`#capabilities`) in `resources/views/frontend/home/index.blade.php`.
- [x] 2.2 Rebuild the flagship academic feature (*ব্যবহারিক বিজ্ঞান ও উদ্ভাবনী ল্যাব*) with `text-2xl font-extrabold` heading, generous whitespace, and a detailed laboratory curriculum checklist.
- [x] 2.3 Rebuild the secondary column with *ডিজিটাল আইসিটি ও কম্পিউটার শিক্ষা* and *স্কাউটিং, বিতর্ক ও ক্রীড়াঙ্গন* using compact padding and concise summaries.
- [x] 2.4 Rebalance Section 4 (`#advantage`) institutional metrics so that headline achievements (*৯৮.৫% সাফল্য*, *১,২৫০+ শিক্ষার্থী*, *৫৭ বছরের ঐতিহ্য*) dominate, while administrative registry codes (EIIN, School Code) are housed in a secondary meta strip.

## 3. Responsive Verification & Visual Testing

- [x] 3.1 Verify desktop viewports (>=1024px) ensuring clear visual hierarchy where the flagship card commands instant attention through size, space, and weight without relying on artificial colors.
- [x] 3.2 Verify mobile and tablet responsiveness ensuring zero horizontal overflow and seamless vertical reflow.
- [x] 3.3 Validate OpenSpec change compliance via `openspec validate`.

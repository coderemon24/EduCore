# Tasks

## 1. Stylesheet & CSS Architecture

- [x] 1.1 Define the `.headline-signature-accent` signature utility class in `public/assets/css/style.css` with inline-block positioning, dark slate-950 font weight (800), and a 3px amber-400 (`#f59e0b`) baseline marker stroke.
- [x] 1.2 Include responsive adjustments in `public/assets/css/style.css` or `public/assets/css/responsive.css` to scale the baseline border to 2px on mobile viewports (<640px) without disrupting glyph descenders.

## 2. Blade Views & Interaction Updates

- [x] 2.1 Update Section 3 (Academic Capabilities) in `resources/views/frontend/home/index.blade.php` to replace `<span class="text-[#005030]">` with `<span class="headline-signature-accent">` on "প্রযুক্তির আলো".
- [x] 2.2 Update Section 4 (Why We Are Different / Advantage) in `resources/views/frontend/home/index.blade.php` to apply `<span class="headline-signature-accent">` to "আমাদের শিক্ষাপদ্ধতি ও ঐতিহ্য".
- [x] 2.3 Update Section 5 (Notice Board & Dispatches) in `resources/views/frontend/home/index.blade.php` to apply `<span class="headline-signature-accent">` to "চলমান প্রাতিষ্ঠানিক পরিক্রমা".
- [x] 2.4 Update Section 6 (Admission & Enrollment Pathway) in `resources/views/frontend/home/index.blade.php` to apply `<span class="headline-signature-accent">` to "সোনাতলা যাত্রার সূচনা".
- [x] 2.5 Update Section 7 (Day-to-Day Experience) in `resources/views/frontend/home/index.blade.php` to apply `<span class="headline-signature-accent">` to "বাস্তব অনুভূতি".
- [x] 2.6 Update Section 8 (Campus Life & Proud Moments Gallery) in `resources/views/frontend/home/index.blade.php` to apply `<span class="headline-signature-accent">` to "সোনাতলা প্রাঙ্গণচিত্র".
- [x] 2.7 Update Section 9 (Closing CTA & Enrollment) in `resources/views/frontend/home/index.blade.php` to apply `<span class="headline-signature-accent">` to "আমরা প্রস্তুত".

## 3. Verification & Testing

- [x] 3.1 Verify optical contrast of the headline marker against both light backgrounds (`#ffffff`, `bg-slate-50/60`) to confirm high contrast and visual clarity.
- [x] 3.2 Inspect responsive layout across mobile (<640px), tablet, and desktop viewports to ensure no awkward line-wrapping or horizontal overflow.
- [x] 3.3 Confirm strict frontend isolation: verify zero modifications to administrative dashboard views or layouts (`resources/views/admin/*`).
- [x] 3.4 Run automated test suite via orchestrator or PHPUnit to confirm all existing tests pass without regressions.

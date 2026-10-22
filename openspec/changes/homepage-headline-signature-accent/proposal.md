# Proposal

## Why

The public frontend homepage for Sonatala High School is clean and structured, but lacks a memorable signature character. Currently, major section headlines attempt to highlight key phrases using `<span class="text-[#005030]">` (dark institutional green). However, because the base headline typography is dark slate (`#0f172a`), the dark green highlight produces almost zero optical contrast, causing the intended visual focal points to disappear.

To give the homepage an unmistakable, human-crafted signature identity without introducing AI slop or visual clutter, we need **one consistent signature detail applied with strict restraint**: an editorial **Warm Amber Headline Marker**. This signature treatment pairs high-contrast dark typography with a warm-amber baseline marker stroke on exactly one thematic phrase per major section headline across the homepage, bringing warmth, visual rhythm, and academic prestige to the reading experience.

## What Changes

1. **Signature Detail Implementation**:
   - Introduce a dedicated CSS utility class (`.headline-signature-accent` or `.headline-accent-marker`) in `public/assets/css/style.css` featuring an inline-block container with a disciplined warm amber baseline stroke (`border-b-2 sm:border-b-[3px] border-amber-400 pb-0.5 text-slate-950 font-black`) and optional subtle background warmth.
2. **Homepage Headline Application**:
   - Update the 7 major section headlines in `resources/views/frontend/home/index.blade.php` to replace the ineffective `<span class="text-[#005030]">` with the new signature accent marker:
     1. **Academic Capabilities**: `হাতেকলমে শিক্ষা, <span class="headline-signature-accent">প্রযুক্তির আলো</span> ও মানবিক মূল্যবোধের পূর্ণাঙ্গ বিকাশ`
     2. **Institutional Advantage**: `সাধারণ স্কুলের চেয়ে কেন ব্যতিক্রমী <span class="headline-signature-accent">আমাদের শিক্ষাপদ্ধতি ও ঐতিহ্য</span>?`
     3. **Notice Board**: `জরুরি নোটিশ বোর্ড ও <span class="headline-signature-accent">চলমান প্রাতিষ্ঠানিক পরিক্রমা</span>`
     4. **Admission Steps**: `মাত্র ৩টি সহজ ধাপে আপনার সন্তানের <span class="headline-signature-accent">সোনাতলা যাত্রার সূচনা</span>`
     5. **Day-to-Day Experience**: `সোনাতলা ক্যাম্পাসে একটি সাধারণ দিনের <span class="headline-signature-accent">বাস্তব অনুভূতি</span>`
     6. **Campus Life Gallery**: `ক্যাম্পাসের সোনালী মুহূর্ত ও <span class="headline-signature-accent">সোনাতলা প্রাঙ্গণচিত্র</span>`
     7. **Closing CTA**: `২০২৬ শিক্ষাবর্ষে আপনার সন্তানের মেধার বিকাশে <span class="headline-signature-accent">আমরা প্রস্তুত</span>`
3. **Restraint & Isolation**:
   - Apply this signature detail strictly to the primary section headlines. It will **not** be scattered onto card titles, descriptions, buttons, or secondary paragraphs.
   - Zero modifications to administrative layouts or views (`resources/views/admin/*`).

## Capabilities

### New Capabilities
*(None. Modifies existing capabilities).*

### Modified Capabilities
- `frontend-color-palette`: Refines the `Bright Accent Reservation for Primary Conversion Action and Headlines` requirement to explicitly specify the signature warm amber editorial marker styling for headline focal phrases, establishing high optical contrast and a memorable visual motif.

## Impact

- **Affected Views**: `resources/views/frontend/home/index.blade.php`
- **Affected Stylesheets**: `public/assets/css/style.css`
- **Admin Isolation**: 100% isolated; no admin files or routes are touched.
- **Dependencies & DB**: Zero database schema, migration, or backend controller changes.

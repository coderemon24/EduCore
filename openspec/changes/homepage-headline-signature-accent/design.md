# Design

## Context

See `proposal.md` for overall motivation.

On the Sonatala High School public homepage (`resources/views/frontend/home/index.blade.php`), the typography uses `Noto Sans Bengali` for Bengali and `Plus Jakarta Sans` for Latin numerals and English fallbacks. The section headings (`.section-title`) are rendered in deep slate (`#0f172a`) with font weights of 800 (`font-extrabold`).

Currently, the attempted focal highlight in each section title uses an inline class `<span class="text-[#005030]">`. Against `#0f172a`, this dark forest green produces virtually unnoticeable optical separation (contrast ratio delta < 1.15), failing to act as a visual hook. At the same time, the section kickers above each heading use generic English SaaS conventions (`WHAT WE DELIVER — ACADEMIC EXCELLENCE`).

## Goals / Non-Goals

**Goals:**
- Provide a single, unmistakable signature accent detail for the public homepage using a Warm Amber Headline Marker.
- Ensure high optical contrast and legibility for both Latin and Bengali script rendering.
- Keep the signature treatment strictly disciplined: exactly one thematic phrase per section headline, avoiding visual pollution.
- Guarantee full responsiveness across mobile (<640px), tablet (640px-1024px), and desktop (>1024px) viewports with zero horizontal overflow or awkward line breaks.

**Non-Goals:**
- Modifying other pages (About, Notices, Teachers, Academic, Contact) — this change is scoped strictly to the homepage.
- Touching any administrative portal views (`resources/views/admin/*`) or admin layouts.
- Altering DOM grid structures, section paddings, or backend database/controller logic.

## Decisions

### Decision 1: CSS Utility `.headline-signature-accent`
We will define a dedicated CSS class `.headline-signature-accent` in `public/assets/css/style.css`.
- **Styling Architecture**:
  ```css
  .headline-signature-accent {
      position: relative;
      display: inline-block;
      color: #020617; /* Slate 950 for maximum contrast */
      font-weight: 800;
      border-bottom: 3px solid #f59e0b; /* Amber 400 signature baseline */
      padding-bottom: 2px;
      line-height: 1.25;
      text-decoration: none;
  }
  @media (max-width: 639px) {
      .headline-signature-accent {
          border-bottom-width: 2px;
          padding-bottom: 1px;
      }
  }
  ```
- **Rationale**: Clean, pure CSS without heavy background boxes, pseudo-element clutter, or SVG filters. A crisp 3px amber baseline anchor creates an unmistakable editorial marker aesthetic (similar to premium academic and book editions) without disrupting font ascenders/descenders.
- **Alternatives Considered**:
  - *Full background highlighter (`bg-amber-100`)*: Feels too bulky and interrupts the baseline flow of Bengali glyphs like 'হ', 'প', 'ল'.
  - *Text-only amber color (`text-amber-500`)*: On a white or light slate background, pure amber text suffers from poor accessibility contrast (<3.2:1 against `#ffffff`), failing WCAG AA. Keeping the text dark `#020617` with an amber baseline marker achieves 16.5:1 text contrast and a striking color accent.

### Decision 2: Specific Phrase Selection Across Homepage Sections
Each section title will highlight exactly one thematic phrase:
1. `#capabilities`: `হাতেকলমে শিক্ষা, <span class="headline-signature-accent">প্রযুক্তির আলো</span> ও মানবিক মূল্যবোধের পূর্ণাঙ্গ বিকাশ`
2. `#advantage`: `সাধারণ স্কুলের চেয়ে কেন ব্যতিক্রমী <span class="headline-signature-accent">আমাদের শিক্ষাপদ্ধতি ও ঐতিহ্য</span>?`
3. `#notices`: `জরুরি নোটিশ বোর্ড ও <span class="headline-signature-accent">চলমান প্রাতিষ্ঠানিক পরিক্রমা</span>`
4. `#admission`: `মাত্র ৩টি সহজ ধাপে আপনার সন্তানের <span class="headline-signature-accent">সোনাতলা যাত্রার সূচনা</span>`
5. `#experience`: `সোনাতলা ক্যাম্পাসে একটি সাধারণ দিনের <span class="headline-signature-accent">বাস্তব অনুভূতি</span>`
6. `#gallery`: `ক্যাম্পাসের সোনালী মুহূর্ত ও <span class="headline-signature-accent">সোনাতলা প্রাঙ্গণচিত্র</span>`
7. `#enroll`: `২০২৬ শিক্ষাবর্ষে আপনার সন্তানের মেধার বিকাশে <span class="headline-signature-accent">আমরা প্রস্তুত</span>`

- **Rationale**: Highlights the core institutional value proposition in each section.

## Risks / Trade-offs

- **[Risk] Line wrapping on narrow mobile screens (<360px) breaking the underline awkward**:
  → *Mitigation*: Use `display: inline-block` with `white-space: normal` or apply the underline directly via `border-bottom` so that if a multi-word phrase wraps, each line retains proper spacing and baseline stroke without clipping.
- **[Risk] High-contrast glare or competition with Admission CTA buttons**:
  → *Mitigation*: The marker uses a restrained 2px-3px bottom border rule rather than solid button fills, ensuring the primary conversion buttons (`bg-amber-400` pills) retain primary hierarchical prominence.

# Change Proposal: Frontend News & Media Portal

## Context & Motivation
Sonatala High School (`সোনাতলা উচ্চ বিদ্যালয়`) requires an engaging, modern, minimalist, and responsive institutional News & Media portal (`/news`) on the public frontend. Previously, the "সংবাদ" navigation link pointed to the notice board as a placeholder. This change delivers an independent, full-featured News & Media page (`frontend.news.index`) and news article detail view (`frontend.news.show`), adhering strictly to the existing design patterns, 7 UI recipes (`ui-refactor.md`), and GSAP animations.

## Key Goals
1. **Dedicated News Hub**: Create `/news` and `/news/{slug}` routes under `web` and `frontend.school` middleware.
2. **Minimalist & Modern Aesthetic**: Institutional `#005030` palette, warm amber accents, crisp typography (`Noto Sans Bengali` & `Plus Jakarta Sans`), asymmetric layout (flagship story card + secondary grid + curated sidebar).
3. **Interactive & Dynamic Polish**: GSAP 3 & ScrollTrigger scroll entrance animations, card hover elevation (`.hover-card-lift`), image zoom transitions, and FontAwesome 6 icons.
4. **Resilience & Zero Empty-State Risk**: Backend controller with robust pagination, search, category filtering, and realistic institutional fallback articles if database records are empty.
5. **Universal Responsiveness**: Flawless experience from mobile (360px+) to wide desktop monitors, with integrated navbar & mobile drawer navigation.

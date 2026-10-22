# Spec Delta

## MODIFIED Requirements

### Requirement: Bright Accent Reservation for Primary Conversion Action and Headlines
The public frontend views SHALL reserve the bright accent color (`amber-400` / `amber-500` / `#f59e0b`) exclusively for the primary institutional conversion action (Online Admission) and for exactly one highlighted phrase per headline, rendered using a signature editorial marker styling with high optical contrast.

#### Scenario: Visual presentation of primary admission action
- **WHEN** the primary call-to-action is rendered (such as the Admission CTA in the navbar, hero slider, or closing enrollment section)
- **THEN** it renders with the prominent bright accent styling (`bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold`), while all secondary buttons and casual navigation links use calm neutral or dark institutional styling.

#### Scenario: Headline signature accent phrase highlight
- **WHEN** major section headlines are displayed on the public landing page (`resources/views/frontend/home/index.blade.php`)
- **THEN** exactly one thematic phrase per headline utilizes the signature accent marker styling (`.headline-signature-accent` featuring an inline-block container with `border-b-2 sm:border-b-[3px] border-amber-400 pb-0.5 text-slate-950 font-black`), eliminating low-contrast green text wraps (`text-[#005030]`) and providing a clear, memorable focal orientation.

#### Scenario: Restraint of signature accent marker
- **WHEN** the homepage is rendered
- **THEN** the signature headline accent marker is strictly restricted to primary section titles (`h2.section-title`), and is NOT applied to card descriptions, auxiliary badges, body paragraphs, or gallery captions.

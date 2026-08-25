# Campussian

> A 100% standalone, modern school WordPress theme by **WhyCodeBD**.

Campussian (`cmpsian`) is a fast, accessible and fully self-contained WordPress theme built for schools, colleges and other educational institutions. It ships a rich, animated homepage, a manageable notice board with PDF previews, an events calendar, an admission page, a campus gallery with a lightbox, a student-portal preview mockup, and a native Light/Dark mode toggle — all with **no external plugin dependencies** for core presentation.

- **Author:** WhyCodeBD
- **Developer:** Salman Farshe — <https://salmanfarshe.me>
- **Version:** 1.0.0
- **License:** GPL-2.0-or-later

---

## ✨ Features

- **Standalone by design** — Bootstrap 5 and AOS are bundled locally under `/assets`; no CDN or companion plugin required.
- **Animated homepage** — modular sections with AOS "fade-up" sequential reveals.
- **Notice board** (`page-notice.php`) — date-wise (year/month) filter bar, card grid, and a Bootstrap modal preview supporting inline **PDF** and text, plus a full-page view.
- **Events** (`page-events.php`) — tabbed **Upcoming / Past** grid driven by an event-date meta field.
- **Admission** (`page-admission.php`) — criteria list, responsive fee-breakdown table and a demo application form.
- **Gallery** (`page-gallery.php`) — masonry image grid with a dependency-free lightbox.
- **Dashboard** (`page-dashboard.php`) — real, role-aware portal (student / teacher / guardian / principal / admin views). Requires the **campussian-core** plugin; shows an activation notice without it.
- **Login** (`page-login.php`) — custom front-end login page.
- **About / Contact** — mission-vision cards, contact cards, a demo contact form and an embedded map.
- **Custom post types** — `Notices` and `Events`, each with their own meta boxes (date, PDF, venue).
- **Powerful Customizer** — branding, hero, marquee, principal, statistics and admission CTA controls.
- **Native Light/Dark mode** — persisted via `localStorage`, with a no-flash boot script.
- **Accessible & responsive** — semantic markup, skip link, keyboard-friendly lightbox, `prefers-reduced-motion` support.

---

## 🎨 Design System

Brand colours are exposed as CSS custom properties in `assets/css/campussian.css`:

| Token | Value | Usage |
| --- | --- | --- |
| `--cmpsian-teal` | `#057B6D` | Primary brand colour |
| `--cmpsian-green` | `#8DAD42` | Accent / success |
| `--cmpsian-orange` | `#ED8B31` | Calls-to-action, highlights |
| `--cmpsian-white` | `#FFFFFF` | Base surface |

Dark mode is handled by overriding semantic tokens under the `[data-theme="dark"]` selector, so components inherit the correct colours automatically.

**Typography:** Poppins (headings) + Inter (body), loaded as web fonts with a robust system-font fallback.

**Frameworks:** Bootstrap 5.3, AOS 2.3 (Animate On Scroll).

---

## 📁 Folder Structure

```
campussian/
├── style.css                     # Theme header + meta
├── functions.php                 # Bootstrapping & includes (constants: WCBD_*)
├── header.php / footer.php        # Utility bar, nav, marquee, footer
├── front-page.php                # Homepage (modular sections)
├── index.php / page.php / single.php
├── 404.php / search / sidebar / comments
│
├── page-notice.php               # Notices board (filter + modal/PDF)
├── page-events.php               # Upcoming / past events
├── page-admission.php            # Criteria, fees, demo form
├── page-gallery.php              # Masonry gallery + lightbox
├── page-dashboard.php             # Real role-aware portal dashboard
├── page-about.php / page-contact.php
│
├── inc/
│   ├── setup.php                 # Theme supports, menus, widgets, image sizes
│   ├── enqueue.php               # Assets + no-flash dark-mode script
│   ├── customizer.php            # Customizer panel & controls
│   ├── customizer-defaults.php   # Defaults + cmpsian_get_option() helper
│   ├── cpt.php                   # Notice & Event CPTs + meta boxes
│   ├── template-tags.php         # Presentational helpers
│   └── template-functions.php    # Body classes, filters, copyright
│
├── template-parts/
│   ├── header/                   # utility-bar, nav
│   ├── home/                     # hero, marquee, principal, notices-events,
│   │                             #   stats-counter, admission-cta, gallery-preview
│   └── content-none.php
│
└── assets/
    ├── css/  (bootstrap.min.css, aos.css, campussian.css)
    ├── js/   (bootstrap.bundle.min.js, aos.js, campussian.js, customizer-preview.js)
    ├── images/  fonts/
```

### Naming conventions

| Prefix | Scope |
| --- | --- |
| `wcbd_` / `WCBD_` | Global brand constants & settings |
| `cmpsian_` | Theme functions, templates, Customizer hooks, CSS classes |

---

## 🚀 Setup & Installation

1. Copy the `campussian` folder into `wp-content/themes/`.
2. In **wp-admin → Appearance → Themes**, activate **Campussian**. On activation the theme registers the *Notices* and *Events* post types and flushes rewrite rules.
3. **Set a static homepage:** *Settings → Reading → Your homepage displays → A static page* (this makes `front-page.php` the homepage).
4. **Create the pages** and assign each its template under **Page Attributes → Template**:
   - Notices → *Notices Board*
   - Events → *Events*
   - Admission → *Admission*
   - Gallery → *Gallery* (attach images to this page's media)
   - Dashboard → *Dashboard Preview*
   - About → *About*
   - Contact → *Contact*
5. **Build the menu:** *Appearance → Menus*, then assign it to the **Primary Menu** location.
6. **Configure the Customizer** (see below), then add some Notices and Events from the admin sidebar.

> **Tip:** Permalinks — if CPT single URLs 404, visit *Settings → Permalinks* and click **Save** once.

---

## ⚙️ Customizer Options

All options live under **Appearance → Customize → Campussian Options**:

| Section | Controls |
| --- | --- |
| **School Branding** | Logo upload, Phone, Email, Address, Facebook / YouTube / LinkedIn / Twitter URLs |
| **Hero Banner** | Background image, Title, Subtitle, Primary & Secondary button text + URL |
| **Notice Marquee** | Enable toggle, Content source (latest notices / manual), Label, Manual text, Scroll speed |
| **Principal & Overview** | Photo, Name, Designation, Message, Overview heading & text |
| **Statistics Counter** | Four number + label pairs (e.g. Students, Teachers, Pass Rate, Years) |
| **Admission CTA** | Heading, Text, Button label & URL |
| **Footer** | About text, Copyright (`{year}` token supported) |

Values are read in templates through the `cmpsian_get_option( 'key' )` helper, which falls back to sensible defaults defined in `inc/customizer-defaults.php`.

---

## 🔒 Security & Coding Standards

- **Input** is sanitised with `sanitize_text_field`, `sanitize_email`, `esc_url_raw`, `absint`, etc.
- **Output** is escaped with `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`.
- Meta boxes verify **nonces**, autosave and capabilities before saving.
- Code follows **WordPress PHP & JS standards** with PHPDoc blocks throughout.

---

## 🧩 Custom Post Types

| CPT | Slug | Meta |
| --- | --- | --- |
| Notice | `cmpsian_notice` | `_cmpsian_notice_date`, `_cmpsian_notice_pdf` |
| Event | `cmpsian_event` | `_cmpsian_event_date`, `_cmpsian_event_time`, `_cmpsian_event_venue` |

Notices also support a `cmpsian_notice_cat` taxonomy for categorisation.

---

## 👤 Credits

Designed & developed by **WhyCodeBD**.
Lead developer: **Salman Farshe** — <https://salmanfarshe.me>

Bundled open-source libraries: [Bootstrap](https://getbootstrap.com/) (MIT), [AOS](https://michalsnik.github.io/aos/) (MIT).

---

© Campussian by WhyCodeBD. Released under the GPL-2.0-or-later license.

# Vastra Veda — WordPress + WooCommerce theme

A standalone classic theme built to match the Vastra Veda design, section by section.
No page builder, no parent theme, no premium plugin dependencies.

---

## Install

1. **Appearance → Themes → Add New → Upload Theme** → choose `vastra-veda.zip` → Install → Activate.
2. Install and activate **WooCommerce** (Plugins → Add New → search "WooCommerce"). Run its setup wizard.
3. **Settings → Reading** → *Your homepage displays* → **A static page** → pick any page (e.g. "Home").
   The homepage sections come from the theme, not from the page content.

The site looks complete immediately — placeholder artwork ships with the theme and is
replaced automatically as you add real images.

---

## The homepage, section by section

| # | Section | Where to edit |
|---|---------|---------------|
| 1 | Full-screen intro slider (3 slides, dot rail, "Skip") | Customize → **Vastra Veda Homepage → 1 · Intro slider** |
| 2 | Shop by category — pinned horizontal row | Customize → **2 · Shop by category** + WooCommerce product categories |
| 3 | Split promo — "Own your drape" | Customize → **3 · Split promo** |
| 4 | New arrivals product row | Customize → **4 · New arrivals** |

### Italic accents
Anywhere you see a headline field, wrap a word in **asterisks** to render it in the
italic display serif:

```
TRADITION *and* MODERN GRACE *in* EVERY DRAPE
```

Press Enter for line breaks — they are preserved exactly.

### Category cards
The cards are your **WooCommerce product categories**, in menu order.
Products → Categories → edit a category → set its **Thumbnail** to the tall image you want on the card.
Until you create categories, six demo cards (Kanjivaram, Banarasi, Organza, Chiffon, Tussar Silk, Linen) show in their place.

Portrait images work best at roughly **900 × 1200 px**.

### The pinned scroll
On screens wider than 1024px the category row is pinned and slides sideways as the visitor
scrolls down. Turn it off in Customize → *2 · Shop by category* → **Pinned horizontal scroll**.
Touch devices and anyone with "reduce motion" enabled always get a normal swipeable row.

---

## Menus

Appearance → Menus. Locations:

- **Primary** — the two links beside the header icons (New Arrivals, Sarees)
- **Slide-out menu** — the big menu behind the hamburger
- **Footer — Shop / Help / House** — the three footer columns
- **Footer — Legal** — the small bottom bar

If a location is empty, sensible defaults are shown.

---

## Images

| Where | Recommended size |
|---|---|
| Intro slides | 1900 × 1100 or larger (landscape, room in the middle for the headline) |
| Category cards | 900 × 1200 (portrait) |
| Split promo | ~1030 × 1120 |
| Products | 800 × 1067 (3:4) — set in WooCommerce → Settings → Products → Images |

Product cards use the **first gallery image** as the hover image. Add one to each product.

**Two ways to set an image.** Either upload it in the Customizer, or drop a file straight into
`assets/images/` using the slug name — `hero-1.jpg`, `hero-2.jpg`, `hero-3.jpg`, `promo.jpg`,
`cat-1.jpg` … A real photo (`.jpg` / `.png` / `.webp`) always wins over the generated `.svg`
placeholder of the same name, so no template edit is needed. Slide 1 already ships with the
campaign photograph this way.

---

## Fonts

Loaded from Google Fonts:

- **Jost** — uppercase headlines
- **Playfair Display** — section headings
- **Cormorant Garamond** — italic accents, logo, category names
- **DM Sans** — body and UI

To swap in licensed brand fonts, filter the URL in a child theme or `functions.php`:

```php
add_filter( 'vv_fonts_url', '__return_empty_string' ); // stop loading Google Fonts
```

then set `--vv-font-head`, `--vv-font-didone`, `--vv-font-serif`, `--vv-font-body`
in Customize → Additional CSS.

---

## Colours

All in `assets/css/theme.css` under `:root`, override in Additional CSS:

```css
:root {
  --vv-green: #0E4034;   /* buttons        */
  --vv-gold:  #A98F6F;   /* italic accents */
  --vv-cream: #F4ECE8;   /* promo band     */
  --vv-grey:  #F0EFED;   /* category band  */
  --vv-dark:  #14110F;   /* hero, footer   */
}
```

---

## File map

```
vastra-veda/
├── front-page.php              homepage — calls the four sections
├── header.php  footer.php
├── template-parts/
│   ├── home/hero.php           1 · intro slider
│   ├── home/categories.php     2 · shop by category
│   ├── home/promo.php          3 · split promo
│   ├── home/arrivals.php       4 · new arrivals
│   ├── overlay-search.php      full-screen search
│   └── overlay-menu.php        slide-out menu
├── woocommerce/
│   └── content-product.php     product card
├── inc/
│   ├── template-tags.php       headline parser, icons, helpers
│   ├── customizer.php          every editable field
│   └── woocommerce.php         shop integration
└── assets/  css · js · placeholder images
```

Built by Cygnotech Labs.

# Schreüder Electrical — Site Package

Ready-to-host static site (Home, Services, About, Contact) for the LouisJay15/Schreuder-electrical repo.

## Push to the repo (I can't push directly — do this once):
1. Download this `site` folder (or the whole project) from the chat.
2. In the empty repo, click **Add file → Upload files**, drag in everything from this folder (keep the `uploads/` subfolder), commit to `main`.
   — or via git:
   ```
   git clone https://github.com/LouisJay15/Schreuder-electrical.git
   cd Schreuder-electrical
   # copy this folder's contents in (including uploads/)
   git add .
   git commit -m "Initial site"
   git push
   ```

## Enable a client-viewable link (GitHub Pages)
1. Repo → **Settings → Pages**.
2. Source: **Deploy from a branch**, Branch: `main`, folder `/ (root)`. Save.
3. Client link goes live in ~1 min at:
   `https://louisjay15.github.io/Schreuder-electrical/`

## Files
- `index.html` — same as Home.dc.html, so the Pages root URL works.
- `Home/Services/About/Contact.dc.html` — the four pages, linked to each other.
- `SiteHeader.dc.html` / `SiteFooter.dc.html` — shared nav/footer, mounted via `<dc-import>`.
- `support.js` — runtime the pages depend on, must stay alongside them.
- `uploads/` — logo images referenced by header/footer.

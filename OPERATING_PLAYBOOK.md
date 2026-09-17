# 📘 Owner's Operating & Management Playbook

## 1. Core Boundaries & Non-Negotiables (What NOT to Do)
* **NEVER give Hermes write access to its own engine folder:** Hermes must only be allowed to read and edit the `/my-website/` directory.
* **NEVER run unconstrained commands in the terminal:** Always run Hermes with non-interactive flags (`CI=true`) and automated 30-second execution timeouts.
* **NEVER force non-technical staff to use server databases:** Keep your employees strictly on Discord and Airtable—never give them access to Hostinger hPanel or phpMyAdmin.
* **NEVER push unverified edits:** Always review file changes in GitHub Desktop before clicking "Push to origin".

---

## 2. Daily Operating Routine

### Step 1: Launch the Agent
1. Open **Docker Desktop** on your PC.
2. Start the `hermes-agent` container.

### Step 2: Give Instructions
1. Open your chat interface with Hermes.
2. Give clear, specific instructions (e.g., *"Add a service card for 'SEO Auditing' to the homepage"*).

### Step 3: Review Changes (Visual Safety Gate)
1. Open **GitHub Desktop**.
2. Look at the visual diffs (Green = added code, Red = deleted code).
3. Open `index.html` in your browser to check visual styling and Day/Night mode appearance.

### Step 4: Deploy Live
1. Click **Commit to main** and **Push to origin** in GitHub Desktop.
2. Hostinger will pull the updated repository and update your live site within seconds.

---

## 3. Non-Technical Staff Workflow (Leads & Orders)

1. **Notification:** An employee receives a desktop or phone notification in **Discord**:
   > 🔔 **New Lead Received!**
   > * **Name:** Customer Name
   > * **Phone:** 555-0199
   > * **Request:** Web Design Quote
2. **Lead Tracking:** Staff opens the **Airtable** link in Chrome/Edge.
3. **Status Update:** Staff changes the status dropdown from **"New"** ➔ **"Contacted"** ➔ **"Closed"** and adds quick notes.

---

## 4. Emergency Rollback Protocol
If a bad edit ever makes it to your live website:
1. Open **GitHub Desktop**.
2. Right-click the previous working commit and select **Revert changes in commit**.
3. Click **Push to origin**—Hostinger will instantly restore the previous working version.

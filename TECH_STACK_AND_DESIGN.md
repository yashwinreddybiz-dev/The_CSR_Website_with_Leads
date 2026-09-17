# 📐 System Design & Technical Architecture

## 1. High-Level Architecture
The system uses a **Local-First, Air-Gapped Development Pipeline** linked to Hostinger via GitHub:

[ Local PC ]                                      [ Private GitHub Repo ]              [ Hostinger Shared Hosting ]
Docker (Hermes Runtime) ──> Local Website Folder ──────(Push via GitHub Desktop)──────> Hostinger Git Auto-Deploy ──> Live Site

---

## 2. Technology Stack

### Frontend & UI Layer
* **Core Framework:** Pure HTML5, CSS3, Vanilla JavaScript (or Tailwind CSS).
* **Performance Goal:** Sub-1-second load times on basic shared hosting with 0% server CPU overhead for UI interactions.
* **Key Design Features:**
  * **Video Hero Section:** Full-width `<video>` (`autoplay`, `muted`, `loop`, `playsinline`) with dark tint overlay for crisp typography.
  * **Glassmorphism:** Frosted glass UI elements using `backdrop-filter: blur(12px)` and translucent borders.
  * **Day/Night Theme Toggle:** Pure JavaScript toggle applying a `.dark` CSS class, saved in browser `localStorage`.
  * **Fully Responsive:** Mobile-first layout grids adapting seamlessly to phones, tablets, and Windows screens.

### Backend & Lead Capture Layer
* **Form Handler:** Lightweight HTML contact form posting to Airtable API / Webhooks (or Hostinger PHP).
* **Lead Database (Airtable):** Acts as a visual cloud database formatted like a spreadsheet.
* **Notification Engine (Discord Webhook):** Posts instant lead cards to your employees' Discord channel upon form submission.
* **Security Layer:**
  * `.htaccess` web server rules blocking public browser access to `.md` and `.json` files.
  * Input sanitization (`htmlspecialchars`) and honeypot fields to prevent spam bots and XSS.

### Agent & Deployment Infrastructure
* **Agent Runtime:** Hermes running inside Docker Desktop on your local PC.
* **Environment Isolation:** Hermes operates strictly in non-interactive mode (`CI=true`, `DEBIAN_FRONTEND=noninteractive`).
* **Source Control:** Private GitHub Repository.
* **Web Hosting:** Hostinger Shared Hosting (hPanel) with Git Auto-Deployment enabled.

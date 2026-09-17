# 📜 HERMES AGENT OPERATING RULES

You are the AI developer for this website. You must adhere strictly to these rules on every turn.

---

## 1. Communication & Output Rules
* **Caveman Mode:** Keep outputs ultra-concise, direct, and technical. Omit conversational filler, greetings, and meta-commentary.
* **ADHD Formatting:** Structure all multi-step plans into clear, bolded, step-by-step checklists (`- [ ]`).

---

## 2. Code Editing & Memory Rules (`/pwf` & `Surgeon`)
* **Plan With Files (/pwf):** For any multi-step task, create or update `task_plan.md` in the project root containing:
  - **Goal:** Brief summary of objective.
  - **Target Files:** Exact files to edit.
  - **Steps:** Checklist of changes (`- [ ]`).
  - **Verification:** Build/syntax check method.
  Update checkboxes (`- [x]`) as steps are completed.
* **Surgeon Patching Mode:** Never rewrite complete code files. Always use targeted search-and-replace patches (`search_text` ➔ `replace_text`).
* **Lessons Log:** After fixing any bug or build failure, append a 1-line rule to `LESSONS_LEARNED.md` to avoid repeating the mistake.

---

## 3. Security Audit Guardrails
* **Public File Protection:** Ensure `.htaccess` blocks public browser access to `.md` and `.json` files.
* **Form Security:** All input fields must include sanitization (`htmlspecialchars`) and a hidden anti-spam honeypot field.
* **Database Safety:** If writing PHP, use Prepared Statements (PDO) exclusively. Never concatenate raw user input into SQL queries.

---

## 4. Technical Design & SEO Requirements
* **Day/Night Mode:** Preserve the theme toggle script and ensure all new UI components support both light and dark (`.dark`) CSS variables.
* **Video Hero:** Background videos must include `autoplay muted loop playsinline` attributes and be kept under 10MB.
* **SEO Standards:** Ensure unique `<title>` and `<meta description>` tags exist on every page, images have `alt` tags, and semantic header structure (`<h1>` ➔ `<h2>`) is maintained.

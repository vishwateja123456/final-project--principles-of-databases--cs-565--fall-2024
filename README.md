# Fall 2024 Principles of Databases (Graduate) — Final Project

* **Read these instructions repeatedly until you understand, then begin your project. If something is not clear, ask.**

## ❖・Before You Begin・❖

1. Log in to GitHub.
2. Fork this repo(sitory). See [this video](http://code-warrior.github.io/tutorials/git/github/forking-and-cloning-at-the-github-web-site/) on how to carry out this step and step `3`.
3. Clone your fork, using either the website or the GitHub Desktop client.
4. Checkout your personalized branch, the one with your name and GitHub handle.

---

## ❖・Introduction・❖

For your final project, you will create an MVC (model-view-controller) LAMP stack app consisting of a database of passwords associated with websites/apps.

---

## ❖・Requirements・❖

Your database should consist of the following information:

* Website/app name and URL, if applicable. I’ll refer to this as an “account.”
* A user’s first name, last name, username, and email address
* A password associated with a unique account
* A comment related to an account
* A timestamp related to when an account was created

Your database implementation tasks are as follows:

* Divide the aforementioned content into proper entity and relation sets
* Establish relations between the entities
* Add key constraints as needed

Create HTML views to do the following:

* **Insert** new entries into the database. Each new entry should accept site/app name, URL, email address, username, password, and a comment. The comment field in the HTML form should use the HTML [`textarea`](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/textarea) element in lieu of the `input` element.
* **Search** every entry in the database, wrapping the result in a table. If the search fails, indicate this to the user.
* **Update** any column/attribute using another distinct column/attribute as a pattern match.
* **Delete** an entry from the database based on a pattern match with another distinct column/attribute.

For passwords, use MySQL’s `AES_ENCRYPT`. (See the slide deck from the CS 365 examples repo.) **Do not enter plain text passwords into the database, and, do not use your own real-world passwords**. Once complete, populate the included `setup.sql` with the SQL syntax required to stand up and populate your database with 10 unique entries of your own choosing.

---

## ❖・Rules・❖

* The database must be called `passwords` (already in `includes/php/config.php`)
* The database username must be `passwords_user` (already in `includes/php/config.php`)
* The database password must be `k(D2Whiue9d8yD` (already in `includes/php/config.php`)
* `mysql/setup.sql` should contain all the SQL code needed to stand up your database.
* Paths used in any of the HTML or PHP files must be relative; **no** absolute paths.
* The commands to create the database and the user should be included in `mysql/setup.sql`
* Standing up the database must be done via the command `source setup.sql`.
* `php/config.php` should contain the database credentials required for the user to log in. These credentials should also be included in `mysql/setup.sql`.
* `php/db.php` should contain functions requiring interactions with the database. In previous examples, this file has been called `db.php`.

---

## ❖・Notes・❖

* Recall that you’ll need to move this repo into your web server’s root folder: `htdocs` in Windows; `Sites` in macOS
* Starter HTML code is included in the `includes/html` folder
* Starter PHP code is included in the `index.php` file
* CSS code is already included in the `css/style.css` file
* See the `crud` examples in `https://github.com/code-warrior/examples--principles-of-databases--cs-365--uhart` for ideas

---

## ❖・Grading・❖

| Item                                    | Points |
|-----------------------------------------|:------:|
| Database implemented properly           | `33`   |
| Project works according to instructions | `33`   |
| Code is neat and professional           | `33`   |

---

## ❖・Due・❖

Tuesday, 17 December 2024, at 10:00 PM. ***Note*: Per the syllabus, NO late submissions will be accepted**

---

## ❖・Submission・❖

You will need to issue a pull request back into the original repo, the one from which your fork was created for this project. See the **Issuing Pull Requests** section of [this site](http://code-warrior.github.io/tutorials/git/github/index.html) for help on how to submit your assignment.

**Note**: This assignment may **only** be submitted via GitHub. **No other form of submission will be accepted**.
